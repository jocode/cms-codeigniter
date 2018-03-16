<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<?php echo $_css; ?>
</head>
<body>
	
	<h1>Plantilla default</h1>

	<?php foreach ($_content as $_view): ?>
		<?php include $_view; ?>
	<?php endforeach; ?>
	<?php echo $_js; ?>
</body>
</html>