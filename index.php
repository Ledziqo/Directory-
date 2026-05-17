<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$pageTitle = 'Ethiopia\'s Gateway for Importers & Exporters';
$pageDescription = 'Source products from Ethiopia or find buyers abroad. Verified suppliers, manufacturers, and export-ready businesses in Addis Ababa.';

// Get stats for JSON-LD and display
$categoryCount = count(getCategories($pdo));
$locationCount = count(getLocations($pdo));
$supplierCount = $pdo->query('SELECT COUNT(*) FROM suppliers WHERE status = "approved"')->fetchColumn();

$featured = $pdo->query('
    SELECT s.*, c.name as category_name, l.name as location_name
    FROM suppliers s
    LEFT JOIN categories c ON s.category_id = c.id
    LEFT JOIN locations l ON s.location_id = l.id
    WHERE s.status = "approved" AND s.is_featured = 1
    ORDER BY s.created_at DESC
    LIMIT 6
')->fetchAll();

$latestRequests = $pdo->query('
    SELECT r.*, c.name as category_name, l.name as location_name, u.full_name as buyer_name
    FROM buyer_requests r
    LEFT JOIN categories c ON r.category_id = c.id
    LEFT JOIN locations l ON r.location_id = l.id
    LEFT JOIN users u ON r.user_id = u.id
    WHERE r.status = "open"
    ORDER BY r.created_at DESC
    LIMIT 5
')->fetchAll();

$openRequests = count($latestRequests);

if (empty($categories)) {
    $defaultCats = [
        ['Car Parts & Accessories','car-parts'],
        ['Construction Materials','construction'],
        ['Printing & Packaging','printing-packaging'],
        ['Hotel & Restaurant Supplies','hotel-restaurant'],
        ['Office Supplies','office-supplies'],
        ['Furniture','furniture'],
        ['Electronics','electronics'],
        ['Machinery & Tools','machinery-tools'],
        ['Importers & Wholesalers','importers-wholesalers'],
        ['Professional Services','services'],
        ['Agriculture & Farming','agriculture-farming'],
        ['Textiles & Garments','textiles-garments'],
        ['Cleaning & Sanitation','cleaning-sanitation'],
        ['Medical & Pharmacy Supplies','medical-pharmacy'],
        ['Beauty & Salon Supplies','beauty-salon'],
        ['Event & Catering Supplies','event-catering'],
        ['Logistics & Delivery','logistics-delivery'],
        ['Solar & Electrical','solar-electrical'],
    ];
    foreach ($defaultCats as $cat) {
        $pdo->prepare('INSERT IGNORE INTO categories (name, slug, sort_order) VALUES (?,?,?)')
            ->execute([$cat[0], $cat[1], 0]);
    }
    $categories = getCategories($pdo);
    $categoryCount = count($categories);
}

$locations = getLocations($pdo);
if (empty($locations)) {
    $defaultLocs = [
        'Bole','Kazanchis','Piassa','Merkato','CMC','Megenagna',
        'Sarbet','Gurd Shola','Ayat','Lafto','Nifas Silk','Kirkos'
    ];
    foreach ($defaultLocs as $i => $loc) {
        $pdo->prepare('INSERT IGNORE INTO locations (name, slug, sort_order) VALUES (?,?,?)')
            ->execute([$loc, slugify($loc), $i]);
    }
    $locations = getLocations($pdo);
    $locationCount = count($locations);
}

// JSON-LD Structured Data
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'EthioMarket',
    'url' => 'https://' . $_SERVER['HTTP_HOST'] . '/',
    'description' => $pageDescription,
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => [
            '@type' => 'EntryPoint',
            'urlTemplate' => 'https://' . $_SERVER['HTTP_HOST'] . '/pages/directory.php?q={search_term_string}'
        ],
        'query-input' => 'required name=search_term_string'
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'EthioMarket',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => 'https://' . $_SERVER['HTTP_HOST'] . '/assets/images/logo.png'
        ]
    ],
    'mainEntity' => [
        '@type' => 'ItemList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Supplier Directory',
                'item' => 'https://' . $_SERVER['HTTP_HOST'] . '/pages/directory.php'
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Buyer Requests',
                'item' => 'https://' . $_SERVER['HTTP_HOST'] . '/pages/requests.php'
            ]
        ]
    ]
];

