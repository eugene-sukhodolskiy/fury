<?php

namespace Fury\Kernel;

class Bootstrap{
	public $project_folder;
	public $db;
	public $router;
	public $events;
	public $call_control;
	public $logging;

	public function __construct($project_folder){
		$this -> project_folder = $project_folder;

		// init
		$this -> init_config();
		$this -> init_consts();
		$this -> init_logging();
		$this -> init_events();

		$this -> app_starting_event();

		$this -> init_call_control();
		$this -> init_app_file();
		$this -> init_db();
		$this -> init_model();

		$this -> ready_app_event();

		$this -> app_finished_event();
	}

	private function init_config(){
		// init project config
		if(!file_exists("{$this -> project_folder}/config.php")){
			die("Config file not found!");
		}
		define("FCONF", include_once("{$this -> project_folder}/config.php"));
	}

	private function init_consts(){
		define("APP_NAME", FCONF['app_name']);
		define("PROJECT_FOLDER", $this -> project_folder);
	}

	private function init_db(){
		if(isset(FCONF['db'])){
			$this -> db = new DB(FCONF['db']);
		}
	}

	private function init_app_file(){
		// init app
		if(isset(FCONF['app_file'])){
			$path_to_app_file = "{$this -> project_folder}/" . FCONF['app_file'];
			if(file_exists($path_to_app_file)){
				include_once($path_to_app_file);
			}
		}
	}

	private function init_events(){
		$this -> events = Events::ins();
	}

	private function init_call_control(){
		$this -> call_control = CallControl::ins($this);
	}

	private function init_logging(){
		$this -> logging = Logging::ins();
	}

	private function init_model(){
		Model::ins($this -> db);
	}

	private function ready_app_event(){
		$this -> events -> kernel_call('Bootstrap.ready_app', ['bootstrap' => $this]);
	}

	private function app_starting_event(){
		$this -> events -> kernel_call('Bootstrap.app_starting', ['bootstrap' => $this]);
	}

	private function app_finished_event(){
		$this -> events -> kernel_call('Bootstrap.app_finished', ['bootstrap' => $this]);
	}
}