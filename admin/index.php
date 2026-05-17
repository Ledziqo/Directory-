<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireAdmin();

$tab = $_GET['tab'] ?? 'dashboard';
$adminId = $_SESSION['user_id'];

$totalUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$totalSuppliers = $pdo->query('SELECT COUNT(*) FROM suppliers')->fetchColumn();
$pendingSuppliers = $pdo->query('SELECT COUNT(*) FROM suppliers WHERE status = "pending"')->fetchColumn();
$totalRequests = $pdo->query('SELECT COUNT(*) FROM buyer_requests')->fetchColumn();
$openRequests = $pdo->query('SELECT COUNT(*) FROM buyer_requests WHERE status = "open"')->fetchColumn();
$pendingPayments = $pdo->query('SELECT COUNT(*) FROM payments WHERE status = "pending"')->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? 0;
    
    if ($action === 'approve_supplier' && $id) {
        $pdo->prepare('UPDATE suppliers SET status = "approved" WHERE id = ?')->execute([$id]);
        logAdminAction($pdo, $adminId, 'approve_supplier', 'supplier', $id, 'Approved supplier listing');
        flashMessage('success', 'Supplier approved.');
    }
    if ($action === 'reject_supplier' && $id) {
        $pdo->prepare('UPDATE suppliers SET status = "rejected" WHERE id = ?')->execute([$id]);
        logAdminAction($pdo, $adminId, 'reject_supplier', 'supplier', $id, 'Rejected supplier listing');
        flashMessage('info', 'Supplier rejected.');
    }
    if ($action === 'verify_supplier' && $id) {
        $pdo->prepare('UPDATE suppliers SET is_verified = 1 WHERE id = ?')->execute([$id]);
        logAdminAction($pdo, $adminId, 'verify_supplier', 'supplier', $id, 'Added verified badge');
        flashMessage('success', 'Supplier verified.');
    }
    if ($action === 'feature_supplier' && $id) {
        $pdo->prepare('UPDATE suppliers SET is_featured = 1 WHERE id = ?')->execute([$id]);
        logAdminAction($pdo, $adminId, 'feature_supplier', 'supplier', $id, 'Featured supplier');
        flashMessage('success', 'Supplier featured.');
    }
    if ($action === 'unfeature_supplier' && $id) {
        $pdo->prepare('UPDATE suppliers SET is_featured = 0 WHERE id = ?')->execute([$id]);
        logAdminAction($pdo, $adminId, 'unfeature_supplier', 'supplier', $id, 'Unfeatured supplier');
        flashMessage('info', 'Feature removed.');
    }
    if ($action === 'close_request' && $id) {
        $pdo->prepare('UPDATE buyer_requests SET status = "closed" WHERE id = ?')->execute([$id]);
        logAdminAction($pdo, $adminId, 'close_request', 'request', $id, 'Closed request');
        flashMessage('info', 'Request closed.');
    }
    if ($action === 'confirm_payment' && $id) {
        $pdo->prepare('UPDATE payments SET status = "confirmed", confirmed_by = ? WHERE id = ?')->execute([$adminId, $id]);
        logAdminAction($pdo, $adminId, 'confirm_payment', 'payment', $id, 'Confirmed payment');
        flashMessage('success', 'Payment confirmed.');
    }
    if ($action === 'add_category') {
        $name = trim($_POST['name'] ?? '');
        if ($name) {
            $slug = ensureUniqueSlug($pdo, 'categories', slugify($name));
            $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?,?)')->execute([$name, $slug]);
            flashMessage('success', 'Category added.');
        }
    }
    if ($action === 'delete_category' && $id) {
        $pdo->prepare('UPDATE categories SET is_active = 0 WHERE id = ?')->execute([$id]);
        flashMessage('info', 'Category deactivated.');
    }
    
    redirect('/admin/?tab=' . $tab);
}

