<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect($path) {
    header('Location: ' . $path);
    exit;
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        redirect('http://localhost/project/Login/login.html');
    }
}

function require_role($role) {
    require_login();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        redirect('http://localhost/project/Login/login.html');
    }
}

function current_user() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $conn = db_connect();
    $stmt = $conn->prepare('SELECT id, first_name, last_name, email, role, phone, dob, gender, specialization, hospital, qualifications FROM users WHERE id = ?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>
