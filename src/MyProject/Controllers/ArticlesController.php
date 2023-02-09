<?php 
namespace MyProject\Controllers;

use MyProject\Services\Db;
use MyProject\View\View;

class ArticlesController 
{
	private $view;
	private $db;

	public function __construct()
	{
		$this->view = new View(__DIR__.'/../../../templates/');
		$this->db = new DB();
	}

	public function view(int $articleId)
	{
		$result = $this->db->query(
			'SELECT * FROM `articles` WHERE id = :id;',
			[':id'=>$articleId]
		);
		if ($result === []) {
			$this->view->renderHtml('errors/404.php',[],404);
			return;
		}
		$this->view->renderHtml('articles/view.php',['article'=>$result[0]]);
	}
}

?>