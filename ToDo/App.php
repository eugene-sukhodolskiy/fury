<?php

namespace ToDo;

use ToDo\AppRoutes;
use Fury\Kernel\Events;
use Fury\Drivers\TemplateDriver;
use Fury\Modules\Template\Template;

class App{
	public $routes;
	public $events;

	public function __construct(){
		$this -> events = Events::ins();
		$this -> routes = new AppRoutes($this);
		Template::set_driver(new TemplateDriver());

		$this -> event_handlers();
	}

	public function event_handlers(){
		$this -> events -> handler('kernel:CallControl.no_calls', function($p){
			echo (new Controllers\ToDoController()) -> err_not_found();
		});
	}
}

new App();