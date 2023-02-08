<?php 
namespace MyProject\Controllers;

class MainController 
{
	public function main()
	{
		echo 'Main page';
	}
	public function sayHello(string $name)
    {
        echo 'Привет, ' . $name;
    }
}


?>