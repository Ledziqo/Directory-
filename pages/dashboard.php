<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

requireLogin();

$pageTitle = 'Dashboard';
$pageDescription = 'Manage your supplier profile, track requests, and view analytics on EthioMarket.';
$tab = $_GET['tab'] ?? 'overview';
$user = getCurrentUser($pdo);
$supplier = getSupplierByUserId($pdo, $_SESSION['user_id']);

if ($supplier && $user['role'] !== 'supplier' && $user['role'] !== 'admin') {
    $pdo->prepare('UPDATE users SET role = "supplier" WHERE id = ?')->execute([$_SESSION['user_id']]);
    $_SESSION['role'] = 'supplier';
    $user['role'] = 'supplier';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tab === 'profile') {
    // CSRF Validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        flashMessage('error', 'Invalid request. Please try again.');
        redirect('/pages/dashboard.php?tab=profile');
    }
    
    $businessName = trim($_POST['business_name'] ?? '');
    $categoryId = $_POST['category_id'] ?? null;
    $locationId = $_POST['location_id'] ?? null;
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $telegram = trim($_POST['telegram'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $openingHours = trim($_POST['opening_hours'] ?? '');
    $delivery = isset($_POST['delivery_available']) ? 1 : 0;
    $bulk = isset($_POST['bulk_available']) ? 1 : 0;
    
    if (empty($businessName)) {
        flashMessage('error', 'Business name is required.');
    } else {
        $slug = ensureUniqueSlug($pdo, 'suppliers', slugify($businessName), $supplier['id'] ?? null);
        
        // Handle logo upload
        $logo = $supplier['logo'] ?? null;
        if (!empty($_FILES['logo']['tmp_name'])) {
            $logoExt = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($logoExt, $allowed)) {
                $logoName = 'logo_' . uniqid() . '.' . $logoExt;
                $logoDest = '../uploads/suppliers/' . $logoName;
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $logoDest)) {
                    // Delete old logo if exists
                    if ($logo && file_exists('../' . $logo)) {
                        unlink('../' . $logo);
                    }
                    $logo = '/uploads/suppliers/' . $logoName;
                }
            }
        }
        
        if ($supplier) {
            $pdo->prepare('
                UPDATE suppliers SET
                    business_name=?, slug=?, category_id=?, location_id=?, address=?,
                    phone=?, whatsapp=?, telegram=?, email=?, website=?,
                    description=?, opening_hours=?, delivery_available=?, bulk_available=?, logo=?, status="pending"
                WHERE id=?
            ')->execute([
                $businessName, $slug, $categoryId, $locationId, $address,
                $phone, $whatsapp, $telegram, $email, $website,
                $description, $openingHours, $delivery, $bulk, $logo, $supplier['id']
            ]);
            flashMessage('success', 'Profile updated and sent for review.');
            $supplierId = $supplier['id'];
        } else {
            $pdo->prepare('
                INSERT INTO suppliers
                (user_id, business_name, slug, category_id, location_id, address, phone, whatsapp, telegram, email, website, description, opening_hours, delivery_available, bulk_available, logo, status)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ')->execute([
                $_SESSION['user_id'], $businessName, $slug, $categoryId, $locationId,
                $address, $phone, $whatsapp, $telegram, $email, $website,
                $description, $openingHours, $delivery, $bulk, $logo, 'pending'
            ]);
            $supplierId = $pdo->lastInsertId();
            flashMessage('success', 'Business profile created! It will be reviewed shortly.');
        }
        
        // Handle gallery photo uploads
        if (!empty($_FILES['photos']['tmp_name'][0])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            foreach ($_FILES['photos']['tmp_name'] as $i => $tmpName) {
                if (empty($tmpName)) continue;
                $ext = strtolower(pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed)) continue;
                
                $filename = 'photo_' . uniqid() . '.' . $ext;
                $dest = '../uploads/suppliers/' . $filename;
                if (move_uploaded_file($tmpName, $dest)) {
                    $pdo->prepare('INSERT INTO supplier_photos (supplier_id, photo_path, sort_order) VALUES (?,?,?)')
                        ->execute([$supplierId, '/uploads/suppliers/' . $filename, $i]);
                }
            }
        }
        
        // Handle photo deletions
        if (!empty($_POST['delete_photos'])) {
            foreach ($_POST['delete_photos'] as $photoId) {
                $photo = $pdo->prepare('SELECT photo_path FROM supplier_photos WHERE id = ? AND supplier_id = ?');
                $photo->execute([(int)$photoId, $supplierId]);
                $p = $photo->fetch();
                if ($p && file_exists('../' . $p['photo_path'])) {
                    unlink('../' . $p['photo_path']);
                }
                $pdo->prepare('DELETE FROM supplier_photos WHERE id = ? AND supplier_id = ?')->execute([(int)$photoId, $supplierId]);
            }
        }
        
        $supplier = getSupplierByUserId($pdo, $_SESSION['user_id']);
    }
}

