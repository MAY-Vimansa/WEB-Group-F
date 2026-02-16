<?php
require_once __DIR__ . '/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/adminpage.php');
}

$id = (int)($_POST['appointment_id'] ?? 0);
if ($id <= 0) {
    redirect('http://localhost/project/adminpage.php?error=invalid_appointment');
}

$conn = db_connect();
$stmt = $conn->prepare('DELETE FROM appointments WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/adminpage.php?success=appointment_deleted');
?>
