<?php

namespace Fury\Kernel;

class DB{
	private $db_params;
	private $connect;
	private $events_ins;

	public function __construct($db_params){
		$this -> events_ins = events();
		return $this -> create_connect($db_params);
	}

	public function create_connect($db_params){
		$this -> db_params = $db_params;
		$dblib = "{$db_params['dblib']}:host={$db_params['host']};dbname={$db_params['dbname']};charset={$db_params['charset']}";
		$this -> connect = new \PDO($dblib, $db_params['user'], $db_params['password']);
		$this -> gen_event_create_connect($this -> connect, $this -> db_params);
		return $this -> connect;
	}

	private function gen_event_create_connect($connect, $db_params){
		$this -> events_ins -> kernel_call(
			'DB.create_connect', 
			compact('connect', 'db_params')
		);
	}

	public function get_connect(){
		return $this -> connect;
	}

	public function query($sql, $params = NULL){
		if(is_null($params)){
			return $this -> connect -> query($sql);
		}

		return $this -> connect -> query($sql, $params);
	}
}