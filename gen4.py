import os

head = """<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Subtle dot pattern background */
        .bg-dots {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        /* Soft gradient blobs for hero */
        .hero-bg {
            background: #f8fafc;
            position: relative;
            overflow: hidden;
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37,99,235,0.06) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-bg::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(15,42,67,0.04) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        /* Subtle diagonal lines */
        .bg-lines {
            background-color: #ffffff;
            background-image: repeating-linear-gradient(
                45deg,
                #f1f5f9 0px,
                #f1f5f9 1px,
                transparent 1px,
                transparent 11px
            );
        }
        /* Category hover */
        .cat-item {
            transition: all 0.2s ease;
        }
        .cat-item:hover {
            border-color: #2563eb;
            box-shadow: 0 4px 12px rgba(37,99,235,0.08);
            transform: translateY(-1px);
        }
        /* Request card hover */
        .req-card {
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .req-card:hover {
            border-color: #94a3b8;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        /* Supplier card hover */
        .sup-card {
            transition: box-shadow 0.2s, border-color 0.2s;
        }
        .sup-card:hover {
            border-color: #94a3b8;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        /* Section divider */
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 0 auto;
            max-width: 200px;
        }
    </style>
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
                <a href="post-request.html" class="btn btn-sm btn-accent" style="color:#fff;">Post Request</a>
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

# All 20 categories with relevant Ethiopian market icons
CATEGORIES = [
    ("Car Parts \u0026 Accessories", "car"),
    ("Construction Materials", "construction"),
    ("Printing \u0026 Packaging", "printing"),
    ("Hotel \u0026 Restaurant Supplies", "hotel"),
    ("Office Supplies", "office"),
    ("Furniture", "furniture"),
    ("Electronics", "electronics"),
    ("Machinery \u0026 Tools", "machinery"),
    ("Agricultural Supplies", "agri"),
    ("Medical Supplies", "medical"),
    ("Beauty \u0026 Salon", "beauty"),
    ("Generator \u0026 Power", "power"),
    ("Solar \u0026 Renewable Energy", "solar"),
    ("Plumbing Supplies", "plumbing"),
    ("Tiles \u0026 Flooring", "tiles"),
    ("Paint \u0026 Coatings", "paint"),
    ("Textiles \u0026 Fabrics", "textile"),
    ("Food \u0026 Beverage", "food"),
    ("Security Systems", "security"),
    ("IT \u0026 Software Services", "it"),
    ("School \u0026 Educational", "school"),
    ("Water Treatment", "water"),
    ("Logistics \u0026 Transport", "logistics"),
    ("Metal Fabrication", "metal"),
    ("Professional Services", "services"),
]

ICONS = {
    "car": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>',
    "construction": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="8" rx="1"/><path d="M17 14v7"/><path d="M7 14v7"/><path d="M17 3v3"/><path d="M7 3v3"/><path d="M10 14 2.3 6.3"/><path d="m14 6 7.7 7.7"/><path d="m8 6 8 8"/></svg>',
    "printing": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>',
    "hotel": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/><path d="m9 16 .348-.24c1.465-1.013 3.84-1.013 5.304 0L15 16"/><path d="M8 7h.01"/><path d="M16 7h.01"/><path d="M12 7h.01"/><path d="M12 11h.01"/><path d="M8 11h.01"/><path d="M16 11h.01"/></svg>',
    "office": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>',
    "furniture": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 9V7a2 2 0 0 0-2-2h-5"/><path d="M14 13V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5"/><path d="M6 12h4"/><path d="M6 16h4"/><path d="M6 8h4"/></svg>',
    "electronics": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>',
    "machinery": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
    "agri": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 22h20"/><path d="M6 18v-8a4 4 0 0 1 8 0v8"/><path d="M10 18V8a4 4 0 0 1 8 0v10"/><path d="M14 18V4a4 4 0 0 1 8 0v14"/></svg>',
    "medical": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
    "beauty": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M9 5H5"/></svg>',
    "power": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
    "solar": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>',
    "plumbing": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Z"/></svg>',
    "tiles": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>',
    "paint": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m19 11-8 8-8-8"/><path d="m19 3-8 8-8-8"/><path d="M2 11h20"/></svg>',
    "textile": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v3"/><path d="M21 16v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3"/><path d="M4 8h16"/><path d="M4 16h16"/></svg>',
    "food": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 21h8a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2z"/><path d="M10 5v16"/><path d="M14 5v16"/><path d="M8 8h8"/><path d="M8 12h8"/><path d="M8 16h8"/></svg>',
    "security": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="1"/><path d="M12 12v2"/></svg>',
    "it": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="12" x="3" y="4" rx="2" ry="2"/><line x1="12" x2="12" y1="18" y2="22"/><line x1="8" x2="16" y1="22" y2="22"/></svg>',
    "school": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 1.66 4 3 9 3s9-1.34 9-3v-5"/></svg>',
    "water": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>',
    "logistics": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="13" x="6" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="M15 22V9"/><path d="M9 22V9"/></svg>',
    "metal": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 0-9.36-1.26l-7.24 7.24a6 6 0 0 0 1.26 9.36l3.77-3.77a1 1 0 0 0 0-1.4l-1.6-1.6a1 1 0 0 0-1.4 0l-3.77 3.77a6 6 0 0 0 9.36 1.26l7.24-7.24a6 6 0 0 0-1.26-9.36z"/></svg>',
    "services": '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 3 2 2 4-4"/></svg>',
}

def cat_item(name, icon_key):
    icon = ICONS.get(icon_key, ICONS["services"])
    return f'            <a href="directory.html" class="cat-item" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;text-decoration:none;color:#0f172a;"><span style="flex-shrink:0;">{icon}</span><span style="font-size:0.92rem;font-weight:500;">{name}</span></a>'

def req_card(title, cat, loc, qty, budget, desc, time, quotes, badge, badge_color, badge_bg):
    return f'''            <a href="request-detail.html" style="text-decoration:none;color:inherit;">
                <div class="req-card" style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:22px;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;">
                        <div style="font-weight:600;font-size:1rem;color:#0f172a;line-height:1.35;">{title}</div>
                        <span style="flex-shrink:0;font-size:0.72rem;font-weight:600;padding:3px 10px;border-radius:999px;background:{badge_bg};color:{badge_color};">{badge}</span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:12px;font-size:0.82rem;color:#64748b;margin-bottom:10px;">
                        <span><strong style="color:#334155;">{cat}</strong></span>
                        <span>&#128205; {loc}</span>
                        <span>&#128207; {qty}</span>
                        <span>&#128176; {budget}</span>
                    </div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;margin-bottom:14px;">{desc}</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.8rem;color:#94a3b8;">{time} &middot; {quotes} quotes</span>
                        <span style="font-size:0.82rem;color:#2563eb;font-weight:600;">View &rarr;</span>
                    </div>
                </div>
            </a>'''

def sup_card(initial, name, cat, loc, rating, reviews, desc, verified=False, featured=False):
    badges = ''
    if verified:
        badges += '<span style="font-size:0.72rem;padding:2px 8px;border-radius:999px;background:#fffbeb;color:#b45309;border:1px solid #fcd34d;">&#10003; Verified</span>'
    if featured:
        badges += '<span style="font-size:0.72rem;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe;">Featured</span>'
    return f'''            <a href="supplier.html" style="text-decoration:none;color:inherit;">
                <div class="sup-card" style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:22px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div style="width:44px;height:44px;background:#f1f5f9;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:700;color:#0f2a43;">{initial}</div>
                        <div>
                            <div style="font-weight:600;color:#0f172a;font-size:1rem;">{name}</div>
                            <div style="display:flex;gap:6px;margin-top:3px;">{badges}</div>
                        </div>
                    </div>
                    <div style="font-size:0.82rem;color:#64748b;margin-bottom:10px;">{cat} &middot; &#128205; {loc} &middot; &#11088; {rating} ({reviews})</div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;">{desc}</p>
                </div>
            </a>'''

print('helpers ok')
