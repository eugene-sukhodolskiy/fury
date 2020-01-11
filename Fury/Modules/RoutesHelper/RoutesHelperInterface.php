<?php

namespace Fury\Modules\RoutesHelper;

interface RoutesHelperInterface{
	/**
	 * Method for generate route by string of action
	 *
	 * @method method
	 *
	 * @param  [string] $action_str [string of action]
	 *
	 * @example  method("Article@post") And result like "/article/post"
	 * 
	 * @return [string] [Result route]
	 */
	public function method(String $action_str);

	/**
	 * Generate routes for all methods of class
	 *
	 * @method class
	 *
	 * @param  [string] $action_str Classname
	 * @param  [array] $without Exclusion list
	 *
	 * @return [array] [Array with generated routes]
	 */
	public function class(String $classname, Array $without);

	/**
	 * Get array with all generated routes
	 *
	 * @method get_generated_routes
	 *
	 * @return [string] array with all generated routes
	 */
	public function get_generated_routes();

	/**
	 * Set routing method on uri
	 *
	 * @method uri
	 *
	 * @return void
	 */
	public function uri();

	/**
	 * Set routing method on get
	 *
	 * @method get
	 *
	 * @return void
	 */
	public function get();

	/**
	 * Set routing method on post
	 *
	 * @method post
	 *
	 * @return void
	 */
	public function post();
}