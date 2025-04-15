
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willnest Clinic</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Header Section -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="https://wellnest.infinityfreeapp.com/wellnest/index.php">
                    <img src="images/logo.jpg" alt="Logo" class="logo">
                </a>
                <a href="login.php"style="text-decoration: none; color: black;"><button class="signup-btn">Log In</button></a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-text">
            <h1>Our Healthcare <br> Solutions Meet <br> Every Need</h1>
            <p style=" margin-left: 60px;">With a group of skilled experts and cutting-edge technologies, <br>we work to keep an eye over your health!</p>
            <p style=" margin-left: 60px;">If you haven't created an account yet, Prioritize your health and <span style="color:#5CDED7; font-weight: bold;">join us.</span></p>
		   <a href="signup.php"><button class="btn btn-warning" style=" margin-left: 60px;">Get Started</button></a>
            <div class="stats">
                <div>400+ <br><span style="color:#5CDED7; font-weight: bold;">Expert Doctors</span></div>
                <div>500+ <br><span style="color:#5CDED7; font-weight: bold;">Recovered Patients</span></div>
                <div>97% <br><span style="color:#5CDED7; font-weight: bold;">Satisfaction Rate</span></div>
            </div>
        </div>
        <div class="hero-image">
            <img src="images/doctors.jpg" alt="Doctors">
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="services-header">
                <h2>We Offer a Wide Range of <br> Unique Services</h2>
                <p>Journey to better health and wellbeing. Treatment for specific conditions.<br>Choose your desired service</p>
            </div>
            <div class="services-container">
                <div class="service-card">
                    <img src="images/dental.jpg" alt="Virtual Consultation"><br>
                    <h3>Dental Services</h3>
                    <p>General dentistry, orthodontics, cosmetic procedures, and oral health education.</p>
                </div>
                <div class="service-card">
                    <img src="images/eye.jpg" alt="Make Appointment"><br><br>
                    <h3>Vision & Eye Health</h3>
                    <p>Treatments for vision health, eye strain, dark circles, and anti-aging concerns, including eye exams, hydration therapies, and wrinkle reduction.<p>
                </div>
                <div class="service-card">
                    <img src="images/skin.jpg" alt="Online Pharmacy">
                    <h3>Beauty Services</h3>
                    <p>Skincare treatments, hair care, and anti-aging solutions.</p>
                </div>
            </div>
            <div class="extra-services">
                <div class="service-card">
                    <img src="images/Virtual_Consultation.jpg" alt="Virtual Consultation">
                    <h3>Virtual Consultation</h3>
                    <p>Online consultations ensure convenient access to medical experts.</p>
                </div>
                <div class="service-card">
                    <img src="images/therapist.jpg" alt="Make Appointment">
                    <h3>Mental Health Support</h3>
                    <p>Access to professional therapists for counseling, stress management, and emotional well-being.</p>
                </div>
                <div class="service-card">
                    <img src="images/Online_Pharmacy.jpg" alt="Online Pharmacy">
                    <h3>Online Pharmacy</h3>
                    <p>Order medicines online and get them delivered to your doorstep.</p>
                </div>
            </div>
            <button class="btn btn-secondary show-more btn-warning">Show More</button>
            <button class="btn btn-secondary show-less btn-warning" style="display: none;">Show Less</button>
        </div>
    </section>

    <!-- Specialty Section -->
    <section class="specialty-section">
        <div class="specialty-container">
            <div class="specialty-features">
                <div class="specialty-feature specialty-1">
                    <img src="images/healthcar.png" alt="Reduce Administrative Work" class="specialty-img">
                   Comprehensive Patient Care:
				   <p>We strive to provide top-notch healthcare services to ensure patient well-being and satisfaction.</p>
                </div>
                <div class="specialty-feature specialty-2">
                    <img src="images/capsules.png" alt="Streamline Communications" class="specialty-img">
                    State-of-the-Art Technology:
					<p>Implementing the latest medical advancements to deliver accurate diagnostics and effective treatments.</p>
                </div>
                <div class="specialty-feature specialty-3">
                    <img src="images/herbal.png" alt="Accelerate Speed to Therapy" class="specialty-img">
                    Enhancing Community Health:
					<p>Promoting a healthier society by offering preventive care, awareness programs, and wellness initiatives.</p>
                </div>
            </div>
            <div class="specialty-content">
                <h2>Our Goals and <br> Mission</h2>
                <p>At Wellnest Clinic, our mission is to provide comprehensive patient care by ensuring top-quality healthcare services that prioritize well-being and satisfaction.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Clinic Website. All Rights Reserved.</p>
        <p>Contact us: email@example.com | +123-456-7890</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
