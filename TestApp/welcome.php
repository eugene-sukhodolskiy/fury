<?php

namespace TestApp;

class Welcome{
	public function hello($var1, $var2){
		echo "{$var1} {$var2}";
	}

	public function testing(){
		echo "testing";
	}

	public function testing_with_params($id, $post){
		return "ID: {$id} and POST: {$post}";
	}
}