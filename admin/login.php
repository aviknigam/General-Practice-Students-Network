<?php
require __DIR__ . '/../core/init.php';

$title = 'Login';

if (isset($_SESSION['adminID'])) {
	header('Location: /admin');
	die();
}

if (isset($_POST['login'])) {
	$adminUsername = sanitize($_POST['adminUsername']);
	$adminPassword = sanitize($_POST['adminPassword']);

	$sql_admin = $conn->prepare("SELECT * FROM `admin` WHERE adminUsername = ?");
	$sql_admin->bind_param("s", $adminUsername);
	$sql_admin->execute();

	$row = ($sql_admin->get_result())->fetch_assoc();
	$hash_password = $row['adminPassword'];
	$hash = password_verify($adminPassword, $hash_password);

	// Track last login
	if ($hash == 1) {
		date_default_timezone_set('Australia/Brisbane');
		$date = date("Y-m-d H:i:s");
	
		$sql_admin = $conn->prepare("UPDATE `admin` SET adminTime = ? WHERE adminUsername = ?");
		$sql_admin->bind_param("ss", $date, $adminUsername);
		$sql_admin->execute();

		$_SESSION['adminID'] = $row['adminID'];

		header('Location: ./');
	}
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include 'includes/head.php'; ?>
	</head>
	<body>
		<header>
			<?php include 'includes/navbar.php'; ?>
		</header>

		<main>
			<!-- Login Form -->
				<div class="container">
					<div class="col-md-4 m-auto">
						<form action="" method="POST">
							<div class="form-group">
								<label>Username:</label>
								<input type="text" class="form-control" name="adminUsername">
							</div>
							<div class="form-group">
								<label>Password:</label>
								<input type="password" class="form-control" name="adminPassword">
							</div>
							<div class="d-flex justify-content-center">
								<button type="submit" class="btn btn-primary" name="login">Login</button>
							</div>
						</form>
					</div>
				</div>
		</main>

		<footer>
			<?php include 'includes/footer.php'; ?>
		</footer>
	</body>
</html>
