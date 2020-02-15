<?php

namespace Starter;

use \Fury\Modules\Router\Router;

class App extends \Fury\Kernel\BaseApp{
	public $routes;
	public $router;
	public $events_handlers;

	public function __construct(){
		parent::__construct();

		$this -> app_init();
	}

	public function app_init(){
		$this -> router = new Router();
		$this -> routes = new Routes($this -> router);
		$this -> events_handlers = new EventsHandlers();
		$this -> events_handlers -> handlers();
	}
}

new App();