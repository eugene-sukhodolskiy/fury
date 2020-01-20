<?php

namespace TestApp;

use Fury\Modules\Template\Template;

class Welcome extends \Fury\Kernel\Controller{
	public function index(){
		return "Hello";
	}
}