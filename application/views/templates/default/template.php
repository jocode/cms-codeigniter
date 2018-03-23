<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../../../favicon.ico">

	<title><?php echo $title; ?></title>
	
	<!-- Bootstrap core and custom CSS -->
	<?php echo $_css; ?>
</head>

<body>

	<nav class="site-header sticky-top py-1">
		<div class="container d-flex flex-column flex-md-row justify-content-between">
			<a class="py-2" href="#">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mx-auto"><circle cx="12" cy="12" r="10"></circle><line x1="14.31" y1="8" x2="20.05" y2="17.94"></line><line x1="9.69" y1="8" x2="21.17" y2="8"></line><line x1="7.38" y1="12" x2="13.12" y2="2.06"></line><line x1="9.69" y1="16" x2="3.95" y2="6.06"></line><line x1="14.31" y1="16" x2="2.83" y2="16"></line><line x1="16.62" y1="12" x2="10.88" y2="21.94"></line></svg>
			</a>
			<a class="py-2 d-none d-md-inline-block" href="#">Tour</a>
			<a class="py-2 d-none d-md-inline-block" href="#">Product</a>
			<a class="py-2 d-none d-md-inline-block" href="#">Features</a>
			<a class="py-2 d-none d-md-inline-block" href="#">Enterprise</a>
			<a class="py-2 d-none d-md-inline-block" href="#">Support</a>
			<a class="py-2 d-none d-md-inline-block" href="#">Pricing</a>
			<a class="py-2 d-none d-md-inline-block" href="#">Cart</a>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-md-8">

				<?php foreach ($_warning as $msg): ?>
					<div class="alert alert-warning" role="alert">
						<?php echo $msg; ?>
					</div>
				<?php endforeach; ?>
				<?php foreach ($_error as $msg): ?>
					<div class="alert alert-danger" role="alert">
						<?php echo $msg; ?>
					</div>
				<?php endforeach; ?>
				<?php foreach ($_success as $msg): ?>
					<div class="alert alert-success" role="alert">
						<?php echo $msg; ?>
					</div>
				<?php endforeach; ?>
				<?php foreach ($_info as $msg): ?>
					<div class="alert alert-info" role="alert">
						<?php echo $msg; ?>
					</div>
				<?php endforeach; ?>

				<?php foreach ($_content as $_view): ?>
					<?php include $_view; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<footer class="container py-5">
		<div class="row">
			<div class="col-12 col-md">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block mb-2"><circle cx="12" cy="12" r="10"></circle><line x1="14.31" y1="8" x2="20.05" y2="17.94"></line><line x1="9.69" y1="8" x2="21.17" y2="8"></line><line x1="7.38" y1="12" x2="13.12" y2="2.06"></line><line x1="9.69" y1="16" x2="3.95" y2="6.06"></line><line x1="14.31" y1="16" x2="2.83" y2="16"></line><line x1="16.62" y1="12" x2="10.88" y2="21.94"></line></svg>
				<small class="d-block mb-3 text-muted">&copy; 2017-2018</small>
			</div>
			<div class="col-6 col-md">
				<h5>Features</h5>
				<ul class="list-unstyled text-small">
					<li><a class="text-muted" href="#">Cool stuff</a></li>
					<li><a class="text-muted" href="#">Random feature</a></li>
					<li><a class="text-muted" href="#">Team feature</a></li>
					<li><a class="text-muted" href="#">Stuff for developers</a></li>
					<li><a class="text-muted" href="#">Another one</a></li>
					<li><a class="text-muted" href="#">Last time</a></li>
				</ul>
			</div>
			<div class="col-6 col-md">
				<h5>Resources</h5>
				<ul class="list-unstyled text-small">
					<li><a class="text-muted" href="#">Resource</a></li>
					<li><a class="text-muted" href="#">Resource name</a></li>
					<li><a class="text-muted" href="#">Another resource</a></li>
					<li><a class="text-muted" href="#">Final resource</a></li>
				</ul>
			</div>
			<div class="col-6 col-md">
				<h5>Resources</h5>
				<ul class="list-unstyled text-small">
					<li><a class="text-muted" href="#">Business</a></li>
					<li><a class="text-muted" href="#">Education</a></li>
					<li><a class="text-muted" href="#">Government</a></li>
					<li><a class="text-muted" href="#">Gaming</a></li>
				</ul>
			</div>
			<div class="col-6 col-md">
				<h5>About</h5>
				<ul class="list-unstyled text-small">
					<li><a class="text-muted" href="#">Team</a></li>
					<li><a class="text-muted" href="#">Locations</a></li>
					<li><a class="text-muted" href="#">Privacy</a></li>
					<li><a class="text-muted" href="#">Terms</a></li>
				</ul>
			</div>
		</div>
	</footer>


    <!-- Bootstrap core JavaScript
    	================================================== -->
    	<!-- Placed at the end of the document so the pages load faster -->
    	<?php echo $_js; ?>
    	<script>
    		Holder.addTheme('thumb', {
    			bg: '#55595c',
    			fg: '#eceeef',
    			text: 'Thumbnail'
    		});
    	</script>
    </body>
    </html>
