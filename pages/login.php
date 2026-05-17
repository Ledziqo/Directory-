<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

if (isLoggedIn()) redirect('/pages/dashboard.php');

$pageTitle = 'Login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (loginUser($pdo, $email, $password)) {
        flashMessage('success', 'Welcome back!');
        redirect('/pages/dashboard.php');
    } else {
        flashMessage('error', 'Invalid email or password.');
    }
}

require_once '../includes/header.php';
?>

<section class="section">
    <div class="auth-box">
        <h2>Log In</h2>
        <?php showFlash(); ?>
        <form method="POST">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Log In</button>
        </form>
        <div class="auth-links">
            Don't have an account? <a href="register.php">Sign up</a><br>
            <a href="forgot-password.php">Forgot password?</a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
