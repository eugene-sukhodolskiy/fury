<?php

namespace TestApp;

use Fury\Modules\Template\Template;

class Welcome extends \Fury\Kernel\Controller{
	public function hello($var1, $var2){
		echo "{$var1} {$var2}";
	}

	public function testing(){
		return "Hello world";
	}

	public function testing_with_params($id, $post){
		return "ID: {$id} and POST: {$post}";
	}

	public function template_test(){
		$template = new Template(
			$this -> bootstrap() -> project_folder,
			F_CONFIG['templates_folder']
		);
		return $template -> make('article', ['title' => 'Hello world']);
	}
}