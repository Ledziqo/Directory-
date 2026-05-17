<?php
require_once '../includes/db.php';

$email = 'admin@ethiomarket.et';
$password = 'admin123';
$fullName = 'System Admin';
$phone = '0911111111';

$chk = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$chk->execute([$email]);

if ($chk->fetch()) {
    die('Admin user already exists.');
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (full_name, email, phone, password_hash, role, email_verified) VALUES (?,?,?,?,?,?)');
$stmt->execute([$fullName, $email, $phone, $hash, 'admin', 1]);

echo 'Admin created successfully!\n';
echo 'Email: ' . $email . '\n';
echo 'Password: ' . $password . '\n';
echo 'IMPORTANT: Change the password after your first login.\n';
?>
