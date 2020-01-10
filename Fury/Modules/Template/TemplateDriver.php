<?php

/**
 * Driver for correctly connect Template module with Fury framework
 */

namespace Fury\Modules\Template;

use \Fury\Kernel\Events;

class TemplateDriver{
	public $events_ins;

	public function __construct(){
		$this -> events_ins = Events::ins();
	}

	public function gen_event_create_template_instance($template_instance){
		$this -> events_ins -> module_call(
			'Template.create_template_instance', 
			compact('template_instance')
		);
	}

	public function gen_event_start_making($template_name, $template_file, $inside_data, $template_instance){
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

	public function gen_event_ready_template($template_name, $template_instance){
		$this -> events_ins -> module_call(
			'Template.ready_template',
			compact(
				'template_instance', 
				'template_name'
			)
		);
	}

	public function gen_event_start_joining($child_template_name, $inside_data){
		$this -> events_ins -> module_call(
			'Template.start_joining',
			compact(
				'child_template_name', 
				'inside_data'
			)
		);
	}
}