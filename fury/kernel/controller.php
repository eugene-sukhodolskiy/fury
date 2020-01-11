<?php

namespace Fury\Kernel;

class Controller extends \Fury\Libs\Singleton{
	protected static $bootstrap;

	public function __construct($bootstrap = NULL){
		if(!is_null($bootstrap)){
			self::$bootstrap = $bootstrap;
		}
	}

	public function bootstrap(){
		return self::$bootstrap;
	}
}