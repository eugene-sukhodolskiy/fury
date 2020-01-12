<?php

namespace TestApp;

class Options extends \Fury\Kernel\Model{
	public function set($key, $val){
		$sql = "INSERT INTO `Options` (`option_key`, `option_val`, `timestamp`) VALUES ('{$key}', '{$val}', NOW())";
		dd($this -> db() -> query($sql));
	}
}