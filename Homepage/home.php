<?php
require_once __DIR__ . '/db.php';

$doctors = [];
$conn = db_connect();
$result = $conn->query("SELECT id, first_name, last_name, specialization FROM users WHERE role = 'doctor' ORDER BY first_name");
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediConnect - Doctor Patient Management System</title>
    <link rel="stylesheet" href="http://localhost/project/Homepage/homestyle.css"> 
</head>
<body class="body">
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
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
                <a href="http://localhost/project/Login/login.html" class="btn btn-outline">Login</a>
                <a href="http://localhost/project/patientRegister/register.html" class="btn btn-primary" target="_blank">Register</a>
            </div>
            
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home" >
        <div class="hero-content" >
            <div class="hero-text">
                <h1>Smart Healthcare Management for Sri Lanka</h1>
                <p>Digitize your hospital operations, improve coordination, and deliver exceptional patient experiences with MediConnect.</p>
                <div class="hero-buttons">
                    <a href="http://localhost/project/page.php" class="btn btn-large btn-white">Book Appointment</a>
                    <a href="#about" class="btn btn-large btn-primary">Learn More</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-illustration">
                    <img src="http://localhost/project/Images for website/homepage/Mindd Foundation Articles.jpg.jpeg" width="570px" height="400px" class="homg-img" alt="Mindd Foundation Article > 
                
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-container">
            <div class="about-image">
                <div class="about-img-placeholder">
                    <img src="http://localhost/project/Images for website/homepage/aboutus.png" width="560px" height="400px" class="aboutus-img" alt="images for website">
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
                                <img src="http://localhost/project/Images for website/homepage/doctor_9619282.png" class="doctor-img" alt="Doctor illustration for Mindd Foundation website">
                </div>
                            </div>
                            <h3><?php echo htmlspecialchars($doc['first_name'] . ' ' . $doc['last_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p><?php echo htmlspecialchars($doc['specialization'] ?: 'Doctor', ENT_QUOTES, 'UTF-8'); ?></p>
                            <div>
                                <a href="http://localhost/project/booking.php?doctor_id=<?php echo (int)$doc['id']; ?>" target="_self"><button class="book-button">Book Now</button></a>
                            </div>
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
                    <li><a href="http://localhost/project/profile.php">Profile</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Services</h3>
                <ul>
                    <li><a href="http://localhost/project/Login/login.html">Patient Management</a></li>
                    <li><a href="#doctors">Doctors</a></li>
                    <li><a href="http://localhost/project/adminpage.php">Appointment Booking</a></li>
                    
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul class="contact-list"><!--change these symboles to pngs-->
                   
	                 <li><img src="http://localhost/project/Images for website/homepage/call vector Image Feb 13, 2026, 09_26_06 AM.png" width="25px" height="25px" alt="Call icon"> +94 11 234 5678</li>
	                 <li><img src="http://localhost/project/Images for website/homepage/email Image Feb 13, 2026, 09_27_34 AM.png" width="25px" height="25px" alt="Email icon"> info@mediconnect.lk</li>
	                 <li><img src="http://localhost/project/Images for website/homepage/location Image Feb 13, 2026, 09_28_44 AM.png" width="25px" height="25px" alt="Location icon"> Colombo, Sri Lanka</li>

                </ul>
            </div>
        </div>
        <div class="footer-bottom" id="footer">
            <p>&copy; 2026 MediConnect. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
