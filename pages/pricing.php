<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$pageTitle = 'Pricing for Suppliers';

require_once '../includes/header.php';
?>

<section class="hero" style="padding:60px 16px 40px;">
    <div class="container" style="text-align:center;">
        <h1>Supplier Plans</h1>
        <p style="opacity:0.85;">Choose a plan that fits your business. All payments are handled manually via Telegram/WhatsApp.</p>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="container">
        <div class="pricing-grid">
            <div class="pricing-card">
                <div class="plan-name">Free</div>
                <div class="plan-price">Free</div>
                <div class="plan-period">Forever</div>
                <ul>
                    <li>Basic business listing</li>
                    <li>1 photo</li>
                    <li>Appear in search</li>
                    <li>Receive quote requests</li>
                    <li>No verification badge</li>
                </ul>
                <a href="register.php" class="btn btn-outline" style="width:100%;">Get Started</a>
            </div>

            <div class="pricing-card featured">
                <div class="plan-name">Verified</div>
                <div class="plan-price">500 ETB</div>
                <div class="plan-period">per month</div>
                <ul>
                    <li>Verified badge</li>
                    <li>Up to 5 photos</li>
                    <li>Priority in search</li>
                    <li>Phone & WhatsApp buttons</li>
                    <li>Access to request board</li>
                    <li>Review collection</li>
                </ul>
                <a href="register.php" class="btn btn-accent" style="width:100%;">Get Verified</a>
            </div>

            <div class="pricing-card">
                <div class="plan-name">Premium</div>
                <div class="plan-price">1,500 ETB</div>
                <div class="plan-period">per month</div>
                <ul>
                    <li>Everything in Verified</li>
                    <li>Featured in category</li>
                    <li>Up to 10 photos</li>
                    <li>Top search results</li>
                    <li>Analytics dashboard</li>
                    <li>Dedicated support</li>
                </ul>
                <a href="register.php" class="btn btn-primary" style="width:100%;">Go Premium</a>
            </div>

            <div class="pricing-card">
                <div class="plan-name">Enterprise</div>
                <div class="plan-price">Custom</div>
                <div class="plan-period">Contact us</div>
                <ul>
                    <li>Everything in Premium</li>
                    <li>Multiple branches</li>
                    <li>Unlimited photos</li>
                    <li>API access (future)</li>
                    <li>Account manager</li>
                    <li>Custom branding</li>
                </ul>
                <a href="#" class="btn btn-outline" style="width:100%;">Contact on Telegram</a>
            </div>
        </div>

        <div style="max-width:600px; margin:40px auto; text-align:center; color:var(--text-light);">
            <h3 style="color:var(--text); margin-bottom:12px;">How to Pay</h3>
            <p style="margin-bottom:16px;">We accept payment via Telebirr, bank transfer, or cash. Contact us on Telegram or WhatsApp to activate your plan after payment.</p>
            <div style="display:flex; gap:12px; justify-content:center;">
                <a href="https://t.me/yourusername" target="_blank" class="contact-btn contact-telegram">Telegram</a>
                <a href="https://wa.me/251xxxxxxxxx" target="_blank" class="contact-btn contact-whatsapp">WhatsApp</a>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>
