<?php

namespace Fury\Kernel;

class Router{
	private $routes_map = ['get' => [], 'post' => []];
	public $uri;

	public function __construct($routes_map = NULL){
		if(is_array($routes_map)){
			$this -> routes_map = $routes_map;
		}

		$this -> uri = $_SERVER['REQUEST_URI'];
	}

	public function get($route, $action){
		$this -> routes_map['get'][$route] = $action;
	} 

	public function get_routes_map(){
		return $this -> routes_map;
	}

	public function start_routing(){
		
	}

	public function call_action($src_route, $action){

	}
}