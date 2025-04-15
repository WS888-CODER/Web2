<?php
session_start();
include 'database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure speciality records exist
$specialities = [
    ['id' => 1, 'name' => 'Dental'],
    ['id' => 2, 'name' => 'Dermatology'],
    ['id' => 3, 'name' => 'Ophthalmology'],
    ['id' => 4, 'name' => 'Psychology']
];

foreach ($specialities as $speciality) {
    $checkSpecialityQuery = "SELECT id FROM speciality WHERE id = " . $speciality['id'];
    $checkSpecialityResult = mysqli_query($conn, $checkSpecialityQuery);
    
    if (mysqli_num_rows($checkSpecialityResult) == 0) {
        $insertSpecialityQuery = "INSERT INTO speciality (id, name) VALUES (" . $speciality['id'] . ", '" . $speciality['name'] . "')";
        mysqli_query($conn, $insertSpecialityQuery);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    // Check which form was submitted
    if (isset($_POST['patient_signup'])) {
        $userType = 'patient';
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];

        // Check if email exists
        $checkEmailQuery = "SELECT id FROM Patient WHERE emailAddress='$email'";
        $checkEmailResult = mysqli_query($conn, $checkEmailQuery);
        
        if (mysqli_num_rows($checkEmailResult) > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            $query = "INSERT INTO Patient (firstName, lastName, emailAddress, password, DoB, Gender) 
                      VALUES ('$firstName', '$lastName', '$email', '$password', '$dob', '$gender')";
        }
    } elseif (isset($_POST['doctor_signup'])) {
        $userType = 'doctor';
        $specialityID = (int)$_POST['speciality'];

        // Handle doctor image upload
        $imageName = null;
        if (!empty($_FILES["photo"]["name"])) {
            $imageName = uniqid() . "_" . basename($_FILES["photo"]["name"]);
            $targetDir = "images/";
            $targetFilePath = $targetDir . $imageName;
            if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                $error = "Failed to upload file.";
            }
        }

        // Check if email exists
        $checkEmailQuery = "SELECT id FROM Doctor WHERE emailAddress='$email'";
        $checkEmailResult = mysqli_query($conn, $checkEmailQuery);
        
        if (mysqli_num_rows($checkEmailResult) > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            $query = "INSERT INTO Doctor (firstName, lastName, emailAddress, password, SpecialityID, uniqueFileName) 
                      VALUES ('$firstName', '$lastName', '$email', '$password', '$specialityID', '$imageName')";
        }
    }

    if (!isset($error) && mysqli_query($conn, $query)) {
        $_SESSION['user_email'] = $email;
        $_SESSION['user_type'] = $userType;
        $_SESSION['user_id'] = mysqli_insert_id($conn);
        header("Location: " . ($userType == 'patient' ? 'PatientHome.php' : 'DoctorHome.php'));
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="header">
    <a href="https://wellnest.infinityfreeapp.com/wellnest/index.php"><img src="images/logo.jpg" alt="Logo" class="logo"></a>
  </header>
  
  <div class="background-overlay"></div>
  
  <div class="login-wrapper">
    <div class="login-container">
      <h2 class="greenColor">Sign Up</h2>
      <?php if(isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
       <div class="role-selection">
        <label>
          <input type="radio" name="role" value="patient"> Patient
          <input type="radio" name="role" value="doctor"> Doctor
        </label>
      </div>

      <!-- Patient Form -->
      <form id="patient-form" class="hidden" action="signup.php" method="POST" enctype="multipart/form-data">
        <label for="patient-firstname" class="left">First Name:
        <input type="text" id="patient-firstname" name="firstname"> </label>
        
        <label for="patient-lastname" class="left">Last Name:
        <input type="text" id="patient-lastname" name="lastname"></label>
        
        <label for="patient-id" class="left">ID:
        <input type="text" id="patient-id" name="id"></label>
        
        <label for="patient-gender" class="left">Gender:</label>
        <select id="patient-gender" name="gender">
          <option value="male">Male</option>
          <option value="female">Female</option>
        </select>
        
        <label for="patient-dob" class="left">DoB:
        <input type="date" id="patient-dob" name="dob"></label>
        
        <label for="patient-email" class="left">Email:</label>
        <input type="email" id="patient-email" name="email">
        
        <label for="patient-password" class="left">Password:</label>
        <input type="password" id="patient-password" name="password">
        <input type="hidden" name="patient_signup" value="1">

        <input type="submit" value="Sign Up">
      </form>

      <!-- Doctor Form -->
      <form id="doctor-form" class="hidden" action="signup.php" method="POST" enctype="multipart/form-data">
        <label for="doctor-firstname" class="left">First Name:
        <input type="text" id="doctor-firstname" name="firstname"></label>
        
        <label for="doctor-lastname" class="left">Last Name:
        <input type="text" id="doctor-lastname" name="lastname"></label>
        
        <label for="doctor-id" class="left">ID:
        <input type="text" id="doctor-id" name="id"></label>
        
        <label for="doctor-photo" class="left">Photo:
        <input type="file" id="doctor-photo" name="photo"></label>
        
        <label for="doctor-speciality" class="left">Speciality:</label>
        <select id="doctor-speciality" name="speciality">
          <option value="1">Dental</option>
          <option value="2">Dermatology</option>
          <option value="3">Ophthalmology</option>
          <option value="4">Psychology</option>
        </select>
        
        <label for="doctor-email" class="left">Email:</label>
        <input type="email" id="doctor-email" name="email">
        
        <label for="doctor-password" class="left">Password:</label>
        <input type="password" id="doctor-password" name="password">
        <input type="hidden" name="doctor_signup" value="1">

        <input type="submit" value="Sign Up">
      </form>
    </div>
  </div>
  
  <footer class="footer">
    <p>&copy; 2025 Clinic Website. All Rights Reserved.</p>
  </footer>
  
  <script>
    document.getElementById('role').addEventListener('change', function() {
      document.getElementById('patient-fields').style.display = this.value === 'patient' ? 'block' : 'none';
      document.getElementById('doctor-fields').style.display = this.value === 'doctor' ? 'block' : 'none';
    });
  </script>
      <script src="script.js"></script>

</body>
</html>