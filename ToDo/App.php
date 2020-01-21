<?php

namespace ToDo;

use ToDo\AppRoutes;
use Fury\Kernel\Events;

class App{
	public $routes;
	public $events;

	public function __construct(){
		$this -> events = Events::ins();
		$this -> routes = new AppRoutes($this);

		$this -> event_handlers();
	}

	public function event_handlers(){
		$this -> events -> handler('kernel:CallControl.no_calls', function($p){
			echo (new Controllers\ToDoController()) -> err_not_found();
		});
	}
}

new App();