<?php

namespace Fury\Kernel;

class Bootstrap{
	public $project_folder;
	public $db;
	public $router;

	public function __construct($project_folder){
		$this -> project_folder = $project_folder;
		$this -> init_config();
		$this -> init_db();
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
		}
	}

	private function init_routes(){
		// init router
		if(isset(F_CONFIG['route_map_file'])){
			$path_to_routes_map_file = "{$this -> project_folder}/{F_CONFIG['routes_map_file']}";
			if(file_exists($path_to_routes_map_file)){
				include_once($path_to_routes_map_file);
			}
		}
	}
}