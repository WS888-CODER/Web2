<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    echo json_encode(false);
    exit();
}

if (isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $doctor_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "UPDATE Appointment SET status = 'Confirmed' WHERE id = ? AND DoctorID = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $appointment_id, $doctor_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode($success);
    exit();
}

echo json_encode(false);
?>
