<?php
session_start();
include 'database.php';

// Check if the doctor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'doctor') {
    header("Location: login.php");
    exit();
}

$doctor_id = $_SESSION['user_id'];
$patient_id = $_GET['patient_id'] ?? $_POST['patient_id'] ?? null;
$appointment_id = $_GET['appointment_id'] ?? null;

// Validate Patient ID and Appointment ID
if (!$patient_id || !is_numeric($patient_id)) {
    die("Patient ID is missing or invalid.");
}

if (!$appointment_id || !is_numeric($appointment_id)) {
    die("Appointment ID is missing or invalid.");
}

// Generate CSRF token if it's not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check the CSRF token on form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token mismatch.");
    }

    $medications = $_POST['medications'] ?? [];
    if (empty($medications)) {
        die("No medication selected.");
    }

    // Validate medications array
    foreach ($medications as &$medication) {
        $medication = htmlspecialchars($medication);
    }

    // Start transaction
    mysqli_begin_transaction($conn);

    try {
        // 1. Update appointment status to "Done"
        $update_query = "UPDATE appointment SET status = 'Done' WHERE id = ? AND DoctorID = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, 'ii', $appointment_id, $doctor_id);
        mysqli_stmt_execute($stmt);
        
        if (mysqli_stmt_affected_rows($stmt) === 0) {
            throw new Exception("Failed to update appointment status.");
        }
        mysqli_stmt_close($stmt);

        // 2. Fetch medication IDs securely
        $placeholders = implode(',', array_fill(0, count($medications), '?'));
        $query = "SELECT id, MedicationName FROM medication WHERE MedicationName IN ($placeholders)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($medications)), ...$medications);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $medication_ids = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $medication_ids[$row['MedicationName']] = $row['id'];
        }
        mysqli_stmt_close($stmt);

        // 3. Insert prescriptions securely
        $query = "INSERT INTO prescription (AppointmentID, MedicationID) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        foreach ($medication_ids as $medication_id) {
            mysqli_stmt_bind_param($stmt, 'ii', $appointment_id, $medication_id);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);

        // Commit transaction
        mysqli_commit($conn);

        // Success message and redirect
        $_SESSION['success_message'] = 'Prescription saved successfully and appointment marked as Done!';
        header("Location: DoctorHome.php");
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        die("Error processing prescription: " . $e->getMessage());
    }
}

// Fetch patient details securely
$query = "SELECT firstName, lastName, TIMESTAMPDIFF(YEAR, DoB, CURDATE()) AS age, gender FROM Patient WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $patient_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $firstName, $lastName, $age, $gender);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$patient_name = htmlspecialchars("$firstName $lastName");
$patient_age = htmlspecialchars($age);
$patient_gender = htmlspecialchars($gender);

// Fetch available medications
$query = "SELECT id, medicationName FROM medication";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescribe Medication</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="appBody">

    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="https://wellnest.infinityfreeapp.com/wellnest/index.php">
                    <img src="images/logo.jpg" alt="Logo" class="logo">
                </a>
                <a href="logout.php" class="signup-btn">Logout</a>
            </div>
        </nav>
    </header>

    <div id="prescribeContainer">
        <h1>Prescribe Medication</h1>
        <form method="post" action="" id="prescribeForm" class="prescribe-form">
            <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
            <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="patient-info">
                <label class="prescribe-label">Patient Name: <?php echo $patient_name; ?></label>
                <label class="prescribe-label">Patient Age: <?php echo $patient_age; ?></label>
                <label class="prescribe-label">Gender: <?php echo $patient_gender; ?></label>
            </div>

            <label class="prescribe-label">Select Medications:</label>
            <div id="medicationsList" class="medications-list">
                <?php while ($med = mysqli_fetch_assoc($result)): ?>
                    <div class="medication-item">
                        <input type="checkbox" id="med_<?php echo $med['id']; ?>" 
                               name="medications[]" value="<?php echo htmlspecialchars($med['medicationName']); ?>"
                               class="medication-checkbox">
                        <label for="med_<?php echo $med['id']; ?>"><?php echo htmlspecialchars($med['medicationName']); ?></label>
                    </div>
                <?php endwhile; ?>
            </div>

            <button type="submit" class="prescribe-button">Submit Prescription</button>
        </form>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Clinic Website. All Rights Reserved.</p>
        <p>Contact us: email@example.com | +123-456-7890</p>
    </footer>
       
</body>
</html>
<?php
mysqli_close($conn);
?>