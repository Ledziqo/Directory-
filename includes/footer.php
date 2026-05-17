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
                    
                    <div class="footer-social" aria-label="Social media links">
                        <a href="#" aria-label="Telegram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="WhatsApp">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385h-3.047v-3.47h3.047v-2.642c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953h-1.512c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385c5.737-.9 10.125-5.864 10.125-11.854z"/>
                            </svg>
                        </a>
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