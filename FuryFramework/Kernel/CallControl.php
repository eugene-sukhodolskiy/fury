<?php

namespace Fury\Kernel;

class CallControl extends \Fury\Libs\Singleton{
	public $bootstrap;
	public $call_was_been = false;

	public function __construct($bootstrap = NULL){
		if($bootstrap){
			$this -> bootstrap = $bootstrap;
		}

		$this -> bootstrap -> events -> handler('kernel:Bootstrap.app_finished', function($params){
			$call_control = CallControl::ins();
			if(!$call_control -> call_was_been){
				Events::ins() -> kernel_call('CallControl.no_calls', []);
			}
		});
	}

	public function call_action($getpost_flag, $src_route, $action, $src_params = []){
		$type = $getpost_flag ? 'GET_POST' : 'URI';

		// make final params;
		if($getpost_flag){
			$route = explode(';', $src_route);
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

	private function call_for_anon_func($type, $src_route, $action, $params){
		$this -> gen_event_leading_call($type, $src_route, $action, $params);
		$res = $action($params); // call
		$this -> gen_event_completed_call($type, $src_route, $action, $params, $res);
		return $res;
	}

	private function call_for_simple_func($type, $src_template, $action, $params){
		// call for simple func
		$ref_func = new \ReflectionFunction($action);
		$real_action_params = $ref_func -> getParameters();
		$final_action_params = [];
		foreach ($real_action_params as $arg) {
			if(isset($params[$arg -> name])){
				$final_action_params[$arg -> name] = $params[$arg -> name];
			}
		}

		Logging::ins() -> set('CallControl@call_for_simple_func', 'Calling controller function', "$type, $src_template, $action, $final_action_params");
		$this -> gen_event_leading_call($type, $src_template, $action, $final_action_params);
		$res = call_user_func_array($action, $final_action_params);
		$this -> gen_event_completed_call($type, $src_template, $action, $final_action_params, $res);
		return $res;
	}

	private function call_for_class_meth($type, $src_template, $action, $params){
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

		Logging::ins() -> set('CallControl@call_for_simple_func', 'Calling controller class and method', "$type, $src_template, $action, $final_action_params");
		$this -> gen_event_leading_call($type, $src_template, $action, $final_action_params);
		$res = call_user_func_array([$class_object, $action_meth], $final_action_params);
		$this -> gen_event_completed_call($type, $src_template, $action, $final_action_params, $res);
		return $res;
	}

	private function gen_event_leading_call($type, $route_template, $action, $params){
		$this -> call_was_been = true;
		$this -> bootstrap -> events -> kernel_call(
			'CallControl.leading_call', 
			compact('type', 'route_template', 'action', 'params')
		);
	}

	private function gen_event_completed_call($type, $route_template, $action, $params, $result){
		$this -> call_was_been = true;
		$this -> bootstrap -> events -> kernel_call(
			'CallControl.completed_call', 
			compact('type', 'route_template', 'action', 'params', 'result')
		);
	}

	private function action_result($result){
		echo $result;

		Logging::ins() -> dump();
	}
}