<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin();

$supplierId = $_POST['supplier_id'] ?? 0;
if (!$supplierId) redirect('/pages/directory.php');

$slugStmt = $pdo->prepare('SELECT slug FROM suppliers WHERE id = ?');
$slugStmt->execute([$supplierId]);
$slugRow = $slugStmt->fetch();
$slug = $slugRow ? $slugRow['slug'] : '';

$chk = $pdo->prepare('SELECT id FROM saved_suppliers WHERE user_id = ? AND supplier_id = ?');
$chk->execute([$_SESSION['user_id'], $supplierId]);
if ($chk->fetch()) {
    $pdo->prepare('DELETE FROM saved_suppliers WHERE user_id = ? AND supplier_id = ?')->execute([$_SESSION['user_id'], $supplierId]);
    flashMessage('info', 'Removed from saved.');
} else {
    $pdo->prepare('INSERT INTO saved_suppliers (user_id, supplier_id) VALUES (?,?)')->execute([$_SESSION['user_id'], $supplierId]);
    flashMessage('success', 'Supplier saved!');
}

redirect('/pages/supplier.php?slug=' . $slug);
?>
