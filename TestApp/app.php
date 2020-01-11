<?php

use Fury\Modules\Router\Router;
use Fury\Kernel\Events;
use Fury\Modules\RoutesHelper\RoutesHelper;

events_handlers(Events::ins());

function events_handlers($events){
	$router = new Router();

	$events -> handler('kernel:Bootstrap.ready_app', function($params) use ($router){
		routes_map($router) -> start_routing();
	});	

	return $events;
}

function routes_map($router){
	$rh = new RoutesHelper($router);
	$rh -> uri() -> class('\TestApp\Welcome');
	$router -> uri('/options/set/$key/$val', '\TestApp\Welcome@db_test');
	return $router;
}