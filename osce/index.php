<?php
require __DIR__ . '/../core/init.php';

$title = "OSCE Night Signup";
$description = "";

// SPOTS PER SESSION
$sessionLimit = '25';

if (isset($_POST['registrationForm'])) {
    $studentNumber = sanitize($_POST['studentNumber']);
    $studentFirstName = sanitize($_POST['studentFirstName']);
    $studentLastName = sanitize($_POST['studentLastName']);
    $studentMobile = sanitize($_POST['studentMobile']);
    $studentEmail = sanitize($_POST['studentEmail']);
    $osceSession = sanitize($_POST['osceSession']);
    $studentYearLevel = sanitize($_POST['studentYearLevel']);
    
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
    $osceCheck = $conn->query("SELECT * FROM osce WHERE studentID = $studentID");
    
    if (!($osceCheck->num_rows) > 0) {
        $osceAdd = $conn->prepare("INSERT INTO osce (studentID, uniID, osceSession, osceCreated) VALUES (?, ?, ?, ?)");
        $osceAdd->bind_param("ssss", $studentID, $uniID, $osceSession, $date);
        $osceAdd->execute();

        header("Location: ./");
    } else {
        $osceUpdate = $conn->prepare("UPDATE osce SET uniID = ?, osceSession = ?, osceCreated = ? WHERE studentID = ?");
        $osceUpdate->bind_param("ssss", $uniID, $osceSession, $date, $studentID);
        $osceUpdate->execute();

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
			<?php // include '../includes/navbar.php'; ?>
		
		<!-- Content -->
			<div class="site-margin">
				<div class="container landing">
					<h2 class="text-center">OSCE Night Signups</h2>
				</div>

                <!-- Announcements -->
					<div class="container section">
                        <p>Register using the form below. Make sure your name appears on the list below (CTRL + F).</p>
                        <ul>
                            <li>To move sessions, refill your details and submit the form.</li>
                            <li>You will be moved to the <span class="text-danger">bottom of the new list</span> if you move sessions.</li>
                            <li>You will <span class="text-danger">forfeit</span> your current spot if you move sessions.</li>
                        </ul>
                        <p>If you are experiencing any difficulties please contact Avik Nigam on <a href="mailto:avik.nigam@student.bond.edu.au" class="blue-a">avik.nigam@student.bond.edu.au</a></p>
                    </div>

                <!-- Navigation -->
                    <div class="container section">
                        <ul class="nav nav-tabs justify-content-center mb-4">
                            <li class="nav-item">
                                <a id="registrationFormButton" class="nav-link active" href="#registrationForm" data-toggle="tab">Registration Form</a>
                            </li>
                            <li class="nav-item">
                                <a id="secondYearButton" class="nav-link" href="#secondYearList" data-toggle="tab">2nd Year List</a>
                            </li>
                            <li class="nav-item">
                                <a id="thirdYearButton" class="nav-link" href="#thirdYearList" data-toggle="tab">3rd Year List</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <!-- Registration Form -->
                            <div class="container section tab-pane fade show active" id="registrationForm">
                                <h4 class="mb-3">Registration Form</h4>
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label>Student ID</label>
                                        <input type="text" class="form-control" name="studentNumber">
                                    </div>
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" name="studentFirstName">
                                    </div>
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" name="studentLastName">
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input type="text" class="form-control" name="studentMobile">
                                    </div>
                                    <div class="form-group">
                                        <label><?= $uniLongName; ?> Email Address</label>
                                        <input type="email" class="form-control" name="studentEmail" value="@student.bond.edu.au">
                                    </div>
                                    <div class="form-group">
                                        <label>Session</label>
                                        <select class="form-control" name="osceSession">
                                            <option selected>-- Select a Session --</option>
                                            <option value="1">Session 1 - 6:30pm - 7:30pm</option>
                                            <option value="2">Session 2 - 7:00pm - 8:00pm</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Year Level</label>
                                        <select class="form-control" name="studentYearLevel">
                                            <option selected>-- Select a Year --</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="registrationForm" class="btn btn-primary">Submit</button>
                                </form>
                            </div>

                        <!-- 2nd Year List -->
                            <div class="container section tab-pane fade table-responsive" id="secondYearList">
                                <h4 class="mb-3">Session 1 - 5:30pm - 6:30pm</h4>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class='t-center'>#ID</th>
                                            <th class='t-center'>Name</th>
                                            <th class='t-center'>Status</th>
                                            <th class='t-center'>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            $stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '1' ORDER BY osceCreated ASC");
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
                                                            <td class='t-center'>$studentFirstName $studentLastName</td>
                                                            <td class='t-center'>" .($i <= $sessionLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
                                                            <td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
                                                        </tr>";

                                                    $i++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <hr class="my-4">

                                <h4 class="mb-3">Session 2 - 7:00pm - 8:00pm</h4>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class='t-center'>#ID</th>
                                            <th class='t-center'>Name</th>
                                            <th class='t-center'>Status</th>
                                            <th class='t-center'>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            $stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '2' ORDER BY osceCreated ASC");
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
                                                            <td class='t-center'>$studentFirstName $studentLastName</td>
                                                            <td class='t-center'>" .($i <= $sessionLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
                                                            <td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
                                                        </tr>";

                                                    $i++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        <!-- 3rd Year List -->
                            <div class="container section tab-pane fade" id="thirdYearList">
                            <h4 class="mb-3">Session 1 - 5:30pm - 6:30pm</h4>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class='t-center'>#ID</th>
                                            <th class='t-center'>Name</th>
                                            <th class='t-center'>Status</th>
                                            <th class='t-center'>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            $stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '1' ORDER BY osceCreated ASC");
                                            while($row = $stmt->fetch_assoc()) {
                                                $osceID = $row['osceID'];
                                                $studentID = $row['studentID'];
                                                $osceCreated = $row['osceCreated'];

                                                $studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '3'");
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
                                                            <td class='t-center'>$studentFirstName $studentLastName</td>
                                                            <td class='t-center'>" .($i <= $sessionLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
                                                            <td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
                                                        </tr>";

                                                    $i++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <hr class="my-4">

                                <h4 class="mb-3">Session 2 - 7:00pm - 8:00pm</h4>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class='t-center'>#ID</th>
                                            <th class='t-center'>Name</th>
                                            <th class='t-center'>Status</th>
                                            <th class='t-center'>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 1;
                                            $stmt = $conn->query("SELECT * FROM osce WHERE uniID = $uniID AND osceSession = '2' ORDER BY osceCreated ASC");
                                            while($row = $stmt->fetch_assoc()) {
                                                $osceID = $row['osceID'];
                                                $studentID = $row['studentID'];
                                                $osceCreated = $row['osceCreated'];

                                                $studentCheck = $conn->query("SELECT * FROM students LEFT JOIN osce ON students.studentID = osce.studentID WHERE osce.studentID = $studentID AND students.studentYearLevel = '3'");
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
                                                            <td class='t-center'>$studentFirstName $studentLastName</td>
                                                            <td class='t-center'>" .($i <= $sessionLimit ? '<span class="text-success">Registered</span>' : '<span class="text-danger">Waitlisted</span>'). "</td>
                                                            <td class='t-center'>" .date('jS M y, H:i:s', strtotime($osceCreated)). "</td>
                                                        </tr>";

                                                    $i++;
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                    </div>
                    
                <!-- Footer -->
                    <?php include '../includes/footer.php'; ?>

            </div>
	</body>
</html>