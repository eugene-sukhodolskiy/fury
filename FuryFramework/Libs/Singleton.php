<?php

namespace Fury\Libs;

class Singleton{
	private static $instance = [];

	public static function ins($param = NULL){
		$classname = get_called_class();
		if(!isset(self::$instance[$classname])){
			if(!is_null($param)){
				self::$instance[$classname] = new $classname($param);
			}else{
				self::$instance[$classname] = new $classname();
			}
		}
		return self::$instance[$classname];
	}
}