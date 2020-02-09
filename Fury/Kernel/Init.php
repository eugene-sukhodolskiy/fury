<?php

namespace Fury\Kernel;

/**
 * Class: Init
 * @author Eugene Sukhodolskiy <e.sukhodolskiy@outlook.com>
 * @version 0.1
 * Date: 09.02.2020
 */

class Init{
	/**
	 * Bootstrap class instance
	 *
	 * @var Bootstrap
	 */
	protected $bootstrap;

	/**
	 * Constructor with params
	 *
	 * @method __construct
	 *
	 * @param  Bootstrap $bootstrap Bootstrap instance
	 */
	public function __construct(Bootstrap $bootstrap){
		$this -> bootstrap = $bootstrap;
	}

	/**
	 * Method for framework and application initialization
	 * If you need changed order of initialization - ok, but be careful :)
	 *
	 * @method init
	 *
	 * @return void
	 */
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