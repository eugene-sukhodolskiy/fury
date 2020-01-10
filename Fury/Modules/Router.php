<?php

namespace Fury\Modules;

use \Fury\Kernel\CallControl;

/**
 * Routing module
 * Author: Eugene Sukhodolkiy
 * Date: 09.01.2020
 * LastUpdate: 11.01.2020
 * Version: 0.1 beta
 */

class Router{
	/**
	 * Map of routes for routing
	 *
	 * @var array
	 */
	private $routes_map = ['get' => [], 'post' => [], 'uri' => []];

	/**
	 * Current URI REQUEST
	 *
	 * @var string
	 */
	public $uri;

	/**
	 * Instance of class CallControl
	 *
	 * @var CallControl
	 */
	private $call_control_instance;

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

	/**
	 * Method for add new route by GET vars
	 *
	 * @method get
	 *
	 * @param  array $route [Array with vars names]
	 * @param  $action [anon func or string name of function or Classname@methodname]
	 *
	 * @example get(['id', 'post'], 'Entry@article') [If exists variables 'id' and 'post' - must be call Entry class and article method]
	 * 
	 * @return void
	 */
	public function get($route, $action){
		if(is_array($route)){
			$route = implode(';', $route);
		}
		$this -> add_route('get', $route, $action);
	} 

	/**
	 * [Method for add new route by POST vars]
	 *
	 * @method post
	 *
	 * @param  array $route [Array with vars names]
	 * @param  $action [anon func or string name of function or Classname@methodname]
	 *
	 * @example post(['id', 'post'], 'Entry@article') [If exists variables 'id' and 'post' - must be call Entry class and article method]
	 * 
	 * @return void
	 */
	public function post($route, $action){
		if(is_array($route)){
			$route = implode(';', $route);
		}
		$this -> add_route('post', $route, $action);
	}

	/**
	 * [Add new route by URI string]
	 *
	 * @method uri
	 *
	 * @param  string $route [String with path like '/post/id/$post_id']
	 * @param  $action [anon func or string name of function or Classname@methodname]
	 *
	 * @return void
	 */
	public function uri($route, $action){
		$this -> add_route('uri', $route, $action);
	}

	/**
	 * [Add new route to routes map]
	 *
	 * @method add_route
	 *
	 * @param  [string] $method [Method of routing "GET_POST" or "URI"]
	 * @param  [string or array] $route [uri route or array with vars names GET/POST]
	 * @param  [string or function] $action [anon func or string name of function or Classname@methodname]
	 */
	private function add_route($method, $route, $action){
		$this -> routes_map[$method][$route] = $action;
	}

	/**
	 * Get current routes map
	 *
	 * @method get_routes_map
	 *
	 * @return [array] [Routes map]
	 */
	public function get_routes_map(){
		return $this -> routes_map;
	}

	/**
	 * Start Routing by GET/POST vars and URI
	 *
	 * @method start_routing
	 *
	 * @return [void]
	 */
	public function start_routing(){
		$result = [];

		$result['get'] = $this -> GET_and_POST_routing($this -> routes_map['get'], $_GET);
		$result['post'] = $this -> GET_and_POST_routing($this -> routes_map['post'], $_POST);
		$result['uri'] = $this -> URI_routing($this -> routes_map['uri']);
	}

	/**
	 * Implementation URI Routing
	 *
	 * @method URI_routing
	 *
	 * @param  [array] $routes_map_part [Part of routes map for URI routing]
	 *
	 * @return  [array] [Array with routes templates, that we need]
	 */
	private function URI_routing($routes_map_part){
		$result_routes_templates = [];
		if(isset($routes_map_part[$this -> uri])){
			$this -> call_control_instance -> call_action(false, $this -> uri, $routes_map_part[$this -> uri]);
		}else{
			$routes_templates = $this -> searching_route_by_uri($routes_map_part, $this -> uri);
			$params = [];
			foreach($routes_templates as $i => $template){
				$params[$template] = $this -> required_params_from_uri($template, $this -> uri);
				$this -> call_control_instance -> call_action(false, $template, $routes_map_part[$template], $params[$template]);
			}
			$result_routes_templates[] = [
				'routes_templates' => $routes_templates,
				'params' => $params
			];
		}

		return $result_routes_templates;
	}

	/**
	 * Searching routes templates by current URI
	 *
	 * @method searching_route_by_uri
	 *
	 * @param  [array] $routes_map Where need searching
	 * @param  [string] $uri Current URI
	 *
	 * @return [array] [Array with result searching]
	 */
	private function searching_route_by_uri($routes_map, $uri){
		$results_routes_templates = [];

		$uri_parts = explode('/', $uri);
		$count_uri_parts = count($uri_parts);
		foreach ($routes_map as $route_template => $action) {
			if(strpos($route_template, '$') === false){
				continue;
			}
			$route_parts = explode('/', $route_template);
			if(count($route_parts) != $count_uri_parts){
				continue;
			}

			$flag = true;
			foreach ($route_parts as $i => $part) {
				if($part[0] == '$'){
					continue;
				}
				if($part != $uri_parts[$i]){
					$flag = false;
					break;
				}
			}

			if($flag){
				$results_routes_templates[] = $route_template;
			}
		}

		return $results_routes_templates;
	}

	/**
	 * Routing by GET and POST vars
	 *
	 * @method GET_and_POST_routing
	 *
	 * @param  [array] $routes_map_part [Array with routes templates]
	 * @param  [array] $vars [Current vars GET or POST]
	 */
	private function GET_and_POST_routing($routes_map_part, $vars){
		$result_routes = [];

		foreach ($routes_map_part as $route => $action) {
			$route_vars = explode(';', $route);
			$flag = true;

			foreach ($route_vars as $i => $rvar) {
				if(!isset($vars[$rvar])){
					$flag = false;
					break;
				}
			}

			if($flag){
				$result_routes[$route] = $action;
				$this -> call_control_instance -> call_action(true, $route, $action, $vars);
			}
		}

		return $result_routes;
	}


	/**
	 * Method for getting params from uri request by route template
	 *
	 * @method required_params_from_uri
	 *
	 * @param  [string] $route_template [Route template]
	 * @param  [string] $uri_path [Current URI request]
	 *
	 * @return [array] [Array with result searching params]
	 */
	private function required_params_from_uri($route_template, $uri_path){
		$route_template_parts = explode('/', $route_template);
		$uri_parts = explode('/', $uri_path);
		$params = [];
		foreach ($route_template_parts as $i => $part) {
			if($part[0] != '$'){
				continue;
			}
			$params[mb_substr($part, 1, strlen($part))] = $uri_parts[$i];
		}

		return $params;
	}


}