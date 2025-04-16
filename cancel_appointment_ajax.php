<?php
session_start();
include 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    echo json_encode(false);
    exit();
}

if (isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $patient_id = $_SESSION['user_id'];

    $stmt = mysqli_prepare($conn, "DELETE FROM Appointment WHERE id = ? AND PatientID = ?");
    mysqli_stmt_bind_param($stmt, 'ii', $appointment_id, $patient_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo json_encode($success);
    exit();
}

echo json_encode(false);
?>
