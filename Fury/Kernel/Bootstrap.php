<?php

namespace Fury\Kernel;

class Bootstrap{
	public $project_folder;
	public $db;
	protected $call_control;
	protected $init;

	public function __construct($project_folder){
		$this -> project_folder = $project_folder;
		AppContainer::set_bootstrap($this);
		$this -> init = new Init($this);
		$this -> init -> init();
	}

	public function init_config(){
		// init project config
		if(!file_exists("{$this -> project_folder}/config.php")){
			die("Config file not found!");
		}
		define("FCONF", include_once("{$this -> project_folder}/config.php"));
	}

	public function init_consts(){
		define("APP_NAME", FCONF['app_name']);
		define("PROJECT_FOLDER", $this -> project_folder);
	}

	public function init_db(){
		if(isset(FCONF['db'])){
			$this -> db = new DB(FCONF['db']);
		}
	}

	public function init_app_file(){
		// init app
		if(isset(FCONF['app_file'])){
			$path_to_app_file = "{$this -> project_folder}/" . FCONF['app_file'];
			if(file_exists($path_to_app_file)){
				include_once($path_to_app_file);
			}
		}
	}

	public function init_events(){
		$events = new Events();
		AppContainer::set_events($events);
	}

	public function init_call_control(){
		$this -> call_control = CallControl::ins($this);
	}

	public function init_logging(){
		$logging = new Logging();
		AppContainer::set_logging($logging);
	}

	public function init_model(){
		Model::ins($this -> db);
	}

	public function ready_app_event(){
		events() -> kernel_call('Bootstrap.ready_app', ['bootstrap' => $this]);
	}

	public function app_starting_event(){
		events() -> kernel_call('Bootstrap.app_starting', ['bootstrap' => $this]);
	}

	public function app_finished_event(){
		events() -> kernel_call('Bootstrap.app_finished', ['bootstrap' => $this]);
	}
}