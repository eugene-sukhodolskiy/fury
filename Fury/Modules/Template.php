<?php

namespace Fury\Modules;

class Template{
	protected $parent;
	protected $template_childs = [];

	public $templates_folder;
	public $project_folder;

	protected $template_html;
	public $template_name;
	public $template_file;
	public $template_content;
	protected $template_extends;

	protected $inside_data;

	public function __construct($project_folder, $templates_folder, $parent = NULL){
		$this -> project_folder = $project_folder;
		$this -> templates_folder = $templates_folder;
		$this -> parent = $parent;
	}

	public function make($template_name, $inside_data = []){
		$template = $this -> t_path($template_name);

		$this -> inside_data = $inside_data;
		$this -> heir_manipulation_run();

		ob_start();
		extract($this -> inside_data);
		include $template;
		$html = ob_get_clean();

		$this -> template_html = $html;
		$this -> template_name = $template_name;
		$this -> template_file = $template;

		if($this -> template_extends){
			list($extends_object, $extends_template_name) = $this -> create_template_object($this -> template_extends);
			$extends_object -> set_content($html);
			$this -> template_html = $extends_object -> make($extends_template_name);
		}
		
		return $this -> template_html;
	}	

	protected function t_path($template_name){
		if(strpos($template_name, '.php') === false){
			$template_name .= '.php';
		}
		return $this -> project_folder . '/' . $this -> templates_folder . '/' . $template_name;
	}

	public function get_html(){
		return $this -> $template_html;
	}

	public function join($child_template_name, array $inside_data = []){
		list($child_template, $child_template_name) = $this -> create_template_object($child_template_name);
		$this -> template_childs[$child_template_name] = $child_template;
		return $child_template -> make($child_template_name, $inside_data);
	}

	private function heir_manipulation_run(){
		$methname = 'heir_manipulation';
		if(method_exists($this, $methname)){
			$this -> $methname();
		}
	}

	protected function create_template_object($child_template_name){
		if(strpos($child_template_name, ':')){
			list($child_template_class, $child_template_name) = explode(':', $child_template_name);
		}

		if(!isset($child_template_class)){
			$child_template = new Template($this -> project_folder, $this -> templates_folder, $this);
		}else{
			$child_template = new $child_template_class($this -> project_folder, $this -> templates_folder, $this);
		}

		return [$child_template, $child_template_name];
	}

	public function parent(){
		return $this -> parent;
	}

	public function childs(){
		return $this -> template_childs;
	}

	public function extends_from($extends_template_name){
		$this -> template_extends = $extends_template_name;
		// Нужно создавать объект уже тут, чтоб добавить его в childs, иначе не сработает
	}

	public function set_content($content){
		$this -> template_content = $content;
	}

	public function content(){
		return $this -> template_content;
	}

	public function get_inside_data(){
		return $this -> inside_data;
	}
}