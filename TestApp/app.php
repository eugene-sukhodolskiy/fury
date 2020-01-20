<?php

use Fury\Modules\Router\Router;
use Fury\Kernel\Events;
use Fury\Modules\RoutesHelper\RoutesHelper;

events_handlers(Events::ins());

$router;

function events_handlers($events){
	global $router;
	$router = new Router();
	$events -> handler('kernel:Bootstrap.ready_app', function($params) use ($router){
		routes_map($router) -> start_routing();
	});	

	$events -> handler('kernel:CallControl.no_calls', function($params){
		echo "ERROR 404";
	});

	return $events;
}

function routes_map($router){
	$rh = new RoutesHelper($router);
	$rh -> get() -> class('\TestApp\Welcome');
	$router -> uri('/', '\TestApp\Welcome@index');
	// dd($router -> urlto('\TestApp\Welcome@index'));
	return $router;
}