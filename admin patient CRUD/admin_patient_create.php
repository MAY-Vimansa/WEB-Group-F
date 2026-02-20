<?php
require_once __DIR__ . '/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/adminpage.php');
}

$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$dob = $_POST['dob'] ?? null;
$gender = $_POST['gender'] ?? null;
$password = $_POST['password'] ?? '';

if ($first === '' || $last === '' || $email === '' || $password === '') {
    redirect('http://localhost/project/adminpage.php?error=invalid_patient');
}

$conn = db_connect();

$check = $conn->prepare('SELECT id FROM users WHERE email = ?');
$check->bind_param('s', $email);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    $conn->close();
    redirect('http://localhost/project/adminpage.php?error=patient_exists');
}
$check->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (role, first_name, last_name, email, password_hash, phone, dob, gender) VALUES (\'patient\', ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssssss', $first, $last, $email, $hash, $phone, $dob, $gender);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/adminpage.php?success=patient_added');
?>
