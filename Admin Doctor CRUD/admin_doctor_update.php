<?php
require_once __DIR__ . '/auth.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/adminpage.php');
}

$id = (int)($_POST['doctor_id'] ?? 0);
$first = trim($_POST['first_name'] ?? '');
$last = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$specialization = trim($_POST['specialization'] ?? '');
$hospital = trim($_POST['hospital'] ?? '');
$qualifications = trim($_POST['qualifications'] ?? '');

if ($id <= 0 || $first === '' || $last === '' || $email === '') {
    redirect('http://localhost/project/adminpage.php?error=invalid_doctor');
}

$conn = db_connect();

$check = $conn->prepare('SELECT id FROM users WHERE email = ? AND id <> ?');
$check->bind_param('si', $email, $id);
$check->execute();
if ($check->get_result()->fetch_assoc()) {
    $check->close();
    $conn->close();
    redirect('http://localhost/project/adminpage.php?error=doctor_exists');
}
$check->close();

$stmt = $conn->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, specialization = ?, hospital = ?, qualifications = ? WHERE id = ? AND role = \'doctor\'');
$stmt->bind_param('sssssssi', $first, $last, $email, $phone, $specialization, $hospital, $qualifications, $id);
$stmt->execute();
$stmt->close();
$conn->close();

redirect('http://localhost/project/adminpage.php?success=doctor_updated');
?>
