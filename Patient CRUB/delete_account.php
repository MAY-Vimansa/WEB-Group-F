<?php
require_once __DIR__ . '/auth.php';
require_role('patient');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('http://localhost/project/home.php');
}

$user_id = (int)($_SESSION['user_id'] ?? 0);
if ($user_id <= 0) {
    redirect('http://localhost/project/home.php');
}

$conn = db_connect();
$stmt = $conn->prepare('DELETE FROM users WHERE id = ? AND role = \'patient\'');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

redirect('http://localhost/project/home.php');
?>
