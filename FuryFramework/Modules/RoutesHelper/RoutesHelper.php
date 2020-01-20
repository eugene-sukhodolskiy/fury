<?php

namespace Fury\Modules\RoutesHelper;

/**
 * RoutesHelper - Module for help with generation routes by Actions
 * Author: Eugene Sukhodolskiy
 * Date: 11.01.2020
 * LastUpdate: 11.01.2020
 * Version: 0.1 beta
 */

use Fury\Modules\Router\Router;

class RoutesHelper implements RoutesHelperInterface{

	use RoutesHelperImplementation;

	public function __construct(Router $router){
		$this -> router = $router;
	}

	public function class(String $classname, Array $without = []){
		$without = array_merge($this -> forbidden_to_create_for, $without);

		$current_router_meth = $this -> router_meth;

		$class = new \ReflectionClass($classname);
		$methods = $class -> getMethods();

		$result_routes = [];

		foreach ($methods as $method){
			if(in_array($method -> name, $without)){
				continue;
			}
			
			$action_str = "{$classname}@{$method -> name}";
			$this -> change_routing_meth($current_router_meth);
			$result_routes = $this -> method($action_str);
		}

		return $result_routes;
	}

	public function method(String $action_str){
		list($classname, $methname) = explode('@', $action_str);
		$route = $this -> generate_route_by_method($classname, $methname);
		$this -> add_route($route, $action_str);
		$this -> log_generated_route($classname, $methname, $route);
		return $route;
	}

	public function get_generated_routes(){
		return $this -> generated_routes;
	}

	public function uri(){
		$this -> change_routing_meth('uri');
		return $this;
	}

	public function get(){
		$this -> change_routing_meth('get');
		return $this;
	}

	public function post(){
		$this -> change_routing_meth('post');
		return $this;
	}
}