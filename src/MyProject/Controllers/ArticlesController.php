<?php 
namespace MyProject\Controllers;

use MyProject\View\View;
use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;
use MyProject\Exceptions\NotFoundException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Exceptions\Forbidden;

class ArticlesController extends AbstractController
{
	
	public function view(int $articleId)
	{
		$article = Article::getById($articleId);
	
		if ($article === null) {
			throw new NotFoundException('article not found');	
		}

        $this->view->renderHtml('articles/view.php', ['article' => $article]);
	}

	public function edit(int $articleId):void
	{
		$article = Article::getById($articleId);

		if ($article === null) {
			throw new NotFoundException('article not found');	
		}

		$article->setName('new');
		$article->setText('new');
		$article->save();
		
	}

	public function add(): void
	{
		if ($this->user === null) {
			throw new UnauthorizedException("Пользователь не авторизован");
		}
		if(!$this->user->isAdmin()) {
            throw new Forbidden('Для добавления статьи нужно обладать правами администратора');
        }

		if (!empty($_POST)) {
	       	try {
	            $article = Article::createFromArray($_POST, $this->user);
	        } catch (InvalidArgumentException $e) {
	            $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
	            return;
	        }

	        header('Location: /articles/' . $article->getId(), true, 302);
	        exit();
	    }

		$this->view->renderHtml('articles/add.php');

		 
	}
	public function delete(int $articleId)
	{
		$article = Article::getById($articleId);

		if ($article === null) {
			throw new NotFoundException('article not found');	
		}
		
		$article->delete();
		$this->view->renderHtml('articles/delete.php');
	}
}

?>