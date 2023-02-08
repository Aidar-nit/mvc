<?php 

spl_autoload_register(function (string $className){
	require_once __DIR__ . '/src/' . str_replace('\\','/',$className) . '.php';
});

$route = $_GET['route'] ?? '';
$routes = require __DIR__.'/src/routes.php';

//preg_match($pattern, $route, $matches);

$isRouteFound = false;

foreach ($routes as $pattern => $controllerAndAction) {
	preg_match($pattern, $route, $matches);
	if (!empty($matches)) {
		$isRouteFound = true;
		break;
	}
}

if (!$isRouteFound) {
	echo 'Page Dont found';
	return;
}

//var_dump($controllerAndAction);
//var_dump($matches);
unset($matches[0]); //Delete 0 el in array

$controllerName = $controllerAndAction[0];
$actionName = $controllerAndAction[1];

$controller = new $controllerName();
$controller->$actionName(...$matches);

?>