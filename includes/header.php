<?php require_once __DIR__ . '/functions.php'; ?>
<?php
// Generate SEO meta data
$pageDescription = $pageDescription ?? 'Find verified Ethiopian suppliers in Addis Ababa. Search by category and location, or post a request and get quotes from sellers. Built-in WhatsApp and Telegram contact.';
$pageImage = $pageImage ?? 'https://ethiomarket.com/assets/images/og-image.jpg';
$pageUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$pageType = $pageType ?? 'website';
$canonicalUrl = $canonicalUrl ?? $pageUrl;

// JSON-LD structured data
$jsonLd = $jsonLd ?? [];
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= htmlspecialchars($pageTitle ?? 'EthioMarket - Ethiopian Supplier Marketplace') ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="theme-color" content="#102A43">
    <meta name="color-scheme" content="light dark">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= htmlspecialchars($canonicalUrl) ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?= htmlspecialchars($pageType) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($pageUrl) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle ?? 'EthioMarket') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($pageImage) ?>">
    <meta property="og:site_name" content="EthioMarket">
    <meta property="og:locale" content="en_ET">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?= htmlspecialchars($pageUrl) ?>">
    <meta name="twitter:title" content="<?= htmlspecialchars($pageTitle ?? 'EthioMarket') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($pageImage) ?>">
    
    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <!-- Prefetch key pages -->
    <link rel="prefetch" href="/pages/categories.php">
    <link rel="prefetch" href="/pages/directory.php">
    <link rel="prefetch" href="/pages/requests.php">
    <link rel="prefetch" href="/pages/post-request.php">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    
    <!-- CSRF Token -->
    <?php if (empty($_SESSION['csrf_token'])): ?>
        <?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
    <?php endif; ?>
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    
    <!-- JSON-LD Structured Data -->
    <?php if (!empty($jsonLd)): ?>
    <script type="application/ld+json">
    <?= json_encode($jsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>
    <?php endif; ?>
    
    <script>
        // Apply saved theme before page renders — default to light mode
        (function() {
            const saved = localStorage.getItem('theme');
            if (saved === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
            }
        })();
    </script>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="container nav-inner">
            <a href="/" class="logo" aria-label="EthioMarket Home">
                <span class="logo-icon" aria-hidden="true"></span>
                <span>EthioMarket</span>
            </a>
            
            <button class="mobile-toggle" 
                    onclick="toggleMobileMenu()" 
                    aria-label="Toggle menu" 
                    aria-expanded="false"
                    aria-controls="nav-menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            
            <div class="nav-links" id="nav-menu" role="menubar">
                <a href="/pages/directory.php" role="menuitem">Suppliers</a>
                <a href="/pages/requests.php" role="menuitem">Requests</a>
                <a href="/pages/post-request.php" class="btn btn-sm btn-accent" role="menuitem">Post Request</a>
                <?php if (isLoggedIn()): ?>
                    <a href="/pages/dashboard.php" role="menuitem">Dashboard</a>
                    <?php if (isAdmin()): ?>
                        <a href="/admin/" role="menuitem">Admin</a>
                    <?php endif; ?>
                    <a href="/pages/logout.php" role="menuitem">Logout</a>
                <?php else: ?>
                    <a href="/pages/login.php" role="menuitem">Login</a>
                    <a href="/pages/register.php" class="btn btn-sm btn-outline" role="menuitem">Sign Up</a>
                <?php endif; ?>
                
                <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode" title="Toggle dark mode">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon-light" aria-hidden="true">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="theme-icon-dark" style="display:none;" aria-hidden="true">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>
    <div class="menu-overlay" id="menuOverlay" onclick="toggleMobileMenu()"></div>
    <div class="main-content" id="main-content">
