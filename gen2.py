import os

head = """<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
"""

nav = """    <nav class="navbar">
        <div class="container nav-inner">
            <a href="index.html" class="logo">
                <span class="logo-icon"></span>
                <span>EthioMarket</span>
            </a>
            <button class="mobile-toggle" onclick="document.querySelector('.nav-links').classList.toggle('open')" aria-label="Toggle menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
            <div class="nav-links">
                <a href="directory.html">Suppliers</a>
                <a href="requests.html">Requests</a>
                <a href="post-request.html" class="btn btn-sm btn-accent" style="color:#fff;">+ Post a Request</a>
                <a href="login.html">Login</a>
                <a href="register.html" class="btn btn-sm btn-outline">Sign Up</a>
            </div>
        </div>
    </nav>
    <div class="main-content">
"""

footer = """    </div>
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h3>EthioMarket</h3>
                    <p>Find verified Ethiopian suppliers. Post what you need. Compare quotes.</p>
                </div>
                <div>
                    <h4>Buyers</h4>
                    <a href="directory.html">Search Suppliers</a>
                    <a href="post-request.html">Post a Request</a>
                    <a href="requests.html">Request Board</a>
                </div>
                <div>
                    <h4>Suppliers</h4>
                    <a href="register.html">List Your Business</a>
                    <a href="pricing.html">Pricing</a>
                </div>
                <div>
                    <h4>Support</h4>
                    <a href="#">How It Works</a>
                    <a href="#">Contact Us</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 EthioMarket. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
"""

# SVG icons - clean line icons
ICONS = {
    "car": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>',
    "construction": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="8" rx="1"/><path d="M17 14v7"/><path d="M7 14v7"/><path d="M17 3v3"/><path d="M7 3v3"/><path d="M10 14 2.3 6.3"/><path d="m14 6 7.7 7.7"/><path d="m8 6 8 8"/></svg>',
    "printing": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>',
    "hotel": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/><path d="m9 16 .348-.24c1.465-1.013 3.84-1.013 5.304 0L15 16"/><path d="M8 7h.01"/><path d="M16 7h.01"/><path d="M12 7h.01"/><path d="M12 11h.01"/><path d="M8 11h.01"/><path d="M16 11h.01"/></svg>',
    "office": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>',
    "furniture": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 9V7a2 2 0 0 0-2-2h-5"/><path d="M14 13V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5"/><path d="M6 12h4"/><path d="M6 16h4"/><path d="M6 8h4"/></svg>',
    "electronics": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>',
    "machinery": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
    "services": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 3 2 2 4-4"/></svg>',
    "medical": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
    "beauty": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M9 5H5"/></svg>',
    "wholesale": '<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="13" x="6" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="M15 22V9"/><path d="M9 22V9"/></svg>',
}

CATEGORIES = [
    ("Car Parts \u0026 Accessories", "car"),
    ("Construction Materials", "construction"),
    ("Printing \u0026 Packaging", "printing"),
    ("Hotel \u0026 Restaurant Supplies", "hotel"),
    ("Office Supplies", "office"),
    ("Furniture", "furniture"),
    ("Electronics", "electronics"),
    ("Machinery \u0026 Tools", "machinery"),
    ("Professional Services", "services"),
    ("Medical Supplies", "medical"),
    ("Beauty \u0026 Salon", "beauty"),
    ("Importers \u0026 Wholesalers", "wholesale"),
]

def cat_card(name, icon_key):
    icon = ICONS.get(icon_key, ICONS["services"])
    return f'            <a href="directory.html" class="card" style="text-decoration:none;"><div class="card-body" style="text-align:center;padding:28px 20px;"><div style="margin-bottom:14px;display:flex;justify-content:center;">{icon}</div><div class="card-title" style="font-size:0.95rem;">{name}</div></div></a>'

print('setup done')
# ------------------------------------------------------------------
# INDEX
# ------------------------------------------------------------------
cat_grid_8 = "\n".join([cat_card(name, icon) for name, icon in CATEGORIES[:8]])

