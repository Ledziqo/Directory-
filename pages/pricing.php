<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Supplier Plans & Pricing';

require_once '../includes/header.php';
?>

<section class="hero" style="padding:60px 16px 40px;">
    <div class="container" style="text-align:center;">
        <h1>Grow Your Business Faster</h1>
        <p style="opacity:0.85;">Stop chasing buyers. Let buyers find you. Upgrade and unlock features that actually bring paying customers.</p>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="container">
        <div class="pricing-grid" style="max-width:1000px; margin-left:auto; margin-right:auto; align-items:stretch;">
            <!-- Free Plan -->
            <div class="pricing-card" style="display:flex; flex-direction:column;">
                <div>
                    <div class="plan-name" style="color:var(--text-light);">Free</div>
                    <div class="plan-price" style="font-size:1.8rem;">Free</div>
                    <div class="plan-period">Just get listed</div>
                    <ul style="margin-bottom:24px;">
                        <li>Basic business profile</li>
                        <li>1 photo only</li>
                        <li>Appear in general search (bottom of list)</li>
                        <li>Receive quotes manually</li>
                        <li>No verification badge</li>
                        <li style="color:var(--text-light); text-decoration:line-through;">No priority placement</li>
                    </ul>
                </div>
                <a href="register.php" class="btn btn-outline" style="width:100%; margin-top:auto;">Start Free</a>
            </div>

            <!-- Verified Plan - 1200 ETB -->
            <div class="pricing-card featured" style="display:flex; flex-direction:column; transform:scale(1.03);">
                <div>
                    <div class="plan-name">Verified</div>
                    <div class="plan-price">1,200 <span style="font-size:0.6em; color:var(--text-light);">ETB/mo</span></div>
                    <div class="plan-period">Most popular for exporters</div>
                    <ul style="margin-bottom:24px;">
                        <li style="color:var(--success); font-weight:600;">&#10004; Verified blue checkmark</li>
                        <li>&#10004; <strong>Up to 8 photos</strong></li>
                        <li>&#10004; <strong>Priority search ranking</strong></li>
                        <li>&#10004; <strong>WhatsApp & Telegram buttons</strong></li>
                        <li>&#10004; <strong>Collect reviews</strong></li>
                        <li>&#10004; <strong>Buyer request board access</strong></li>
                        <li>&#10004; <strong>Verified supplier badge</strong></li>
                    </ul>
                </div>
                <a href="register.php" class="btn btn-accent" style="width:100%; margin-top:auto;">Get Verified — 1,200 ETB</a>
            </div>

            <!-- Premium Plan - 2000 ETB -->
            <div class="pricing-card" style="border-color:var(--accent); border-width:2px; display:flex; flex-direction:column;">
                <div>
                    <div class="plan-name" style="color:var(--accent);">Premium</div>
                    <div class="plan-price" style="color:var(--accent);">2,000 <span style="font-size:0.6em; color:var(--text-light);">ETB/mo</span></div>
                    <div class="plan-period">For serious exporters</div>
                    <ul style="margin-bottom:24px;">
                        <li style="color:var(--success); font-weight:600;">&#9733; <strong>Featured on homepage</strong></li>
                        <li>&#9733; <strong>Top of category pages</strong></li>
                        <li>&#9733; <strong>Up to 20 photos</strong></li>
                        <li>&#9733; <strong>Profile analytics</strong></li>
                        <li>&#9733; <strong>"Featured" gold badge</strong></li>
                        <li>&#9733; <strong>Daily quote alerts</strong></li>
                        <li>&#9733; <strong>Priority support</strong></li>
                        <li>&#9733; <strong>Everything in Verified</strong></li>
                    </ul>
                </div>
                <a href="register.php" class="btn btn-primary" style="width:100%; margin-top:auto;">Go Premium — 2,000 ETB</a>
            </div>
        </div>

        <div style="max-width:600px; margin:48px auto; text-align:center; color:var(--text-light);">
            <h3 style="color:var(--text); margin-bottom:16px;">How to Upgrade</h3>
            <p style="margin-bottom:20px; font-size:1.02rem;">Pay via Telebirr, CBE Birr, or bank transfer. Send proof to our Telegram and we'll activate your plan in under 10 minutes.</p>
            <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap;">
                <a href="https://t.me/Aesliex" target="_blank" class="contact-btn contact-telegram" rel="noopener noreferrer">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                    </svg>
                    @Aesliex on Telegram
                </a>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>