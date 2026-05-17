<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (isLoggedIn()) redirect('/pages/dashboard.php');

$pageTitle = 'Set New Password';
$token = $_GET['token'] ?? '';
$error = '';
$success = false;

// Validate token
$user = null;
if ($token) {
    $stmt = $pdo->prepare('SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()');
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    if (!$user) {
        $error = 'Invalid or expired reset link. Please request a new one.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $user) {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare('UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?')
            ->execute([$hash, $user['id']]);
        $success = true;
    }
}

require_once '../includes/header.php';
?>

<section class="section">
    <div class="auth-box">
        <h2>Set New Password</h2>
        <?php showFlash(); ?>
        
        <?php if ($success): ?>
            <div class="flash flash-success" style="margin-bottom:20px;">
                Password updated successfully. <a href="login.php">Log in now</a>.
            </div>
        <?php elseif ($error): ?>
            <div class="flash flash-error" style="margin-bottom:20px;">
                <?= htmlspecialchars($error) ?>
            </div>
            <div class="auth-links">
                <a href="forgot-password.php">Request new reset link</a>
            </div>
        <?php elseif ($user): ?>
            <form method="POST">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" required minlength="6">
                    <div class="form-hint">Min 6 characters</div>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;">Update Password</button>
            </form>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>