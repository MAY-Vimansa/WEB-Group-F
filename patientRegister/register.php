<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: http://localhost/project/patientRegister/register.html');
    exit;
}

$first = trim($_POST['firstName'] ?? '');
$last = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phonenumber'] ?? '');
$dob = $_POST['dob'] ?? null;
$gender = $_POST['gender'] ?? null;
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirmPassword'] ?? '';

if ($first === '' || $last === '' || $email === '' || $password === '' || $password !== $confirm) {
    header('Location: http://localhost/project/patientRegister/register.html?error=invalid');
    exit;
}

$conn = db_connect();

$check = $conn->prepare('SELECT id FROM users WHERE email = ?');
$check->bind_param('s', $email);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    $conn->close();
    header('Location: http://localhost/project/patientRegister/register.html?error=exists');
    exit;
}
$check->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (role, first_name, last_name, email, password_hash, phone, dob, gender) VALUES (\'patient\', ?, ?, ?, ?, ?, ?, ?)');
$stmt->bind_param('sssssss', $first, $last, $email, $hash, $phone, $dob, $gender);
$stmt->execute();
$stmt->close();
$conn->close();

header('Location: http://localhost/project/Login/login.html?success=registered');
exit;
?>
