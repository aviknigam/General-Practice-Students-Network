<?php
require __DIR__ . '/core/init.php';

$title = "Page Not Found";
$description = "Bond GPSN page not found.";

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
				<header class="container landing text-center">
					<div class="site-title">
						<a href="<?= $configURL; ?>">Uh Oh.</a>
					</div>
					<div class="site-description">Error 404 | Page not found</div>
				</header>

                <div class="section">
                    <p>The page you were looking for doesn't exist.</p>
                    <p>It could have been moved or possibly been deleted, please <a href='/'>go back</a></p>
                </div>

				<!-- Footer -->
					<?php include 'includes/footer.php'; ?>
			</div>

	</body>
</html>