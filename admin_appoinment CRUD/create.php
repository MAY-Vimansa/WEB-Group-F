<?php
require_once __DIR__ . '/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/adminpage.php');
}

$patient_id = (int)($_POST['patient_id'] ?? 0);
$doctor_id = (int)($_POST['doctor_id'] ?? 0);
$date = $_POST['appointment_date'] ?? '';
$time = $_POST['appointment_time'] ?? '';
$reason = trim($_POST['reason'] ?? 'Admin created appointment');

if ($patient_id <= 0 || $doctor_id <= 0 || $date === '' || $time === '') {
    redirect('http://localhost/project/adminpage.php?error=invalid_appointment');
}

$today = date('Y-m-d');
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || $date < $today) {
    redirect('http://localhost/project/adminpage.php?error=past_appointment_date');
}

$conn = db_connect();
$stmt = $conn->prepare('INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason, status) VALUES (?, ?, ?, ?, ?, \'pending\')');
$stmt->bind_param('iisss', $patient_id, $doctor_id, $date, $time, $reason);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/adminpage.php?success=appointment_added');
?>
