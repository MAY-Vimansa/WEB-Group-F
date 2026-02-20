<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: http://localhost/project/Login/login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><script>alert("Please enter your email and password.");window.location.href="http://localhost/project/Login/login.html";</script></head><body></body></html>';
    exit;
}

$conn = db_connect();
$stmt = $conn->prepare('SELECT id, role, first_name, last_name, password_hash FROM users WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($password, $user['password_hash'])) {
    $conn->close();
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><script>alert("Invalid email or password.");window.location.href="http://localhost/project/Login/login.html";</script></head><body></body></html>';
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];
$_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];

$conn->close();

if ($user['role'] === 'admin') {
    header('Location: http://localhost/project/adminpage.php');
} elseif ($user['role'] === 'doctor') {
    header('Location: http://localhost/project/doctorpage.php');
} else {
    header('Location: http://localhost/project/page.php');
}
exit;
?>
