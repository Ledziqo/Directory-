<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$cats = [
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
    ['Medical Supplies','medical'],
    ['Beauty & Salon','beauty-salon'],
];
foreach ($cats as $i => $c) {
    $pdo->prepare('INSERT IGNORE INTO categories (name, slug, sort_order) VALUES (?,?,?)')->execute([$c[0], $c[1], $i]);
}

$locs = [
    'Bole','Kazanchis','Piassa','Merkato','CMC','Megenagna',
    'Sarbet','Gurd Shola','Ayat','Lafto','Nifas Silk','Kirkos',
    'Bole Bulbula','Alem Bank','Summit','Gerji'
];
foreach ($locs as $i => $l) {
    $pdo->prepare('INSERT IGNORE INTO locations (name, slug, sort_order) VALUES (?,?,?)')->execute([$l, slugify($l), $i]);
}

echo "Seed complete. Categories and locations inserted.\n";
?>
