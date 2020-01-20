<?php

include_once __DIR__ . "/__autoload.php";
include_once __DIR__ . "/utils.php";

use Fury\Kernel\Bootstrap;

function fury_init($project_folder){
	return new Bootstrap($project_folder);
}