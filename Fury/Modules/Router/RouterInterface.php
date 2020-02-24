<?php

namespace Fury\Modules\Router;

interface RouterInterface{

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
	public function get($route, $action);

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
	public function post($route, $action);

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
	public function uri($route, $action);

	/**
	 * Get current routes map
	 *
	 * @method get_routes_map
	 *
	 * @return [array] [Routes map]
	 */
	public function get_routes_map();

	/**
	 * Start Routing by GET/POST vars and URI
	 *
	 * @method start_routing
	 *
	 * @return [void]
	 */
	public function start_routing();

	/**
	 * Get route by action.
	 *
	 * @method route_to
	 *
	 * @param  String $action Controller in string
	 *
	 * @example  route_to('Welcome@home') [return like "/home"]
	 *
	 * @return [string] 
	 */
	public function route_to(String $action);

	/**
	 * Get URL to action. With params or without params
	 *
	 * @method urlto
	 *
	 * @param  String $action Controller in string
	 * @param  Array $params [description]
	 *
	 * @example  urlto('Post@article', [id => 23]) [return like "/post/post_id/23"]
	 *
	 * @return string
	 */
	public function urlto(String $action, Array $params = []);
}