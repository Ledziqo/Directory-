<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Buyer Requests';

$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';
$urgency = $_GET['urgency'] ?? '';

// Get all open requests
$stmt = $pdo->query('SELECT * FROM buyer_requests WHERE status = "open" ORDER BY is_pinned DESC, created_at DESC');
$requests = $stmt->fetchAll();

$categories = getCategories($pdo);
$locations = getLocations($pdo);

// Build lookup maps
$catMap = [];
foreach ($categories as $cat) $catMap[$cat['id']] = $cat['name'];
$locMap = [];
foreach ($locations as $loc) $locMap[$loc['id']] = $loc['name'];

// Filter in PHP
$filtered = [];
foreach ($requests as $req) {
    if ($category && ($req['category_id'] ?? '') != $category) continue;
    if ($location && ($req['location_id'] ?? '') != $location) continue;
    if ($urgency && ($req['urgency'] ?? '') != $urgency) continue;

    $req['category_name'] = $catMap[$req['category_id'] ?? ''] ?? 'General';
    $req['location_name'] = $locMap[$req['location_id'] ?? ''] ?? 'Addis Ababa';
    $filtered[] = $req;
}

require_once '../includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Buyer Requests</h2>
            <a href="post-request.php" class="btn btn-sm btn-accent">+ Post Request</a>
        </div>

        <div class="filters" style="margin-bottom:24px;">
            <form method="GET" class="filters" style="margin-bottom:0;">
                <div class="form-group">
                    <select name="category" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="location" onchange="this.form.submit()">
                        <option value="">All Locations</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?= $loc['id'] ?>" <?= $location == $loc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($loc['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <select name="urgency" onchange="this.form.submit()">
                        <option value="">Any Urgency</option>
                        <option value="today" <?= $urgency === 'today' ? 'selected' : '' ?>>Urgent: Today</option>
                        <option value="this_week" <?= $urgency === 'this_week' ? 'selected' : '' ?>>This Week</option>
                        <option value="flexible" <?= $urgency === 'flexible' ? 'selected' : '' ?>>Flexible</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="grid-3">
            <?php foreach ($filtered as $req): ?>
                <a href="request-detail.php?id=<?= $req['id'] ?>" class="card request-card <?= getUrgencyClass($req['urgency']) ?> animate-on-scroll stagger-1">
                    <div class="card-body">
                        <div class="req-header">
                            <div>
                                <div class="card-title">
                                    <span class="urgency-dot <?= str_replace('urgent-', '', getUrgencyClass($req['urgency'])) ?>" aria-hidden="true"></span>
                                    <?= htmlspecialchars(truncate($req['title'], 60)) ?>
                                </div>
                            </div>
                            <span class="badge <?= getUrgencyClass($req['urgency']) ?>"><?= getUrgencyLabel($req['urgency']) ?></span>
                        </div>
                        <div class="req-meta">
                            <span><strong><?= htmlspecialchars($req['category_name']) ?></strong></span>
                            <span><?= htmlspecialchars($req['location_name']) ?></span>
                            <span><?= timeAgo($req['created_at']) ?></span>
                        </div>
                        <div class="card-text"><?= htmlspecialchars(truncate($req['description'], 120)) ?></div>
                        <div class="req-meta" style="margin-top:14px; font-size:0.85rem;">
                            <?= $req['quote_count'] ?> quotes received
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if (empty($filtered)): ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">&#128221;</div>
                    <div class="empty-state-title">No requests found</div>
                    <div class="empty-state-text">Be the first to <a href="post-request.php">post a request</a> and get quotes.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>