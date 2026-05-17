<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) { redirect('/pages/directory.php'); }

$stmt = $pdo->prepare('
    SELECT s.*, c.name as category_name, l.name as location_name, u.full_name as owner_name, u.email as owner_email
    FROM suppliers s
    LEFT JOIN categories c ON s.category_id = c.id
    LEFT JOIN locations l ON s.location_id = l.id
    LEFT JOIN users u ON s.user_id = u.id
    WHERE s.slug = ? AND s.status = "approved"
');
$stmt->execute([$slug]);
$supplier = $stmt->fetch();

if (!$supplier) {
    flashMessage('error', 'Supplier not found.');
    redirect('/pages/directory.php');
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'review' && isLoggedIn()) {
    $rating = (int)($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    
    if ($rating < 1 || $rating > 5) {
        flashMessage('error', 'Please select a rating.');
    } elseif (empty($comment)) {
        flashMessage('error', 'Please write a review comment.');
    } else {
        // Check if user already reviewed this supplier
        $check = $pdo->prepare('SELECT id FROM reviews WHERE supplier_id = ? AND user_id = ?');
        $check->execute([$supplier['id'], $_SESSION['user_id']]);
        
        if ($check->fetch()) {
            flashMessage('error', 'You have already reviewed this supplier.');
        } else {
            $pdo->prepare('INSERT INTO reviews (supplier_id, user_id, rating, comment) VALUES (?,?,?,?)')
                ->execute([$supplier['id'], $_SESSION['user_id'], $rating, $comment]);
            flashMessage('success', 'Review submitted! Thank you.');
        }
    }
    redirect('/pages/supplier.php?slug=' . $slug);
}

// Track view (only once per session per supplier)
$viewKey = 'viewed_supplier_' . $supplier['id'];
if (!isset($_SESSION[$viewKey])) {
    $pdo->prepare('UPDATE suppliers SET view_count = view_count + 1 WHERE id = ?')->execute([$supplier['id']]);
    $_SESSION[$viewKey] = true;
    
    // Log real view for analytics
    try {
        $pdo->prepare('INSERT INTO supplier_views (supplier_id, ip_address, user_agent) VALUES (?,?,?)')
            ->execute([$supplier['id'], $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]);
    } catch (PDOException $e) {
        // Table might not exist yet
    }
}

$photosStmt = $pdo->prepare('SELECT * FROM supplier_photos WHERE supplier_id = ? ORDER BY sort_order');
$photosStmt->execute([$supplier['id']]);
$photos = $photosStmt->fetchAll();

$reviewsStmt = $pdo->prepare('
    SELECT r.*, u.full_name as reviewer_name
    FROM reviews r
    LEFT JOIN users u ON r.user_id = u.id
    WHERE r.supplier_id = ?
    ORDER BY r.created_at DESC
');
$reviewsStmt->execute([$supplier['id']]);
$reviews = $reviewsStmt->fetchAll();

$isSaved = false;
if (isLoggedIn()) {
    $chk = $pdo->prepare('SELECT id FROM saved_suppliers WHERE user_id = ? AND supplier_id = ?');
    $chk->execute([$_SESSION['user_id'], $supplier['id']]);
    $isSaved = $chk->fetch() ? true : false;
}

// Calculate average rating
$avgRating = 0;
if (count($reviews) > 0) {
    $total = array_sum(array_column($reviews, 'rating'));
    $avgRating = round($total / count($reviews), 1);
}

// Check if open now
function isOpenNow($hoursStr) {
    if (empty($hoursStr)) return null;
    // Simple check: if string contains "Closed" for today, return false
    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $today = $days[date('w')];
    if (stripos($hoursStr, $today) !== false && stripos($hoursStr, 'Closed') !== false) {
        return false;
    }
    // Check if current hour is within range (simplified)
    $hour = (int)date('G');
    if ($hour >= 8 && $hour < 18) {
        return true;
    }
    return null; // unknown
}

$isOpen = isOpenNow($supplier['opening_hours'] ?? '');

$pageTitle = htmlspecialchars($supplier['business_name']);
$pageDescription = htmlspecialchars(truncate($supplier['description'] ?? '', 160));
$pageType = 'LocalBusiness';

$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $supplier['business_name'],
    'description' => $supplier['description'],
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => $supplier['location_name'] ?? 'Addis Ababa',
        'addressCountry' => 'ET'
    ],
    'telephone' => $supplier['phone'] ?? '',
    'email' => $supplier['email'] ?? '',
    'url' => 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
    'aggregateRating' => [
        '@type' => 'AggregateRating',
        'ratingValue' => $avgRating,
        'reviewCount' => count($reviews)
    ],
    'priceRange' => '$$',
    'paymentAccepted' => 'Cash, Telebirr, Bank Transfer'
];

require_once '../includes/header.php';
?>

<section class="profile-hero" aria-label="Supplier profile header">
    <div class="container">
        <div class="profile-hero-inner">
            <div class="profile-logo" aria-label="Business logo">
                <?php if ($supplier['logo']): ?>
                    <img src="<?= htmlspecialchars($supplier['logo']) ?>" alt="<?= htmlspecialchars($supplier['business_name']) ?> logo" loading="lazy">
                <?php else: ?>
                    <?= strtoupper(substr($supplier['business_name'], 0, 1)) ?>
                <?php endif; ?>
            </div>
            <div class="profile-info">
                <h1>
                    <?= htmlspecialchars($supplier['business_name']) ?>
                    <?php if ($supplier['is_verified']): ?>
                        <span class="badge badge-verified">Verified</span>
                    <?php endif; ?>
                    <?php if ($supplier['is_featured']): ?>
                        <span class="badge badge-premium">Featured</span>
                    <?php endif; ?>
                </h1>
                <div class="profile-meta">
                    <span><?= htmlspecialchars($supplier['category_name'] ?? '') ?></span>
                    <span><?= htmlspecialchars($supplier['location_name'] ?? '') ?></span>
                    <span>Rating <?= number_format($avgRating, 1) ?> (<?= count($reviews) ?> reviews)</span>
                    <span><?= $supplier['view_count'] ?> views</span>
                    <?php if ($isOpen === true): ?>
                        <span class="open-indicator">
                            <span class="pulse" aria-hidden="true"></span>
                            Open Now
                        </span>
                    <?php elseif ($isOpen === false): ?>
                        <span class="badge badge-closed">Closed</span>
                    <?php endif; ?>
                </div>
                <div class="profile-actions">
                    <?php if ($supplier['whatsapp'] || $supplier['phone']): ?>
                        <?php $wa = generateWhatsAppLink($supplier['whatsapp'] ?: $supplier['phone'], 'Hi, I found you on EthioMarket and I am interested in your products/services.'); ?>
                        <a href="<?= $wa ?>" target="_blank" class="contact-btn contact-whatsapp" rel="noopener noreferrer">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>
                    <?php endif; ?>
                    <?php if ($supplier['telegram']): ?>
                        <a href="<?= generateTelegramLink($supplier['telegram']) ?>" target="_blank" class="contact-btn contact-telegram" rel="noopener noreferrer">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                            Telegram
                        </a>
                    <?php endif; ?>
                    <?php if ($supplier['phone']): ?>
                        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $supplier['phone']) ?>" class="contact-btn contact-phone">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                            Call
                        </a>
                    <?php endif; ?>
                    <?php if (isLoggedIn()): ?>
                        <form method="POST" action="save-supplier.php" style="display:inline;">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                            <input type="hidden" name="supplier_id" value="<?= $supplier['id'] ?>">
                            <button type="submit" class="btn btn-outline"><?= $isSaved ? '&#10084; Saved' : '&#9825; Save' ?></button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sticky Mobile Contact Bar -->