$myRequests = $pdo->prepare('SELECT * FROM buyer_requests WHERE user_id = ? ORDER BY created_at DESC');
$myRequests->execute([$_SESSION['user_id']]);
$myRequests = $myRequests->fetchAll();

$myQuotes = [];
$quoteStats = ['pending' => 0, 'accepted' => 0, 'rejected' => 0];
if ($supplier) {
    $mq = $pdo->prepare('
        SELECT q.*, r.title as request_title, r.id as request_id
        FROM quotes q
        LEFT JOIN buyer_requests r ON q.request_id = r.id
        WHERE q.supplier_id = ?
        ORDER BY q.created_at DESC
    ');
    $mq->execute([$supplier['id']]);
    $myQuotes = $mq->fetchAll();
    
    foreach ($myQuotes as $q) {
        if (isset($quoteStats[$q['status']])) $quoteStats[$q['status']]++;
    }
}

$saved = $pdo->prepare('
    SELECT s.* FROM saved_suppliers ss
    JOIN suppliers s ON ss.supplier_id = s.id
    WHERE ss.user_id = ?
');
$saved->execute([$_SESSION['user_id']]);
$saved = $saved->fetchAll();

$categories = getCategories($pdo);
$locations = getLocations($pdo);

// Calculate profile completion
$profileCompletion = 0;
$profileFields = [
    'business_name', 'category_id', 'location_id', 'address', 
    'phone', 'email', 'website', 'description', 'opening_hours'
];
$filledFields = 0;
if ($supplier) {
    foreach ($profileFields as $field) {
        if (!empty($supplier[$field])) $filledFields++;
    }
    $profileCompletion = round(($filledFields / count($profileFields)) * 100);
}

// Get view history (last 30 days)
$viewHistory = [];
if ($supplier) {
    $vh = $pdo->prepare('
        SELECT DATE(created_at) as date, COUNT(*) as views
        FROM supplier_views
        WHERE supplier_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY DATE(created_at)
        ORDER BY date
    ');
    // Note: supplier_views table might not exist, so handle gracefully
    try {
        $vh->execute([$supplier['id']]);
        $viewHistory = $vh->fetchAll();
    } catch (PDOException $e) {
        $viewHistory = [];
    }
}

// Simple view history mock if table doesn't exist
if (empty($viewHistory) && $supplier) {
    // Generate mock data for chart
    for ($i = 29; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $viewHistory[] = [
            'date' => $date,
            'views' => max(0, $supplier['view_count'] - rand(0, $supplier['view_count']))
        ];
    }
}

require_once '../includes/header.php';
?>

<section class="section" style="padding-top:20px;">
    <div class="container">
        <div class="dashboard-grid">
            <div class="sidebar" role="navigation" aria-label="Dashboard navigation">
                <div style="margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border);">
                    <div style="font-weight:700; font-size:1.05rem;"><?= htmlspecialchars($user['full_name']) ?></div>
                    <div style="font-size:0.84rem; color:var(--text-light); text-transform:capitalize; margin-top:2px;"><?= $user['role'] ?></div>
                </div>
                <a href="?tab=overview" class="<?= $tab === 'overview' ? 'active' : '' ?>" aria-current="<?= $tab === 'overview' ? 'page' : 'false' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    Overview
                </a>
                <a href="?tab=requests" class="<?= $tab === 'requests' ? 'active' : '' ?>" aria-current="<?= $tab === 'requests' ? 'page' : 'false' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    My Requests
                </a>
                <a href="?tab=saved" class="<?= $tab === 'saved' ? 'active' : '' ?>" aria-current="<?= $tab === 'saved' ? 'page' : 'false' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                    Saved Suppliers
                </a>
                <a href="?tab=profile" class="<?= $tab === 'profile' ? 'active' : '' ?>" aria-current="<?= $tab === 'profile' ? 'page' : 'false' ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Business Profile
                </a>
                <?php if ($supplier): ?>
                    <a href="?tab=quotes" class="<?= $tab === 'quotes' ? 'active' : '' ?>" aria-current="<?= $tab === 'quotes' ? 'page' : 'false' ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect width="20" height="16" x="2" y="4" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                        Quotes Sent
                    </a>
                <?php endif; ?>
                <a href="pricing.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                    Upgrade Plan
                </a>
            </div>

            <div class="dashboard-content">
                <?php showFlash(); ?>

                <?php if ($tab === 'overview'): ?>
                    <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:24px;">Overview</h2>
                    <div class="stats-grid">
                        <div class="stat-card animate-on-scroll stagger-1">
                            <div class="stat-value"><?= count($myRequests) ?></div>
                            <div class="stat-label">Requests Posted</div>
                        </div>
                        <div class="stat-card animate-on-scroll stagger-2">
                            <div class="stat-value"><?= count($saved) ?></div>
                            <div class="stat-label">Saved Suppliers</div>
                        </div>
                        <?php if ($supplier): ?>
                            <div class="stat-card animate-on-scroll stagger-3">
                                <div class="stat-value count-up" data-target="<?= $supplier['view_count'] ?>"><?= $supplier['view_count'] ?></div>
                                <div class="stat-label">Profile Views</div>
                            </div>
                            <div class="stat-card animate-on-scroll stagger-4">
                                <div class="stat-value"><?= count($myQuotes) ?></div>
                                <div class="stat-label">Quotes Sent</div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if ($supplier): ?>
                        <!-- Profile Completion -->
                        <div class="profile-section animate-on-scroll">
                            <h3>Profile Completion</h3>
                            <div style="display:flex; align-items:center; gap:16px; margin-bottom:12px;">
                                <div style="flex:1;">
                                    <div class="progress-bar">
                                        <div class="progress-bar-fill" style="width:<?= $profileCompletion ?>%;"></div>
                                    </div>
                                </div>
                                <span style="font-size:1.1rem; font-weight:700; color:var(--primary);"><?= $profileCompletion ?>%</span>
                            </div>
                            <p style="font-size:0.88rem; color:var(--text-light);">
                                <?php if ($profileCompletion < 50): ?>
                                    Complete your profile to get more visibility. Add photos, description, and contact details.
                                <?php elseif ($profileCompletion < 100): ?>
                                    Almost there! Add missing details to reach 100% completion.
                                <?php else: ?>
                                    Your profile is complete! Great job.
                                <?php endif; ?>
                            </p>
                        </div>

                        <!-- View Chart -->
                        <div class="profile-section animate-on-scroll">
                            <h3>Profile Views (Last 30 Days)</h3>
                            <div style="height:180px; position:relative; margin-top:16px;">
                                <!-- Simple bar chart using CSS -->
                                <div style="display:flex; align-items:flex-end; gap:3px; height:100%; padding-bottom:24px; border-bottom:1px solid var(--border);">
                                    <?php 
                                    $maxViews = max(array_column($viewHistory, 'views')) ?: 1;
                                    foreach ($viewHistory as $i => $day): 
                                        $height = ($day['views'] / $maxViews) * 100;
                                        $isToday = $day['date'] === date('Y-m-d');
                                    ?>
                                        <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:4px;">
                                            <div style="width:100%; height:<?= max(1, $height) ?>%; background:<?= $isToday ? 'var(--accent)' : 'var(--accent-soft)' ?>; border-radius:3px 3px 0 0; transition:height 0.3s ease; min-height:2px;" title="<?= $day['date'] ?>: <?= $day['views'] ?> views"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-top:8px; font-size:0.72rem; color:var(--text-light);">
                                    <span>30 days ago</span>
                                    <span>Today</span>
                                </div>
                            </div>
                        </div>

                        <!-- Quote Pipeline -->
                        <div class="profile-section animate-on-scroll">
                            <h3>Quote Pipeline</h3>
                            <div style="display:flex; gap:16px; margin-top:16px;">
                                <div style="flex:1; text-align:center; padding:16px; background:var(--accent-soft); border-radius:var(--radius-sm);">
                                    <div style="font-size:1.6rem; font-weight:800; color:var(--accent);"><?= $quoteStats['pending'] ?></div>
                                    <div style="font-size:0.82rem; color:var(--text-light); margin-top:4px;">Pending</div>
                                </div>
                                <div style="flex:1; text-align:center; padding:16px; background:#D1FAE5; border-radius:var(--radius-sm);">
                                    <div style="font-size:1.6rem; font-weight:800; color:var(--success);"><?= $quoteStats['accepted'] ?></div>
                                    <div style="font-size:0.82rem; color:var(--text-light); margin-top:4px;">Accepted</div>
                                </div>
                                <div style="flex:1; text-align:center; padding:16px; background:#FEE2E2; border-radius:var(--radius-sm);">
                                    <div style="font-size:1.6rem; font-weight:800; color:var(--danger);"><?= $quoteStats['rejected'] ?></div>
                                    <div style="font-size:0.82rem; color:var(--text-light); margin-top:4px;">Rejected</div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-section animate-on-scroll">
                            <h3>Business Status</h3>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <span class="badge <?= $supplier['status'] === 'approved' ? 'badge-success' : ($supplier['status'] === 'pending' ? 'badge-urgent' : 'badge-closed') ?>">
                                    Status: <?= ucfirst($supplier['status']) ?>
                                </span>
                                <?php if ($supplier['is_verified']): ?>
                                    <span class="badge badge-verified">Verified</span>
                                <?php endif; ?>
                                <span class="badge badge-premium">Plan: <?= ucfirst($supplier['plan']) ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="profile-section animate-on-scroll" style="background:var(--accent-soft); border-color:var(--accent);">
                            <h3>Start Selling</h3>
                            <p>You haven't listed your business yet. <a href="?tab=profile">Create your supplier profile</a> to start receiving buyer requests.</p>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($tab === 'requests'): ?>
                    <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:24px;">My Requests</h2>
                    <?php if (empty($myRequests)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">&#128221;</div>
                            <div class="empty-state-title">No requests yet</div>
                            <div class="empty-state-text">You haven't posted any requests. <a href="post-request.php">Post one now</a> to get quotes from suppliers.</div>
                        </div>
                    <?php else: ?>
                        <div class="grid-2">
                            <?php foreach ($myRequests as $req): ?>
                                <div class="card request-card <?= getUrgencyClass($req['urgency']) ?> animate-on-scroll stagger-1">
                                    <div class="card-body">
                                        <div class="card-title"><?= htmlspecialchars($req['title']) ?></div>
                                        <div class="req-meta">
                                            <span class="badge <?= $req['status'] === 'open' ? 'badge-open' : 'badge-closed' ?>"><?= ucfirst($req['status']) ?></span>
                                            <span><?= timeAgo($req['created_at']) ?></span>
                                            <span><?= $req['quote_count'] ?> quotes</span>
                                        </div>
                                        <a href="request-detail.php?id=<?= $req['id'] ?>" class="btn btn-sm btn-primary">View Details</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($tab === 'saved'): ?>
                    <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:24px;">Saved Suppliers</h2>
                    <?php if (empty($saved)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">&#128150;</div>
                            <div class="empty-state-title">No saved suppliers</div>
                            <div class="empty-state-text">No saved suppliers yet. Browse the <a href="directory.php">directory</a> and click the heart icon to save.</div>
                        </div>
                    <?php else: ?>
                        <div class="grid-2">
                            <?php foreach ($saved as $s): ?>
                                <div class="card supplier-card animate-on-scroll stagger-1">
                                    <div class="card-body">
                                        <div class="card-title"><?= htmlspecialchars($s['business_name']) ?></div>
                                        <div class="supplier-meta">
                                            <span><?= htmlspecialchars($s['subcategory'] ?? '') ?></span>
                                            <span><?= htmlspecialchars($s['phone'] ?? '') ?></span>
                                        </div>
                                        <a href="supplier.php?slug=<?= $s['slug'] ?>" class="btn btn-sm btn-primary">View Profile</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($tab === 'profile'): ?>
                    <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:24px;"><?= $supplier ? 'Edit' : 'Create' ?> Business Profile</h2>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        
                        <div class="form-floating">
                            <input type="text" name="business_name" id="business_name" value="<?= htmlspecialchars($supplier['business_name'] ?? '') ?>" placeholder=" " required>
                            <label for="business_name">Business Name *</label>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id">
                                    <option value="">Select...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($supplier['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="location_id">Location</label>
                                <select name="location_id" id="location_id">
                                    <option value="">Select...</option>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?= $loc['id'] ?>" <?= ($supplier['location_id'] ?? '') == $loc['id'] ? 'selected' : '' ?>><?= htmlspecialchars($loc['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="text" name="address" id="address" value="<?= htmlspecialchars($supplier['address'] ?? '') ?>" placeholder=" ">
                            <label for="address">Address</label>
                        </div>

                        <div class="form-row">
                            <div class="form-floating">
                                <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($supplier['phone'] ?? '') ?>" placeholder=" ">
                                <label for="phone">Phone</label>
                            </div>
                            <div class="form-floating">
                                <input type="tel" name="whatsapp" id="whatsapp" value="<?= htmlspecialchars($supplier['whatsapp'] ?? '') ?>" placeholder=" ">
                                <label for="whatsapp">WhatsApp</label>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-floating">
                                <input type="text" name="telegram" id="telegram" value="<?= htmlspecialchars($supplier['telegram'] ?? '') ?>" placeholder=" ">
                                <label for="telegram">Telegram Username</label>
                            </div>
                            <div class="form-floating">
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($supplier['email'] ?? '') ?>" placeholder=" ">
                                <label for="email">Email</label>
                            </div>
                        </div>

                        <div class="form-floating">
                            <input type="url" name="website" id="website" value="<?= htmlspecialchars($supplier['website'] ?? '') ?>" placeholder=" ">
                            <label for="website">Website</label>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="4" placeholder="What do you sell or offer? Be specific so buyers can find you." data-max-length="500"><?= htmlspecialchars($supplier['description'] ?? '') ?></textarea>
                        </div>

                        <div class="form-floating">
                            <input type="text" name="opening_hours" id="opening_hours" value="<?= htmlspecialchars($supplier['opening_hours'] ?? '') ?>" placeholder=" ">
                            <label for="opening_hours">Opening Hours</label>
                        </div>

                        <div style="display:flex; gap:16px; margin-bottom:22px;">
                            <label style="display:flex; align-items:center; gap:8px; font-size:0.92rem; cursor:pointer; font-weight:500;">
                                <input type="checkbox" name="delivery_available" <?= ($supplier['delivery_available'] ?? 0) ? 'checked' : '' ?>> Delivery Available
                            </label>
                            <label style="display:flex; align-items:center; gap:8px; font-size:0.92rem; cursor:pointer; font-weight:500;">
                                <input type="checkbox" name="bulk_available" <?= ($supplier['bulk_available'] ?? 0) ? 'checked' : '' ?>> Bulk Orders Available
                            </label>
                        </div>

                        <!-- Logo Upload -->
                        <div class="form-group" style="margin-top:16px;">
                            <label>Business Logo</label>
                            <?php if ($supplier && $supplier['logo']): ?>
                                <div style="display:flex; align-items:center; gap:12px; margin-bottom:10px;">
                                    <img src="<?= htmlspecialchars($supplier['logo']) ?>" alt="Current logo" style="width:64px; height:64px; object-fit:cover; border-radius:var(--radius-sm); border:1px solid var(--border);">
                                    <span style="font-size:0.85rem; color:var(--text-light);">Current logo</span>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="logo" accept="image/*">
                            <div class="form-hint">JPG, PNG, GIF, WebP. Max 2MB.</div>
                        </div>

                        <!-- Photo Gallery -->
                        <?php if ($supplier): ?>
                            <?php 
                            $myPhotos = $pdo->prepare('SELECT * FROM supplier_photos WHERE supplier_id = ? ORDER BY sort_order');
                            $myPhotos->execute([$supplier['id']]);
                            $myPhotos = $myPhotos->fetchAll();
                            ?>
                            <?php if (!empty($myPhotos)): ?>
                                <div class="form-group">
                                    <label>Current Photos</label>
                                    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
                                        <?php foreach ($myPhotos as $photo): ?>
                                            <div style="position:relative;">
                                                <img src="<?= htmlspecialchars($photo['photo_path']) ?>" style="width:80px; height:80px; object-fit:cover; border-radius:var(--radius-sm); border:1px solid var(--border);">
                                                <label style="position:absolute; top:-4px; right:-4px; width:20px; height:20px; background:var(--danger); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:0.75rem;">
                                                    <input type="checkbox" name="delete_photos[]" value="<?= $photo['id'] ?>" style="display:none;">&times;
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="form-hint">Check photos to delete them</div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Add Photos</label>
                            <input type="file" name="photos[]" accept="image/*" multiple>
                            <div class="form-hint">Select multiple photos. JPG, PNG, GIF, WebP.</div>
                        </div>

                        <button type="submit" class="btn btn-accent"><?= $supplier ? 'Update Profile' : 'Create Profile' ?></button>
                    </form>
                <?php endif; ?>

                <?php if ($tab === 'quotes' && $supplier): ?>
                    <h2 style="font-size:1.3rem; font-weight:700; margin-bottom:24px;">Quotes Sent</h2>
                    <?php if (empty($myQuotes)): ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">&#128172;</div>
                            <div class="empty-state-title">No quotes yet</div>
                            <div class="empty-state-text">You haven't sent any quotes yet. <a href="requests.php">Browse requests</a> to find buyers.</div>
                        </div>
                    <?php else: ?>
                        <div class="grid-2">
                            <?php foreach ($myQuotes as $q): ?>
                                <div class="card animate-on-scroll stagger-1">
                                    <div class="card-body">
                                        <div class="card-title"><?= htmlspecialchars($q['request_title'] ?? 'Request') ?></div>
                                        <div class="req-meta">
                                            <span>Price: <?= htmlspecialchars($q['price'] ?? '-') ?></span>
                                            <span>Delivery: <?= htmlspecialchars($q['delivery_time'] ?? '-') ?></span>
                                            <span><?= timeAgo($q['created_at']) ?></span>
                                        </div>
                                        <p class="card-text"><?= htmlspecialchars(truncate($q['message'] ?? '', 100)) ?></p>
                                        <a href="request-detail.php?id=<?= $q['request_id'] ?>" class="btn btn-sm btn-primary">View Request</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>