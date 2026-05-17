<?php
require_once '../includes/db.php';
header('Content-Type: application/xml; charset=utf-8');

$baseUrl = 'https://' . $_SERVER['HTTP_HOST'];

// Get dynamic content
$suppliers = $pdo->query('SELECT slug, updated_at FROM suppliers WHERE status = "approved" ORDER BY updated_at DESC')->fetchAll();
$requests = $pdo->query('SELECT id, updated_at FROM buyer_requests WHERE status = "open" ORDER BY updated_at DESC')->fetchAll();
$categories = $pdo->query('SELECT slug FROM categories WHERE is_active = 1')->fetchAll();
$locations = $pdo->query('SELECT slug FROM locations WHERE is_active = 1')->fetchAll();

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    
    <!-- Static Pages -->
    <url>
        <loc><?= $baseUrl ?>/</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/pages/directory.php</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/pages/requests.php</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>hourly</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/pages/categories.php</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/pages/pricing.php</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/pages/register.php</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/pages/login.php</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc><?= $baseUrl ?>/pages/post-request.php</loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <!-- Categories -->
    <?php foreach ($categories as $cat): ?>
    <url>
        <loc><?= $baseUrl ?>/pages/directory.php?category=<?= $cat['slug'] ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Locations -->
    <?php foreach ($locations as $loc): ?>
    <url>
        <loc><?= $baseUrl ?>/pages/directory.php?location=<?= $loc['slug'] ?></loc>
        <lastmod><?= date('Y-m-d') ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Suppliers -->
    <?php foreach ($suppliers as $sup): ?>
    <url>
        <loc><?= $baseUrl ?>/pages/supplier.php?slug=<?= htmlspecialchars($sup['slug']) ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($sup['updated_at'])) ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <?php endforeach; ?>
    
    <!-- Requests -->
    <?php foreach ($requests as $req): ?>
    <url>
        <loc><?= $baseUrl ?>/pages/request-detail.php?id=<?= $req['id'] ?></loc>
        <lastmod><?= date('Y-m-d', strtotime($req['updated_at'])) ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>
    
</urlset>