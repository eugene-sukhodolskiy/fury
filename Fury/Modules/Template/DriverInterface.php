<?php

namespace Fury\Modules\Template;

/**
 * interface DriverInterface
 * @author Eugene Sukhodolskiy <e.sukhodolskiy@outlook.com>
 * @version 0.1
 * Date: 29.01.2020
 */

interface DriverInterface{
	/**
	 * Generating event about create new template
	 *
	 * @method event_create_template_instance
	 *
	 * @param  Object $template_instance Object of Template type 
	 *
	 */
	public function event_create_template_instance($template_instance);

	/**
	 * Generating event about start making html template
	 *
	 * @method event_start_making
	 *
	 * @param  String $template_name Path to html template file
	 * @param  String $template_file [description]
	 * @param  Array $inside_data Data in Array format
	 * @param  Object $template_instance Object of Template type
	 *
	 */
	public function event_start_making(String $template_name, String $template_file, Array $inside_data, $template_instance);

	/**
	 * Event about ready template for printing
	 *
	 * @method event_ready_template
	 *
	 * @param  String $template_name Name of template
	 * @param  Object $template_instance Object of Template type
	 *
	 */
	public function event_ready_template(String $template_name, $template_instance);

	/**
	 * Event about joining part of template to main template
	 *
	 * @method event_start_joining
	 *
	 * @param  String $child_template_name Child template name
	 * @param  Array $inside_data Array with data
	 *
	 */
	public function event_start_joining(String $child_template_name, Array $inside_data);
}