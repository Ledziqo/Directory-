</div><!-- /.main-content -->
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Back to top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>
    
    <!-- Mobile FAB -->
    <button class="fab" id="fab" onclick="window.location.href='/pages/post-request.php'" aria-label="Post a request">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </button>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer" role="region" aria-live="polite" aria-label="Notifications"></div>
    
    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3>EthioMarket</h3>
                    <p>Find verified Ethiopian suppliers. Post what you need. Compare quotes.</p>
                    
                    <div class="footer-newsletter">
                        <h4 style="font-size:0.9rem; margin-bottom:8px;">Stay Updated</h4>
                        <form action="#" method="POST" onsubmit="event.preventDefault(); showToast('Subscribed!', 'success');">
                            <input type="email" placeholder="Your email" aria-label="Email for newsletter">
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                    
                    <div class="footer-social" aria-label="Contact us">
                        <a href="https://t.me/Aesliex" target="_blank" aria-label="Telegram @Aesliex" rel="noopener noreferrer">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </a>
                        <span style="font-size:0.85rem; color:rgba(255,255,255,0.7);">@Aesliex</span>
                    </div>
                </div>
                <div>
                    <h4>Buyers</h4>
                    <a href="/pages/directory.php">Search Suppliers</a>
                    <a href="/pages/post-request.php">Post a Request</a>
                    <a href="/pages/requests.php">Request Board</a>
                </div>
                <div>
                    <h4>Suppliers</h4>
                    <a href="/pages/register.php">List Your Business</a>
                    <a href="/pages/pricing.php">Pricing</a>
                    <a href="/pages/dashboard.php">Supplier Dashboard</a>
                </div>
                <div>
                    <h4>Support</h4>
                    <a href="#">How It Works</a>
                    <a href="#">Contact Us</a>
                    <a href="#">Report an Issue</a>
                </div>
                <div>
                    <h4>Legal</h4>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> EthioMarket. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="/assets/js/main.js"></script>
    
    <?php
    // Output flash messages as JS toasts
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $type = $f['type'] ?? 'info';
        $msg = htmlspecialchars($f['msg']);
        echo "<script>document.addEventListener('DOMContentLoaded', function() { showToast('$msg', '$type'); });</script>";
    }
    ?>
</body>
</html>