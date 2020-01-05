<?php

namespace Fury\Kernel;

class DB{
	private $db_params;
	private $connect;

	public function __construct($db_params){
		return $this -> create_connect($db_params);
	}

	public function create_connect($db_params){
		$this -> db_params = $db_params;
		$dblib = "{$db_params['dblib']}:host={$db_params['host']};dbname={$db_params['dbname']};charset={$db_params['charset']}";
		$this -> connect = new \PDO($dblib, $db_params['user'], $db_params['password']);
		return $this -> connect;
	}

	public function get_connect(){
		return $this -> connect;
	}
}