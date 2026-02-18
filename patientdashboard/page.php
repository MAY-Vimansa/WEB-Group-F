<?php
require_once __DIR__ . '/auth.php';
require_role('patient');

$user = current_user();
$conn = db_connect();

$doctors = [];
$result = $conn->query("SELECT id, first_name, last_name, specialization FROM users WHERE role = 'doctor' ORDER BY first_name");
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

$appointments = [];
$stmt = $conn->prepare("SELECT a.id, a.appointment_date, a.appointment_time, a.status, a.reason, d.first_name, d.last_name, d.specialization FROM appointments a JOIN users d ON a.doctor_id = d.id WHERE a.patient_id = ? ORDER BY a.appointment_date, a.appointment_time");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Doctor Patient Management System</title>
    <link rel="stylesheet" href="http://localhost/project/Homepage/homestyle.css">
    <link rel="stylesheet" href="http://localhost/project/patientdashboard/page.css">
</head>
<body>
    <div id="top"></div>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#top" class="logo">
                <div class="logo-icon"><img src="http://localhost/project/Images for website/homepage/img.png" width="65px" height="65px"></div>
                <span>MediConnect</span>
            </a> 
            <ul class="nav-links">
                <li><a href="#top">Home</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#doctors">Doctor</a></li>
                <li><a href="http://localhost/project/profile.php">Profile</a></li>
                <li><a href="#footer">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                <a href="http://localhost/project/logout.php" class="logout-btn">Logout</a>
            </div>
            
        </div>
    </nav>

    <!-- Patient Welcome -->
    <section class="patient-welcome-bar">
        <div class="patient-welcome-bar-container" >
            <div class="patient-welcome-text">
                <h1 >
                <span class="welcome-label" >WELCOME</span>
                <strong class="welcome-name"><?php echo e($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                </h1>
                
            </div>
            
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero"  >
        <div class="hero-content" >
            <div class="hero-text">
                <h1>Smart Healthcare Management for Sri Lanka</h1>
                <p>Digitize your hospital operations, improve coordination, and deliver exceptional patient experiences with MediConnect.</p>
                <div class="hero-buttons">
                    <a href="http://localhost/project/booking.php" class="btn btn-large btn-white">Book Appointment</a>
                    <a href="#about" class="btn btn-large btn-primary">Learn More</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-illustration">
                    <img src="http://localhost/project/Images for website/homepage/Mindd Foundation Articles.jpg.jpeg" width="570px"height="400px" class="homg-img"> 
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-container">
            <div class="about-image">
                <div class="about-img-placeholder">
                    <img src="http://localhost/project/Images for website/homepage/aboutus.png" width="560px" height="400px" class="aboutus-img">
                </div>
            </div>
            <div class="about-content">
                <h2>About Us</h2>
                <p>MediConnect empowers Sri Lankan healthcare providers with a smart, user-friendly Hospital Management System that digitizes operations, improves coordination, and delivers better patient experiences. By simplifying administration and increasing operational visibility, MediConnect helps hospitals boost efficiency, retain loyal patients, and attract new ones in today's evolving healthcare environment.</p>
                
                <div class="about-features">
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">Digital Operations</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">Better Coordination</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">Enhanced Experience</div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div class="feature-text">Increased Efficiency</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="doctors">
        <div class="features-container">
            <div class="section-title">
                <h2>Our Doctors</h2>
                <p>Book your Doctor NOW!</p>
            </div>
            <div class="features-grid">
                <?php if (count($doctors) === 0): ?>
                    <p>No doctors available right now. Please check later.</p>
                <?php else: ?>
                    <?php foreach ($doctors as $doc): ?>
                        <div class="doctor-card">
                            <div class="doctor-icon">
                                <img src="http://localhost/project/Images for website/homepage/doctor_9619282.png" class="doctor-img">
                            </div>
                            <h3><?php echo e($doc['first_name'] . ' ' . $doc['last_name']); ?></h3>
                            <p><?php echo e($doc['specialization'] ?: 'Doctor'); ?></p>
                            <div>
                                <a href="http://localhost/project/booking.php?doctor_id=<?php echo (int)$doc['id']; ?>" target="_self"><button class="book-button">Book Now</button></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Patient Welcome + Ongoing Appointments -->
    <section class="patient-dashboard">
        <div class="patient-dashboard-container">
            <div class="patient-welcome">
                <h3>Heres your ongoing appointments.</h3>
            </div>
            <div class="patient-appointments">
                <?php if (count($appointments) === 0): ?>
                    <p>No appointments yet.</p>
                <?php else: ?>
                    <?php foreach ($appointments as $appt): ?>
                        <div class="appointment-card">
                            <h3><?php echo e('Dr. ' . $appt['first_name'] . ' ' . $appt['last_name']); ?></h3>
                            <p>Date: <?php echo e($appt['appointment_date']); ?></p>
                            <p>Time: <?php echo e($appt['appointment_time']); ?></p>
                            <span class="status-badge"><?php echo e(ucfirst($appt['status'])); ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2>Ready to Transform Your Hospital?</h2>
            <p>Join leading healthcare providers across Sri Lanka using MediConnect</p>
            <a href="#contact" class="btn btn-large btn-white">Contact Us Today</a>
        </div>
    </section>
    

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>MediConnect</h3>
                <p>Empowering Sri Lankan healthcare with smart, efficient hospital management solutions.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#doctors">Doctor</a></li>
                    <li><a href="http://localhost/project/patientprofile page/profile.php">Profile</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Services</h3>
                <ul>
                    <li><a href="#doctors">Doctors</a></li>
                    <li><a href="http://localhost/project/booking.php">Appointment Booking</a></li>
                    
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3> 
                <ul class="contact-list">
                    <li><img src="http://localhost/project/Images for website/homepage/call vector Image Feb 13, 2026, 09_26_06 AM.png" width="25px" height="25px"> +94 11 234 5678</li>
                    <li><img src="http://localhost/project/Images for website/homepage/email Image Feb 13, 2026, 09_27_34 AM.png" width="25px" height="25px"> info@mediconnect.lk</li>
                    <li><img src="http://localhost/project/Images for website/homepage/location Image Feb 13, 2026, 09_28_44 AM.png" width="25px" height="25px"> Colombo, Sri Lanka</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom" id="footer">
            <p>&copy; 2026 MediConnect. All rights reserved.</p>
        </div>
    </footer>
    <script src="http://localhost/project/patientdashboard/page.js?v=1"></script>
</body>
</html>
