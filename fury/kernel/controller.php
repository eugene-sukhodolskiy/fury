<?php

namespace Fury\Kernel;

class Controller extends \Fury\Libs\Singleton{
	protected $bootstrap;

	public function __construct($bootstrap = NULL){
		if(!is_null($bootstrap)){
			$this -> bootstrap = $bootstrap;
		}
	}

	public function bootstrap(){
		return $this -> bootstrap;
	}
}