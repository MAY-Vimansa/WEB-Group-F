<?php
require_once __DIR__ . '/auth.php';
require_role('patient');

$conn = db_connect();
$doctors = [];
$result = $conn->query("SELECT id, first_name, last_name, specialization, hospital, qualifications FROM users WHERE role = 'doctor' ORDER BY first_name");
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}
$selected_id = isset($_GET['doctor_id']) ? (int)$_GET['doctor_id'] : 0;
$selected = null;
foreach ($doctors as $doc) {
    if ((int)$doc['id'] === $selected_id) {
        $selected = $doc;
        break;
    }
}
if (!$selected && count($doctors) > 0) {
    $selected = $doctors[0];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - MediConnect</title>
    <link rel="stylesheet" href="http://localhost/project/user_booking page/booking.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <div class="logo-icon"><img src="http://localhost/project/Images for website/homepage/img.png" width="65px" height="65px"></div>
                <span>MediConnect</span>
            </a>
            <ul class="nav-links">
                <li><a href="http://localhost/project/page.php#home#home">Home</a></li>
                <li><a href="http://localhost/project/page.php#home#about">About Us</a></li>
                <li><a href="http://localhost/project/page.php#home#doctors">Doctor</a></li>
                <li><a href="http://localhost/project/profile.php">Profile</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                <a href="http://localhost/project/page.php" class="backloghome-btn">← Back to Home</a>
            </div>
            
        </div>
    </nav>

    <div class="booking-container">
        <div class="booking-card">
            <div class="booking-header">
                <div class="doctor-profile">
                    <div class="doctor-avatar">
                        <img src="http://localhost/project/Images for website/homepage/doctor_9619282.png"height="95px" width="95px">
                    </div>
                    <div>
                        <h2 id="doctorName">
                            <?php echo $selected ? e($selected['first_name'] . ' ' . $selected['last_name']) : 'No Doctor Available'; ?>
                        </h2>
                        <p id="doctorSpecialty">
                            <?php echo $selected ? e($selected['specialization'] ?: 'Doctor') : ''; ?>
                        </p>
                        <?php if ($selected && !empty($selected['hospital'])): ?>
                            <p id="doctorHospital"><?php echo e($selected['hospital']); ?></p>
                        <?php endif; ?>
                        <?php if ($selected && !empty($selected['qualifications'])): ?>
                            <p id="doctorQualifications"><?php echo e($selected['qualifications']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="booking-body">
                <div class="time-info">
                        <div class="time-info-text">
                            <strong>Doctor Available Hours</strong>
                            <span>4:00 PM - 8:00 PM</span>
                        </div>
                </div>

                <?php
                $error = $_GET['error'] ?? '';
                $success = $_GET['success'] ?? '';
                if ($error === 'conflict') {
                    echo '<p style="color:#b00020;">You already have an appointment at this date and time. Please choose another slot.</p>';
                } elseif ($error === 'doctor_repeat') {
                    echo '<p style="color:#b00020;">You already have an appointment with this doctor. Please choose another doctor.</p>';
                } elseif ($error === 'slot_taken') {
                    echo '<p style="color:#b00020;">That time slot is already booked. Please choose another slot.</p>';
                } elseif ($error === 'invalid') {
                    echo '<p style="color:#b00020;">Please fill all required fields.</p>';
                } elseif ($error === 'doctor') {
                    echo '<p style="color:#b00020;">Selected doctor is invalid. Please try again.</p>';
                }
                ?>
                <?php if (count($doctors) === 0): ?>
                    <p>No doctors available right now. Please contact admin.</p>
                <?php else: ?>
                <form name="bookingForm" method="POST" action="http://localhost/project/book_appointment.php" onsubmit="return validateBooking()">
                    <input type="hidden" name="doctor_id" value="<?php echo (int)$selected['id']; ?>">

                    <div class="form-group">
                        <label for="doctorSelect">Select Doctor <span class="required">*</span></label>
                        <select id="doctorSelect" name="doctor_select" required onchange="location.href='?doctor_id=' + this.value;">
                            <?php foreach ($doctors as $doc): ?>
                                <option value="<?php echo (int)$doc['id']; ?>" <?php echo ($selected && (int)$doc['id'] === (int)$selected['id']) ? 'selected' : ''; ?>>
                                    <?php echo e($doc['first_name'] . ' ' . $doc['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="patientName">Patient Name <span class="required">*</span></label>
                        <input type="text" id="patientName" name="patientName" value="<?php echo e($_SESSION['name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="date">Appointment Date <span class="required">*</span></label>
                        <input type="date" id="date" name="date" required>
                    </div>

                    <div class="form-group">
                        <label>Select Time Slot (4:00 PM - 8:00 PM) <span class="required">*</span></label>
                        <div class="time-slots">
                            <input type="radio" id="time1" name="time" value="4:00 PM" class="time-slot-input" required>
                            <label for="time1" class="time-slot-label">4:00 PM</label>

                            <input type="radio" id="time2" name="time" value="4:30 PM" class="time-slot-input" required>
                            <label for="time2" class="time-slot-label">4:30 PM</label>

                            <input type="radio" id="time3" name="time" value="5:00 PM" class="time-slot-input" required>
                            <label for="time3" class="time-slot-label">5:00 PM</label>

                            <input type="radio" id="time4" name="time" value="5:30 PM" class="time-slot-input" required>
                            <label for="time4" class="time-slot-label">5:30 PM</label>

                            <input type="radio" id="time5" name="time" value="6:00 PM" class="time-slot-input" required>
                            <label for="time5" class="time-slot-label">6:00 PM</label>

                            <input type="radio" id="time6" name="time" value="6:30 PM" class="time-slot-input" required>
                            <label for="time6" class="time-slot-label">6:30 PM</label>

                            <input type="radio" id="time7" name="time" value="7:00 PM" class="time-slot-input" required>
                            <label for="time7" class="time-slot-label">7:00 PM</label>

                            <input type="radio" id="time8" name="time" value="7:30 PM" class="time-slot-input" required>
                            <label for="time8" class="time-slot-label">7:30 PM</label>

                            <input type="radio" id="time9" name="time" value="8:00 PM" class="time-slot-input" required>
                            <label for="time9" class="time-slot-label">8:00 PM</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason for Visit <span class="required">*</span></label>
                        <textarea id="reason" name="reason" placeholder="Please describe your symptoms or reason for appointment" required></textarea>
                    </div>

                    <button type="submit" class="book-button">Confirm Appointment</button>
                </form>
                <?php endif; ?>

                <!--<div class="back-link">
                    <a href="#"></a>
                </div>-->
            </div>
        </div>
    </div>
    <?php if ($success === 'booked'): ?>
        <script>
            alert('Appointment booked successfully!');
        </script>
    <?php endif; ?>
    <script src="http://localhost/project/user_booking page/booking.js"></script>
</body>
</html>
