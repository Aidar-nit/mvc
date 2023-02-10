<?php 
namespace MyProject\Controllers;

use MyProject\View\View;
use MyProject\Models\Articles\Article;
use MyProject\Models\Users\User;

class ArticlesController 
{
	private $view;
	

	public function __construct()
	{
		$this->view = new View(__DIR__.'/../../../templates/');
		
	}

	public function view(int $articleId)
	{
		$article = Article::getById($articleId);
	
		if ($article === null) {
			$this->view->renderHtml('errors/404.php',[],404);
			return;
		}

        $this->view->renderHtml('articles/view.php', ['article' => $article]);
	}

	public function edit(int $articleId):void
	{
		$article = Article::getById($articleId);

		if ($article === null) {
			$this->view->renderHtml('errors/404.php',[],404);
			return;
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
}

?>