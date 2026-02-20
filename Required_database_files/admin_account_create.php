<?php
require_once __DIR__ . '/db.php';

// Run this once to create an admin account.
// Change these values before running in production.
$email = 'admin@mediconnect.local';
$password = 'admin123';
$first = 'Admin';
$last = 'User';

$conn = db_connect();
$check = $conn->prepare('SELECT id FROM users WHERE email = ?');
$check->bind_param('s', $email);
$check->execute();
$exists = $check->get_result()->fetch_assoc();
$check->close();

if ($exists) {
    echo 'Admin already exists.';
    $conn->close();
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (role, first_name, last_name, email, password_hash) VALUES (\'admin\', ?, ?, ?, ?)');
$stmt->bind_param('ssss', $first, $last, $email, $hash);
$stmt->execute();
$stmt->close();
$conn->close();

echo 'Admin created: ' . $email . ' / ' . $password;
?>
