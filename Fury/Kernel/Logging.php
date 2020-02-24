<?php

namespace Fury\Kernel;

/**
 * Class for free logging
 */

class Logging extends \Fury\Libs\Singleton{
	/**
	 * Storage for session logs
	 *
	 * @var arrray
	 */
	protected $storage;

	/**
	 * Unique ID of SESSION
	 *
	 * @var string
	 */
	protected $session_id;

	/**
	 * Path to folder with logs
	 *
	 * @var string
	 */
	public $logs_folder;

	public function __construct(){
		if(!FCONF['logs_enable'])
				return false;

		$this -> storage = [];
		$this -> session_id = uniqid();
		$this -> logs_folder = FCONF['logs_folder'];
	}

	/**
	 * Set new log item
	 *
	 * @method set
	 *
	 * @param  string $place String in format "Classname@methname" or "funcname"
	 * @param  string $title Title of log. 
	 * @param  string $message Any text message
	 */
	public function set($place, $title, $message){
		if(!FCONF['logs_enable'])
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


	/**
	 * Dumping session logs to json file
	 *
	 * @method dump
	 *
	 * @return boolean Result of writing to log file
	 */
	public function dump(){
		$log_filename = date('d.m.Y') . '.log.json';
		$path_to_log_file = $this -> logs_folder . '/' . $log_filename;
		$session = [
			'session_id' => $this -> session_id,
			'timestamp' => microtime(true),
			'logs' => $this -> storage
		];

		if(!file_exists($path_to_log_file)){
			file_put_contents($path_to_log_file, '');
			chmod($path_to_log_file, 0755);
			return file_put_contents($path_to_log_file, json_encode([$session], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
		}

		$logs = json_decode(file_get_contents($path_to_log_file), true);
		$logs[] = $session;
		return file_put_contents($path_to_log_file, json_encode($logs, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
	}
}