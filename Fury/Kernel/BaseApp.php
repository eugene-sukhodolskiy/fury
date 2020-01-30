<?php

namespace Fury\Kernel;

class BaseApp{
	public function __construct(){
		AppContainer::set_app($this);
	}
}