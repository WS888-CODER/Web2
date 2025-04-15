<?php
session_start();
include 'database.php';

// Check if doctor is logged in and has a valid session
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php"); // Redirect to login if not logged in as doctor
    exit();
}

$doctor_id = $_SESSION['user_id']; // Get doctor ID from session

// Get appointment ID from the URL
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];
} else {
    // Redirect to the homepage if appointment_id is not provided
    header("Location: DoctorHome.php");
    exit();
}

// Update appointment status to "Confirmed"
$query = "UPDATE Appointment SET status = 'Confirmed' WHERE id = ? AND DoctorID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $appointment_id, $doctor_id);
$success = mysqli_stmt_execute($stmt);

if ($success) {
    // Redirect back to the doctor’s homepage after confirmation
    header("Location: DoctorHome.php");
} else {
    // Handle any error that might occur during the update
    echo "Error confirming the appointment. Please try again later.";
}

// Close the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
