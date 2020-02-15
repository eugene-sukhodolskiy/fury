<?php

namespace Starter\Middleware;

use \Fury\Modules\Template\Template;

class Controller extends \Fury\Kernel\Controller{
	public function __construct(){
		parent::__construct();
	}

	public function new_template(){
		return new Template(PROJECT_FOLDER, FCONF['templates_folder']);
	}
}