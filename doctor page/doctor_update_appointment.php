<?php
require_once __DIR__ . '/auth.php';
require_role('doctor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/doctorpage.php');
}

$appointment_id = (int)($_POST['appointment_id'] ?? 0);
$action = $_POST['action'] ?? '';
$bulk_date = $_POST['bulk_date'] ?? '';
$bulk_action = $_POST['bulk_action'] ?? '';

if ($bulk_date !== '' && in_array($bulk_action, ['accepted', 'declined'], true)) {
    $conn = db_connect();
    $stmt = $conn->prepare('UPDATE appointments SET status = ? WHERE doctor_id = ? AND appointment_date = ?');
    $stmt->bind_param('sis', $bulk_action, $_SESSION['user_id'], $bulk_date);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    redirect('http://localhost/project/doctorpage.php?success=updated');
}

if ($appointment_id <= 0 || !in_array($action, ['accepted', 'declined'], true)) {
    redirect('http://localhost/project/doctorpage.php?error=invalid');
}

$conn = db_connect();
$stmt = $conn->prepare('UPDATE appointments SET status = ? WHERE id = ? AND doctor_id = ?');
$stmt->bind_param('sii', $action, $appointment_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/doctorpage.php?success=updated');
?>
