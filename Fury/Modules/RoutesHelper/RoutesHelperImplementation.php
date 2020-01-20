<?php

namespace Fury\Modules\RoutesHelper;

trait RoutesHelperImplementation{
	protected $generated_routes = [];

	protected $router;

	protected $router_meth;

	protected $forbidden_to_create_for = ['ins', '__construct', 'bootstrap'];

	protected function generate_route_by_method(String $classname, String $methname){
		$class = new \ReflectionClass($classname);
		$method = $class -> getMethod($methname);
		$src_params = $method -> getParameters();

		$params = array_map(function($param){
			return $param -> name;
		}, $src_params);

		$classname = strtolower($classname);
		$methname = strtolower($methname);

		$classname = mb_substr($classname, 1, strlen($classname));

		if($this -> router_meth == 'uri'){
			$final_route = $this -> gen_route_for_uri($classname, $methname, $params);
		}elseif($this -> router_meth == 'get' or $this -> router_meth == 'post'){
			$final_route = $this -> gen_route_for_getpost($classname, $methname, $params);
		}

		return $final_route;
	}

	protected function gen_route_for_uri(String $classname, String $methname, Array $params){
		$classname = str_replace('\\', '-', $classname);

		$static_route = str_replace('_', '-', "/{$classname}/{$methname}");
		$params_route = '';
		foreach ($params as $param) {
			$params_route .= '/' . $param . '/$' . $param;
		}

		$final_route = $static_route . $params_route;

		return $final_route;
	}

	protected function gen_route_for_getpost(String $classname, String $methname, Array $params){
		$vars = explode('\\', $classname);
		$vars[] = $methname;
		$final_route = array_merge($vars, $params);
		return $final_route;
	}

	protected function log_generated_route_from_array(Array $route_log){
		$this -> generated_routes[] = $route_log;
	}

	protected function log_generated_route(String $classname, String $methname, $route){
		$route_log = [
			'classname' => $classname,
			'methname' => $methname,
			'route' => $route
		];

		$this -> log_generated_route_from_array($route_log);
	}

	protected function log_generated_routes_array(Array $routes){
		foreach ($routes as $route) {
			$this -> log_generated_route_from_array($route);
		}
	}

	protected function add_route($route, String $action_str){
		if(!$this -> router_meth or $this -> router_meth == ''){
			return false;
		}

		$meth = $this -> router_meth;
		$this -> router -> $meth($route, $action_str);
		$this -> clear_routing_meth();
	}

	protected function change_routing_meth(String $routing_meth){
		$this -> router_meth = $routing_meth;
	}

	protected function clear_routing_meth(){
		$this -> change_routing_meth('');
	}

}