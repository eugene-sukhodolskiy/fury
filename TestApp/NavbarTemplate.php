<?php

namespace TestApp;

class NavbarTemplate extends \Fury\Modules\Template\Template{
	public function heir_manipulation(){
		$this -> inside_data = [
			'items' => [
					'Home', 'About', 'Contacts'
				]
			];
	}
}