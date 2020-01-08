<?php

use Fury\Modules\Router;

$router = new Router();

function routes_map($router){
	$router -> get(['var1', 'var2'], '\TestApp\Welcome@hello');
	$router -> uri('/testing', '\TestApp\Welcome@testing');
	$router -> uri('/id/$id/$post', '\TestApp\Welcome@testing_with_params');
	return $router;
}

routes_map($router) -> start_routing();