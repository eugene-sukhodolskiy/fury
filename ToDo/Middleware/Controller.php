<?php

namespace ToDo\Middleware;

use \Fury\Modules\Template\Template;

class Controller extends \Fury\Kernel\Controller{
	public function create_template(){
		return new Template(PROJECT_FOLDER, FCONF['templates_folder']);
	}
}