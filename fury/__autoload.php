<?php

spl_autoload_register(function($classname){
	$classname_lwrcase = strtolower($classname);
	$class_file = str_replace("\\", "/", $classname_lwrcase) . ".php";
	include_once $class_file;
});