<?php

namespace TestApp;

class Welcome extends \Fury\Kernel\Controller{
	public function hello($var1, $var2){
		echo "{$var1} {$var2}";
	}

	public function testing(){
		dd($this -> app());
	}

	public function testing_with_params($id, $post){
		return "ID: {$id} and POST: {$post}";
	}
}