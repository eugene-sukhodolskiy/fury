<?php

namespace ThinBuilderTest;

use \Fury\Modules\ThinBuilder\ThinBuilder;
use \Fury\Drivers\ThinBuilderDriver;

class Test{
	public $tb;

	public function __construct(){
		$events = \Fury\Kernel\Events::ins();
		$self = $this;

		$events -> handler('kernel:ThinBuilder.query', function($p){
			dd($p);
		});

		$events -> handler('kernel:Bootstrap.ready_app', function($p) use ($self){
			extract($p);
			$db_conf = FCONF['db'];
			$self -> tb = new ThinBuilder($db_conf, new ThinBuilderDriver($bootstrap));
			dd($self -> tb -> select('users'));
		});
		
	}
}

new Test();