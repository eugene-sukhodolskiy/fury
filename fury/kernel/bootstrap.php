<?php

namespace Fury\Kernel;

class Bootstrap{
	public $project_folder;
	public $db;
	public $router;
	public $events;

	public function __construct($project_folder){
		$this -> project_folder = $project_folder;
		$this -> init_config();
		$this -> init_events();
		$this -> events -> handler('kernel:init_db', function($params){
			echo "INITITALIZATION DB WAS SUCCESS";
		});
		$this -> init_db();
		$this -> init_routes();
	}

	private function init_config(){
		// init project config
		if(!file_exists("{$this -> project_folder}/config.php")){
			die("Config file not found!");
		}
		define("F_CONFIG", include_once("{$this -> project_folder}/config.php"));
	}

	private function init_db(){
		// init DB
		if(isset(F_CONFIG['db'])){
			$this -> db = new DB(F_CONFIG['db']);
			$this -> events -> kernel_call('init_db', ['db' => $this -> db]);
		}
	}

	private function init_routes(){
		// init router
		if(isset(F_CONFIG['routes_map_file'])){
			$path_to_routes_map_file = "{$this -> project_folder}/" . F_CONFIG['routes_map_file'];
			if(file_exists($path_to_routes_map_file)){
				include_once($path_to_routes_map_file);
			}
		}
	}

	private function init_events(){
		$this -> events = new Events();
	}
}