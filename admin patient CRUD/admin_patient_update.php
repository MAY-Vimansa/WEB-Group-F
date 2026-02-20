<?php
require_once __DIR__ . '/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/adminpage.php');
}

$id = (int)($_POST['patient_id'] ?? 0);
$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$dob = $_POST['dob'] ?? null;
$gender = $_POST['gender'] ?? null;

if ($id <= 0 || $first === '' || $last === '' || $email === '') {
    redirect('http://localhost/project/adminpage.php?error=invalid_patient');
}

$conn = db_connect();

$check = $conn->prepare('SELECT id FROM users WHERE email = ? AND id <> ?');
$check->bind_param('si', $email, $id);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    $conn->close();
    redirect('http://localhost/project/adminpage.php?error=patient_exists');
}
$check->close();

$stmt = $conn->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, dob = ?, gender = ? WHERE id = ? AND role = \'patient\'');
$stmt->bind_param('ssssssi', $first, $last, $email, $phone, $dob, $gender, $id);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/adminpage.php?success=patient_updated');
?>
