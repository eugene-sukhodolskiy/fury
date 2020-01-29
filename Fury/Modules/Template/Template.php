<?php

namespace Fury\Modules\Template;

/**
 * Class: Template
 * @author Eugene Sukhodolskiy <e.sukhodolskiy@outlook.com>
 * @version 0.1
 * Date: 10.01.2020
 */

class Template implements TemplateInterface{
	protected static $driver = false;

	protected $parent;
	protected $template_childs = [];

	public $templates_folder;
	public $project_folder;

	protected $template_html;
	public $template_name;
	public $template_file;
	public $template_content;
	protected $template_extends = ['flag' => false, 'srcname' => '', 'name' => '', 'object' => NULL];
	public $was_drawn = false;

	protected $inside_data;

	public static $all_templates = [];

	public function __construct($project_folder, $templates_folder, $parent = NULL){
		$this -> project_folder = $project_folder;
		$this -> templates_folder = $templates_folder;
		$this -> parent = $parent;
		self::$all_templates[] = $this;

		if(self::$driver){
			self::$driver -> event_create_template_instance($this);
		}
	}

	public static function set_driver($driver){
		self::$driver = $driver;
	}

	public function make($template_name, $inside_data = []){
		$template = $this -> t_path($template_name);

		if(self::$driver){
			self::$driver -> event_start_making($template_name, $template, $inside_data, $this);
		}

		$this -> inside_data = $inside_data;
		$this -> heir_manipulation_run();

		ob_start();
		extract($this -> inside_data);
		include $template;
		$html = ob_get_clean();

		$this -> template_html = $html;
		$this -> template_name = $template_name;
		$this -> template_file = $template;

		if($this -> template_extends['flag']){
			$this -> template_extends['object'] -> set_content($html);
			$this -> template_html = $this -> template_extends['object'] -> make($this -> template_extends['name']);
		}
		
		$this -> was_drawn();
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
		if(self::$driver){
			self::$driver -> event_start_joining($child_template_name, $inside_data);
		}
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
		$this -> template_extends['flag'] = true;
		$this -> template_extends['srcname'] = $extends_template_name;
		list($child_template, $child_template_name) = $this -> create_template_object($extends_template_name);
		$this -> template_extends['name'] = $child_template_name;
		$this -> template_extends['object'] = $child_template;
		$this -> template_childs[$this -> template_extends['srcname']] = $child_template;
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

	private function was_drawn(){
		$this -> was_drawn = true;

		if(self::$driver){
			self::$driver -> event_ready_template($this -> template_name, $this);
		}
	}
}