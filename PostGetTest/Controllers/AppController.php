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
}