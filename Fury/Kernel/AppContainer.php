<?php

namespace Fury\Kernel;

class AppContainer{
	protected static $already_set = [
		'bootstrap' => false,
		'app' => false,
		'events' => false,
		'logging' => false
	];

	protected static $container = [];

	public static function __callStatic($name, $args){
		try{
			if(strpos($name, 'set_') === false){
				throw new \Exception('Undefined method ' . $name);
			}

			list(, $var_name) = explode('set_', $name);
			if(!isset(self::$already_set[$var_name])){
				throw new \Exception('Undefined method ' . $name);
			}

			if(!self::$already_set[$var_name]){
				self::$already_set[$var_name] = true;
				self::$container[$var_name] = $args[0];
				return true;
			}else{
				return false;
			}
		}catch(\Exception $e){
			echo $e -> getMessage();
		}
	}

	public static function app(){
		return self::$container['app'];
	}

	public static function bootstrap(){
		return self::$container['bootstrap'];
	}

	public static function events(){
		return self::$container['events'];
	}

	public static function logging(){
		return self::$container['logging'];
	}
}