<?php
session_start();
include 'database.php';

// Check if doctor is logged in and has a valid session
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php"); // Redirect to login if not logged in as doctor
    exit();
}

$doctor_id = $_SESSION['user_id']; // Get doctor ID from session

// Fetch doctor details including photo
$query = "SELECT firstName, lastName, emailAddress, SpecialityID, uniqueFileName FROM Doctor WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $doctor_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $firstName, $lastName, $email, $specialityID, $uniqueFileName);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Set doctor photo path
$doctorPhoto = !empty($uniqueFileName) ? "images/" . htmlspecialchars($uniqueFileName) : "images/default-doctor.jpg";


// Fetch doctor speciality
$query = "SELECT speciality FROM Speciality WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $specialityID);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $speciality);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Fetch upcoming appointments for the doctor (pending or confirmed)
$query = "SELECT a.date, a.time, p.firstName AS patientFirstName, p.lastName AS patientLastName, 
TIMESTAMPDIFF(YEAR, p.DoB, CURDATE()) AS age, p.gender, a.reason, a.status, a.id AS appointment_id, p.id AS patient_id
FROM Appointment a 
JOIN Patient p ON a.PatientID = p.id
WHERE a.DoctorID = ? AND (a.status = 'Pending' OR a.status = 'Confirmed')
ORDER BY a.date, a.time";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $doctor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch all patients who had 'Done' appointments with this doctor
$query_patients = "SELECT DISTINCT p.id, p.firstName, p.lastName, TIMESTAMPDIFF(YEAR, p.DoB, CURDATE()) AS age, p.gender 
                   FROM Appointment a 
                   JOIN Patient p ON a.PatientID = p.id 
                   WHERE a.DoctorID = ? AND a.status = 'Done'
                   ORDER BY p.lastName, p.firstName";
$stmt_patients = mysqli_prepare($conn, $query_patients);
mysqli_stmt_bind_param($stmt_patients, 'i', $doctor_id);
mysqli_stmt_execute($stmt_patients);
$patients_result = mysqli_stmt_get_result($stmt_patients);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Homepage</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="appBody">
  <header class="header">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <a href="https://wellnest.infinityfreeapp.com/wellnest/index.php"><img src="images/logo.jpg" alt="Logo" class="logo"></a>
        <a href="logout.php" class="signup-btn">Logout</a>
    </div>
</header>
    <div id="m-section-container">
        <div class="hero-doctor">
            <h1>Welcome <span><?php echo htmlspecialchars($firstName . " " . $lastName); ?></span><br><span class="greenColor">WELLNEST</span> proud to <br> have you as part of <br> our team</h1>
        </div>
       <div id="m-section-image-container">
    <img src="<?php echo $doctorPhoto; ?>" alt="Doctor Profile Picture">
</div>


        
        <div id="m-section-ovals-container">
            <div class="m-section-oval" id="oval1">
                <img src="images/firstName.png" alt="Oval Image 1">
                <span class="m-section-text">First Name:</span><span><?php echo htmlspecialchars($firstName); ?></span>
            </div>
            <div class="m-section-oval" id="oval2">
                <img src="images/lastName.png" alt="Oval Image 2">
                <span class="m-section-text">Last Name:</span><span><?php echo htmlspecialchars($lastName); ?></span>
            </div>
            <div class="m-section-oval" id="oval3">
                <img src="images/id.png" alt="Oval Image 3">
                <span class="m-section-text">ID:</span><span><?php echo htmlspecialchars($doctor_id); ?></span>
            </div>
            <div class="m-section-oval" id="oval4">
                <img src="images/specialty.png" alt="Oval Image 4">
                <span class="m-section-text">Specialty:</span><span><?php echo htmlspecialchars($speciality); ?></span>
            </div>
            <div class="m-section-oval" id="oval5">
                <img src="images/email.png" alt="Oval Image 5">
                <span class="m-section-text">Email:</span><span><?php echo htmlspecialchars($email); ?></span>
            </div>
        </div>
    </div>
    
    <section class="doctorTables">
        <h2>Upcoming Appointments</h2>
        <table id="upcomingAppointmentsTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient's Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Reason for Visit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['date']); ?></td>
            <td><?php echo htmlspecialchars($row['time']); ?></td>
            <td><?php echo htmlspecialchars($row['patientFirstName'] . " " . $row['patientLastName']); ?></td>
            <td><?php echo htmlspecialchars($row['age']); ?></td>
            <td><?php echo htmlspecialchars($row['gender']); ?></td>
            <td><?php echo htmlspecialchars($row['reason']); ?></td>
            <td>
                <?php 
                    if ($row['status'] === 'Pending'): 
                        echo '<span style="color: orange;">Pending</span><br>';
                        echo '<a href="confirm_appointment.php?appointment_id=' . $row['appointment_id'] . '" class="prescribe-btn">Confirm</a>';
                    elseif ($row['status'] === 'Confirmed'):
                        echo '<span style="color: green;">Confirmed</span><br>';
                        echo '<a href="prescribemedication.php?patient_id=' . $row['patient_id'] . '&appointment_id=' . $row['appointment_id'] . '" class="prescribe-btn">Prescribe</a>';
                    endif;
                    
                ?>
            </td>
        </tr>
    <?php endwhile; ?>
</tbody>

        </table>
        
        <!-- Previous code remains the same until the patients table section -->
        <h2>Your Patients</h2>
        <table id="patientsTable">
            <thead>
                <tr>
                    <th>Patient's Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Medications</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($patient = mysqli_fetch_assoc($patients_result)): 
                    // Fetch medications for this patient
                    $patient_id = $patient['id'];
                    $med_query = "SELECT m.MedicationName 
                                 FROM Prescription p
                                 JOIN Medication m ON p.MedicationID = m.id
                                 JOIN Appointment a ON p.AppointmentID = a.id
                                 WHERE a.PatientID = ? AND a.DoctorID = ?
                                 GROUP BY m.MedicationName";
                    $med_stmt = mysqli_prepare($conn, $med_query);
                    mysqli_stmt_bind_param($med_stmt, 'ii', $patient_id, $doctor_id);
                    mysqli_stmt_execute($med_stmt);
                    $med_result = mysqli_stmt_get_result($med_stmt);
                    
                    $medications = array();
                    while ($med_row = mysqli_fetch_assoc($med_result)) {
                        $medications[] = $med_row['MedicationName'];
                    }
                    mysqli_stmt_close($med_stmt);
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($patient['firstName'] . " " . $patient['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($patient['age']); ?></td>
                        <td><?php echo htmlspecialchars($patient['gender']); ?></td>
                        <td>
                            <?php if (!empty($medications)): ?>
                                <?php echo htmlspecialchars(implode(", ", $medications)); ?>
                            <?php else: ?>
                                No medications prescribed
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
<!-- Rest of the code remains the same -->
    </section>

    <footer class="footer">
        <p>&copy; 2025 Clinic Website. All Rights Reserved.</p>
        <p>Contact us: email@example.com | +123-456-7890</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>