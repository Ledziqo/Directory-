<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (isLoggedIn()) redirect('/pages/dashboard.php');

$pageTitle = 'Reset Password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        flashMessage('error', 'Please enter your email.');
    } else {
        // Check if email exists
        $stmt = $pdo->prepare('SELECT id, full_name, email FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Generate token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            $pdo->prepare('UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?')
                ->execute([$token, $expires, $user['id']]);
            
            $resetLink = 'https://' . $_SERVER['HTTP_HOST'] . '/pages/reset-password.php?token=' . $token;
            
            // In production, send email here. For now, show link in flash message.
            flashMessage('success', 'Reset link generated. Copy it below:');
            $showResetLink = true;
        } else {
            // Don't reveal if email doesn't exist (security)
            flashMessage('success', 'If this email exists, a reset link has been generated.');
        }
    }
}

require_once '../includes/header.php';
?>

<section class="section">
    <div class="auth-box">
        <h2>Reset Password</h2>
        <?php showFlash(); ?>
        
        <?php if (!empty($showResetLink) && !empty($resetLink)): ?>
            <div class="flash flash-info" style="margin-bottom:20px; word-break:break-all;">
                <strong>Preview mode — copy this link:</strong><br>
                <a href="<?= htmlspecialchars($resetLink) ?>" style="color:var(--accent); font-weight:600;"><?= htmlspecialchars($resetLink) ?></a>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Enter your registered email">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Get Reset Link</button>
        </form>
        <div class="auth-links">
            Remember your password? <a href="login.php">Log in</a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>