<div class="mobile-contact-bar" role="toolbar" aria-label="Quick contact actions">
    <?php if ($supplier['whatsapp'] || $supplier['phone']): ?>
        <?php $wa = generateWhatsAppLink($supplier['whatsapp'] ?: $supplier['phone'], 'Hi, I found you on EthioMarket and I am interested in your products/services.'); ?>
        <a href="<?= $wa ?>" target="_blank" class="btn btn-success" rel="noopener noreferrer">WhatsApp</a>
    <?php endif; ?>
    <?php if ($supplier['phone']): ?>
        <a href="tel:<?= preg_replace('/[^0-9+]/', '', $supplier['phone']) ?>" class="btn btn-primary">Call Now</a>
    <?php endif; ?>
</div>

<section class="section" style="padding-top:20px;">
    <div class="container">
        <div class="grid-2">
            <div>
                <div class="profile-section">
                    <h3>About</h3>
                    <p><?= nl2br(htmlspecialchars($supplier['description'] ?? 'No description provided.')) ?></p>
                </div>

                <?php if ($supplier['opening_hours']): ?>
                <div class="profile-section">
                    <h3>Opening Hours</h3>
                    <p><?= nl2br(htmlspecialchars($supplier['opening_hours'])) ?></p>
                </div>
                <?php endif; ?>

                <?php if (!empty($photos)): ?>
                <div class="profile-section">
                    <h3>Photos</h3>
                    <div class="photo-grid" role="list" aria-label="Business photos">
                        <?php foreach ($photos as $photo): ?>
                            <img src="<?= htmlspecialchars($photo['photo_path']) ?>" 
                                 alt="<?= htmlspecialchars($photo['caption'] ?? 'Business photo') ?>" 
                                 loading="lazy"
                                 role="listitem">
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Lightbox -->
                <div class="lightbox" id="lightbox" role="dialog" aria-label="Photo viewer" aria-modal="true">
                    <button class="lightbox-close" id="lightboxClose" aria-label="Close photo viewer">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <img src="" alt="" id="lightboxImg">
                </div>
                <?php endif; ?>

                <div class="profile-section">
                    <h3>Reviews (<?= count($reviews) ?>)</h3>
                    <?php if (empty($reviews)): ?>
                        <div class="empty-state" style="padding:30px 0;">
                            <div class="empty-state-icon">&#128172;</div>
                            <div class="empty-state-title">No reviews yet</div>
                            <div class="empty-state-text">Be the first to review this supplier after doing business.</div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($reviews as $rev): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div>
                                        <strong><?= htmlspecialchars($rev['reviewer_name'] ?? 'Anonymous') ?></strong>
                                        <div class="stars" aria-label="<?= $rev['rating'] ?> out of 5 stars">
                                            <?= str_repeat('&#9733;', $rev['rating']) . str_repeat('&#9734;', 5 - $rev['rating']) ?>
                                        </div>
                                    </div>
                                    <span style="font-size:0.82rem; color:var(--text-light);"><?= timeAgo($rev['created_at']) ?></span>
                                </div>
                                <p><?= htmlspecialchars($rev['comment'] ?? '') ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if (isLoggedIn() && $_SESSION['user_id'] != $supplier['user_id']): ?>
                        <?php
                        $alreadyReviewed = false;
                        foreach ($reviews as $rev) {
                            if ($rev['user_id'] == $_SESSION['user_id']) {
                                $alreadyReviewed = true;
                                break;
                            }
                        }
                        ?>
                        <?php if (!$alreadyReviewed): ?>
                            <div style="margin-top:24px; padding-top:24px; border-top:1px solid var(--border);">
                                <h4 style="font-size:1rem; font-weight:600; margin-bottom:14px;">Write a Review</h4>
                                <form method="POST">
                                    <input type="hidden" name="action" value="review">
                                    <div class="form-group" style="margin-bottom:14px;">
                                        <label>Rating</label>
                                        <div style="display:flex; gap:8px; font-size:1.6rem; cursor:pointer;" id="starRating">
                                            <span data-value="1" onclick="setRating(1)" style="color:#CBD5E1;">&#9733;</span>
                                            <span data-value="2" onclick="setRating(2)" style="color:#CBD5E1;">&#9733;</span>
                                            <span data-value="3" onclick="setRating(3)" style="color:#CBD5E1;">&#9733;</span>
                                            <span data-value="4" onclick="setRating(4)" style="color:#CBD5E1;">&#9733;</span>
                                            <span data-value="5" onclick="setRating(5)" style="color:#CBD5E1;">&#9733;</span>
                                        </div>
                                        <input type="hidden" name="rating" id="ratingInput" value="0" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Your Experience</label>
                                        <textarea name="comment" rows="3" placeholder="How was your experience with this supplier?" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-accent">Submit Review</button>
                                </form>
                                <script>
                                    function setRating(n) {
                                        document.getElementById('ratingInput').value = n;
                                        const stars = document.querySelectorAll('#starRating span');
                                        stars.forEach((s, i) => {
                                            s.style.color = i < n ? '#F59E0B' : '#CBD5E1';
                                        });
                                    }
                                </script>
                            </div>
                        <?php endif; ?>
                    <?php elseif (!isLoggedIn()): ?>
                        <div style="margin-top:24px; padding-top:24px; border-top:1px solid var(--border);">
                            <p style="color:var(--text-light); font-size:0.9rem;">
                                <a href="login.php">Log in</a> to leave a review.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="profile-section">
                    <h3>Business Details</h3>
                    <div style="display:grid; gap:16px; font-size:0.94rem;">
                        <div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:3px;">Category</div>
                            <strong><?= htmlspecialchars($supplier['category_name'] ?? '-') ?></strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:3px;">Location</div>
                            <strong><?= htmlspecialchars($supplier['address'] ?? $supplier['location_name'] ?? 'Addis Ababa') ?></strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:3px;">Phone</div>
                            <strong>
                                <a href="tel:<?= preg_replace('/[^0-9+]/', '', $supplier['phone'] ?? '') ?>" style="color:var(--accent);">
                                    <?= htmlspecialchars($supplier['phone'] ?? '-') ?>
                                </a>
                            </strong>
                        </div>
                        <?php if ($supplier['email']): ?>
                        <div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:3px;">Email</div>
                            <strong>
                                <a href="mailto:<?= htmlspecialchars($supplier['email']) ?>" style="color:var(--accent);">
                                    <?= htmlspecialchars($supplier['email']) ?>
                                </a>
                            </strong>
                        </div>
                        <?php endif; ?>
                        <?php if ($supplier['website']): ?>
                        <div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:3px;">Website</div>
                            <a href="<?= htmlspecialchars($supplier['website']) ?>" target="_blank" rel="noopener noreferrer" style="color:var(--accent);">
                                <?= htmlspecialchars($supplier['website']) ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        <div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:3px;">Delivery</div>
                            <strong><?= $supplier['delivery_available'] ? 'Available' : 'Not available' ?></strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem; color:var(--text-light); margin-bottom:3px;">Bulk Orders</div>
                            <strong><?= $supplier['bulk_available'] ? 'Available' : 'Not available' ?></strong>
                        </div>
                    </div>
                </div>
                
                <!-- Mini Map -->
                <div class="profile-section">
                    <h3>Location</h3>
                    <div class="map-embed" role="img" aria-label="Map showing <?= htmlspecialchars($supplier['location_name'] ?? 'Addis Ababa') ?>">
                        <iframe
                            width="100%"
                            height="100%"
                            frameborder="0"
                            scrolling="no"
                            marginheight="0"
                            marginwidth="0"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=38.7%2C8.9%2C39.0%2C9.1&layer=mapnik&marker=9.0%2C38.75"
                            style="border:0;"
                            title="Map of <?= htmlspecialchars($supplier['location_name'] ?? 'Addis Ababa') ?>"
                            loading="lazy">
                        </iframe>
                    </div>
                    <p style="font-size:0.82rem; color:var(--text-light); margin-top:8px;">
                        Approximate location in <?= htmlspecialchars($supplier['location_name'] ?? 'Addis Ababa') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>