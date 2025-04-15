<?php
session_start();
include 'database.php';

// Ensure CSRF token exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF validation failed");
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $userType = $_POST['user-type'];

    $table = ($userType == 'patient') ? 'Patient' : 'Doctor';
    
    // Prevent SQL Injection with Prepared Statements
    $stmt = $conn->prepare("SELECT id, password FROM $table WHERE emailAddress = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['user_email'] = $email;
            $_SESSION['user_type'] = $userType;
            $_SESSION['user_id'] = $user['id'];
            header("Location: " . ($userType == 'patient' ? 'PatientHome.php' : 'DoctorHome.php'));
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <a href="https://wellnest.infinityfreeapp.com/wellnest/index.php"><img src="images/logo.jpg" alt="Logo" class="logo"></a>
    </header>
    
    <div class="background-overlay"></div>
    
    <div class="login-wrapper">
        <div class="login-container">
            <h2 class="greenColor">Login</h2>
            <?php if(isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
            <form method="POST" action="login.php">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <label for="email" class="left">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password" class="left">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <label for="user-type" class="left">Login as:</label>
                <select id="user-type" name="user-type">
                    <option value="patient">Patient</option>
                    <option value="doctor">Doctor</option>
                </select>
                
                <input type="submit" value="Login">
            </form>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </div>
    </div>
    
    <footer class="footer">
        <p>&copy; 2025 Clinic Website. All Rights Reserved.</p>
    </footer>
        <?php 
    // Display errors if any
    if (isset($_SESSION['error'])) {
        echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']); // Remove error after displaying
    }
    ?>
</body>
</html>
