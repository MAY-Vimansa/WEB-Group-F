<?php
require_once __DIR__ . '/auth.php';
require_role('admin');

$conn = db_connect();

$counts = ['doctors' => 0, 'patients' => 0, 'appointments' => 0];
$counts['doctors'] = (int)$conn->query("SELECT COUNT(*) AS c FROM users WHERE role = 'doctor'")->fetch_assoc()['c'];
$counts['patients'] = (int)$conn->query("SELECT COUNT(*) AS c FROM users WHERE role = 'patient'")->fetch_assoc()['c'];
$counts['appointments'] = (int)$conn->query("SELECT COUNT(*) AS c FROM appointments")->fetch_assoc()['c'];

$doctors = [];
$result = $conn->query("SELECT id, first_name, last_name, email, phone, specialization, hospital, qualifications FROM users WHERE role = 'doctor' ORDER BY first_name");
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

$patients = [];
$result = $conn->query("SELECT id, first_name, last_name, email, phone, dob, gender FROM users WHERE role = 'patient' ORDER BY first_name");
while ($row = $result->fetch_assoc()) {
    $patients[] = $row;
}

$appointments = [];
$result = $conn->query("SELECT a.id, a.appointment_date, a.appointment_time, a.status, p.first_name AS p_first, p.last_name AS p_last, d.first_name AS d_first, d.last_name AS d_last FROM appointments a JOIN users p ON a.patient_id = p.id JOIN users d ON a.doctor_id = d.id ORDER BY a.appointment_date, a.appointment_time");
while ($row = $result->fetch_assoc()) {
    $appointments[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MediConnect</title>
    <link rel="stylesheet" href="http://localhost/project/adminpage/adminpage.css">
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                <div class="logo-icon"><img src="http://localhost/project/Images for website/homepage/img.png" width="65px" height="65px"></div>
                <span>MediConnect</span>
            </a>
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
                <h1>ADMIN DASHBOARD</h1>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo (int)$counts['doctors']; ?></h3>
                    <p class="totd">Total Doctors</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo (int)$counts['patients']; ?></h3>
                    <p>Total Patients</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3><?php echo (int)$counts['appointments']; ?></h3>
                    <p>Total Appointments</p>
                </div>
            </div>
        </div>

        <!-- Management Section -->
        <div class="management-section">
            <div class="tabs-header">
                <button class="tab-button active" onclick="openTab(event, 'doctors')">
                     Manage Doctors
                </button>
                <button class="tab-button" onclick="openTab(event, 'patients')">
                    Manage Patients
                </button>
                <button class="tab-button" onclick="openTab(event, 'appointments')">
                    Manage Appointments
                </button>
            </div>

            <!-- Doctors Tab -->
            <div id="doctors" class="tab-content active">
                <div class="action-header">
                    <h2>Doctor Management</h2>
                    <button class="add-btn" onclick="openModal('addDoctorModal')">Add New Doctor</button>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Specialization</th>
                            <th>Hospital</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($doctors) === 0): ?>
                            <tr><td colspan="5">No doctors yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($doctors as $doc): ?>
                                <tr>
                                    <td>#D<?php echo (int)$doc['id']; ?></td>
                                    <td><?php echo e('Dr. ' . $doc['first_name'] . ' ' . $doc['last_name']); ?></td>
                                    <td><?php echo e($doc['specialization'] ?: 'Doctor'); ?></td>
                                    <td><?php echo e($doc['hospital'] ?: '-'); ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <!--<button class="view-btn">View</button>-->
                                            <button class="edit-btn edit-doctor" data-id="<?php echo (int)$doc['id']; ?>" data-first="<?php echo e($doc['first_name']); ?>" data-last="<?php echo e($doc['last_name']); ?>" data-email="<?php echo e($doc['email']); ?>" data-phone="<?php echo e($doc['phone']); ?>" data-specialization="<?php echo e($doc['specialization']); ?>" data-hospital="<?php echo e($doc['hospital']); ?>" data-qualifications="<?php echo e($doc['qualifications']); ?>">Edit</button>
                                            <form method="POST" action="http://localhost/project/admin_doctor_delete.php" style="display:inline;">
                                                <input type="hidden" name="doctor_id" value="<?php echo (int)$doc['id']; ?>">
                                                <button class="delete-btn" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?> 
                    </tbody>
                </table>
            </div>

            <!-- Patients Tab -->
            <div id="patients" class="tab-content">
                <div class="action-header">
                    <h2>Patient Management</h2>
                    <button class="add-btn" onclick="openModal('addPatientModal')">Add New Patient</button>
                </div>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($patients) === 0): ?>
                            <tr><td colspan="6">No patients yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($patients as $pat): ?>
                                <tr>
                                    <td>#P<?php echo (int)$pat['id']; ?></td>
                                    <td><?php echo e($pat['first_name'] . ' ' . $pat['last_name']); ?></td>
                                    <td><?php echo e($pat['email']); ?></td>
                                    <td><?php echo e($pat['phone'] ?: '-'); ?></td>
                                    <td><?php echo e($pat['gender'] ?: '-'); ?></td>
                                    <td>
                                        <div class="table-actions">
                                            <!--<button class="view-btn">View</button>-->
                                            <button class="edit-btn edit-patient" data-id="<?php echo (int)$pat['id']; ?>" data-first="<?php echo e($pat['first_name']); ?>" data-last="<?php echo e($pat['last_name']); ?>" data-email="<?php echo e($pat['email']); ?>" data-phone="<?php echo e($pat['phone']); ?>" data-dob="<?php echo e($pat['dob']); ?>" data-gender="<?php echo e($pat['gender']); ?>">Edit</button>
                                            <form method="POST" action="http://localhost/project/admin_patient_delete.php" style="display:inline;">
                                                <input type="hidden" name="patient_id" value="<?php echo (int)$pat['id']; ?>">
                                                <button class="delete-btn" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Appointments Tab -->
            <div id="appointments" class="tab-content">
                <div class="action-header">
                    <h2>Appointment Management</h2>
                    <button class="add-btn" onclick="openModal('addAppointmentModal')">Add New Appointment</button>
                </div>
                <?php
                $appt_error = $_GET['error'] ?? '';
                if ($appt_error === 'past_appointment_date') {
                    echo '<p style="color:#b00020;">Appointment date cannot be in the past.</p>';
                } elseif ($appt_error === 'invalid_appointment') {
                    echo '<p style="color:#b00020;">Please fill all appointment fields.</p>';
                }
                ?>

                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($appointments) === 0): ?>
                            <tr><td colspan="7">No appointments yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($appointments as $appt): ?>
                                <tr>
                                    <td>#A<?php echo (int)$appt['id']; ?></td>
                                    <td><?php echo e($appt['p_first'] . ' ' . $appt['p_last']); ?></td>
                                    <td><?php echo e('Dr. ' . $appt['d_first'] . ' ' . $appt['d_last']); ?></td>
                                    <td><?php echo e($appt['appointment_date']); ?></td>
                                    <td><?php echo e($appt['appointment_time']); ?></td>
                                    <td><span class="status-badge status-pending"><?php echo e(ucfirst($appt['status'])); ?></span></td>
                                    <td>
                                        <div class="table-actions">
                                            <!--<button class="view-btn">View</button>-->
                                            <button class="edit-btn edit-appointment" data-id="<?php echo (int)$appt['id']; ?>" data-date="<?php echo e($appt['appointment_date']); ?>" data-time="<?php echo e($appt['appointment_time']); ?>" data-status="<?php echo e($appt['status']); ?>">Edit</button>
                                            <form method="POST" action="http://localhost/project/admin_appointment_delete.php" style="display:inline;">
                                                <input type="hidden" name="appointment_id" value="<?php echo (int)$appt['id']; ?>">
                                                <button class="delete-btn" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Doctor Modal -->
    <div id="addDoctorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Doctor</h3>
                <button class="close-btn" onclick="closeModal('addDoctorModal')">&times;</button>
            </div>
            <form method="POST" action="http://localhost/project/admin_doctor_create.php" onsubmit="return validateDoctorForm(event)">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" placeholder="John" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" placeholder="Doe" required>
                </div>
                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" placeholder="General Physician" required>
                </div>
                <div class="form-group">
                    <label>Hospital</label>
                    <input type="text" name="hospital" placeholder="Colombo General" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="doctor@example.com" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" placeholder="0771234567" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Doctor login password" required>
                </div>
                <div class="form-group">
                    <label>Qualifications</label>
                    <textarea rows="3" name="qualifications" placeholder="MBBS, MD - 10 years experience"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="save-btn">Save Doctor</button>
                    <button type="button" class="cancel-btn" onclick="closeModal('addDoctorModal');">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Doctor Modal -->
    <div id="editDoctorModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Doctor</h3>
                <button class="close-btn" onclick="closeModal('editDoctorModal')">&times;</button>
            </div>
            <form method="POST" action="http://localhost/project/admin_doctor_update.php">
                <input type="hidden" name="doctor_id" id="editDoctorId">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" id="editDoctorFirst" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" id="editDoctorLast" required>
                </div>
                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" id="editDoctorSpec" required>
                </div>
                <div class="form-group">
                    <label>Hospital</label>
                    <input type="text" name="hospital" id="editDoctorHospital" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="editDoctorEmail" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone" id="editDoctorPhone">
                </div>
                <div class="form-group">
                    <label>Qualifications</label>
                    <textarea rows="3" name="qualifications" id="editDoctorQual"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="save-btn">Update Doctor</button>
                    <button type="button" class="cancel-btn" onclick="closeModal('editDoctorModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" class="modal"> 
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Patient</h3>
                <button class="close-btn" onclick="closeModal('addPatientModal')">&times;</button>
            </div>
            <form method="POST" action="http://localhost/project/admin_patient_create.php" onsubmit="return validateForm(event)">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" placeholder="John" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" placeholder="Doe" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="patient@example.com" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="number" name="phone" placeholder="0771234567" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Patient login password" required>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="save-btn">Save Patient</button>
                    <button type="button" class="cancel-btn" onclick="closeModal('addPatientModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Patient Modal -->
    <div id="editPatientModal" class="modal"> 
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Patient</h3>
                <button class="close-btn" onclick="closeModal('editPatientModal')">&times;</button>
            </div>
            <form method="POST" action="http://localhost/project/admin_patient_update.php">
                <input type="hidden" name="patient_id" id="editPatientId">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" id="editPatientFirst" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" id="editPatientLast" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="editPatientEmail" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="number" name="phone" id="editPatientPhone">
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" id="editPatientDob" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" id="editPatientGender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="save-btn">Update Patient</button>
                    <button type="button" class="cancel-btn" onclick="closeModal('editPatientModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Appointment Modal -->
    <div id="addAppointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add Appointment</h3>
                <button class="close-btn" onclick="closeModal('addAppointmentModal')">&times;</button>
            </div>
            <form method="POST" action="http://localhost/project/admin_appointment_create.php" onsubmit="return validateAppointmentForm(event)">
                <div class="form-group">
                    <label>Patient</label>
                    <select name="patient_id" required>
                        <option value="">Select Patient</option>
                        <?php foreach ($patients as $pat): ?>
                            <option value="<?php echo (int)$pat['id']; ?>"><?php echo e($pat['first_name'] . ' ' . $pat['last_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Doctor</label>
                    <select name="doctor_id" required>
                        <option value="">Select Doctor</option>
                        <?php foreach ($doctors as $doc): ?>
                            <option value="<?php echo (int)$doc['id']; ?>"><?php echo e('Dr. ' . $doc['first_name'] . ' ' . $doc['last_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <select name="appointment_time" required>
                        <option value="">Select Time</option>
                        <option value="04:00 PM">04:00 PM</option>
                        <option value="04:30 PM">04:30 PM</option>
                        <option value="05:00 PM">05:00 PM</option>
                        <option value="05:30 PM">05:30 PM</option>
                        <option value="06:00 PM">06:00 PM</option>
                        <option value="06:30 PM">06:30 PM</option>
                        <option value="07:00 PM">07:00 PM</option>
                        <option value="07:30 PM">07:30 PM</option>
                        <option value="08:00 PM">08:00 PM</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Reason</label>
                    <textarea rows="3" name="reason" placeholder="Reason for appointment"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="save-btn">Save Appointment</button>
                    <button type="button" class="cancel-btn" onclick="closeModal('addAppointmentModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <div id="editAppointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Appointment</h3>
                <button class="close-btn" onclick="closeModal('editAppointmentModal')">&times;</button>
            </div>
            <form method="POST" action="http://localhost/project/admin_appointment_update.php">
                <input type="hidden" name="appointment_id" id="editAppointmentId">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="appointment_date" id="editAppointmentDate" required>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <input type="text" name="appointment_time" id="editAppointmentTime" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editAppointmentStatus" required>
                        <option value="pending">Pending</option>
                        <option value="accepted">Accepted</option>
                        <option value="declined">Declined</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button type="submit" class="save-btn">Update Appointment</button>
                    <button type="button" class="cancel-btn" onclick="closeModal('editAppointmentModal')">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="http://localhost/project/adminpage/adminpage.js"></script>
    <script>
        document.querySelectorAll('.edit-doctor').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('editDoctorId').value = this.dataset.id;
                document.getElementById('editDoctorFirst').value = this.dataset.first;
                document.getElementById('editDoctorLast').value = this.dataset.last;
                document.getElementById('editDoctorEmail').value = this.dataset.email;
                document.getElementById('editDoctorPhone').value = this.dataset.phone;
                document.getElementById('editDoctorSpec').value = this.dataset.specialization;
                document.getElementById('editDoctorHospital').value = this.dataset.hospital;
                document.getElementById('editDoctorQual').value = this.dataset.qualifications;
                openModal('editDoctorModal');
            });
        });

        document.querySelectorAll('.edit-patient').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('editPatientId').value = this.dataset.id;
                document.getElementById('editPatientFirst').value = this.dataset.first;
                document.getElementById('editPatientLast').value = this.dataset.last;
                document.getElementById('editPatientEmail').value = this.dataset.email;
                document.getElementById('editPatientPhone').value = this.dataset.phone;
                document.getElementById('editPatientDob').value = this.dataset.dob;
                document.getElementById('editPatientGender').value = this.dataset.gender;
                openModal('editPatientModal');
            });
        });

        document.querySelectorAll('.edit-appointment').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('editAppointmentId').value = this.dataset.id;
                document.getElementById('editAppointmentDate').value = this.dataset.date;
                document.getElementById('editAppointmentTime').value = this.dataset.time;
                document.getElementById('editAppointmentStatus').value = this.dataset.status;
                openModal('editAppointmentModal');
            });
        });
    </script>
</body>
</html>
