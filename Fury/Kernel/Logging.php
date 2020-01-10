<?php

namespace Fury\Kernel;

class Logging extends \Fury\Libs\Singleton{
	protected $storage;
	protected $session_id;
	public $logs_folder;
	public $project_folder;

	public function __construct(){
		if(!F_CONFIG['logs_enable'])
				return false;

		$this -> storage = [];
		$this -> session_id = uniqid();
		$this -> logs_folder = F_CONFIG['logs_folder'];
	}

	public function set($place, $title, $message){
		if(!F_CONFIG['logs_enable'])
				return false;

		if(strpos($place, '@') === false){
			$class = '';
			$meth = $place;
		}else{
			list($class, $meth) = explode('@', $place);
		}

		$this -> storage[] = [
			'class' => $class,
			'meth' => $meth,
			'title' => $title,
			'message' => $message,
			'timestamp' => microtime(true)
		];

		return true;
	}

	public function dump(){
		$log_filename = date('d.m.Y') . '.log.json';
		$path_to_log_file = $this -> logs_folder . '/' . $log_filename;
		$session = [
			'session_id' => $this -> session_id,
			'timestamp' => microtime(true),
			'logs' => $this -> storage
		];

		if(!file_exists($path_to_log_file)){
			return file_put_contents($path_to_log_file, json_encode([$session], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
		}

		$logs = json_decode(file_get_contents($path_to_log_file), true);
		$logs[] = $session;
		return file_put_contents($path_to_log_file, json_encode($logs, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
	}
}