<? $this -> extends_from('base') ?>
<div class="row pt-3 pb-3">
	<div class="col-12">
		<h3>Create new task</h3>
	</div>	
</div>

<form action="/create" method="post">
	<div class="form-group">
		<label for="task">Task Description</label>
		<textarea name="task" id="task" class="form-control"></textarea>
	</div>
	<div class="form-group">
		<button class="btn btn-primary">Save</button>
	</div>
</form>