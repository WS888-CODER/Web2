<?php
session_start();
include 'database.php';

// Check user session
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch specialties
$specialties = [];
$specialties_query = "SELECT id, speciality FROM speciality";
$result = mysqli_query($conn, $specialties_query);
while ($row = mysqli_fetch_assoc($result)) {
    $specialties[] = $row;
}

// AJAX request: return doctors by speciality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['specialityID'])) {
    header('Content-Type: application/json');
    $specialityID = intval($_POST['specialityID']);
    $doctors = [];

    $stmt = mysqli_prepare($conn, "SELECT id, firstName, lastName FROM doctor WHERE specialityID = ?");
    mysqli_stmt_bind_param($stmt, 'i', $specialityID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }

    mysqli_stmt_close($stmt);
    echo json_encode($doctors);
    exit();
}

// Form submission: book appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['doctor_id'], $_POST['appointment_date'], $_POST['appointment_time'], $_POST['reason'], $_POST['csrf_token'])) {

    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Invalid CSRF token.";
        exit();
    }

    $doctor_id = intval($_POST['doctor_id']);
    $appointment_date = trim($_POST['appointment_date']);
    $appointment_time = trim($_POST['appointment_time']);
    $reason = htmlspecialchars(trim($_POST['reason']), ENT_QUOTES, 'UTF-8');

    if (empty($appointment_date) || empty($appointment_time) || empty($reason)) {
        echo "All fields are required.";
        exit();
    }

    if (!strtotime($appointment_date) || !strtotime($appointment_time) || strtotime($appointment_date . ' ' . $appointment_time) <= time()) {
        echo "Invalid date or time.";
        exit();
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    mysqli_stmt_bind_param($stmt, 'iisss', $patient_id, $doctor_id, $appointment_date, $appointment_time, $reason);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "<script>alert('Appointment booked successfully!'); window.location.href = 'PatientHome.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book an Appointment</title>
    <link rel="stylesheet" href="styles.css">

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function () {
        $('#speciality').change(function () {
            let specialityID = $(this).val();
            if (specialityID === "") return;

            $.ajax({
                type: 'POST',
                url: 'AppointmentBooking.php',
                data: { specialityID: specialityID },
                dataType: 'json',
                success: function (data) {
                    let doctorSelect = $('#doctor');
                    doctorSelect.empty();
                    doctorSelect.append("<option value=''>-- Select Doctor --</option>");
                    $.each(data, function (index, doctor) {
                        doctorSelect.append(`<option value="${doctor.id}">${doctor.firstName} ${doctor.lastName}</option>`);
                    });
                },
                error: function () {
                    alert('Error fetching doctors.');
                }
            });
        });
    });
    </script>
</head>
<body class="appBody">
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="https://wellnest.infinityfreeapp.com/wellnest/index.php">
                    <img src="images/logo.jpg" alt="Logo" class="logo">
                </a>
            </div>
        </nav>
    </header>

    <div id="appointmentContainer">
        <h1 id="book">Book an Appointment</h1>

        <form class="appointment-form">
            <label for="speciality" class="appointment-label">Select Specialty:</label>
            <select id="speciality" class="appointment-select">
                <option value="">-- Choose Specialty --</option>
                <?php foreach ($specialties as $specialty): ?>
                    <option value="<?= htmlspecialchars($specialty['id']) ?>">
                        <?= htmlspecialchars($specialty['speciality']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <form method="POST" id="appointmentForm" class="appointment-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <label for="doctor" class="appointment-label">Select Doctor:</label>
            <select name="doctor_id" id="doctor" class="appointment-select" required>
                <option value="">-- Select Doctor --</option>
            </select>

            <label for="appointment_date" class="appointment-label">Select Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" class="appointment-input" required>

            <label for="appointment_time" class="appointment-label">Select Time:</label>
            <input type="time" id="appointment_time" name="appointment_time" class="appointment-input" required>

            <label for="reason" class="appointment-label">Reason for Visit:</label>
            <textarea id="reason" name="reason" class="appointment-textarea" rows="4" placeholder="Enter the reason for your visit..." required></textarea>

            <button type="submit" class="appointment-button">Submit Booking</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Clinic Website. All Rights Reserved.</p>
        <p>Contact us: email@example.com | +123-456-7890</p>
    </footer>
</body>
</html>
