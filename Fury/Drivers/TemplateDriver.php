<?php

/**
 * Description: Driver for correctly connect Template module with Fury framework
 * Date: 29.01.2020
 * @author Eugene SUkhodolskiy <e.sukhodolskiy@outlook.com>
 * @version 0.1
 */

namespace Fury\Drivers;

use \Fury\Modules\Template\DriverInterface;
use \Fury\Kernel\AppContainer;

class TemplateDriver implements DriverInterface{
	public $events_ins;

	public function __construct(){
		$this -> events_ins = AppContainer::events();
	}

	public function event_create_template_instance($template_instance){
		$this -> events_ins -> module_call(
			'Template.create_template_instance', 
			compact('template_instance')
		);
	}

	public function event_start_making(String $template_name, String $template_file, Array $inside_data, $template_instance){
		$this -> events_ins -> module_call(
			'Template.start_making',
			compact(
				'template_instance', 
				'template_name', 
				'inside_data', 
				'template_file'
			)
		);
	}

	public function event_ready_template(String $template_name, $template_instance){
		$this -> events_ins -> module_call(
			'Template.ready_template',
			compact(
				'template_instance', 
				'template_name'
			)
		);
	}

	public function event_start_joining(String $child_template_name, Array $inside_data){
		$this -> events_ins -> module_call(
			'Template.start_joining',
			compact(
				'child_template_name', 
				'inside_data'
			)
		);
	}
}