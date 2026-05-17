<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Supplier Directory';

$q = $_GET['q'] ?? '';
$category = $_GET['category'] ?? '';
$location = $_GET['location'] ?? '';
$verified = $_GET['verified'] ?? '';
$delivery = $_GET['delivery'] ?? '';

// Get all approved suppliers
$stmt = $pdo->query('SELECT * FROM suppliers WHERE status = "approved" ORDER BY is_featured DESC, is_verified DESC, created_at DESC');
$suppliers = $stmt->fetchAll();

// Get categories and locations for dropdowns
$categories = getCategories($pdo);
$locations = getLocations($pdo);

// Build lookup maps
$catMap = [];
foreach ($categories as $cat) $catMap[$cat['id']] = $cat['name'];
$locMap = [];
foreach ($locations as $loc) $locMap[$loc['id']] = $loc['name'];

// Filter in PHP (PreviewPDO doesn't handle complex JOINs/GROUP BY well)
$filtered = [];
foreach ($suppliers as $sup) {
    // Text search
    if ($q) {
        $searchable = strtolower(($sup['business_name'] ?? '') . ' ' . ($sup['description'] ?? '') . ' ' . ($sup['subcategory'] ?? ''));
        if (strpos($searchable, strtolower($q)) === false) continue;
    }
    // Category filter
    if ($category && ($sup['category_id'] ?? '') != $category) continue;
    // Location filter
    if ($location && ($sup['location_id'] ?? '') != $location) continue;
    // Verified filter
    if ($verified && empty($sup['is_verified'])) continue;
    // Delivery filter
    if ($delivery && empty($sup['delivery_available'])) continue;

    // Add joined data
    $sup['category_name'] = $catMap[$sup['category_id'] ?? ''] ?? '';
    $sup['location_name'] = $locMap[$sup['location_id'] ?? ''] ?? '';

    // Get reviews for this supplier
    $revStmt = $pdo->prepare('SELECT rating FROM reviews WHERE supplier_id = ?');
    $revStmt->execute([$sup['id']]);
    $reviews = $revStmt->fetchAll();
    $ratings = array_column($reviews, 'rating');
    $sup['avg_rating'] = !empty($ratings) ? round(array_sum($ratings) / count($ratings), 1) : 0;
    $sup['review_count'] = count($reviews);

    $filtered[] = $sup;
}

require_once '../includes/header.php';
?>

<div class="search-bar">
    <div class="container">
        <form method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search suppliers..." value="<?= htmlspecialchars($q) ?>">
            <select name="category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $category == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="location">
                <option value="">All Locations</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= $loc['id'] ?>" <?= $location == $loc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($loc['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="filters" style="margin-top:12px;">
            <label style="display:flex; align-items:center; gap:6px; font-size:0.88rem;">
                <input type="checkbox" name="verified" value="1" <?= $verified ? 'checked' : '' ?> onchange="this.form.submit()"> Verified Only
            </label>
            <label style="display:flex; align-items:center; gap:6px; font-size:0.88rem;">
                <input type="checkbox" name="delivery" value="1" <?= $delivery ? 'checked' : '' ?> onchange="this.form.submit()"> Delivery Available
            </label>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2><?= count($filtered) ?> Supplier<?= count($filtered) !== 1 ? 's' : '' ?> Found</h2>
        </div>

        <div class="grid-3">
            <?php foreach ($filtered as $sup): ?>
                <div class="card supplier-card">
                    <div class="card-body">
                        <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                            <div class="profile-logo" style="width:48px; height:48px; font-size:1.2rem;">
                                <?= strtoupper(substr($sup['business_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <div class="card-title" style="margin-bottom:2px;"><?= htmlspecialchars($sup['business_name']) ?></div>
                                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                    <?php if ($sup['is_verified']): ?>
                                        <span class="badge badge-verified">Verified</span>
                                    <?php endif; ?>
                                    <?php if ($sup['is_featured']): ?>
                                        <span class="badge badge-premium">Featured</span>
                                    <?php endif; ?>
                                    <?php if ($sup['delivery_available']): ?>
                                        <span class="badge badge-success">Delivery</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="supplier-meta">
                            <span><?= htmlspecialchars($sup['category_name'] ?? '') ?></span>
                            <span><?= htmlspecialchars($sup['location_name'] ?? '') ?></span>
                            <span>Rating <?= number_format($sup['avg_rating'] ?? 0, 1) ?> (<?= $sup['review_count'] ?>)</span>
                        </div>
                        <div class="card-text"><?= htmlspecialchars(truncate($sup['description'], 100)) ?></div>
                        <div class="card-actions">
                            <a href="supplier.php?slug=<?= $sup['slug'] ?>" class="btn btn-sm btn-primary">View Profile</a>
                            <?php if ($sup['whatsapp'] || $sup['phone']): ?>
                                <?php $wa = generateWhatsAppLink($sup['whatsapp'] ?: $sup['phone'], 'Hi, I found you on EthioMarket. I am interested in your services.'); ?>
                                <a href="<?= $wa ?>" target="_blank" class="btn btn-sm btn-success">WhatsApp</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($filtered)): ?>
                <div style="grid-column: 1 / -1; text-align:center; padding:60px 20px; color:var(--text-light);">
                    <div class="step-icon">0</div>
                    <p style="font-size:1.1rem; margin-bottom:8px;">No suppliers found.</p>
                    <p>Try a different search or <a href="post-request.php">post a request</a> to let suppliers find you.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>