<?php

namespace Fury\Libs;

class Singleton{
	private static $instance = [];

	public static function ins(){
		$classname = get_called_class();
		if(!isset(self::$instance[$classname])){
			self::$instance[$classname] = new $classname();
		}
		return self::$instance[$classname];
	}
}