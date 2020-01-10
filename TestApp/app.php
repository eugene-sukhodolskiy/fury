<?php

use Fury\Modules\Router;
use Fury\Kernel\Events;

$router = new Router();

$events = Events::ins();

events_handlers($events);
routes_map($router) -> start_routing();

function events_handlers($events){
	$events -> handler('module:Template.start_joining', function($params){
		// dd($params);
	});
	return $events;
}

function routes_map($router){
	$router -> get(['var1', 'var2'], '\TestApp\Welcome@hello');
	$router -> uri('/testing', '\TestApp\Welcome@testing');
	$router -> uri('/id/$id/$post', '\TestApp\Welcome@testing_with_params');
	$router -> uri('/base-template', '\TestAPP\Welcome@template_test');
	return $router;
}