// LocalBusiness for featured suppliers
$localBusinesses = [];
foreach (array_slice($featured, 0, 3) as $sup) {
    $localBusinesses[] = [
        '@type' => 'LocalBusiness',
        'name' => $sup['business_name'],
        'description' => $sup['description'],
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => $sup['location_name'] ?? 'Addis Ababa',
            'addressCountry' => 'ET'
        ],
        'url' => 'https://' . $_SERVER['HTTP_HOST'] . '/pages/supplier.php?slug=' . $sup['slug']
    ];
}

if (!empty($localBusinesses)) {
    $jsonLd['@graph'] = $localBusinesses;
}

require_once 'includes/header.php';
?>

<section class="hero" aria-label="Hero section">
    <div class="container">
        <div class="hero-layout">
            <div class="hero-copy">
                <span class="eyebrow">Import & Export Hub</span>
                <h1>Find verified Ethiopian suppliers. Or buyers abroad.</h1>
                <p>Connect with export-ready manufacturers, wholesale suppliers, and verified businesses in Addis Ababa. Built for importers who need reliable partners.</p>
                <div class="hero-buttons">
                    <a href="/pages/directory.php" class="btn btn-lg btn-accent">Find Suppliers</a>
                    <a href="/pages/post-request.php" class="btn btn-lg btn-outline">Source Products</a>
                </div>
                <div class="hero-contact" aria-label="Contact methods available between buyers and sellers">
                    <span class="hero-contact-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        Verified exporters on Telegram & WhatsApp
                    </span>
                </div>
                
                <!-- Mobile Stats Bar -->
                <div class="hero-mobile-stats" aria-hidden="true">
                    <span class="stat-pill"><strong><?= $categoryCount ?>+</strong> categories</span>
                    <span class="stat-pill"><strong><?= $supplierCount ?>+</strong> suppliers</span>
                    <span class="stat-pill"><strong><?= $openRequests ?></strong> open needs</span>
                </div>
            </div>
            
            <!-- Right Panel: Flow -->
            <div class="hero-flow-panel" aria-label="How the marketplace works">
                <div class="flow-cards">
                    <div class="flow-card">
                        <div class="flow-avatar">B</div>
                        <div class="flow-card-body">
                            <span class="flow-label">Buyer</span>
                            <strong>Posts need</strong>
                            <p>Product, quantity, location</p>
                        </div>
                    </div>
                    <div class="flow-divider" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <div class="flow-card">
                        <div class="flow-avatar flow-avatar-supplier">S</div>
                        <div class="flow-card-body">
                            <span class="flow-label">Supplier</span>
                            <strong>Sends quote</strong>
                            <p>Price, stock, delivery</p>
                        </div>
                    </div>
                </div>
                <div class="hero-panel-stats">
                    <div class="panel-stat">
                        <strong class="count-up" data-target="<?= $categoryCount ?>"><?= $categoryCount ?>+</strong>
                        <span>categories</span>
                    </div>
                    <div class="panel-stat">
                        <strong class="count-up" data-target="<?= $supplierCount ?>"><?= $supplierCount ?>+</strong>
                        <span>suppliers</span>
                    </div>
                    <div class="panel-stat">
                        <strong class="count-up" data-target="<?= $openRequests ?>"><?= $openRequests ?></strong>
                        <span>open needs</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="search-bar" aria-label="Search suppliers">
    <div class="container" style="position:relative;">
        <form action="/pages/directory.php" method="GET" class="search-form" role="search">
            <input type="text" name="q" placeholder="Search: Toyota bumper, cement, office chairs..." value="" aria-label="Search suppliers" autocomplete="off">
            <select name="category" aria-label="Filter by category">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="location" aria-label="Filter by location">
                <option value="">All Locations</option>
                <?php foreach ($locations as $loc): ?>
                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                Search
            </button>
        </form>
        <div class="search-autocomplete" id="searchAutocomplete" role="listbox" aria-label="Search suggestions"></div>
    </div>