$suppliers = $pdo->query('
    SELECT s.*, c.name as category_name, l.name as location_name, u.full_name as owner_name, u.email as owner_email
    FROM suppliers s
    LEFT JOIN categories c ON s.category_id = c.id
    LEFT JOIN locations l ON s.location_id = l.id
    LEFT JOIN users u ON s.user_id = u.id
    ORDER BY s.created_at DESC
')->fetchAll();

$pendingSups = array_filter($suppliers, fn($s) => $s['status'] === 'pending');

$requests = $pdo->query('
    SELECT r.*, c.name as category_name, l.name as location_name, u.full_name as buyer_name
    FROM buyer_requests r
    LEFT JOIN categories c ON r.category_id = c.id
    LEFT JOIN locations l ON r.location_id = l.id
    LEFT JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
')->fetchAll();

$payments = $pdo->query('
    SELECT p.*, u.full_name as user_name, s.business_name as supplier_name
    FROM payments p
    LEFT JOIN users u ON p.user_id = u.id
    LEFT JOIN suppliers s ON p.supplier_id = s.id
    ORDER BY p.created_at DESC
')->fetchAll();

$categories = getCategories($pdo);
$locations = getLocations($pdo);

$logsStmt = $pdo->prepare('SELECT l.*, u.full_name as admin_name FROM admin_logs l LEFT JOIN users u ON l.admin_id = u.id ORDER BY l.created_at DESC LIMIT 100');
$logsStmt->execute();
$logs = $logsStmt->fetchAll();

$pageTitle = 'Admin Panel';
require_once '../includes/header.php';
?>

<div class="admin-header">
    <div class="container">
        <h1>Admin Panel</h1>
        <div class="admin-nav">
            <a href="?tab=dashboard" class="<?= $tab === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="?tab=pending" class="<?= $tab === 'pending' ? 'active' : '' ?>">Pending (<?= count($pendingSups) ?>)</a>
            <a href="?tab=suppliers" class="<?= $tab === 'suppliers' ? 'active' : '' ?>">Suppliers</a>
            <a href="?tab=requests" class="<?= $tab === 'requests' ? 'active' : '' ?>">Requests</a>
            <a href="?tab=payments" class="<?= $tab === 'payments' ? 'active' : '' ?>">Payments</a>
            <a href="?tab=categories" class="<?= $tab === 'categories' ? 'active' : '' ?>">Categories</a>
            <a href="?tab=logs" class="<?= $tab === 'logs' ? 'active' : '' ?>">Logs</a>
        </div>
    </div>
</div>

<section class="section" style="padding-top:20px;">
    <div class="container">
        <?php showFlash(); ?>

        <?php if ($tab === 'dashboard'): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value"><?= $totalUsers ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $totalSuppliers ?></div>
                    <div class="stat-label">Total Suppliers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $pendingSuppliers ?></div>
                    <div class="stat-label">Pending Approval</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $totalRequests ?></div>
                    <div class="stat-label">Total Requests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $openRequests ?></div>
                    <div class="stat-label">Open Requests</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= $pendingPayments ?></div>
                    <div class="stat-label">Pending Payments</div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'pending'): ?>
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:20px;">Pending Suppliers</h2>
            <?php if (empty($pendingSups)): ?>
                <p style="color:var(--text-light);">No pending suppliers. All caught up!</p>
            <?php else: ?>
                <div class="grid-2">
                    <?php foreach ($pendingSups as $s): ?>
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title"><?= htmlspecialchars($s['business_name']) ?></div>
                                <div class="card-text" style="margin-bottom:10px;">
                                    <strong>Owner:</strong> <?= htmlspecialchars($s['owner_name']) ?> <br>
                                    <strong>Email:</strong> <?= htmlspecialchars($s['owner_email']) ?> <br>
                                    <strong>Phone:</strong> <?= htmlspecialchars($s['phone'] ?? '-') ?> <br>
                                    <strong>Category:</strong> <?= htmlspecialchars($s['category_name'] ?? '-') ?> <br>
                                    <strong>Location:</strong> <?= htmlspecialchars($s['location_name'] ?? '-') ?>
                                </div>
                                <p class="card-text"><?= htmlspecialchars(truncate($s['description'] ?? '', 150)) ?></p>
                                <div style="display:flex; gap:8px; margin-top:14px;">
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="approve_supplier">
                                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="reject_supplier">
                                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                    <a href="../pages/supplier.php?slug=<?= $s['slug'] ?>" target="_blank" class="btn btn-sm btn-outline">Preview</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($tab === 'suppliers'): ?>
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:20px;">All Suppliers</h2>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Business</th>
                            <th>Owner</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Verified</th>
                            <th>Featured</th>
                            <th>Plan</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($suppliers as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['business_name']) ?></td>
                                <td><?= htmlspecialchars($s['owner_name']) ?></td>
                                <td><?= htmlspecialchars($s['category_name'] ?? '-') ?></td>
                                <td><span class="badge <?= $s['status'] === 'approved' ? 'badge-success' : ($s['status'] === 'pending' ? 'badge-urgent' : 'badge-closed') ?>"><?= ucfirst($s['status']) ?></span></td>
                                <td><?= $s['is_verified'] ? 'Yes' : 'No' ?></td>
                                <td><?= $s['is_featured'] ? 'Yes' : 'No' ?></td>
                                <td><?= ucfirst($s['plan']) ?></td>
                                <td>
                                    <div style="display:flex; gap:4px;">
                                        <?php if (!$s['is_verified']): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="verify_supplier">
                                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-warning">Verify</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if (!$s['is_featured']): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="feature_supplier">
                                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-accent">Feature</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="unfeature_supplier">
                                                <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-outline">Unfeature</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'requests'): ?>
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:20px;">All Requests</h2>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Buyer</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Quotes</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars(truncate($r['title'], 40)) ?></td>
                                <td><?= htmlspecialchars($r['buyer_name']) ?></td>
                                <td><?= htmlspecialchars($r['category_name'] ?? '-') ?></td>
                                <td><span class="badge <?= $r['status'] === 'open' ? 'badge-open' : 'badge-closed' ?>"><?= ucfirst($r['status']) ?></span></td>
                                <td><?= $r['quote_count'] ?></td>
                                <td><?= timeAgo($r['created_at']) ?></td>
                                <td>
                                    <div style="display:flex; gap:4px;">
                                        <a href="../pages/request-detail.php?id=<?= $r['id'] ?>" target="_blank" class="btn btn-sm btn-outline">View</a>
                                        <?php if ($r['status'] === 'open'): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="close_request">
                                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Close</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'payments'): ?>
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:20px;">Payments</h2>
            <?php if (empty($payments)): ?>
                <p style="color:var(--text-light);">No payments recorded yet.</p>
            <?php else: ?>
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Supplier</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $p): ?>
                                <tr>
                                    <td><?= htmlspecialchars($p['user_name']) ?></td>
                                    <td><?= htmlspecialchars($p['supplier_name'] ?? '-') ?></td>
                                    <td><?= ucfirst($p['type']) ?></td>
                                    <td><?= htmlspecialchars($p['amount'] ?? '-') ?></td>
                                    <td><span class="badge <?= $p['status'] === 'confirmed' ? 'badge-success' : ($p['status'] === 'pending' ? 'badge-urgent' : 'badge-closed') ?>"><?= ucfirst($p['status']) ?></span></td>
                                    <td><?= timeAgo($p['created_at']) ?></td>
                                    <td>
                                        <?php if ($p['status'] === 'pending'): ?>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="action" value="confirm_payment">
                                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="font-size:0.82rem; color:var(--text-light);">Confirmed</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($tab === 'categories'): ?>
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:20px;">Categories</h2>
            <form method="POST" style="max-width:400px; margin-bottom:24px;">
                <input type="hidden" name="action" value="add_category">
                <div style="display:flex; gap:8px;">
                    <input type="text" name="name" placeholder="New category name" required style="flex:1;">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['name']) ?></td>
                                <td><?= htmlspecialchars($c['slug']) ?></td>
                                <td><?= $c['is_active'] ? 'Yes' : 'No' ?></td>
                                <td>
                                    <?php if ($c['is_active']): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="action" value="delete_category">
                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if ($tab === 'logs'): ?>
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:20px;">Admin Logs</h2>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Action</th>
                            <th>Target</th>
                            <th>Details</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $l): ?>
                            <tr>
                                <td><?= htmlspecialchars($l['admin_name'] ?? 'Unknown') ?></td>
                                <td><?= htmlspecialchars($l['action']) ?></td>
                                <td><?= htmlspecialchars($l['target_type'] ?? '-') ?> #<?= $l['target_id'] ?></td>
                                <td><?= htmlspecialchars($l['details'] ?? '') ?></td>
                                <td><?= timeAgo($l['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
