<?php
require_once __DIR__ . '/auth.php';
require_role('doctor');

$user = current_user();
$conn = db_connect();

$appointments = [];
$stmt = $conn->prepare("SELECT a.id, a.appointment_date, a.appointment_time, a.status, p.first_name, p.last_name FROM appointments a JOIN users p ON a.patient_id = p.id WHERE a.doctor_id = ? ORDER BY a.appointment_date, a.appointment_time");
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $appointments[] = $row;
}
$stmt->close();
$conn->close();

$grouped = [];
foreach ($appointments as $appt) {
    $date = $appt['appointment_date'];
    if (!isset($grouped[$date])) {
        $grouped[$date] = [];
    }
    $grouped[$date][] = $appt;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - MediConnect</title>
    <link rel="stylesheet" href="http://localhost/project/doctorpage/doctorpage.css">      
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar"> 
        <div class="nav-container">
            <a href="#home" class="logo">
                <div class="logo-icon"><img src="http://localhost/project/Images for website/homepage/img.png" width="65px" height="65px"></div>
                <span>MediConnect</span>
            </a>
            <ul class="nav-links">

                
                <li><a href="#contact"><strong>CONTACT - 011 234 5678</strong></a></li>

            </ul>
            <div class="nav-buttons">
                <a href="http://localhost/project/logout.php" class="logout-btn">Logout</a>
            </div>  
        </div>
    </nav>

    <!-- Dashboard Container -->
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Welcome, Dr. <?php echo e($user['first_name'] . ' ' . $user['last_name']); ?> </h1>
                <p>Here are your appointments</p>
            </div>
        </div>

        <!-- Appointments Section -->
        <div class="appointments-section">
            <div class="section-header">
                <h2>
                    
                    Patient Appointments
                </h2>
            </div>
            <div class="appointments-list">
                <?php if (count($appointments) === 0): ?>
                    <div class="empty-state">
                        <h3>No appointments yet.</h3>
                        <p>New bookings will appear here grouped by date.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($grouped as $date => $items): ?>
                        <div class="date-card">
                            <div class="date-header">
                                <div class="date-title">
                                    <h3><?php echo e($date); ?></h3>
                                    <span class="date-count"><?php echo count($items); ?> patients</span>
                                </div>
                                <div class="date-actions">
                                    <form method="POST" action="http://localhost/project/doctor_update_appointment.php" style="display:inline;">
                                        <input type="hidden" name="bulk_date" value="<?php echo e($date); ?>">
                                        <input type="hidden" name="bulk_action" value="accepted">
                                        <button class="action-btn accept-btn" type="submit">✓ Accept All</button>
                                    </form>
                                    <form method="POST" action="http://localhost/project/doctor_update_appointment.php" style="display:inline;">
                                        <input type="hidden" name="bulk_date" value="<?php echo e($date); ?>">
                                        <input type="hidden" name="bulk_action" value="declined">
                                        <button class="action-btn decline-btn" type="submit">✕ Decline All</button>
                                    </form>
                                </div>
                            </div>
                            <div class="date-list">
                                <?php foreach ($items as $appt): ?>
                                    <div class="date-row">
                                        <div class="row-main">
                                            <div class="row-patient"><?php echo e($appt['first_name'] . ' ' . $appt['last_name']); ?></div>
                                            <div class="row-time"><?php echo e($appt['appointment_time']); ?></div>
                                            <div class="row-status status-<?php echo e($appt['status']); ?>"><?php echo e(ucfirst($appt['status'])); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<script src="http://localhost/project/doctorpage/doctorpage.js"></script>
</html>
