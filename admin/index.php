<?php
require __DIR__ . '/../core/init.php';

$title = 'Admin Panel';

if (!isset($_SESSION['adminID'])) {
	header('Location: /admin/login');
	die();
}

// SPOTS PER SESSION
$studentLimit = '25';
$spLimit = '20';
$tutorLimit = '20';

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

		<div class="section">
			<?php
				$signed_up = $conn->query("SELECT * FROM students ORDER BY studentID DESC LIMIT 1");
				$signed_up = ($signed_up->fetch_assoc())['studentID'];
			?>
			<h2 class="text-center"><?= $signed_up; ?> members at Bond GPSN!</h2>
		</div>

		<div class="section">
			<!-- Navigation -->
				<div class="container section">
					<ul class="nav nav-tabs justify-content-center mb-4">
						<li class="nav-item">
							<a id="studentListButton" class="nav-link active" href="#studentList" data-toggle="tab">Students</a>
						</li>
						<li class="nav-item">
							<a id="spListButton" class="nav-link" href="#spList" data-toggle="tab">Simulated Patients</a>
						</li>
						<li class="nav-item">
							<a id="tutorListButton" class="nav-link" href="#tutorList" data-toggle="tab">Tutors</a>
						</li>
					</ul>
				</div>
		</div>
		<div class="section tab-content">
			<!-- Student List -->
				<div class="container section tab-pane fade show active table-responsive" id="studentList">
					<h4 class="mb-3">Session 1 - 6:00pm - 6:50pm</h4>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th class='t-center'>#ID</th>
								<th class="t-center">Student Number</th>
								<th class='t-center'>Name</th>
								<th class='t-center'>Email</th>
								<th class='t-center'>Status</th>
								<th class='t-center'>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i = 1;
								$stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '1' AND osceRole = 'student' ORDER BY osceCreated ASC");
								while($row = $stmt->fetch_assoc()) {
									$osceID = $row['osceID'];
									$studentID = $row['studentID'];
									$osceCreated = $row['osceCreated'];

									$studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '2'");
									while($row = $studentCheck->fetch_assoc()) {
										$studentID = $row['studentID'];
										$studentNumber = $row['studentNumber'];
										$studentFirstName = $row['studentFirstName'];
										$studentLastName = $row['studentLastName'];
										$studentMobile = $row['studentMobile'];
										$studentEmail = $row['studentEmail'];
										$studentYearLevel = $row['studentYearLevel'];

										echo "
											<tr>
												<td class='t-center'>$i</td>
												<td class='t-center'>$studentNumber</td>
												<td class='t-center'>$studentFirstName $studentLastName</td>
												<td class='t-center'>$studentEmail</td>
												<td class='t-center'>" .($i <= $studentLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
												<td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
											</tr>";

										$i++;
									}
								}
							?>
						</tbody>
					</table>
					<hr class="my-4">

					<h4 class="mb-3">Session 2 - 7:10pm - 8:00pm</h4>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th class='t-center'>#ID</th>
								<th class="t-center">Student Number</th>
								<th class='t-center'>Name</th>
								<th class='t-center'>Email</th>
								<th class='t-center'>Status</th>
								<th class='t-center'>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i = 1;
								$stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '2' AND osceRole = 'student' ORDER BY osceCreated ASC");
								while($row = $stmt->fetch_assoc()) {
									$osceID = $row['osceID'];
									$studentID = $row['studentID'];
									$osceCreated = $row['osceCreated'];

									$studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '2'");
									while($row = $studentCheck->fetch_assoc()) {
										$studentID = $row['studentID'];
										$studentNumber = $row['studentNumber'];
										$studentFirstName = $row['studentFirstName'];
										$studentLastName = $row['studentLastName'];
										$studentMobile = $row['studentMobile'];
										$studentEmail = $row['studentEmail'];
										$studentYearLevel = $row['studentYearLevel'];

										echo "
											<tr>
												<td class='t-center'>$i</td>
												<td class='t-center'>$studentNumber</td>
												<td class='t-center'>$studentFirstName $studentLastName</td>
												<td class='t-center'>$studentEmail</td>
												<td class='t-center'>" .($i <= $studentLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
												<td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
											</tr>";

										$i++;
									}
								}
							?>
						</tbody>
					</table>
				</div>
			
			<!-- SPs List -->
				<div class="container section tab-pane fade table-responsive" id="spList">
					<h4 class="mb-3">Session 1 - 6:00pm - 6:50pm</h4>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th class='t-center'>#ID</th>
								<th class="t-center">Student Number</th>
								<th class='t-center'>Name</th>
								<th class='t-center'>Email</th>
								<th class='t-center'>Status</th>
								<th class='t-center'>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i = 1;
								$stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '1' AND osceRole = 'sp' ORDER BY osceCreated ASC");
								while($row = $stmt->fetch_assoc()) {
									$osceID = $row['osceID'];
									$studentID = $row['studentID'];
									$osceCreated = $row['osceCreated'];

									$studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '2' AND osceSession = '1' AND osceRole = 'sp'");
									while($row = $studentCheck->fetch_assoc()) {
										$studentID = $row['studentID'];
										$studentNumber = $row['studentNumber'];
										$studentFirstName = $row['studentFirstName'];
										$studentLastName = $row['studentLastName'];
										$studentMobile = $row['studentMobile'];
										$studentEmail = $row['studentEmail'];
										$studentYearLevel = $row['studentYearLevel'];

										echo "
											<tr>
												<td class='t-center'>$i</td>
												<td class='t-center'>$studentNumber</td>
												<td class='t-center'>$studentFirstName $studentLastName</td>
												<td class='t-center'>$studentEmail</td>
												<td class='t-center'>" .($i <= $spLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
												<td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
											</tr>";

										$i++;
									}
								}
							?>
						</tbody>
					</table>
					<hr class="my-4">

					<h4 class="mb-3">Session 2 - 7:10pm - 8:00pm</h4>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th class='t-center'>#ID</th>
								<th class="t-center">Student Number</th>
								<th class='t-center'>Name</th>
								<th class='t-center'>Email</th>
								<th class='t-center'>Status</th>
								<th class='t-center'>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i = 1;
								$stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '2' AND osceRole = 'sp' ORDER BY osceCreated ASC");
								while($row = $stmt->fetch_assoc()) {
									$osceID = $row['osceID'];
									$studentID = $row['studentID'];
									$osceCreated = $row['osceCreated'];

									$studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '2' AND osceSession = '2' AND osceRole = 'sp'");
									while($row = $studentCheck->fetch_assoc()) {
										$studentID = $row['studentID'];
										$studentNumber = $row['studentNumber'];
										$studentFirstName = $row['studentFirstName'];
										$studentLastName = $row['studentLastName'];
										$studentMobile = $row['studentMobile'];
										$studentEmail = $row['studentEmail'];
										$studentYearLevel = $row['studentYearLevel'];

										echo "
											<tr>
												<td class='t-center'>$i</td>
												<td class='t-center'>$studentNumber</td>
												<td class='t-center'>$studentFirstName $studentLastName</td>
												<td class='t-center'>$studentEmail</td>
												<td class='t-center'>" .($i <= $spLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
												<td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
											</tr>";

										$i++;
									}
								}
							?>
						</tbody>
					</table>
				</div>

			<!-- Tutor List -->
				<div class="container section tab-pane fade table-responsive" id="tutorList">
					<h4 class="mb-3">Session 1 - 6:00pm - 6:50pm</h4>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th class='t-center'>#ID</th>
								<th class="t-center">Student Number</th>
								<th class='t-center'>Name</th>
								<th class='t-center'>Email</th>
								<th class='t-center'>Status</th>
								<th class='t-center'>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i = 1;
								$stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '1' AND osceRole = 'tutor' ORDER BY osceCreated ASC");
								while($row = $stmt->fetch_assoc()) {
									$osceID = $row['osceID'];
									$studentID = $row['studentID'];
									$osceCreated = $row['osceCreated'];

									$studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '2' AND osceSession = '1' AND osceRole = 'tutor'");
									while($row = $studentCheck->fetch_assoc()) {
										$studentID = $row['studentID'];
										$studentNumber = $row['studentNumber'];
										$studentFirstName = $row['studentFirstName'];
										$studentLastName = $row['studentLastName'];
										$studentMobile = $row['studentMobile'];
										$studentEmail = $row['studentEmail'];
										$studentYearLevel = $row['studentYearLevel'];

										echo "
											<tr>
												<td class='t-center'>$i</td>
												<td class='t-center'>$studentNumber</td>
												<td class='t-center'>$studentFirstName $studentLastName</td>
												<td class='t-center'>$studentEmail</td>
												<td class='t-center'>" .($i <= $tutorLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
												<td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
											</tr>";

										$i++;
									}
								}
							?>
						</tbody>
					</table>
					<hr class="my-4">

					<h4 class="mb-3">Session 2 - 7:10pm - 8:00pm</h4>
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th class='t-center'>#ID</th>
								<th class="t-center">Student Number</th>
								<th class='t-center'>Name</th>
								<th class='t-center'>Email</th>
								<th class='t-center'>Status</th>
								<th class='t-center'>Created</th>
							</tr>
						</thead>
						<tbody>
							<?php
								$i = 1;
								$stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '2' AND osceRole = 'tutor' ORDER BY osceCreated ASC");
								while($row = $stmt->fetch_assoc()) {
									$osceID = $row['osceID'];
									$studentID = $row['studentID'];
									$osceCreated = $row['osceCreated'];

									$studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '2' AND osceSession = '2' AND osceRole = 'tutor'");
									while($row = $studentCheck->fetch_assoc()) {
										$studentID = $row['studentID'];
										$studentNumber = $row['studentNumber'];
										$studentFirstName = $row['studentFirstName'];
										$studentLastName = $row['studentLastName'];
										$studentMobile = $row['studentMobile'];
										$studentEmail = $row['studentEmail'];
										$studentYearLevel = $row['studentYearLevel'];

										echo "
											<tr>
												<td class='t-center'>$i</td>
												<td class='t-center'>$studentNumber</td>
												<td class='t-center'>$studentFirstName $studentLastName</td>
												<td class='t-center'>$studentEmail</td>
												<td class='t-center'>" .($i <= $tutorLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
												<td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
											</tr>";

										$i++;
									}
								}
							?>
						</tbody>
					</table>
				</div>
			
		
		<footer>
			<?php include 'includes/footer.php'; ?>
		</footer>
	</body>
</html>
