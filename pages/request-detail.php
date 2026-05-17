<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

$id = $_GET['id'] ?? 0;
if (!$id) { redirect('/pages/requests.php'); }

$stmt = $pdo->prepare('
    SELECT r.*, c.name as category_name, l.name as location_name, u.full_name as buyer_name, u.phone as buyer_phone, u.email as buyer_email
    FROM buyer_requests r
    LEFT JOIN categories c ON r.category_id = c.id
    LEFT JOIN locations l ON r.location_id = l.id
    LEFT JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
');
$stmt->execute([$id]);
$request = $stmt->fetch();

if (!$request) {
    flashMessage('error', 'Request not found.');
    redirect('/pages/requests.php');
}

$pdo->prepare('UPDATE buyer_requests SET view_count = view_count + 1 WHERE id = ?')->execute([$id]);

$quotesStmt = $pdo->prepare('
    SELECT q.*, s.business_name, s.slug as supplier_slug, s.whatsapp, s.phone, s.telegram
    FROM quotes q
    LEFT JOIN suppliers s ON q.supplier_id = s.id
    WHERE q.request_id = ?
    ORDER BY q.created_at DESC
');
$quotesStmt->execute([$id]);
$quotes = $quotesStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isSupplier()) {
    $supplier = getSupplierByUserId($pdo, $_SESSION['user_id']);
    if ($supplier) {
        $price = trim($_POST['price'] ?? '');
        $delivery = trim($_POST['delivery_time'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        $pdo->prepare('INSERT INTO quotes (request_id, supplier_id, price, delivery_time, message) VALUES (?,?,?,?,?)')
            ->execute([$id, $supplier['id'], $price, $delivery, $message]);
        
        $pdo->prepare('UPDATE buyer_requests SET quote_count = quote_count + 1 WHERE id = ?')->execute([$id]);
        
        flashMessage('success', 'Your quote has been sent!');
        redirect('/pages/request-detail.php?id=' . $id);
    }
}

$canSeeContact = false;
if ($request['privacy'] === 'public') {
    $canSeeContact = isLoggedIn();
} else {
    $canSeeContact = isLoggedIn() && isSupplier();
}

// Handle request close/fulfill by owner
$isOwner = isLoggedIn() && $_SESSION['user_id'] == $request['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isOwner && isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action === 'close_request') {
        $pdo->prepare('UPDATE buyer_requests SET status = "closed" WHERE id = ?')->execute([$id]);
        flashMessage('info', 'Request closed.');
        redirect('/pages/request-detail.php?id=' . $id);
    } elseif ($action === 'fulfill_request') {
        $pdo->prepare('UPDATE buyer_requests SET status = "fulfilled" WHERE id = ?')->execute([$id]);
        flashMessage('success', 'Request marked as fulfilled! 🎉');
        redirect('/pages/request-detail.php?id=' . $id);
    }
}

$pageTitle = htmlspecialchars($request['title']);
require_once '../includes/header.php';
?>

<section class="section" style="background:#fff;">
    <div class="container">
        <div class="grid-2" style="align-items:start;">
            <div>
                <div style="margin-bottom:16px;">
                    <a href="requests.php" style="font-size:0.9rem; color:var(--text-light);">? Back to Request Board</a>
                </div>

                <h1 style="font-size:1.6rem; font-weight:800; margin-bottom:10px;"><?= htmlspecialchars($request['title']) ?></h1>
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
                    <span class="badge <?= getUrgencyClass($request['urgency']) ?>"><?= getUrgencyLabel($request['urgency']) ?></span>
                    <span class="badge badge-open"><?= ucfirst($request['status']) ?></span>
                    <?php if ($request['is_pinned']): ?>
                        <span class="badge badge-premium">? Pinned</span>
                    <?php endif; ?>
                </div>

                <div class="profile-section">
                    <div style="display:grid; gap:14px; font-size:0.95rem;">
                        <div>
                            <strong style="color:var(--text-light); font-weight:500;">Category:</strong>
                            <?= htmlspecialchars($request['category_name'] ?? 'General') ?>
                        </div>
                        <div>
                            <strong style="color:var(--text-light); font-weight:500;">Location:</strong>
                            <?= htmlspecialchars($request['location_name'] ?? 'Addis Ababa') ?>
                        </div>
                        <?php if ($request['quantity']): ?>
                        <div>
                            <strong style="color:var(--text-light); font-weight:500;">Quantity:</strong>
                            <?= htmlspecialchars($request['quantity']) ?>
                        </div>
                        <?php endif; ?>
                        <?php if ($request['budget']): ?>
                        <div>
                            <strong style="color:var(--text-light); font-weight:500;">Budget:</strong>
                            <?= htmlspecialchars($request['budget']) ?>
                        </div>
                        <?php endif; ?>
                        <div>
                            <strong style="color:var(--text-light); font-weight:500;">Posted:</strong>
                            <?= timeAgo($request['created_at']) ?>
                        </div>
                    </div>
                </div>

                <div class="profile-section">
                    <h3>Description</h3>
                    <p style="white-space:pre-wrap;"><?= nl2br(htmlspecialchars($request['description'] ?? '')) ?></p>
                </div>

                <?php if ($request['photo']): ?>
                <div class="profile-section">
                    <img src="<?= htmlspecialchars($request['photo']) ?>" alt="Request photo" style="max-width:100%; border-radius:var(--radius);">
                </div>
                <?php endif; ?>

                <?php if ($isOwner && $request['status'] === 'open'): ?>
                <div class="profile-section" style="background:#fffbeb; border-color:#fcd34d;">
                    <h3>Manage Request</h3>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="fulfill_request">
                            <button type="submit" class="btn btn-success">✓ Mark Fulfilled</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="close_request">
                            <button type="submit" class="btn btn-outline">Close Request</button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($canSeeContact): ?>
                <div class="profile-section" style="background:#f0fdf4; border-color:#86efac;">
                    <h3>Contact Buyer</h3>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <?php if ($request['contact_method'] === 'whatsapp' && $request['contact_value']): ?>
                            <a href="<?= generateWhatsAppLink($request['contact_value'], 'Hi, I saw your request on EthioMarket: ' . $request['title']) ?>" target="_blank" class="contact-btn contact-whatsapp">WhatsApp Buyer</a>
                        <?php elseif ($request['contact_method'] === 'telegram' && $request['contact_value']): ?>
                            <a href="<?= generateTelegramLink($request['contact_value']) ?>" target="_blank" class="contact-btn contact-telegram">Telegram Buyer</a>
                        <?php elseif ($request['contact_method'] === 'email' && $request['contact_value']): ?>
                            <a href="mailto:<?= htmlspecialchars($request['contact_value']) ?>" class="contact-btn contact-email">Email Buyer</a>
                        <?php elseif ($request['contact_method'] === 'phone' && $request['contact_value']): ?>
                            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $request['contact_value']) ?>" class="contact-btn contact-phone">Call Buyer</a>
                        <?php else: ?>
                            <p>No contact method provided.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php else: ?>
                <div class="profile-section" style="background:#eff6ff;">
                    <p><strong>Contact hidden.</strong> <?php if ($request['privacy'] === 'private'): ?>Suppliers can see this after logging in.<?php else: ?>Log in to see buyer contact details.<?php endif; ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div>
                <?php if (isSupplier()): ?>
                    <?php $mySupplier = getSupplierByUserId($pdo, $_SESSION['user_id']); ?>
                    <?php if ($mySupplier && $mySupplier['status'] === 'approved'): ?>
                        <div class="profile-section">
                            <h3>Send a Quote</h3>
                            <p style="font-size:0.9rem; color:var(--text-light); margin-bottom:14px;">Responding as <strong><?= htmlspecialchars($mySupplier['business_name']) ?></strong></p>
                            <form method="POST">
                                <div class="form-group">
                                    <label>Your Price / Quote</label>
                                    <input type="text" name="price" placeholder="e.g., 5,000 ETB or negotiable" required>
                                </div>
                                <div class="form-group">
                                    <label>Delivery Time</label>
                                    <input type="text" name="delivery_time" placeholder="e.g., 2 days, same day" required>
                                </div>
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea name="message" rows="4" placeholder="Describe your offer, available options, etc." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-accent" style="width:100%;">Send Quote</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="profile-section" style="background:#fef3c7;">
                            <p>Your supplier profile must be approved before you can send quotes. <a href="dashboard.php">Check status</a></p>
                        </div>
                    <?php endif; ?>
                <?php elseif (isLoggedIn()): ?>
                    <div class="profile-section" style="background:#f3f4f6;">
                        <p>You are logged in as a buyer. <a href="dashboard.php?tab=requests">Manage your requests</a>.</p>
                    </div>
                <?php else: ?>
                    <div class="profile-section" style="background:#eff6ff;">
                        <p><a href="login.php">Log in</a> as a supplier to send a quote.</p>
                    </div>
                <?php endif; ?>

                <?php if ($request['user_id'] == ($_SESSION['user_id'] ?? 0) || isAdmin()): ?>
                    <div class="profile-section">
                        <h3>Quotes Received (<?= count($quotes) ?>)</h3>
                        <?php if (empty($quotes)): ?>
                            <p style="color:var(--text-light);">No quotes yet. Suppliers will reply soon.</p>
                        <?php else: ?>
                            <?php foreach ($quotes as $q): ?>
                                <div style="padding:16px 0; border-bottom:1px solid var(--border);">
                                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                                        <strong><a href="supplier.php?slug=<?= $q['supplier_slug'] ?>"><?= htmlspecialchars($q['business_name']) ?></a></strong>
                                        <span style="font-size:0.82rem; color:var(--text-light);"><?= timeAgo($q['created_at']) ?></span>
                                    </div>
                                    <div style="font-size:0.9rem; margin-bottom:6px;">
                                        <span style="color:var(--success); font-weight:700;"><?= htmlspecialchars($q['price'] ?? '-') ?></span>
                                        <span style="color:var(--text-light); margin-left:10px;"><?= htmlspecialchars($q['delivery_time'] ?? '-') ?></span>
                                    </div>
                                    <p style="font-size:0.88rem; color:var(--text);"><?= htmlspecialchars($q['message'] ?? '') ?></p>
                                    <div style="display:flex; gap:8px; margin-top:10px;">
                                        <?php if ($q['whatsapp'] || $q['phone']): ?>
                                            <a href="<?= generateWhatsAppLink($q['whatsapp'] ?: $q['phone'], 'Hi, I received your quote for: ' . $request['title']) ?>" target="_blank" class="btn btn-sm btn-success">WhatsApp</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
