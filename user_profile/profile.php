<?php
require_once __DIR__ . '/auth.php';
require_role('patient');

$user = current_user();
$conn = db_connect();

$appointments = [];
$stmt = $conn->prepare("SELECT a.appointment_date, a.appointment_time, a.status, a.reason, d.first_name, d.last_name FROM appointments a JOIN users d ON a.doctor_id = d.id WHERE a.patient_id = ? ORDER BY a.appointment_date DESC");
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
    <title>My Profile - MediConnect</title>
    <link rel="stylesheet" href="http://localhost/project/patientprofile page/profile.css">
</head>
<body>
    <nav class="navbar"> 
        <div class="nav-container">
            <a href="#home" class="logo">
                <div class="logo-icon"><img src="http://localhost/project/Images for website/homepage/img.png" width="65px" height="65px"></div>
                <span>MediConnect</span>
            </a>
            <ul class="nav-links">
                <li><a href="http://localhost/project/page.php#home">Home</a></li>
                <li><a href="http://localhost/project/page.php#about">About Us</a></li>
                <li><a href="http://localhost/project/page.php#doctors">Doctor</a></li>
                <li><a href="#profile">Profile</a></li>
                <li><a href="http://localhost/project/page.php#contact">Contact</a></li>
            </ul>
            <div class="nav-buttons">
                <a href="http://localhost/project/page.php" class="backloghome-btn">← Back to Home</a>
            </div>
            
        </div>
    </nav>


    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-picture">
               <img src="http://localhost/project/Images for website/homepage/vecteezy_3d-user-icon-on-transparent-background_16774583.png" width="120px" height="120px" alt="3D user icon on transparent background">
            </div>
            <h1><?php echo e($user['first_name'] . ' ' . $user['last_name']); ?></h1>
            <p>Patient ID: MC<?php echo (int)$user['id']; ?></p>
        </div>

        <div class="profile-body">
            <h2 class="section-title">Personal Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Full Name</div>
                        <div class="info-value"><?php echo e($user['first_name'] . ' ' . $user['last_name']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo e($user['email']); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value"><?php echo e($user['phone'] ?: '-'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <div class="info-value"><?php echo e($user['dob'] ?: '-'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <div class="info-value"><?php echo e($user['gender'] ?: '-'); ?></div>
                    </div>
                </div>

            <h2 class="section-title">Edit Profile</h2>
            <form method="POST" action="http://localhost/project/update_profile.php">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">First Name</div>
                        <input type="text" name="first_name" value="<?php echo e($user['first_name']); ?>" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Name</div>
                        <input type="text" name="last_name" value="<?php echo e($user['last_name']); ?>" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <input type="email" name="email" value="<?php echo e($user['email']); ?>" required>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <input type="text" name="phone" value="<?php echo e($user['phone']); ?>">
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date of Birth</div>
                        <input type="date" name="dob" value="<?php echo e($user['dob']); ?>">
                    </div>
                    <div class="info-item">
                        <div class="info-label">Gender</div>
                        <select name="gender">
                            <option value="">Select</option>
                            <option value="male" <?php echo ($user['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo ($user['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo ($user['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                <button class="edit-profile-btn" type="submit">Save Changes</button>
            </form>
            <form method="POST" action="http://localhost/project/delete_account.php" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');" style="margin-top: 16px;">
                <button class="delete-account-btn" type="submit">Delete My Account</button>
            </form>

            <div class="appointments-section">
                <h2 class="section-title">My Appointments</h2>

                <?php if (count($appointments) === 0): ?>
                    <p>No appointments yet.</p>
                <?php else: ?>
                    <?php foreach ($appointments as $appt): ?>
                        <div class="appointment-card">
                            <div class="appointment-info">
                                <h4><?php echo e('Dr. ' . $appt['first_name'] . ' ' . $appt['last_name']); ?></h4>
                                <p><?php echo e($appt['appointment_date']); ?> at <?php echo e($appt['appointment_time']); ?></p>
                                <p><?php echo e($appt['reason']); ?></p>
                            </div>
                            <div class="appointment-status"><?php echo e(ucfirst($appt['status'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
