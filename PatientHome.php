<?php
session_start();
include 'database.php';

// Check if patient is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'patient') {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

// Fetch patient details
$query = "SELECT firstName, lastName, emailAddress FROM Patient WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $patient_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $firstName, $lastName, $email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Fetch appointments
$query_appointments = "SELECT a.id, a.date, a.time, d.firstName AS doctorFirstName, d.lastName AS doctorLastName, 
                             d.uniqueFileName AS doctorImage, a.status 
                       FROM Appointment a 
                       JOIN Doctor d ON a.DoctorID = d.id 
                       WHERE a.PatientID = ? 
                       ORDER BY a.date, a.time";
$stmt_appointments = mysqli_prepare($conn, $query_appointments);
mysqli_stmt_bind_param($stmt_appointments, 'i', $patient_id);
mysqli_stmt_execute($stmt_appointments);
$appointments_result = mysqli_stmt_get_result($stmt_appointments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Homepage</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.cancel-btn').on('click', function (e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to cancel this appointment?')) return;

        const row = $(this).closest('tr');
        const appointmentId = $(this).data('id');

        $.ajax({
          type: 'POST',
          url: 'cancel_appointment_ajax.php',
          data: { appointment_id: appointmentId },
          success: function (response) {
            if (response === true || response === "true") {
              row.remove();
            } else {
              alert('Cancellation failed.');
            }
          },
          error: function () {
            alert('Error sending cancellation request.');
          }
        });
      });
    });
  </script>
</head>
<body>
  <header class="header">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <a href="https://wellnest.infinityfreeapp.com/wellnest/index.php"><img src="images/logo.jpg" alt="Logo" class="logo"></a>
        <a href="logout.php" class="signup-btn">Logout</a>
    </div>
  </header>

  <section>
    <div id="m-section-container">
      <div class="hero-doctor">
        <h1>Welcome <span><?= htmlspecialchars($firstName . " " . $lastName); ?></span> <br> 
        <span class="greenColor">WELLNEST</span> glad to <br> take care of you</h1>
      </div>

      <div id="ovals-grid-section">
        <div class="grid-oval" id="grid-oval1">
          <img src="images/firstName.png" alt="Oval Image 1">
          <span class="grid-text">First Name:</span><span><?= htmlspecialchars($firstName); ?></span>
        </div>
        <div class="grid-oval" id="grid-oval2">
          <img src="images/lastName.png" alt="Oval Image 2">
          <span class="grid-text">Last Name:</span><span><?= htmlspecialchars($lastName); ?></span>
        </div>
        <div class="grid-oval" id="grid-oval3">
          <img src="images/id.png" alt="Oval Image 3">
          <span class="grid-text">ID:</span><span><?= htmlspecialchars($patient_id); ?></span>
        </div>
        <div class="grid-oval" id="grid-oval4">
          <img src="images/email.png" alt="Oval Image 4">
          <span class="grid-text">Email:</span><span><?= htmlspecialchars($email); ?></span>
        </div>
      </div>
    </div>

    <section class="doctorTables">
      <h2>Your Appointments</h2>
      <a href="AppointmentBooking.php" class="prescribe-btn">Book an Appointment</a>
      <table id="patientAppointmentsTable">
        <thead>
          <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Doctor's Name</th>
            <th>Doctor's Photo</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($appointments_result)): ?>
            <tr>
              <td><?= htmlspecialchars($row['date']); ?></td>
              <td><?= htmlspecialchars($row['time']); ?></td>
              <td><?= htmlspecialchars($row['doctorFirstName'] . " " . $row['doctorLastName']); ?></td>
              <td><img src="images/<?= htmlspecialchars($row['doctorImage']); ?>" alt="Doctor Photo" width="50"></td>
              <td><?= htmlspecialchars($row['status']); ?></td>
              <td>
                <?php if ($row['status'] !== 'Done'): ?>
                  <a href="#" class="prescribe-btn cancel-btn" data-id="<?= $row['id']; ?>">Cancel</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </section>

    <footer class="footer">
      <p>&copy; 2025 Clinic Website. All Rights Reserved.</p>
      <p>Contact us: email@example.com | +123-456-7890</p>
    </footer>
  </section>
</body>
</html>

<?php
mysqli_close($conn);
?>
