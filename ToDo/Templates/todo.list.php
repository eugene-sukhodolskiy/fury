<?php $this -> extends_from('base'); ?>
<div class="row pt-3 pb-3">
	<h3>Fury ToDo List</h3>
</div>

<div class="row w-100">
	<form action="/update" class="w-100" method="post">
		<input type="hidden" name="todo_update">
		<ul class="list-group w-100">
			<?php foreach ($todo as $i => $todo_item): ?>
				<li class="list-group-item align-middle <? if($todo_item -> check): ?>checked<? endif ?>">

						<input 
							type="checkbox" 
							id="task_<?= $i ?>"
							name="tasks[]" 
							<? if($todo_item -> check): ?>
								checked="checked"
							<? endif ?>
							value="<?= $i ?>">

						<span><?= $todo_item -> task ?></span>
						<a href="/delete/<?= $i ?>" class="btn btn-danger btn-sm float-right">
							<i class="fa fa-trash"></i>
						</a>
				</li>
			<?php endforeach ?>
		</ul>
		<hr>
		<button class="btn btn-primary">Update</button>
		<a href="/page/create" class="btn btn-success">Create new</a>
	</form>
</div>