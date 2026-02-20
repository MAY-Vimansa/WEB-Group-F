<?php
require_once __DIR__ . '/auth.php';
require_role('patient');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/profile.php');
}

$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$dob = $_POST['dob'] ?? null;
$gender = $_POST['gender'] ?? null;

if ($first === '' || $last === '' || $email === '') {
    redirect('http://localhost/project/profile.php?error=invalid');
}

$conn = db_connect();

$check = $conn->prepare('SELECT id FROM users WHERE email = ? AND id <> ?');
$check->bind_param('si', $email, $_SESSION['user_id']);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    $conn->close();
    redirect('http://localhost/project//profile.php?error=exists');
}
$check->close();

$stmt = $conn->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, dob = ?, gender = ? WHERE id = ?');
$stmt->bind_param('ssssssi', $first, $last, $email, $phone, $dob, $gender, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION['name'] = $first . ' ' . $last;

redirect('http://localhost/project//profile.php?success=updated');
?>
