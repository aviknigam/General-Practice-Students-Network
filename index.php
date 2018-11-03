<?php
require __DIR__ . '/core/init.php';

$title = "Home";
$description = "";

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include 'includes/head.php'; ?>
		<link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?= time(); ?>"/>
		
	</head>
	<body>
		<!-- Navbar -->
			<?php include 'includes/navbar.php'; ?>
		
		<!-- Content -->
			<div class="site-margin">
				<div class="container landing">
					<h2 class="text-center">Welcome to Bond GPSN!</h2>
				</div>

				<!-- Announcements -->
					<div class="container section">
						<h4><a href="/osce" class="blue-a">OSCE Night Registrations are now open <i class="fa fa-fw fa-external-link" aria-hidden="true"></i></a></h4>
					</div>
					
					
				<!-- Footer -->
					<?php include 'includes/footer.php'; ?>

			</div>
	</body>
</html>