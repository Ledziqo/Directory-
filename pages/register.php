<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (isLoggedIn()) redirect('/pages/dashboard.php');

$pageTitle = 'Sign Up';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (empty($fullName) || empty($email) || empty($phone) || empty($password)) {
        flashMessage('error', 'All fields are required.');
    } elseif ($password !== $confirm) {
        flashMessage('error', 'Passwords do not match.');
    } elseif (strlen($password) < 6) {
        flashMessage('error', 'Password must be at least 6 characters.');
    } else {
        $chk = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $chk->execute([$email]);
        if ($chk->fetch()) {
            flashMessage('error', 'Email already registered.');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (full_name, email, phone, password_hash, role) VALUES (?,?,?,?,?)');
            $stmt->execute([$fullName, $email, $phone, $hash, 'buyer']);
            $userId = $pdo->lastInsertId();
            
            $_SESSION['user_id'] = $userId;
            $_SESSION['role'] = 'buyer';
            $_SESSION['full_name'] = $fullName;
            
            flashMessage('success', 'Account created! Welcome to EthioMarket.');
            redirect('/pages/dashboard.php');
        }
    }
}

require_once '../includes/header.php';
?>

<section class="section">
    <div class="auth-box">
        <h2>Create Account</h2>
        <?php showFlash(); ?>
        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel" name="phone" placeholder="e.g., 0911xxxxxx" required>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required>
                <div class="form-hint">Min 6 characters</div>
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Create Account</button>
        </form>
        <div class="auth-links">
            Already have an account? <a href="login.php">Log in</a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
