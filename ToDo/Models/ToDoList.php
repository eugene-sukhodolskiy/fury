<?php

namespace ToDo\Models;

class ToDoList{
	public function get_list(){
		$db_file = file_get_contents(PROJECT_FOLDER . '/DatabaseJSON/ToDo.list.json');
		return array_reverse(json_decode($db_file));
	}

	public function update($tasks = []){
		$db_tasks = $this -> get_list();

		foreach ($db_tasks as $i => $db_task) {
			$db_tasks[$i] -> check = false;
		}

		foreach ($tasks as $inx) {
			$db_tasks[$inx] -> check = true;
		}

		return $this -> store($db_tasks);
	}

	public function store($tasks){
		$tasks = array_reverse($tasks);
		return file_put_contents(PROJECT_FOLDER . '/DatabaseJSON/ToDo.list.json', json_encode($tasks));
	}

	public function add($task){
		$tasks = $this -> get_list();
		$tasks[] = [
			"task" => $task,
			"check" => false
		];
		return $this -> store($tasks);
	}

	public function delete($inx){
		$tasks = $this -> get_list();
		unset($tasks[$inx]);
		return $this -> store($tasks);
	}
}