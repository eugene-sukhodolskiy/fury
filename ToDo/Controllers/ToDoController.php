<?php

namespace ToDo\Controllers;

use ToDo\Models\ToDoList;

class ToDoController extends \ToDo\Middleware\Controller{
	public function index(){
		$todo_list = new ToDoList();
		return $this -> create_template() -> make(
			'todo.list', 
			['todo' => $todo_list -> get_list()]
		);
	}

	public function err_not_found(){
		return $this -> create_template() -> make('404');
	}

	public function update(){
		$tasks = $_POST['tasks'] ? $_POST['tasks'] : [];
		(new ToDoList()) -> update($tasks);
		return header('Location: /');
	}	

	public function create_page(){
		return $this -> create_template() -> make('todo.create');
	}

	public function create($task){
		if(!strlen($task)){
			return header('Location: /page/create');
		}

		(new ToDoList()) -> add($task);

		return header('Location: /');
	}

	public function delete($inx){
		(new ToDoList()) -> delete($inx);

		return header('Location: /');
	}
}