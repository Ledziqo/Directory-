<?php
require_once 'db.php';

function getCategories($pdo) {
    $stmt = $pdo->query('SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order, name');
    return $stmt->fetchAll();
}

function getLocations($pdo) {
    $stmt = $pdo->query('SELECT * FROM locations WHERE is_active = 1 ORDER BY sort_order, name');
    return $stmt->fetchAll();
}

function getCategoryById($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getLocationById($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM locations WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    return date('M j, Y', $time);
}

function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

function ensureUniqueSlug($pdo, $table, $slug, $excludeId = null) {
    $base = $slug;
    $counter = 1;
    while (true) {
        $sql = "SELECT id FROM $table WHERE slug = ?";
        $params = [$slug];
        if ($excludeId) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if (!$stmt->fetch()) break;
        $slug = $base . '-' . $counter;
        $counter++;
    }
    return $slug;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isSupplier() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'supplier';
}

function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function getSupplierByUserId($pdo, $userId) {
    $stmt = $pdo->prepare('SELECT * FROM suppliers WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch();
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function flashMessage($type, $msg) {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function showFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $cls = $f['type'] === 'success' ? 'flash-success' : ($f['type'] === 'error' ? 'flash-error' : 'flash-info');
        echo '<div class="flash ' . $cls . '">' . htmlspecialchars($f['msg']) . '</div>';
    }
}

function logAdminAction($pdo, $adminId, $action, $targetType, $targetId, $details = '') {
    $stmt = $pdo->prepare('INSERT INTO admin_logs (admin_id, action, target_type, target_id, details) VALUES (?,?,?,?,?)');
    $stmt->execute([$adminId, $action, $targetType, $targetId, $details]);
}

function truncate($text, $len = 100) {
    if (strlen($text) <= $len) return $text;
    return substr($text, 0, $len) . '...';
}

function generateWhatsAppLink($number, $message = '') {
    $num = preg_replace('/[^0-9]/', '', $number);
    if (strlen($num) <= 10 && strpos($num, '251') !== 0) {
        $num = '251' . ltrim($num, '0');
    }
    $url = 'https://wa.me/' . $num;
    if ($message) $url .= '?text=' . urlencode($message);
    return $url;
}

function generateTelegramLink($username) {
    $u = ltrim($username, '@');
    return 'https://t.me/' . $u;
}

function getUrgencyLabel($urgency) {
    $map = ['today' => 'Urgent: Today', 'this_week' => 'This Week', 'flexible' => 'Flexible'];
    return $map[$urgency] ?? 'Flexible';
}

function getUrgencyClass($urgency) {
    $map = ['today' => 'urgent-today', 'this_week' => 'urgent-week', 'flexible' => 'urgent-flex'];
    return $map[$urgency] ?? 'urgent-flex';
}
?>
