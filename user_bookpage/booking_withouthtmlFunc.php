<?php
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/booking.php');
}

$doctor_id = (int)($_POST['doctor_id'] ?? 0);
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$reason = trim($_POST['reason'] ?? '');

if ($doctor_id <= 0 || $date === '' || $time === '' || $reason === '') {
    redirect('http://localhost/project/booking.php?error=invalid');
}

$conn = db_connect();

$doc = $conn->prepare('SELECT id FROM users WHERE id = ? AND role = \'doctor\'');
$doc->bind_param('i', $doctor_id);
$doc->execute();
if (!$doc->get_result()->fetch_assoc()) {
    $doc->close();
    $conn->close();
    redirect('http://localhost/project/booking.php?error=doctor');
}
$doc->close();

$check = $conn->prepare('SELECT id FROM appointments WHERE patient_id = ? AND doctor_id = ? LIMIT 1');
$check->bind_param('ii', $_SESSION['user_id'], $doctor_id);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    $conn->close();
    redirect('http://localhost/project/booking.php?error=doctor_repeat');
}
$check->close();

$check = $conn->prepare('SELECT id FROM appointments WHERE patient_id = ? AND appointment_date = ? AND appointment_time = ? LIMIT 1');
$check->bind_param('iss', $_SESSION['user_id'], $date, $time);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    $conn->close();
    redirect('http://localhost/project/booking.php?error=conflict');
}
$check->close();

$slot = $conn->prepare('SELECT id FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? LIMIT 1');
$slot->bind_param('iss', $doctor_id, $date, $time);
$slot->execute();
if ($slot->get_result()->fetch_assoc()) {
    $slot->close();
    $conn->close();
    redirect('http://localhost/project/booking.php?error=slot_taken');
}
$slot->close();

$stmt = $conn->prepare('INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason) VALUES (?, ?, ?, ?, ?)');
$stmt->bind_param('iisss', $_SESSION['user_id'], $doctor_id, $date, $time, $reason);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/booking.php?success=booked');
?>
