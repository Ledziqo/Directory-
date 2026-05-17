<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Supplier Categories';
$categories = getCategories($pdo);

$categoryIcons = [
    ['icon' => 'M18 18.5H6m1.5 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm12 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM5 16l1.6-5.2A3 3 0 0 1 9.5 8.7h5a3 3 0 0 1 2.9 2.1L19 16M7.4 12h9.2'],
    ['icon' => 'M4 20h16M6 20V8l6-4 6 4v12M9 20v-6h6v6M8 10h.01M12 10h.01M16 10h.01'],
    ['icon' => 'M7 8V4h10v4M6 18H5a2 2 0 0 1-2-2v-5a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v5a2 2 0 0 1-2 2h-1M7 14h10v6H7zM17 11h.01'],
    ['icon' => 'M4 20V8a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12M8 20v-6h8v6M8 10h.01M12 10h.01M16 10h.01M8 14h.01M16 14h.01'],
    ['icon' => 'M4 7h16v12H4zM8 7V5h8v2M8 11h8M8 15h5'],
    ['icon' => 'M4 12h16M6 12V8a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4M7 12v7M17 12v7M9 16h6'],
    ['icon' => 'M8 3h8v18H8zM11 18h2M10 6h4'],
    ['icon' => 'M14.7 6.3a4 4 0 0 0-5 5L4 17v3h3l5.7-5.7a4 4 0 0 0 5-5l-3 3-3-3z'],
    ['icon' => 'M3 7h18M6 7v13M18 7v13M8 11h8M8 15h8M10 3h4l2 4H8z'],
    ['icon' => 'M7 8h10M7 12h10M7 16h6M5 4h14v16H5z'],
    ['icon' => 'M12 3v18M5 8c4 0 7 3 7 7-4 0-7-3-7-7Zm14 0c-4 0-7 3-7 7 4 0 7-3 7-7Z'],
    ['icon' => 'M6 4h12l-2 7 2 9H6l2-9-2-7Zm2 7h8'],
    ['icon' => 'M4 14c4-6 12-6 16 0M7 14v6M17 14v6M10 11l2-7 2 7'],
    ['icon' => 'M12 5v14M5 12h14M7 7l10 10M17 7 7 17'],
    ['icon' => 'M7 20c0-6 10-6 10 0M9 8a3 3 0 1 0 6 0 3 3 0 0 0-6 0M5 12c2-2 12-2 14 0'],
    ['icon' => 'M4 10h16M6 10v10M18 10v10M8 10V7a4 4 0 0 1 8 0v3'],
    ['icon' => 'M3 7h11v10H3zM14 11h4l3 3v3h-7zM6 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z'],
    ['icon' => 'M13 2 5 14h7l-1 8 8-12h-7z'],
];

require_once '../includes/header.php';
?>

<section class="page-hero compact-hero">
    <div class="container">
        <span class="eyebrow">Browse the marketplace</span>
        <h1>All supplier categories</h1>
        <p>Pick a category to see relevant suppliers, compare businesses, and contact sellers directly.</p>
    </div>
</section>

<section class="section section-surface">
    <div class="container">
        <div class="grid-4">
            <?php foreach ($categories as $i => $cat): ?>
                <?php $catIcon = $categoryIcons[$i % count($categoryIcons)]; ?>
                <a href="/pages/directory.php?category=<?= $cat['id'] ?>" class="card category-list-card">
                    <div class="card-body category-card">
                        <div class="category-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="<?= htmlspecialchars($catIcon['icon']) ?>"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="card-title"><?= htmlspecialchars($cat['name']) ?></div>
                            <div class="card-text">View matching suppliers</div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
