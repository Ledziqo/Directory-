<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';
requireLogin();

$pageTitle = 'Post a Request';
$categories = getCategories($pdo);
$locations = getLocations($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $categoryId = $_POST['category'] ?? null;
    $locationId = $_POST['location'] ?? null;
    $quantity = trim($_POST['quantity'] ?? '');
    $budget = trim($_POST['budget'] ?? '');
    $urgency = $_POST['urgency'] ?? 'flexible';
    $description = trim($_POST['description'] ?? '');
    $contactMethod = $_POST['contact_method'] ?? 'phone';
    $contactValue = trim($_POST['contact_value'] ?? '');
    $privacy = $_POST['privacy'] ?? 'public';
    $photo = null;

    if (empty($title) || empty($description)) {
        flashMessage('error', 'Title and description are required.');
    } else {
        if (!empty($_FILES['photo']['tmp_name'])) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $dest = '../uploads/requests/' . $filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                $photo = '/uploads/requests/' . $filename;
            }
        }

        $stmt = $pdo->prepare('
            INSERT INTO buyer_requests
            (user_id, title, category_id, location_id, quantity, budget, urgency, description, photo, contact_method, contact_value, privacy)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
        ');
        $stmt->execute([
            $_SESSION['user_id'], $title, $categoryId ?: null, $locationId ?: null,
            $quantity, $budget, $urgency, $description, $photo,
            $contactMethod, $contactValue, $privacy
        ]);

        flashMessage('success', 'Your request has been posted!');
        redirect('/pages/requests.php');
    }
}

require_once '../includes/header.php';
?>

<section class="section" style="max-width:720px; margin:0 auto;">
    <div class="auth-box">
        <h2>Post a Request</h2>
        <?php showFlash(); ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>What are you looking for? *</label>
                <input type="text" name="title" placeholder="e.g., Need Toyota Vitz 2012 front bumper" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="">Select category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <select name="location">
                        <option value="">Select location</option>
                        <?php foreach ($locations as $loc): ?>
                            <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Quantity (optional)</label>
                    <input type="text" name="quantity" placeholder="e.g., 500 bags, 30 chairs">
                </div>
                <div class="form-group">
                    <label>Budget (optional)</label>
                    <input type="text" name="budget" placeholder="e.g., 50,000 ETB">
                </div>
            </div>

            <div class="form-group">
                <label>Urgency</label>
                <select name="urgency">
                    <option value="flexible" selected>Flexible</option>
                    <option value="this_week">This Week</option>
                    <option value="today">Urgent: Today</option>
                </select>
            </div>

            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="5" placeholder="Describe exactly what you need, specifications, quality, etc." required></textarea>
            </div>

            <div class="form-group">
                <label>Upload Photo (optional)</label>
                <input type="file" name="photo" accept="image/*">
                <div class="form-hint">JPG, PNG up to 5MB</div>
            </div>

            <div class="form-group">
                <label>Contact Method *</label>
                <select name="contact_method" id="contact_method" onchange="document.getElementById('contact_label').innerText = this.value === 'phone' ? 'Your Phone' : (this.value === 'email' ? 'Your Email' : 'Your ' + this.value.charAt(0).toUpperCase() + this.value.slice(1))">
                    <option value="phone">Phone</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="telegram">Telegram</option>
                    <option value="email">Email</option>
                </select>
            </div>

            <div class="form-group">
                <label id="contact_label">Your Phone *</label>
                <input type="text" name="contact_value" placeholder="e.g., 0911xxxxxx or @username" required>
            </div>

            <div class="form-group">
                <label>Privacy</label>
                <select name="privacy">
                    <option value="public">Public - anyone can see (contact hidden until logged in)</option>
                    <option value="private">Private - only verified suppliers can respond</option>
                </select>
            </div>

            <button type="submit" class="btn btn-accent" style="width:100%;">Post Request</button>
        </form>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
