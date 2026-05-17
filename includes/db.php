<?php
session_start();

// Hostinger MySQL credentials
$host = 'localhost';
$db   = 'u409029281_Directory';
$user = 'u409029281_Aesliexx';
$pass = 'Aesliex2005';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

date_default_timezone_set('Africa/Nairobi');
?>
