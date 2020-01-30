<?php

namespace Fury\Kernel;

/**
 * class: AppContainer
 * description: container for base application instances
 * @author Eugene Sukhodolskiy <e.sukhodolskiy@outlook.com>
 * Date: 31.01.2020
 * @version 0.1
 */

class AppContainer{
	/**
	 * Array with flags
	 *
	 * @var Array
	 */
	protected static $already_set = [
		'bootstrap' => false,
		'app' => false,
		'events' => false,
		'logging' => false
	];

	/**
	 * Container with instances
	 *
	 * @var array
	 */
	protected static $container = [];

	/**
	 * Magic method for adding instance to container
	 * @example AppContainer::set_app($app_instance) or AppContainer::set_bootstrap($app_instance) or ...
	 *
	 * @method __callStatic
	 *
	 */
	public static function __callStatic(String $name, $args){
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

	/**
	 * Get application instance
	 *
	 * @method app
	 *
	 * @return mixed 
	 */
	public static function app(){
		return self::$container['app'];
	}

	/**
	 * Get bootstrap instance
	 *
	 * @method bootstrap
	 *
	 * @return mixed
	 */
	public static function bootstrap(){
		return self::$container['bootstrap'];
	}

	/**
	 * Get Event instance
	 *
	 * @method events
	 *
	 * @return Events
	 */
	public static function events(){
		return self::$container['events'];
	}

	/**
	 * Get Logging instance
	 *
	 * @method logging
	 *
	 * @return Logging
	 */
	public static function logging(){
		return self::$container['logging'];
	}
}