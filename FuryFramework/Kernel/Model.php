<?php

namespace Fury\Kernel;

class Model extends \Fury\Libs\Singleton{
	protected static $db;

	public function __construct($db = NULL){
		if(!is_null($db)){
			self::$db = $db;
		}
	}

	public function db(){
		return self::$db;
	}
}