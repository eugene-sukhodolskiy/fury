<?php

namespace Starter\Controllers;

class Welcome extends \Starter\Middleware\Controller{
	public function index(){
		return $this -> new_template() -> make("welcome");
	}
}