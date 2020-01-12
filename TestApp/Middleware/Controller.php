<?php

namespace TestApp\Middleware;

class Controller extends \Fury\Kernel\Controller{
	protected static $router;

	public function __construct(){
		global $router;
		self::$router = $router;
	}

	public function router(){
		return self::$router;
	}
}