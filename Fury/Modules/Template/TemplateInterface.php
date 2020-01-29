<?php

namespace Fury\Modules\Template;

interface TemplateInterface{
	public static function set_driver($driver);
	
	/**
	 * Make and return html template
	 *
	 * @method make
	 *
	 * @param  string $template_name path to html template from template folder
	 * @param  array $inside_data Data for integration to template
	 *
	 * @return string Ready for view html
	 */
	public function make($template_name, $inside_data = []);

	/**
	 * Join to this template other
	 *
	 * @method join
	 *
	 * @param  string $child_template_name Path to template. "TemplateExtendsClass:template_to_path" or "template_to_path"
	 * @param  array $inside_data Data for integration to child template
	 *
	 * @return string Ready for view html, join child template
	 */
	public function join($child_template_name, array $inside_data = []);

	/**
	 * Get parent template object
	 *
	 * @method parent
	 *
	 * @return object Parent object
	 */
	public function parent();

	/**
	 * Get childs list for this template
	 *
	 * @method childs
	 *
	 * @return array childs list
	 */
	public function childs();

	/**
	 * Sets who the parent pattern is.
	 *
	 * @method extends_from
	 *
	 * @param  script $extends_template_name Path to parent (base) template. "TemplateExtendsClass:template_to_path" or "template_to_path"
	 *
	 * @return void
	 */
	public function extends_from($extends_template_name);

	/**
	 * Indicate in which place you want to display the content of the child
	 *
	 * @method content
	 *
	 * @return string Html content, ready for view
	 */
	public function content();

	/**
	 * Get inside data from this template
	 *
	 * @method get_inside_data
	 *
	 * @return array
	 */
	public function get_inside_data();

	/**
	 * Get ready for view html from this template
	 *
	 * @method get_html
	 *
	 * @return string HTML ready for view
	 */
	public function get_html();
}