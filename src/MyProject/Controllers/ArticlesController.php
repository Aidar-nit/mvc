<?php 
namespace MyProject\Controllers;

use MyProject\View\View;
use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;
use MyProject\Exceptions\NotFoundException;

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
		$author = User::getById(1);
		$article = new Article();
		$article->setAuthor($author);
		$article->setName('add new article');
		$article->setText('add new text');
		$article->save();

		var_dump($article);
		 
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