<?php

namespace Starter;

class EventsHandlers{
	public function handlers(){
		events() -> handler('kernel:Bootstrap.ready_app', function($params){
			app() -> routes -> routes_init();
			app() -> router -> start_routing();
		});

		events() -> handler('kernel:CallControl.no_calls', function($p){
			echo "404 not found";
		});
	}
}