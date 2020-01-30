<?php

namespace Fury\Kernel;

class Events{
	/**
	 * Events handlers storage (map)
	 *
	 * @var array
	 */
	private $map = [];

	/**
	 * Storage for call logging
	 *
	 * @var array
	 */
	private $call_history = [];

	public $event_types = ['kernel', 'app', 'module'];

	public function __construct(){
		$this -> map = [];
		foreach($this -> event_types as $type){
			$this -> map[$type] = [];
		}
	}

	/**
	 * Get events map
	 *
	 * @method get_map
	 *
	 * @param  boolean $full_map_flag Flag. If true - returned full map with app and kernel events, else only events map of app
	 * 
	 * @return array
	 */
	public function get_map($full_map_flag = false){
		if($full_map_flag){
			return $this -> map;
		}

		return $this -> map['app'];
	}

	/**
	 * Get history of calling events handlers
	 *
	 * @method get_call_history
	 *
	 * @return array
	 */
	public function get_call_history(){
		return $this -> call_history;
	}

	/**
	 * Event generation in the right places
	 *
	 * @method call
	 *
	 * @param  string $type kernel or app
	 * @param  string $event_name Uniq name of event
	 * @param  array $params Array with parameters for event handlers
	 *
	 * @return boolean
	 */
	private function call($type, $event_name, $params){
		if(!isset($this -> map[$type][$event_name])){
			return false;
		}

		foreach ($this -> map[$type][$event_name] as $i => $handler) {
			$log_id = $this -> log_call_history($type, $event_name, $params);
			$result = $handler($params);
			$this -> add_result_to_log_call_history($log_id, $result);
		}

		return true;
	}

	/**
	 * logging call to history list
	 *
	 * @method log_call_history
	 *
	 * @param  string $type Events type
	 * @param  string $event_name Name of events
	 * @param  array $params Parameters
	 *
	 * @return int Unique ID of handler call
	 */
	private function log_call_history($type, $event_name, $params){
		if(!FCONF['debug']){
			return false;
		}

		$log_id = uniqid();
		$this -> call_history[$log_id] = [
			'type' => $type,
			'event_name' => $event_name,
			'params' => $params
		];
		return $log_id;
	}

	/**
	 * Add result of working event handler
	 *
	 * @method add_result_to_log_call_history
	 *
	 * @param  int $log_id Unique log id
	 * @param  any $result Result of working event handler
	 */
	private function add_result_to_log_call_history($log_id, $result){
		if(!FCONF['debug']){
			return false;
		}

		if(isset($this -> call_history[$log_id])){
			$this -> call_history[$log_id]['result'] = $result;
			return true;
		}
		return false;
	}

	/**
	 * Add new handler for event
	 *
	 * @method handler
	 *
	 * @param  string $event_name Name of events with type. Example "app:my_event" or "kernel:some_event"
	 * @param  function $handler Anonymous function that will be the handler
	 *
	 * @return boolean result
	 */
	public function handler($event_name, $handler){
		list($type, $event_name) = explode(':', $event_name);
		$type = trim(strtolower($type));
		$event_name = trim($event_name);

		if(!isset($this -> map[$type])){
			return false;
		}

		if($event_name == ''){
			return false;
		}

		if(isset($this -> map[$type][$event_name]) and is_array($this -> map[$type][$event_name])){
			$this -> map[$type][$event_name][] = $handler;
		}else{
			$this -> map[$type][$event_name] = [$handler];
		}

		return true;
	}

	public function __call($methname, $args){
		foreach ($this -> event_types as $i => $type) {
			if(strpos($methname, $type . '_call') === false) 
				continue;
			return $this -> call($type, $args[0], $args[1]);
		}
		return false;
	}

}