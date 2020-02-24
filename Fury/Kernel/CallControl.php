<?php

namespace Fury\Kernel;

/**
 * class: CallControl
 * @author Eugene Sukhodoslkiy <e.sukhodolskiy@outlook.com>
 * @version 0.1
 * Date: 08.01.2020
 * Update At: 31.01.2020
 */

class CallControl extends \Fury\Libs\Singleton{
	/**
	 * loca container for bootstrap instance
	 *
	 * @var Bootstrap
	 */
	protected $bootstrap;

	/**
	 * Flag about call
	 *
	 * @var boolean
	 */
	public $call_was_been = false;

	/**
	 * This is constructor. You know him
	 *
	 * @method __construct
	 *
	 * @param  Bootstrap | NULL $bootstrap Bootstrap class instance
	 */
	public function __construct($bootstrap = NULL){
		if($bootstrap){
			$this -> bootstrap = $bootstrap;
		}

		events() -> handler('kernel:Bootstrap.app_finished', function($params){
			$call_control = CallControl::ins();
			if(!$call_control -> call_was_been){
				events() -> kernel_call('CallControl.no_calls', []);
			}
		});
	}

	/**
	 * Call to controller class method. 
	 *
	 * @method call_action
	 *
	 * @param  String $getpost_flag 'GET_POST' | 'URI'
	 * @param  String $src_route Raw route
	 * @param  Mixed $action Action. We need call to him
	 * @param  array $src_params Raw array with params
	 *
	 * @return void
	 */
	public function call_action(String $getpost_flag, String $src_route, $action, $src_params = []){
		$type = $getpost_flag ? 'GET_POST' : 'URI';

		// make final params;
		if($getpost_flag){
			if(strpos($src_route, '?')){
				list(, $src_route) = explode('?', $src_route);
			}
			$route = explode('&', $src_route);
			$params = [];
			foreach ($route as $i => $var) {
				$params[$var] = $src_params[$var];
			}
		}else{
			$params = $src_params;
		}

		// call action with params
		if(is_object($action)){
			$this -> action_result($this -> call_for_anon_func($type, $src_route, $action, $params));
		}elseif(strpos($action, '@') === false){
			$this -> action_result($this -> call_for_simple_func($type, $src_route, $action, $params));
		}else{
			$this -> action_result($this -> call_for_class_meth($type, $src_route, $action, $params));
		}
	}

	/**
	 * Call to anonymize function
	 *
	 * @method call_for_anon_func
	 *
	 * @param  String $type 'GET_POST' | 'URI'
	 * @param  String $src_route Raw route string
	 * @param  Function $action Anon func. We call to her
	 * @param  Array $params Parameters for our anon func
	 *
	 * @return mixed [Result of working our action (anon func)]
	 */
	protected function call_for_anon_func($type, $src_route, $action, $params){
		$this -> gen_event_leading_call($type, $src_route, $action, $params);
		$res = $action($params); // call
		$this -> gen_event_completed_call($type, $src_route, $action, $params, $res);
		return $res;
	}

	/**
	 * Call to action if action is simple function
	 *
	 * @method call_for_simple_func
	 *
	 * @param  String $type 'GET_POST' | 'URI'
	 * @param  Mixed $src_template raw route template
	 * @param  String $action Function name in string type
	 * @param  Array $params Function arguments
	 *
	 * @return mixed [Result of working our action (anon func)]
	 */
	protected function call_for_simple_func(String $type, $src_template, String $action, $params){
		// call for simple func
		$ref_func = new \ReflectionFunction($action);
		$real_action_params = $ref_func -> getParameters();
		$final_action_params = [];
		foreach ($real_action_params as $arg) {
			if(isset($params[$arg -> name])){
				$final_action_params[$arg -> name] = $params[$arg -> name];
			}
		}

		logging() -> set('CallControl@call_for_simple_func', 'Calling controller function', "$type, $src_template, $action, $final_action_params");
		$this -> gen_event_leading_call($type, $src_template, $action, $final_action_params);
		$res = call_user_func_array($action, $final_action_params);
		$this -> gen_event_completed_call($type, $src_template, $action, $final_action_params, $res);
		return $res;
	}

	/**
	 * Call to action if action is method of class
	 *
	 * @method call_for_class_meth
	 *
	 * @param  String $type 'GET_POST' | 'URI'
	 * @param  Mixed $src_template Raw route template
	 * @param  String $action Class name and method name in string type. Like "Classname@methname"
	 * @param  Array $params Method arguments
	 *
	 * @return mixed [Result of working our action (anon func)]
	 */
	protected function call_for_class_meth(String $type, $src_template, String $action, $params){
		list($action_class, $action_meth) = explode('@', $action);
		$class_object = call_user_func_array([$action_class, 'ins'], [$this -> bootstrap]);
		$ref_class = new \ReflectionClass($action_class);
		$real_action_params = $ref_class -> getMethod($action_meth) -> getParameters();
		$final_action_params = [];
		foreach ($real_action_params as $arg) {
			if(isset($params[$arg -> name])){
				$final_action_params[$arg -> name] = $params[$arg -> name];
			}
		}

		logging() -> set('CallControl@call_for_simple_func', 'Calling controller class and method', $type, $src_template, $action, $final_action_params);
		$this -> gen_event_leading_call($type, $src_template, $action, $final_action_params);
		$res = call_user_func_array([$class_object, $action_meth], $final_action_params);
		$this -> gen_event_completed_call($type, $src_template, $action, $final_action_params, $res);
		return $res;
	}

	/**
	 * Generating event leading call. Will be runned before call action
	 *
	 * @method gen_event_leading_call
	 *
	 * @param  String $type 'GET_POST' | 'URI'
	 * @param  String $route_template Raw route template
	 * @param  Mixed $action
	 * @param  Array $params Arguments for action
	 *
	 * @return void
	 */
	protected function gen_event_leading_call(String $type, String $route_template, $action, $params){
		$this -> call_was_been = true;
		events() -> kernel_call(
			'CallControl.leading_call', 
			compact('type', 'route_template', 'action', 'params')
		);
	}

	/**
	 * Generating event completed call. Will be runned after call to action
	 *
	 * @method gen_event_completed_call
	 *
	 * @param  String $type 'GET_POST' | 'URI'
	 * @param  String $route_template Raw route template
	 * @param  Mixed $action
	 * @param  Array $params Arguments for action
	 * @param  Mixed $result Result of worked action
	 *
	 * @return void
	 */
	protected function gen_event_completed_call(String $type, String $route_template, $action, $params, $result){
		$this -> call_was_been = true;
		events() -> kernel_call(
			'CallControl.completed_call', 
			compact('type', 'route_template', 'action', 'params', 'result')
		);
	}

	/**
	 * Result of action. Will be runned when action woking is finished.
	 *
	 * @method action_result
	 *
	 * @param  Mixed $result Result of worked action
	 *
	 * @return void
	 */
	protected function action_result($result){
		echo $result;

		logging() -> dump();
	}
}