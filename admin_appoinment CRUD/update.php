<?php
require_once __DIR__ . '/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/adminpage.php');
}

$id = (int)($_POST['appointment_id'] ?? 0);
$date = $_POST['appointment_date'] ?? '';
$time = $_POST['appointment_time'] ?? '';
$status = $_POST['status'] ?? 'pending';

if ($id <= 0 || $date === '' || $time === '' || !in_array($status, ['pending','accepted','declined','completed'], true)) {
    redirect('http://localhost/project/adminpage.php?error=invalid_appointment');
}

$conn = db_connect();
$stmt = $conn->prepare('UPDATE appointments SET appointment_date = ?, appointment_time = ?, status = ? WHERE id = ?');
$stmt->bind_param('sssi', $date, $time, $status, $id);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/adminpage.php?success=appointment_updated');
?>
