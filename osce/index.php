<?php
require __DIR__ . '/../core/init.php';

$title = "OSCE Night Signup";
$description = "";

if (isset($_POST['studentForm']) || isset($_POST['spForm']) || isset($_POST['tutorForm'])) {
	$studentNumber = sanitize($_POST['studentNumber']);
	$studentFirstName = sanitize($_POST['studentFirstName']);
	$studentLastName = sanitize($_POST['studentLastName']);
	$studentMobile = sanitize($_POST['studentMobile']);
	$studentEmail = sanitize($_POST['studentEmail']);
	$studentYearLevel = sanitize($_POST['studentYearLevel']);

	// Determine OSCE Sessions
	if (isset($_POST['osceSession'][0])) {
		$osceSession1 = sanitize($_POST['osceSession'][0]);
	}

	if (isset($_POST['osceSession'][1])) {
		$osceSession2 = sanitize($_POST['osceSession'][1]);
	}

	// Determine OSCE Role
	if (isset($_POST['studentForm'])) { $osceRole = "student"; } 
	if (isset($_POST['spForm'])) { $osceRole = "sp"; } 
	if (isset($_POST['tutorForm'])) { $osceRole = "tutor"; } 
	
	// Check if student exists
	$studentEmail_check = $conn->prepare("SELECT * FROM students WHERE studentEmail = ?");
	$studentEmail_check->bind_param("s", $studentEmail);
	$studentEmail_check->execute();
	$studentEmail_check = $studentEmail_check->get_result();

	if (($studentEmail_check->num_rows) > 0) {
		while ($row = $studentEmail_check->fetch_assoc()) {
			$studentID = $row['studentID'];
		}

		$studentsUpdate = $conn->prepare("UPDATE students SET studentNumber = ?, studentFirstName = ?, studentLastName = ?, studentMobile = ?, studentEmail = ?, studentYearLevel = ?, uniID = ? WHERE studentID = ?");
		$studentsUpdate->bind_param("ssssssss", $studentNumber, $studentFirstName, $studentLastName, $studentMobile, $studentEmail, $studentYearLevel, $uniID, $studentID);
		$studentsUpdate->execute();

	} else {
		$studentsAdd = $conn->prepare("INSERT INTO students (studentNumber, studentFirstName, studentLastName, studentMobile, studentEmail, studentYearLevel, uniID, studentCreated) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$studentsAdd->bind_param("ssssssss", $studentNumber, $studentFirstName, $studentLastName, $studentMobile, $studentEmail, $studentYearLevel, $uniID, $date);
		$studentsAdd->execute();

		$studentID_check = $conn->query("SELECT * FROM students WHERE studentEmail = '$studentEmail'");
		while ($row = $studentID_check->fetch_assoc()) {
			$studentID = $row['studentID'];
		}
	}

	// Add to OSCE List
	if (isset($_POST['studentForm'])) {
		$osceCheck = $conn->query("SELECT * FROM osce WHERE studentID = $studentID AND osceRole = $osceRole");
	
		if ((!($osceCheck->num_rows) > 0) && isset($osceSession1)) {
			$osceAdd = $conn->prepare("INSERT INTO osce (studentID, uniID, osceRole, osceSession, osceCreated) VALUES (?, ?, ?, ?, ?)");
			$osceAdd->bind_param("sssss", $studentID, $uniID, $osceRole, $osceSession1, $date);
			$osceAdd->execute();

			header("Location: ./");
		} elseif ((!($osceCheck->num_rows) > 0) && isset($osceSession2)){
			$osceAdd = $conn->prepare("INSERT INTO osce (studentID, uniID, osceRole, osceSession, osceCreated) VALUES (?, ?, ?, ?, ?)");
			$osceAdd->bind_param("sssss", $studentID, $uniID, $osceRole, $osceSession2, $date);
			$osceAdd->execute();
	
			header("Location: ./");
			die();
		}
	} elseif (isset($osceSession1) && isset($osceSession2)) {
		$osceAdd = $conn->prepare("INSERT INTO osce (studentID, uniID, osceRole, osceSession, osceCreated) VALUES (?, ?, ?, ?, ?)");
		$osceAdd->bind_param("sssss", $studentID, $uniID, $osceRole, $osceSession1, $date);
		$osceAdd->execute();

		$osceAdd = $conn->prepare("INSERT INTO osce (studentID, uniID, osceRole, osceSession, osceCreated) VALUES (?, ?, ?, ?, ?)");
		$osceAdd->bind_param("sssss", $studentID, $uniID, $osceRole, $osceSession2, $date);
		$osceAdd->execute();

		header("Location: ./");
	} elseif (isset($osceSession1)) {
		$osceAdd = $conn->prepare("INSERT INTO osce (studentID, uniID, osceRole, osceSession, osceCreated) VALUES (?, ?, ?, ?, ?)");
		$osceAdd->bind_param("sssss", $studentID, $uniID, $osceRole, $osceSession1, $date);
		$osceAdd->execute();

		header("Location: ./");
	} elseif (isset($osceSession2)) {
		$osceAdd = $conn->prepare("INSERT INTO osce (studentID, uniID, osceRole, osceSession, osceCreated) VALUES (?, ?, ?, ?, ?)");
		$osceAdd->bind_param("sssss", $studentID, $uniID, $osceRole, $osceSession2, $date);
		$osceAdd->execute();

		header("Location: ./");
	}
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php include '../includes/head.php'; ?>
		<link rel="stylesheet" type="text/css" href="/dist/css/osce.css?<?= time(); ?>"/>
		<link rel="stylesheet" type="text/css" href="/dist/css/responsive.css?<?= time(); ?>"/>
		
	</head>
	<body>
		<!-- Navbar -->
			<?php include '../includes/navbar.php'; ?>
		
		<!-- Content -->
			<div class="site-margin">
				<div class="container landing">
					<h2 class="text-center">OSCE Night Signups</h2>
					<hr>
					<h4 class="text-center">14th November 2018</h2>
				</div>

				<!-- Announcements -->
					<div class="container section">
						<p>Register using the form below.</p>
						<ul>
							<li><span class="text-danger">If you cannot make it, please contact me below.</span></li>
						</ul>
						<p>If you are experiencing any difficulties please contact Avik Nigam on <a href="mailto:avik.nigam@student.bond.edu.au" class="blue-a">avik.nigam@student.bond.edu.au</a></p>
					</div>

				<!-- Navigation -->
					<div class="container section">
						<ul class="nav nav-tabs justify-content-center mb-4">
							<li class="nav-item">
								<a id="studentFormButton" class="nav-link active" href="#studentForm" data-toggle="tab">Students</a>
							</li>
							<li class="nav-item">
								<a id="spFormButton" class="nav-link" href="#spForm" data-toggle="tab">Simulated Patients</a>
							</li>
							<li class="nav-item">
								<a id="tutorFormButton" class="nav-link" href="#tutorForm" data-toggle="tab">Tutors</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<!-- Student Registration Form -->
							<div class="container section tab-pane fade show active" id="studentForm">
								<h4 class="mb-3">Registration Form - Students</h4>
								<form action="" method="POST">
									<div class="form-group">
										<label>Student ID <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentNumber" required>
									</div>
									<div class="form-group">
										<label>First Name <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentFirstName" required>
									</div>
									<div class="form-group">
										<label>Last Name <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentLastName" required>
									</div>
									<div class="form-group">
										<label>Mobile Number</label>
										<input type="text" class="form-control" name="studentMobile">
									</div>
									<div class="form-group">
										<label><?= $uniLongName; ?> Email Address <span class="text-red">*</span></label>
										<input type="email" class="form-control" name="studentEmail" value="@student.bond.edu.au" required>
									</div>
									<div class="form-group">
										<label>Preferred Session on 14th November<span class="text-red">*</span></label>
										<select class="form-control" name="osceSession[]">
											<option selected>-- Select a Session --</option>
											<option value="1">Session 1 - 6:00pm - 6:50pm</option>
											<option value="2">Session 2 - 7:10pm - 8:00pm</option>
										</select>
									</div>
									<div class="form-group">
										<label>Year Level <span class="text-red">*</span></label>
										<select class="form-control" name="studentYearLevel">
											<option selected>-- Select a Year --</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<!-- <option value="6">6</option> -->
										</select>
									</div>
									<button type="submit" name="studentForm" class="btn btn-primary">Submit</button>
								</form>
							</div>

						<!-- SP's Registration Form -->
							<div class="container section tab-pane fade" id="spForm">
								<h4 class="mb-3">Registration Form - Simulated Patients</h4>
								<form action="" method="POST">
									<div class="form-group">
										<label>Student ID <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentNumber" required>
									</div>
									<div class="form-group">
										<label>First Name <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentFirstName" required>
									</div>
									<div class="form-group">
										<label>Last Name <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentLastName" required>
									</div>
									<div class="form-group">
										<label>Mobile Number</label>
										<input type="text" class="form-control" name="studentMobile" required>
									</div>
									<div class="form-group">
										<label><?= $uniLongName; ?> Email Address <span class="text-red">*</span></label>
										<input type="email" class="form-control" name="studentEmail" value="@student.bond.edu.au" required>
									</div>
									<div class="my-4">
										<label>Availability on 14th November<span class="text-red">*</span></label>
											<div class="form-check pl-4">
												<input class="form-check-input" name="osceSession[]" type="checkbox" value="1">
												<label class="form-check-label">Session 1 - 6:00pm - 6:50pm</label>
											</div>
											<div class="form-check pl-4">
												<input class="form-check-input" name="osceSession[]" type="checkbox" value="2">
												<label class="form-check-label">Session 2 - 7:10pm - 8:00pm</label>
											</div>
									</div>
									<div class="form-group">
										<label>Year Level <span class="text-red">*</span></label>
										<select class="form-control" name="studentYearLevel">
											<option selected>-- Select a Year --</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<!-- <option value="6">6</option> -->
										</select>
									</div>
									<button type="submit" name="spForm" class="btn btn-primary">Submit</button>
								</form>
							</div>
						
						<!-- Tutor Registration Form -->
							<div class="container section tab-pane fade" id="tutorForm">
								<h4 class="mb-3">Registration Form - Tutors</h4>
								<form action="" method="POST">
									<div class="form-group">
										<label>Student ID <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentNumber" required>
									</div>
									<div class="form-group">
										<label>First Name <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentFirstName" required>
									</div>
									<div class="form-group">
										<label>Last Name <span class="text-red">*</span></label>
										<input type="text" class="form-control" name="studentLastName" required>
									</div>
									<div class="form-group">
										<label>Mobile Number</label>
										<input type="text" class="form-control" name="studentMobile" required>
									</div>
									<div class="form-group">
										<label><?= $uniLongName; ?> Email Address <span class="text-red">*</span></label>
										<input type="email" class="form-control" name="studentEmail" value="@student.bond.edu.au" required>
									</div>
									<div class="my-4">
										<label>Availability on 14th November<span class="text-red">*</span></label>
											<div class="form-check pl-4">
												<input class="form-check-input" name="osceSession[]" type="checkbox" value="1">
												<label class="form-check-label">Session 1 - 6:00pm - 6:50pm</label>
											</div>
											<div class="form-check pl-4">
												<input class="form-check-input" name="osceSession[]" type="checkbox" value="2">
												<label class="form-check-label">Session 2 - 7:10pm - 8:00pm</label>
											</div>
									</div>
									<div class="form-group">
										<label>Year Level <span class="text-red">*</span></label>
										<select class="form-control" name="studentYearLevel">
											<option selected>-- Select a Year --</option>
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<!-- <option value="6">6</option> -->
										</select>
									</div>
									<button type="submit" name="tutorForm" class="btn btn-primary">Submit</button>
								</form>
							</div>

					</div>
					
				<!-- Footer -->
					<?php include '../includes/footer.php'; ?>

			</div>
	</body>
</html>