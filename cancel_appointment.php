<?php
session_start();
include 'database.php';

// Check if patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.php"); // Redirect to login if not logged in as patient
    exit();
}

$patient_id = $_SESSION['user_id']; // Get patient ID from session

// Check if appointment ID is provided in the URL
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Delete the appointment from the database
    $query = "DELETE FROM Appointment WHERE id = ? AND PatientID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $appointment_id, $patient_id);
    
    if (mysqli_stmt_execute($stmt)) {
        // Redirect back to patient homepage after successful cancellation
        header("Location: PatientHome.php");
        exit();
    } else {
        // Handle error if the deletion fails
        echo "Error cancelling the appointment. Please try again later.";
    }
    
    mysqli_stmt_close($stmt);
} else {
    // If appointment ID is not provided, redirect to patient homepage
    header("Location: PatientHome.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