index = head + '<title>EthioMarket - Find Suppliers or Post What You Need</title></head><body>' + nav + """
<section class="hero">
    <div class="container">
        <h1>Find Suppliers or Post What You Need</h1>
        <p>Search verified Ethiopian businesses, compare suppliers, or post a request and let sellers come to you.</p>
        <div class="hero-buttons">
            <a href="directory.html" class="btn btn-lg btn-accent">Search Suppliers</a>
            <a href="post-request.html" class="btn btn-lg btn-outline">Post a Request</a>
            <a href="register.html" class="btn btn-lg btn-outline">List Your Business</a>
        </div>
    </div>
</section>

<!-- Trust Bar -->
<section style="background:#fff;border-bottom:1px solid var(--border);padding:28px 16px;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;text-align:center;">
            <div>
                <div style="font-size:1.6rem;font-weight:800;color:var(--primary);">200+</div>
                <div style="font-size:0.85rem;color:var(--text-light);">Verified Suppliers</div>
            </div>
            <div>
                <div style="font-size:1.6rem;font-weight:800;color:var(--primary);">1,200+</div>
                <div style="font-size:0.85rem;color:var(--text-light);">Requests Posted</div>
            </div>
            <div>
                <div style="font-size:1.6rem;font-weight:800;color:var(--primary);">12</div>
                <div style="font-size:0.85rem;color:var(--text-light);">Categories</div>
            </div>
            <div>
                <div style="font-size:1.6rem;font-weight:800;color:var(--primary);">Addis</div>
                <div style="font-size:0.85rem;color:var(--text-light);">Ababa Focused</div>
            </div>
        </div>
    </div>
</section>

<section class="search-bar">
    <div class="container">
        <form action="directory.html" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search: Toyota bumper, cement, office chairs..." value="">
            <select name="category">
                <option value="">All Categories</option>
                <option value="1">Car Parts &amp; Accessories</option>
                <option value="2">Construction Materials</option>
                <option value="3">Printing &amp; Packaging</option>
                <option value="4">Hotel &amp; Restaurant Supplies</option>
                <option value="5">Office Supplies</option>
                <option value="6">Furniture</option>
                <option value="7">Electronics</option>
                <option value="8">Machinery &amp; Tools</option>
            </select>
            <select name="location">
                <option value="">All Locations</option>
                <option value="1">Bole</option>
                <option value="2">Kazanchis</option>
                <option value="3">Piassa</option>
                <option value="4">Merkato</option>
                <option value="5">CMC</option>
                <option value="6">Megenagna</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Popular Categories</h2>
            <a href="categories.html">View All &rarr;</a>
        </div>
        <div class="grid-4">
""" + cat_grid_8 + """
        </div>
    </div>
</section>

<section class="section" style="background:linear-gradient(135deg,#f0f9ff,#f8fafc);">
    <div class="container">
        <div class="section-header">
            <h2>Latest Buyer Requests</h2>
            <a href="requests.html">View All &rarr;</a>
        </div>
        <div class="grid-3">
            <a href="request-detail.html" class="card request-card urgent-today">
                <div class="card-body">
                    <div class="req-header">
                        <div class="card-title">Need Toyota Vitz 2012 front bumper...</div>
                        <span class="badge urgent-today">Urgent: Today</span>
                    </div>
                    <div class="req-meta">
                        <span><strong>Car Parts &amp; Accessories</strong></span>
                        <span>&#128205; Bole</span>
                        <span>2 hours ago</span>
                    </div>
                    <div class="card-text">Looking for a genuine or good quality aftermarket front bumper for Toyota Vitz 2012 model...</div>
                    <div style="margin-top:12px;font-size:0.85rem;color:var(--text-light);">3 quotes received</div>
                </div>
            </a>
            <a href="request-detail.html" class="card request-card urgent-week">
                <div class="card-body">
                    <div class="req-header">
                        <div class="card-title">Looking for 500 custom paper bags...</div>
                        <span class="badge urgent-week">This Week</span>
                    </div>
                    <div class="req-meta">
                        <span><strong>Printing &amp; Packaging</strong></span>
                        <span>&#128205; Merkato</span>
                        <span>5 hours ago</span>
                    </div>
                    <div class="card-text">Need kraft paper bags with our company logo printed in 2 colors. Size 30x40cm...</div>
                    <div style="margin-top:12px;font-size:0.85rem;color:var(--text-light);">1 quote received</div>
                </div>
            </a>
            <a href="request-detail.html" class="card request-card urgent-flex">
                <div class="card-body">
                    <div class="req-header">
                        <div class="card-title">Need 100 bags of cement near CMC</div>
                        <span class="badge urgent-flex">Flexible</span>
                    </div>
                    <div class="req-meta">
                        <span><strong>Construction Materials</strong></span>
                        <span>&#128205; CMC</span>
                        <span>1 day ago</span>
                    </div>
                    <div class="card-text">Looking for Dangote or Dashen cement. Need delivery to construction site near CMC...</div>
                    <div style="margin-top:12px;font-size:0.85rem;color:var(--text-light);">5 quotes received</div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Why EthioMarket -->
<section class="section">
    <div class="container">
        <div style="text-align:center;max-width:560px;margin:0 auto 36px;">
            <h2 style="font-size:1.5rem;font-weight:700;color:var(--primary);margin-bottom:8px;">Why EthioMarket?</h2>
            <p style="color:var(--text-light);">The smarter way to find suppliers and get quotes in Addis Ababa.</p>
        </div>
        <div class="grid-4">
            <div class="card" style="padding:28px 22px;text-align:center;">
                <div style="width:48px;height:48px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="card-title">Verified Suppliers</div>
                <p class="card-text">Every supplier is manually reviewed before appearing on the platform.</p>
            </div>
            <div class="card" style="padding:28px 22px;text-align:center;">
                <div style="width:48px;height:48px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="card-title">Save Time</div>
                <p class="card-text">Post once and receive multiple quotes. No more calling 20 shops.</p>
            </div>
            <div class="card" style="padding:28px 22px;text-align:center;">
                <div style="width:48px;height:48px;background:#fffbeb;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--warning)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div class="card-title">Compare Quotes</div>
                <p class="card-text">See prices, delivery times, and reviews side by side before choosing.</p>
            </div>
            <div class="card" style="padding:28px 22px;text-align:center;">
                <div style="width:48px;height:48px;background:#fef2f2;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="card-title">Safe &amp; Trusted</div>
                <p class="card-text">Contact details stay private until you decide to share. Report any issues instantly.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section" style="background:#fff;">
    <div class="container">
        <div style="text-align:center;max-width:560px;margin:0 auto 36px;">
            <h2 style="font-size:1.5rem;font-weight:700;color:var(--primary);margin-bottom:8px;">What People Say</h2>
            <p style="color:var(--text-light);">Real buyers and suppliers in Addis Ababa.</p>
        </div>
        <div class="grid-3">
            <div class="card" style="padding:28px;">
                <div style="display:flex;gap:4px;margin-bottom:12px;">
                    <span style="color:var(--warning);">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                </div>
                <p style="font-size:0.95rem;color:var(--text);line-height:1.6;margin-bottom:16px;">"I posted a request for 500 paper bags and got 3 quotes within 2 hours. Saved me so much time compared to walking around Merkato."</p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">M</div>
                    <div>
                        <div style="font-size:0.9rem;font-weight:600;">Meskerem Tadesse</div>
                        <div style="font-size:0.8rem;color:var(--text-light);">Buyer, Kazanchis</div>
                    </div>
                </div>
            </div>
            <div class="card" style="padding:28px;">
                <div style="display:flex;gap:4px;margin-bottom:12px;">
                    <span style="color:var(--warning);">&#9733;&#9733;&#9733;&#9733;&#9733;</span>
                </div>
                <p style="font-size:0.95rem;color:var(--text);line-height:1.6;margin-bottom:16px;">"Since listing my auto parts business, I get 3-5 serious quote requests every week. The buyers are real and the leads are quality."</p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">A</div>
                    <div>
                        <div style="font-size:0.9rem;font-weight:600;">Abebe Kebede</div>
                        <div style="font-size:0.8rem;color:var(--text-light);">Supplier, Bole</div>
                    </div>
                </div>
            </div>
            <div class="card" style="padding:28px;">
                <div style="display:flex;gap:4px;margin-bottom:12px;">
                    <span style="color:var(--warning);">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                </div>
                <p style="font-size:0.95rem;color:var(--text);line-height:1.6;margin-bottom:16px;">"The verification badge helped me win trust immediately. Customers mention they chose me because they saw the verified mark."</p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:0.85rem;">D</div>
                    <div>
                        <div style="font-size:0.9rem;font-weight:600;">Dawit Hailu</div>
                        <div style="font-size:0.8rem;color:var(--text-light);">Supplier, Piassa</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Verified Suppliers</h2>
            <a href="directory.html">View All &rarr;</a>
        </div>
        <div class="grid-3">
            <a href="supplier.html" class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">A</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">ABC Auto Parts</div>
                            <div style="display:flex;gap:6px;">
                                <span class="badge badge-verified">&#10003; Verified</span>
                                <span class="badge badge-premium">Featured</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Car Parts &amp; Accessories</span>
                        <span>&#128205; Bole</span>
                    </div>
                    <div class="card-text">Genuine and aftermarket Toyota, Hyundai, and Kia parts. Specializing in body parts, engines, and electrical...</div>
                    <div class="card-actions"><span class="btn btn-sm btn-outline">View Profile</span></div>
                </div>
            </a>
            <a href="supplier.html" class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">M</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">Megenagna Cement Supply</div>
                            <div style="display:flex;gap:6px;"><span class="badge badge-verified">&#10003; Verified</span></div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Construction Materials</span>
                        <span>&#128205; Megenagna</span>
                    </div>
                    <div class="card-text">Wholesale cement, rebar, and construction materials. Delivery available across Addis Ababa...</div>
                    <div class="card-actions"><span class="btn btn-sm btn-outline">View Profile</span></div>
                </div>
            </a>
            <a href="supplier.html" class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">P</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">PrintPro Ethiopia</div>
                            <div style="display:flex;gap:6px;">
                                <span class="badge badge-verified">&#10003; Verified</span>
                                <span class="badge badge-premium">Featured</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Printing &amp; Packaging</span>
                        <span>&#128205; Piassa</span>
                    </div>
                    <div class="card-text">Custom packaging, paper bags, labels, and commercial printing. From design to delivery...</div>
                    <div class="card-actions"><span class="btn btn-sm btn-outline">View Profile</span></div>
                </div>
            </a>
        </div>
    </div>
</section>

<section class="section" style="background:#fff;">
    <div class="container">
        <div style="text-align:center;max-width:560px;margin:0 auto 36px;">
            <h2 style="font-size:1.5rem;font-weight:700;color:var(--primary);margin-bottom:8px;">How It Works</h2>
            <p style="color:var(--text-light);">Three simple steps to get what you need.</p>
        </div>
        <div class="grid-3" style="text-align:center;">
            <div class="card" style="padding:32px 24px;">
                <div style="width:56px;height:56px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:#fff;font-weight:800;font-size:1.2rem;">1</div>
                <div class="card-title">Search or Post</div>
                <p class="card-text">Find verified suppliers by category and location, or post exactly what you need.</p>
            </div>
            <div class="card" style="padding:32px 24px;">
                <div style="width:56px;height:56px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:#fff;font-weight:800;font-size:1.2rem;">2</div>
                <div class="card-title">Get Quotes</div>
                <p class="card-text">Suppliers browse your request and reply with prices and delivery details via WhatsApp or Telegram.</p>
            </div>
            <div class="card" style="padding:32px 24px;">
                <div style="width:56px;height:56px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;color:#fff;font-weight:800;font-size:1.2rem;">3</div>
                <div class="card-title">Compare &amp; Choose</div>
                <p class="card-text">Compare multiple quotes, check supplier reviews, and pick the best deal.</p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:linear-gradient(135deg, #0F2A43, #1a3d5c); color:#fff; text-align:center;">
    <div class="container">
        <h2 style="font-size:1.8rem; margin-bottom:12px;">Are You a Supplier?</h2>
        <p style="opacity:0.85; max-width:500px; margin:0 auto 24px;">List your business, reach new buyers, and grow your sales in Addis Ababa.</p>
        <a href="register.html" class="btn btn-lg btn-accent">List Your Business for Free</a>
    </div>
</section>
""" + footer

