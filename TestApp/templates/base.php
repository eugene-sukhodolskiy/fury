<? extract($this -> parent() -> get_inside_data()) ?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Base template</title>
</head>
<body>
	<h1><?= $title ?></h1>
	<?= $this -> join('\TestApp\NavbarTemplate:navbar') ?>

	<main>
		<?= $this -> content() ?>
	</main>
</body>
</html>