<?php

namespace Fury\Kernel;

class Init{
	protected $bootstrap;

	public function __construct($bootstrap){
		$this -> bootstrap = $bootstrap;
	}

	public function init(){
		$this -> bootstrap -> init_config();
		$this -> bootstrap -> init_consts();
		$this -> bootstrap -> init_logging();
		$this -> bootstrap -> init_events();

		$this -> bootstrap -> app_starting_event();

		$this -> bootstrap -> init_call_control();
		$this -> bootstrap -> init_app_file();
		$this -> bootstrap -> init_db();
		$this -> bootstrap -> init_model();

		$this -> bootstrap -> ready_app_event();

		$this -> bootstrap -> app_finished_event();
	}
}