<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Restrict access based on user type
if ($_SESSION['user_type'] == 'doctor' && basename($_SERVER['PHP_SELF']) == 'PatientHome.php') {
    header("Location: DoctorHome.php");
    exit();
} elseif ($_SESSION['user_type'] == 'patient' && basename($_SERVER['PHP_SELF']) == 'DoctorHome.php') {
    header("Location: PatientHome.php");
    exit();
}
?>
