<?php

namespace TestApp;

use Fury\Modules\Template\Template;

class Welcome extends \Fury\Kernel\Controller{
	public function hello($var1, $var2){
		echo "{$var1} {$var2}";
	}

	public function testing(){
		dd($this -> bootstrap());
		dd([
			"Name" => "John",
			"Surname" => "Doe",
			"Age" => 48,
			"Student" => [
				"University" => "ZDTU",
				"Course" => 3
			]
		]);
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

	public function db_test($key, $val){
		Options::ins() -> set($key, $val);
	}
}