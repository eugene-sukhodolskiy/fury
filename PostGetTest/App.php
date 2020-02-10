<?php

namespace PostGetTest;

use \Fury\Modules\Router\Router;

class App extends \Fury\Kernel\BaseApp{
	public $router;

	public function __construct(){
		parent::__construct();
		$this -> router = new Router();
		$this -> handlers();
	}

	public function handlers(){
		events() -> handler('kernel:Bootstrap.ready_app', function($p){
			app() -> router -> get(['p', 'id'], '\PostGetTest\Controllers\AppController@get_test', '/post');
			app() -> router -> uri('/post', '\PostGetTest\Controllers\AppController@uri_test');
			app() -> router -> start_routing();
		});

		events() -> handler('kernel:CallControl.no_calls', function($p){
			echo "404";
		});
	}
}

new App();