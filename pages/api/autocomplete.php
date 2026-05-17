<?php
require_once '../../includes/db.php';

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) {
    echo json_encode([]);
    exit;
}

try {
    // Search suppliers using FULLTEXT if available, fallback to LIKE
    $like = '%' . $q . '%';
    $stmt = $pdo->prepare('
        SELECT DISTINCT business_name as name, "supplier" as type, slug
        FROM suppliers
        WHERE status = "approved" AND (business_name LIKE ? OR subcategory LIKE ?)
        LIMIT 5
    ');
    $stmt->execute([$like, $like]);
    $suppliers = $stmt->fetchAll();
    
    // Search buyer requests
    $stmt2 = $pdo->prepare('
        SELECT DISTINCT title as name, "request" as type, id as slug
        FROM buyer_requests
        WHERE status = "open" AND title LIKE ?
        LIMIT 5
    ');
    $stmt2->execute([$like]);
    $requests = $stmt2->fetchAll();
    
    // Merge and sort
    $results = array_merge($suppliers, $requests);
    
    // Also search categories for quick category links
    $stmt3 = $pdo->prepare('
        SELECT name, "category" as type, id as slug
        FROM categories
        WHERE is_active = 1 AND name LIKE ?
        LIMIT 3
    ');
    $stmt3->execute([$like]);
    $categories = $stmt3->fetchAll();
    
    $results = array_merge($results, $categories);
    
    echo json_encode($results);
} catch (Exception $e) {
    echo json_encode([]);
}