</section>

<section class="section section-surface" aria-labelledby="categories-heading">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <div>
                <h2 id="categories-heading">Popular Categories</h2>
            </div>
            <a href="/pages/categories.php">View all</a>
        </div>
        <div class="grid-4">
            <?php $categoryIcons = [
                ['icon' => 'M18 18.5H6m1.5 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm12 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM5 16l1.6-5.2A3 3 0 0 1 9.5 8.7h5a3 3 0 0 1 2.9 2.1L19 16M7.4 12h9.2', 'label' => 'Auto'],
                ['icon' => 'M4 20h16M6 20V8l6-4 6 4v12M9 20v-6h6v6M8 10h.01M12 10h.01M16 10h.01', 'label' => 'Build'],
                ['icon' => 'M7 8V4h10v4M6 18H5a2 2 0 0 1-2-2v-5a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v5a2 2 0 0 1-2 2h-1M7 14h10v6H7zM17 11h.01', 'label' => 'Print'],
                ['icon' => 'M4 20V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12M8 20v-6h8v6M8 10h.01M12 10h.01M16 10h.01M8 14h.01M16 14h.01', 'label' => 'Hotel'],
                ['icon' => 'M4 7h16v12H4zM8 7V5h8v2M8 11h8M8 15h5', 'label' => 'Office'],
                ['icon' => 'M4 12h16M6 12V8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4M7 12v7M17 12v7M9 16h6', 'label' => 'Home'],
                ['icon' => 'M8 3h8v18H8zM11 18h2M10 6h4', 'label' => 'Tech'],
                ['icon' => 'M14.7 6.3a4 4 0 0 0-5 5L4 17v3h3l5.7-5.7a4 4 0 0 0 5-5l-3 3-3-3z', 'label' => 'Tools'],
                ['icon' => 'M12 3v18M5 8c4 0 7 3 7 7-4 0-7-3-7-7Zm14 0c-4 0-7 3-7 7 4 0 7-3 7-7Z', 'label' => 'Farm'],
                ['icon' => 'M6 4h12l-2 7 2 9H6l2-9-2-7Zm2 7h8', 'label' => 'Textile'],
                ['icon' => 'M4 14c4-6 12-6 16 0M7 14v6M17 14v6M10 11l2-7 2 7', 'label' => 'Clean'],
                ['icon' => 'M12 5v14M5 12h14M7 7l10 10M17 7 7 17', 'label' => 'Health'],
            ]; ?>
            <?php foreach (array_slice($categories, 0, 12) as $i => $cat): ?>
                <a href="/pages/directory.php?category=<?= $cat['id'] ?>" class="card animate-on-scroll stagger-<?= min($i % 4 + 1, 4) ?>">
                    <div class="card-body category-card">
                        <?php $catIcon = $categoryIcons[$i % count($categoryIcons)] ?? ['icon' => 'M4 6h16M4 12h16M4 18h16', 'label' => 'Cat']; ?>
                        <div class="category-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="<?= htmlspecialchars($catIcon['icon']) ?>"></path>
                            </svg>
                        </div>
                        <div class="card-title"><?= htmlspecialchars($cat['name']) ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why EthioMarket Section -->
