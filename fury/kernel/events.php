<?php

namespace Fury\Kernel;

class Events{
	private $storage = [];

	public function __construct(){

	}

	public function get(){
		return $this -> $storage;
	}

	public function call($event_name, $params){

	}

	public function handler($event_name, $handler){
		
	}

}