<?php

namespace PostGetTest\Controllers;

class AppController extends \Fury\Kernel\Controller{
	public function get_test($p, $id){
		var_dump(compact('p', 'id'));
		return "PAGE TEST <br>";
	}

	public function uri_test(){
		return "URI TEST";
	}

	public function uri_with_params($p, $id){
		dd([$p, $id]);
	}

	public function index(){
		return app() -> router -> urlto('\PostGetTest\Controllers\AppController@get_test', ['p' => 1, 'id' => 2]);
	}
}