<section class="section" aria-labelledby="why-heading">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <div>
                <h2 id="why-heading">Built for Serious Buyers</h2>
                <p>Whether you're sourcing from Dubai, importing to Ethiopia, or exporting coffee — find partners you can trust.</p>
            </div>
        </div>
        <div class="grid-2">
            <div class="card animate-on-scroll stagger-1">
                <div class="card-body" style="display:flex;gap:16px;align-items:flex-start;">
                    <div class="category-icon" style="flex-shrink:0;" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Verified Businesses</h3>
                        <p class="card-text">Every supplier is manually reviewed and verified before appearing in our directory. No scams, no fake listings.</p>
                    </div>
                </div>
            </div>
            <div class="card animate-on-scroll stagger-2">
                <div class="card-body" style="display:flex;gap:16px;align-items:flex-start;">
                    <div class="category-icon" style="flex-shrink:0;" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Direct Communication</h3>
                        <p class="card-text">Contact suppliers directly via WhatsApp, Telegram, or phone. No middlemen, no hidden fees.</p>
                    </div>
                </div>
            </div>
            <div class="card animate-on-scroll stagger-3">
                <div class="card-body" style="display:flex;gap:16px;align-items:flex-start;">
                    <div class="category-icon" style="flex-shrink:0;" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2"/>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Compare Quotes</h3>
                        <p class="card-text">Receive offers from multiple suppliers and choose the best deal. All quotes are transparent and negotiable.</p>
                    </div>
                </div>
            </div>
            <div class="card animate-on-scroll stagger-4">
                <div class="card-body" style="display:flex;gap:16px;align-items:flex-start;">
                    <div class="category-icon" style="flex-shrink:0;" aria-hidden="true">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="card-title">Save Time</h3>
                        <p class="card-text">Stop calling 20 shops. Post once and let verified sellers come to you with their best offers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="requests-heading">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <div>
                <h2 id="requests-heading">Latest Buyer Requests</h2>
            </div>
            <a href="/pages/requests.php">View all</a>
        </div>
        <div class="grid-3">
            <?php foreach ($latestRequests as $req): ?>
                <a href="/pages/request-detail.php?id=<?= $req['id'] ?>" class="card request-card <?= getUrgencyClass($req['urgency']) ?> animate-on-scroll stagger-1">
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
                            <span><strong><?= htmlspecialchars($req['category_name'] ?? 'General') ?></strong></span>
                            <span><?= htmlspecialchars($req['location_name'] ?? 'Addis Ababa') ?></span>
                            <span><?= timeAgo($req['created_at']) ?></span>
                        </div>
                        <div class="card-text"><?= htmlspecialchars(truncate($req['description'], 120)) ?></div>
                        <div class="req-meta" style="margin-top:14px; font-size:0.85rem;">
                            <?= $req['quote_count'] ?> quotes received
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section section-surface" aria-labelledby="suppliers-heading">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <div>
                <h2 id="suppliers-heading">Featured Verified Suppliers</h2>
            </div>
            <a href="/pages/directory.php?verified=1">View all</a>
        </div>
        <div class="grid-3">
            <?php foreach ($featured as $i => $sup): ?>
                <a href="/pages/supplier.php?slug=<?= $sup['slug'] ?>" class="card supplier-card animate-on-scroll stagger-<?= min($i % 3 + 1, 3) ?>">
                    <div class="card-body">
                        <div style="display:flex; align-items:center; gap:14px; margin-bottom:12px;">
                            <div class="profile-logo" style="width:52px; height:52px; font-size:1.3rem;">
                                <?= strtoupper(substr($sup['business_name'], 0, 1)) ?>
                            </div>
                            <div style="min-width:0;">
                                <div class="card-title" style="margin-bottom:4px;"><?= htmlspecialchars($sup['business_name']) ?></div>
                                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                    <?php if ($sup['is_verified']): ?>
                                        <span class="badge badge-verified">Verified</span>
                                    <?php endif; ?>
                                    <?php if ($sup['is_featured']): ?>
                                        <span class="badge badge-premium">Featured</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="supplier-meta">
                            <span><?= htmlspecialchars($sup['category_name'] ?? '') ?></span>
                            <span><?= htmlspecialchars($sup['location_name'] ?? '') ?></span>
                        </div>
                        <div class="card-text"><?= htmlspecialchars(truncate($sup['description'], 100)) ?></div>
                        <div class="card-actions">
                            <span class="btn btn-sm btn-outline">View Profile</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
            <?php if (empty($featured)): ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">&#128188;</div>
                    <div class="empty-state-title">No featured suppliers yet</div>
                    <div class="empty-state-text">Be the first to <a href="/pages/pricing.php">become featured</a> and reach more buyers.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="section" aria-labelledby="testimonials-heading">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <div>
                <h2 id="testimonials-heading">What Buyers & Suppliers Say</h2>
            </div>
        </div>
        <div class="grid-3">
            <div class="testimonial-card animate-on-scroll stagger-1">
                <div class="testimonial-text">I found a reliable cement supplier within 30 minutes of posting my request. The quotes were competitive and the delivery was prompt.</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">A</div>
                    <div class="testimonial-info">
                        <strong>Abel T.</strong>
                        <span>Construction Manager, Bole</span>
                        <div class="stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card animate-on-scroll stagger-2">
                <div class="testimonial-text">EthioMarket brought us 3 new regular customers in our first month. The verification badge really helps build trust with buyers.</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">S</div>
                    <div class="testimonial-info">
                        <strong>Sarah M.</strong>
                        <span>Owner, PrintPro Ethiopia</span>
                        <div class="stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card animate-on-scroll stagger-3">
                <div class="testimonial-text">As a hotel procurement officer, this platform saves me hours every week. I can compare multiple suppliers and negotiate directly.</div>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">D</div>
                    <div class="testimonial-info">
                        <strong>Daniel K.</strong>
                        <span>Procurement, Kazanchis Hotel</span>
                        <div class="stars" aria-label="4 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9734;</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trust Badges -->
        <div class="trust-badges animate-on-scroll" role="list" aria-label="Trust indicators">
            <div class="trust-badge" role="listitem">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                Verified Suppliers
            </div>
            <div class="trust-badge" role="listitem">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                Secure Communication
            </div>
            <div class="trust-badge" role="listitem">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                Manual Review Process
            </div>
            <div class="trust-badge" role="listitem">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="m16 3 2 2 4-4"/>
                </svg>
                Growing Community
            </div>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="how-heading">
    <div class="container">
        <div class="section-header animate-on-scroll">
            <div>
                <h2 id="how-heading">How It Works</h2>
            </div>
        </div>
        <div class="grid-3" style="text-align:center;">
            <div class="card animate-on-scroll stagger-1">
                <div class="card-body">
                    <div class="step-icon">1</div>
                    <div class="card-title">Search or Post</div>
                    <p class="card-text">Find verified suppliers by category and location, or post exactly what you need.</p>
                </div>
            </div>
            <div class="card animate-on-scroll stagger-2">
                <div class="card-body">
                    <div class="step-icon">2</div>
                    <div class="card-title">Get Quotes</div>
                    <p class="card-text">Suppliers browse your request and reply with prices and delivery details via WhatsApp or Telegram.</p>
                </div>
            </div>
            <div class="card animate-on-scroll stagger-3">
                <div class="card-body">
                    <div class="step-icon">3</div>
                    <div class="card-title">Compare & Choose</div>
                    <p class="card-text">Compare multiple quotes, check supplier reviews, and pick the best deal.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section cta-section" aria-label="Call to action for suppliers">
    <div class="container">
        <h2>Are You a Supplier?</h2>
        <p>List your business, reach new buyers, and grow your sales in Addis Ababa.</p>
        <a href="/pages/register.php" class="btn btn-lg btn-accent">List Your Business for Free</a>
        
        <!-- Recently Joined Ticker -->
        <div class="ticker" style="margin-top:36px; max-width:600px; margin-left:auto; margin-right:auto;">
            <div class="ticker-inner">
                <span class="ticker-item">&#127942; ABC Auto Parts joined</span>
                <span class="ticker-item">&#127942; Megenagna Cement joined</span>
                <span class="ticker-item">&#127942; PrintPro Ethiopia joined</span>
                <span class="ticker-item">&#127942; Sarbet Electronics joined</span>
                <span class="ticker-item">&#127942; Lafto Furniture joined</span>
                <span class="ticker-item">&#127942; Bole Construction joined</span>
                <!-- Duplicate for seamless loop -->
                <span class="ticker-item">&#127942; ABC Auto Parts joined</span>
                <span class="ticker-item">&#127942; Megenagna Cement joined</span>
                <span class="ticker-item">&#127942; PrintPro Ethiopia joined</span>
                <span class="ticker-item">&#127942; Sarbet Electronics joined</span>
                <span class="ticker-item">&#127942; Lafto Furniture joined</span>
                <span class="ticker-item">&#127942; Bole Construction joined</span>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>