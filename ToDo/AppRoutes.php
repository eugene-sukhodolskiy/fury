<?php

namespace ToDo;

use Fury\Modules\Router\Router;

class AppRoutes{
	public $router;
	public $app;

	public function __construct($app){
		$this -> app = $app;
		$this -> router = new Router();
	}

	public function init_routes(){
		$router = $this -> router;
		$this -> app -> events -> handler('kernel:Bootstrap.ready_app', function($params) use ($router){
			$router -> uri('/', '\ToDo\Controllers\ToDoController@index');
			$router -> uri('/update', '\ToDo\Controllers\ToDoController@update');
			$router -> uri('/page/create', '\ToDo\Controllers\ToDoController@create_page');
			$router -> uri('/delete/$inx', '\ToDo\Controllers\ToDoController@delete');
			$router -> post(['task'], '\ToDo\Controllers\ToDoController@create');

			$router -> start_routing();
		});
	}
}

