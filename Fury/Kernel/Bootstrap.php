<?php

namespace Fury\Kernel;

/**
 * Class: Bootstrap
 * @author Eugene Sukhodolskiy <e.sikhodolskiy@outlook.com>
 * Date: 05.01.2020
 * Update At: 09.02.2020
 * @version  0.1
 */

class Bootstrap{
	/**
	 * Name of current project folder
	 *
	 * @var String
	 */
	public $project_folder;

	/**
	 * DB class instance
	 *
	 * @var DB
	 */
	public $db;

	/**
	 * CallControl instance
	 *
	 * @var CallControl
	 */
	protected $call_control;

	/**
	 * Init class instance
	 *
	 * @var Init
	 */
	protected $init;

	/**
	 * Constructor with params
	 *
	 * @method __construct
	 *
	 * @param  String $project_folder Name of current project folder
	 */
	public function __construct(String $project_folder){
		$this -> project_folder = $project_folder;
		AppContainer::set_bootstrap($this);
		$this -> init = new Init($this);
		$this -> init -> init();
	}

	/**
	 * Initialization global application config
	 *
	 * @method init_config
	 *
	 * @return void
	 */
	public function init_config(){
		// init project config
		if(!file_exists("{$this -> project_folder}/config.php")){
			die("Config file not found!");
		}
		define("FCONF", include_once("{$this -> project_folder}/config.php"));
	}

	/**
	 * Initialization global application constants
	 *
	 * @method init_consts
	 *
	 * @return void
	 */
	public function init_consts(){
		define("APP_NAME", FCONF['app_name']);
		define("PROJECT_FOLDER", $this -> project_folder);
	}

	/**
	 * Initialization of default DB connection, but only if exists connect parameters to database
	 *
	 * @method init_db
	 *
	 * @return void
	 */
	public function init_db(){
		if(isset(FCONF['db']) and FCONF['default_db_wrap']){
			$this -> db = new DB(FCONF['db']);
		}
	}

	/**
	 * Initialization of application started files
	 *
	 * @method init_app_file
	 *
	 * @return void
	 */
	public function init_app_file(){
		if(isset(FCONF['app_file'])){
			$path_to_app_file = "{$this -> project_folder}/" . FCONF['app_file'];
			if(file_exists($path_to_app_file)){
				include_once($path_to_app_file);
			}
		}
	}

	/**
	 * Initialization of events system
	 *
	 * @method init_events
	 *
	 * @return void
	 */
	public function init_events(){
		$events = new Events();
		AppContainer::set_events($events);
	}

	/**
	 * Initialization of call controller
	 *
	 * @method init_call_control
	 *
	 * @return void
	 */
	public function init_call_control(){
		$this -> call_control = CallControl::ins($this);
	}

	/**
	 * Initialization of logging system
	 *
	 * @method init_logging
	 *
	 * @return void
	 */
	public function init_logging(){
		$logging = new Logging();
		AppContainer::set_logging($logging);
	}

	/**
	 * Initialization of base model
	 *
	 * @method init_model
	 *
	 * @return void
	 */
	public function init_model(){
		Model::ins($this -> db);
	}

	/**
	 * Adding call of event about application on ready
	 *
	 * @method ready_app_event
	 *
	 * @return void
	 */
	public function ready_app_event(){
		events() -> kernel_call('Bootstrap.ready_app', ['bootstrap' => $this]);
	}

	/**
	 * Adding call of event about application started
	 *
	 * @method app_starting_event
	 *
	 * @return void
	 */
	public function app_starting_event(){
		events() -> kernel_call('Bootstrap.app_starting', ['bootstrap' => $this]);
	}

	/**
	 * Adding call of event about application finished
	 *
	 * @method app_finished_event
	 *
	 * @return void
	 */
	public function app_finished_event(){
		events() -> kernel_call('Bootstrap.app_finished', ['bootstrap' => $this]);
	}
}