with open('preview/index.html','w',encoding='utf-8') as f:
    f.write(index)
print('index done')
# ------------------------------------------------------------------
# CATEGORIES PAGE
# ------------------------------------------------------------------
cat_grid_all = "\n".join([cat_card(name, icon) for name, icon in CATEGORIES])

categories = head + '<title>All Categories - EthioMarket</title></head><body>' + nav + """
<section class="hero" style="padding:50px 16px 40px;">
    <div class="container" style="text-align:center;">
        <h1>Browse All Categories</h1>
        <p style="opacity:0.85;">Find suppliers across every industry in Addis Ababa.</p>
    </div>
</section>

<section class="search-bar">
    <div class="container">
        <form action="directory.html" method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search suppliers..." value="">
            <select name="category">
                <option value="">All Categories</option>
                <option value="1">Car Parts &amp; Accessories</option>
                <option value="2">Construction Materials</option>
                <option value="3">Printing &amp; Packaging</option>
                <option value="4">Hotel &amp; Restaurant Supplies</option>
                <option value="5">Office Supplies</option>
                <option value="6">Furniture</option>
                <option value="7">Electronics</option>
                <option value="8">Machinery &amp; Tools</option>
            </select>
            <select name="location">
                <option value="">All Locations</option>
                <option value="1">Bole</option>
                <option value="2">Kazanchis</option>
                <option value="3">Piassa</option>
                <option value="4">Merkato</option>
                <option value="5">CMC</option>
                <option value="6">Megenagna</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="section-header">
            <h2>12 Categories</h2>
        </div>
        <div class="grid-4">
""" + cat_grid_all + """
        </div>
    </div>
</section>

<section class="section" style="background:linear-gradient(135deg, #0F2A43, #1a3d5c); color:#fff; text-align:center;">
    <div class="container">
        <h2 style="font-size:1.6rem; margin-bottom:12px;">Don't see your category?</h2>
        <p style="opacity:0.85; max-width:480px; margin:0 auto 24px;">We add new categories based on demand. Contact us to suggest one.</p>
        <a href="requests.html" class="btn btn-lg btn-accent">Post a Request Anyway</a>
    </div>
</section>
""" + footer

with open('preview/categories.html','w',encoding='utf-8') as f:
    f.write(categories)
print('categories done')
