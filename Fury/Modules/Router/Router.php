<?php

namespace Fury\Modules\Router;

use \Fury\Kernel\CallControl;

/**
 * Routing module
 * Author: Eugene Sukhodolkiy
 * Date: 09.01.2020
 * LastUpdate: 12.01.2020
 * Version: 0.2 beta
 */

class Router implements RouterInterface{
	/**
	 * Container with router implementation 
	 */
	use RouterImplementation;

	/**
	 * Constructor 
	 *
	 * @method __construct
	 *
	 * @param  array $routes_map Starting routes map
	 */
	public function __construct($routes_map = NULL){
		$this -> call_control_instance = CallControl::ins();

		if(is_array($routes_map)){
			$this -> routes_map = $routes_map;
		}

		$this -> uri = $_SERVER['REQUEST_URI'];
		$uri_length = strlen($this -> uri);
		if($uri_length > 1 and $this -> uri[$uri_length - 1] == '/'){
			$this -> uri = mb_substr($this -> uri, 0, -1);
		}
	}

	public function get($route, $action){
		if(is_array($route)){
			$route = implode(';', $route);
		}
		$this -> add_route('get', $route, $action);
	} 

	public function post($route, $action){
		if(is_array($route)){
			$route = implode(';', $route);
		}
		$this -> add_route('post', $route, $action);
	}

	public function uri($route, $action){
		$this -> add_route('uri', $route, $action);
	}

	public function get_routes_map(){
		return $this -> routes_map;
	}

	public function start_routing(){
		$result = [];

		$result['get'] = $this -> GET_and_POST_routing($this -> routes_map['get'], $_GET);
		$result['post'] = $this -> GET_and_POST_routing($this -> routes_map['post'], $_POST);
		$result['uri'] = $this -> URI_routing($this -> routes_map['uri']);
	}

	public function route_to(String $action){
		$routes_list = [];

		$route = array_search($action, $this -> routes_map['uri']);

		return $route === false ? '' : $route;
	}

	public function urlto(String $action, Array $params = []){
		$route_template = $this -> route_to($action);
		$route_params = $this -> get_params_from_route_template($route_template);
		$url = str_replace($route_params, $params, $route_template);
		return $url;
	}
}