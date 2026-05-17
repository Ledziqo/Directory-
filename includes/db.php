<?php
session_start();

$host = 'localhost';
$db   = 'ethio_marketplace';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_TIMEOUT            => 1,
];

$isLocalPreview = in_array($_SERVER['HTTP_HOST'] ?? '', ['127.0.0.1:8000', 'localhost:8000'], true);

if ($isLocalPreview && empty($_GET['live_db'])) {
    require_once __DIR__ . '/preview_data.php';
    $pdo = new PreviewPDO();
    $GLOBALS['previewMode'] = true;
    date_default_timezone_set('Africa/Nairobi');
    return;
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    if (!$isLocalPreview) {
        die('Database connection failed: ' . $e->getMessage());
    }

    require_once __DIR__ . '/preview_data.php';
    $pdo = new PreviewPDO();
    $GLOBALS['previewMode'] = true;
}

date_default_timezone_set('Africa/Nairobi');
?>
