<?php

namespace Fury\Modules;

use \Fury\Kernel\CallControl;

class Router{
	private $routes_map = ['get' => [], 'post' => [], 'uri' => []];
	public $uri;
	private $call_control_instance;

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

	private function add_route($method, $route, $action){
		$this -> routes_map[$method][$route] = $action;
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