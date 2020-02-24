<?php

namespace Fury\Modules\Router;

use \Fury\Kernel\CallControl;

trait RouterImplementation{
	/**
	 * Map of routes for routing
	 *
	 * @var array
	 */
	protected $routes_map = ['get' => [], 'post' => [], 'uri' => []];

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
	protected $call_control_instance;

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
	protected function required_params_from_uri($route_template, $uri_path){
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

	/**
	 * Routing by GET and POST vars
	 *
	 * @method GET_and_POST_routing
	 *
	 * @param  [array] $routes_map_part [Array with routes templates]
	 * @param  [array] $vars [Current vars GET or POST]
	 */
	protected function GET_and_POST_routing($routes_map_part, $vars){
		$result_routes = [];

		foreach ($routes_map_part as $route => $action) {
			if(strpos($route, '?')){
				list($route_uri, $route_vars) = explode('?', $route);
			}else{
				$route_vars = $route;
			}

			$route_vars = explode('&', $route_vars);
			$flag = true;
			if(isset($route_uri) and $route_uri != $this -> uri){
				$flag = false;
			}

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
	 * Searching routes templates by current URI
	 *
	 * @method searching_route_by_uri
	 *
	 * @param  [array] $routes_map Where need searching
	 * @param  [string] $uri Current URI
	 *
	 * @return [array] [Array with result searching]
	 */
	protected function searching_route_by_uri($routes_map, $uri){
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
	 * Implementation URI Routing
	 *
	 * @method URI_routing
	 *
	 * @param  [array] $routes_map_part [Part of routes map for URI routing]
	 *
	 * @return  [array] [Array with routes templates, that we need]
	 */
	protected function URI_routing($routes_map_part){
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
	 * [Add new route to routes map]
	 *
	 * @method add_route
	 *
	 * @param  [string] $method [Method of routing "GET_POST" or "URI"]
	 * @param  [string or array] $route [uri route or array with vars names GET/POST]
	 * @param  [string or function] $action [anon func or string name of function or Classname@methodname]
	 */
	protected function add_route($method, $route, $action){
		$this -> routes_map[$method][$route] = $action;
	}

	/**
	 * Get Parameters from route template. Only for uri routes
	 *
	 * @method get_params_from_route_template
	 *
	 * @param  String $route_template 
	 *
	 * @return array
	 */
	protected function get_params_from_route_template(String $route_template){
		$template_parts = explode('/', $route_template);
		$params = [];
		foreach ($template_parts as $part) {
			if(isset($part[0]) and $part[0] == '$'){
				$params[] = $part;
			}
		}

		return $params;
	}
}