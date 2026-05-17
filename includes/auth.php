<?php
require_once 'db.php';
require_once 'functions.php';

function requireLogin() {
    if (!isLoggedIn()) {
        flashMessage('error', 'Please log in to continue.');
        redirect('/pages/login.php');
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        flashMessage('error', 'Access denied.');
        redirect('/');
    }
}

function requireSupplier() {
    requireLogin();
    if (!isSupplier()) {
        flashMessage('error', 'Supplier access only.');
        redirect('/');
    }
}

function loginUser($pdo, $email, $password) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        $pdo->prepare('UPDATE users SET last_active = NOW() WHERE id = ?')->execute([$user['id']]);
        return true;
    }
    return false;
}

function logoutUser() {
    session_destroy();
}
?>
