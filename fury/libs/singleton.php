<?php

namespace Fury\Libs;

class Singleton{
	private static $instance = [];

	public static function ins($param = NULL){
		$classname = get_called_class();
		if(!isset(self::$instance[$classname])){
			self::$instance[$classname] = new $classname();
			if(!is_null($param)){
				call_user_func_array([self::$instance[$classname], '__construct'], [$param]);
			}
		}
		return self::$instance[$classname];
	}
}