<?php

use Fury\Modules\Router;
use Fury\Kernel\Events;

$router = new Router();

$events = Events::ins();
events_handlers($events);

routes_map($router) -> start_routing();

function events_handlers($events){
	$events -> handler('kernel:DB.create_connect', function($params){
		// dd($params);
	});
	return $events;
}

function routes_map($router){
	$router -> get(['var1', 'var2'], '\TestApp\Welcome@hello');
	$router -> uri('/testing', '\TestApp\Welcome@testing');
	$router -> uri('/id/$id/$post', '\TestApp\Welcome@testing_with_params');
	return $router;
}