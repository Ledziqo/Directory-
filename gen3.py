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

# Category icons - cleaner line icons
ICONS = {
    "car": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>',
    "construction": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="20" height="8" rx="1"/><path d="M17 14v7"/><path d="M7 14v7"/><path d="M17 3v3"/><path d="M7 3v3"/><path d="M10 14 2.3 6.3"/><path d="m14 6 7.7 7.7"/><path d="m8 6 8 8"/></svg>',
    "printing": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>',
    "hotel": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Z"/><path d="m9 16 .348-.24c1.465-1.013 3.84-1.013 5.304 0L15 16"/><path d="M8 7h.01"/><path d="M16 7h.01"/><path d="M12 7h.01"/><path d="M12 11h.01"/><path d="M8 11h.01"/><path d="M16 11h.01"/></svg>',
    "office": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>',
    "furniture": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 9V7a2 2 0 0 0-2-2h-5"/><path d="M14 13V5a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-5"/><path d="M6 12h4"/><path d="M6 16h4"/><path d="M6 8h4"/></svg>',
    "electronics": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>',
    "machinery": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
    "services": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m16 3 2 2 4-4"/></svg>',
    "medical": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>',
    "beauty": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M9 5H5"/></svg>',
    "wholesale": '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="13" x="6" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="M15 22V9"/><path d="M9 22V9"/></svg>',
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

def cat_link(name, icon_key):
    icon = ICONS.get(icon_key, ICONS["services"])
    return f'            <a href="directory.html" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fff;border:1px solid #e5e7eb;border-radius:8px;transition:all 0.2s;text-decoration:none;color:#111827;"><span style="flex-shrink:0;">{icon}</span><span style="font-size:0.92rem;font-weight:500;">{name}</span></a>'

print('setup ok')
cat_links = "\n".join([cat_link(name, icon) for name, icon in CATEGORIES])

index = head + '<title>EthioMarket - Find Suppliers or Post What You Need</title></head><body>' + nav + """

<!-- Hero -->
<section style="background:linear-gradient(180deg,#f8fafc 0%,#ffffff 100%);padding:64px 16px 48px;text-align:center;border-bottom:1px solid #e5e7eb;">
    <div class="container">
        <div style="max-width:640px;margin:0 auto;">
            <h1 style="font-size:2.6rem;font-weight:800;color:#0f172a;line-height:1.15;margin-bottom:16px;letter-spacing:-0.5px;">
                Find Suppliers or Post What You Need
            </h1>
            <p style="font-size:1.15rem;color:#64748b;line-height:1.6;margin-bottom:32px;">
                Search verified Ethiopian businesses in Addis Ababa, compare suppliers, or post a request and let sellers come to you.
            </p>
            
            <!-- Search -->
            <form action="directory.html" method="GET" style="display:flex;gap:8px;max-width:560px;margin:0 auto 24px;flex-wrap:wrap;">
                <input type="text" name="q" placeholder="Search suppliers, products, services..." style="flex:1;min-width:220px;padding:14px 18px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.95rem;background:#fff;outline:none;transition:border-color 0.2s;">
                <select name="category" style="padding:14px 16px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:0.9rem;background:#fff;outline:none;min-width:140px;">
                    <option value="">All Categories</option>
                    <option value="1">Car Parts</option>
                    <option value="2">Construction</option>
                    <option value="3">Printing</option>
                    <option value="4">Hotel/Restaurant</option>
                    <option value="5">Office</option>
                    <option value="6">Furniture</option>
                </select>
                <button type="submit" class="btn btn-primary" style="padding:14px 28px;font-size:0.95rem;">Search</button>
            </form>
            
            <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
                <a href="post-request.html" class="btn btn-accent">+ Post a Request</a>
                <a href="register.html" class="btn btn-outline">List Your Business</a>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section style="padding:48px 16px;">
    <div class="container">
        <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:8px;">
            <h2 style="font-size:1.3rem;font-weight:700;color:#0f172a;">Browse Categories</h2>
            <a href="categories.html" style="font-size:0.9rem;color:#2563eb;font-weight:500;">View all 12 &rarr;</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:10px;">
""" + cat_links + """
        </div>
    </div>
</section>

<!-- Buyer Requests -->
<section style="background:#fff;padding:48px 16px;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;">
    <div class="container">
        <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:8px;">
            <h2 style="font-size:1.3rem;font-weight:700;color:#0f172a;">Latest Buyer Requests</h2>
            <a href="requests.html" style="font-size:0.9rem;color:#2563eb;font-weight:500;">View all &rarr;</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:14px;">
            <!-- Request 1 -->
            <a href="request-detail.html" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;transition:box-shadow 0.2s;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;">
                        <div style="font-weight:600;font-size:1rem;color:#0f172a;line-height:1.3;">Need Toyota Vitz 2012 front bumper, new or used</div>
                        <span style="flex-shrink:0;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:999px;background:#fef2f2;color:#991b1b;">Urgent: Today</span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:14px;font-size:0.85rem;color:#64748b;margin-bottom:10px;">
                        <span><strong style="color:#334155;">Car Parts &amp; Accessories</strong></span>
                        <span>&#128205; Bole</span>
                        <span>&#128207; 1 piece</span>
                        <span>&#128176; 3,000 - 5,000 ETB</span>
                    </div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;margin-bottom:12px;">Looking for genuine or good quality aftermarket front bumper for Toyota Vitz 2012. Silver preferred...</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.82rem;color:#64748b;">2 hours ago &middot; 3 quotes</span>
                        <span style="font-size:0.85rem;color:#2563eb;font-weight:600;">View &rarr;</span>
                    </div>
                </div>
            </a>
            
            <!-- Request 2 -->
            <a href="request-detail.html" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;transition:box-shadow 0.2s;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;">
                        <div style="font-weight:600;font-size:1rem;color:#0f172a;line-height:1.3;">500 custom paper bags with logo printing</div>
                        <span style="flex-shrink:0;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:999px;background:#fffbeb;color:#92400e;">This Week</span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:14px;font-size:0.85rem;color:#64748b;margin-bottom:10px;">
                        <span><strong style="color:#334155;">Printing &amp; Packaging</strong></span>
                        <span>&#128205; Merkato</span>
                        <span>&#128207; 500 bags</span>
                        <span>&#128176; 15,000 ETB</span>
                    </div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;margin-bottom:12px;">Need kraft paper bags with company logo in 2 colors. Size 30x40cm with handles. Delivery by Friday.</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.82rem;color:#64748b;">5 hours ago &middot; 1 quote</span>
                        <span style="font-size:0.85rem;color:#2563eb;font-weight:600;">View &rarr;</span>
                    </div>
                </div>
            </a>
            
            <!-- Request 3 -->
            <a href="request-detail.html" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;transition:box-shadow 0.2s;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;">
                        <div style="font-weight:600;font-size:1rem;color:#0f172a;line-height:1.3;">100 bags of cement near CMC</div>
                        <span style="flex-shrink:0;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:999px;background:#f0fdf4;color:#065f46;">Flexible</span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:14px;font-size:0.85rem;color:#64748b;margin-bottom:10px;">
                        <span><strong style="color:#334155;">Construction Materials</strong></span>
                        <span>&#128205; CMC</span>
                        <span>&#128207; 100 bags</span>
                        <span>&#128176; Negotiable</span>
                    </div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;margin-bottom:12px;">Looking for Dangote or Dashen cement. Need delivery to construction site. Regular monthly supply.</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.82rem;color:#64748b;">1 day ago &middot; 5 quotes</span>
                        <span style="font-size:0.85rem;color:#2563eb;font-weight:600;">View &rarr;</span>
                    </div>
                </div>
            </a>
            
            <!-- Request 4 -->
            <a href="request-detail.html" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;transition:box-shadow 0.2s;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;">
                        <div style="font-weight:600;font-size:1rem;color:#0f172a;line-height:1.3;">30 office chairs for a company</div>
                        <span style="flex-shrink:0;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:999px;background:#f0fdf4;color:#065f46;">Flexible</span>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:14px;font-size:0.85rem;color:#64748b;margin-bottom:10px;">
                        <span><strong style="color:#334155;">Furniture</strong></span>
                        <span>&#128205; Kazanchis</span>
                        <span>&#128207; 30 chairs</span>
                        <span>&#128176; 45,000 ETB</span>
                    </div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;margin-bottom:12px;">Ergonomic office chairs with wheels and adjustable height. Black or grey. Delivery and assembly needed.</p>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.82rem;color:#64748b;">2 days ago &middot; 2 quotes</span>
                        <span style="font-size:0.85rem;color:#2563eb;font-weight:600;">View &rarr;</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Suppliers -->
<section style="padding:48px 16px;">
    <div class="container">
        <div style="display:flex;align-items:baseline;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:8px;">
            <h2 style="font-size:1.3rem;font-weight:700;color:#0f172a;">Featured Suppliers</h2>
            <a href="directory.html" style="font-size:0.9rem;color:#2563eb;font-weight:500;">Browse all &rarr;</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:14px;">
            <a href="supplier.html" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:44px;height:44px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:#0f2a43;">A</div>
                        <div>
                            <div style="font-weight:600;color:#0f172a;font-size:1rem;">ABC Auto Parts</div>
                            <div style="display:flex;gap:6px;margin-top:2px;">
                                <span style="font-size:0.75rem;padding:2px 8px;border-radius:999px;background:#fffbeb;color:#b45309;border:1px solid #fcd34d;">&#10003; Verified</span>
                                <span style="font-size:0.75rem;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe;">Featured</span>
                            </div>
                        </div>
                    </div>
                    <div style="font-size:0.85rem;color:#64748b;margin-bottom:8px;"><span>Car Parts &amp; Accessories</span> &middot; <span>&#128205; Bole</span> &middot; <span>&#11088; 4.5 (12)</span></div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;">Genuine and aftermarket Toyota, Hyundai, and Kia parts. Specializing in body parts, engines, and electrical components.</p>
                </div>
            </a>
            
            <a href="supplier.html" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:44px;height:44px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:#0f2a43;">M</div>
                        <div>
                            <div style="font-weight:600;color:#0f172a;font-size:1rem;">Megenagna Cement Supply</div>
                            <div style="display:flex;gap:6px;margin-top:2px;">
                                <span style="font-size:0.75rem;padding:2px 8px;border-radius:999px;background:#fffbeb;color:#b45309;border:1px solid #fcd34d;">&#10003; Verified</span>
                            </div>
                        </div>
                    </div>
                    <div style="font-size:0.85rem;color:#64748b;margin-bottom:8px;"><span>Construction Materials</span> &middot; <span>&#128205; Megenagna</span> &middot; <span>&#11088; 4.2 (8)</span></div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;">Wholesale cement, rebar, and construction materials. Delivery available across Addis Ababa. Bulk discounts for contractors.</p>
                </div>
            </a>
            
            <a href="supplier.html" style="text-decoration:none;color:inherit;">
                <div style="background:#fff;border:1px solid #e5e7eb;border-radius:10px;padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                        <div style="width:44px;height:44px;background:#f1f5f9;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:#0f2a43;">P</div>
                        <div>
                            <div style="font-weight:600;color:#0f172a;font-size:1rem;">PrintPro Ethiopia</div>
                            <div style="display:flex;gap:6px;margin-top:2px;">
                                <span style="font-size:0.75rem;padding:2px 8px;border-radius:999px;background:#fffbeb;color:#b45309;border:1px solid #fcd34d;">&#10003; Verified</span>
                                <span style="font-size:0.75rem;padding:2px 8px;border-radius:999px;background:#eff6ff;color:#1e40af;border:1px solid #bfdbfe;">Featured</span>
                            </div>
                        </div>
                    </div>
                    <div style="font-size:0.85rem;color:#64748b;margin-bottom:8px;"><span>Printing &amp; Packaging</span> &middot; <span>&#128205; Piassa</span> &middot; <span>&#11088; 4.8 (24)</span></div>
                    <p style="font-size:0.9rem;color:#475569;line-height:1.5;">Custom packaging, paper bags, labels, and commercial printing. From design to delivery. MOQ 100 units.</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section style="background:#f8fafc;padding:48px 16px;">
    <div class="container">
        <div style="text-align:center;margin-bottom:36px;">
            <h2 style="font-size:1.3rem;font-weight:700;color:#0f172a;margin-bottom:6px;">How It Works</h2>
            <p style="color:#64748b;font-size:0.95rem;">Three simple steps to get what you need.</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
            <div style="text-align:center;">
                <div style="width:48px;height:48px;background:#0f2a43;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:#fff;font-weight:700;">1</div>
                <div style="font-weight:600;color:#0f172a;margin-bottom:6px;">Search or Post</div>
                <p style="font-size:0.9rem;color:#64748b;line-height:1.5;">Find suppliers by category, or post exactly what you need.</p>
            </div>
            <div style="text-align:center;">
                <div style="width:48px;height:48px;background:#0f2a43;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:#fff;font-weight:700;">2</div>
                <div style="font-weight:600;color:#0f172a;margin-bottom:6px;">Get Quotes</div>
                <p style="font-size:0.9rem;color:#64748b;line-height:1.5;">Suppliers reply with prices and delivery details.</p>
            </div>
            <div style="text-align:center;">
                <div style="width:48px;height:48px;background:#0f2a43;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:#fff;font-weight:700;">3</div>
                <div style="font-weight:600;color:#0f172a;margin-bottom:6px;">Compare &amp; Choose</div>
                <p style="font-size:0.9rem;color:#64748b;line-height:1.5;">Compare quotes and reviews, then pick the best deal.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section style="background:#0f2a43;padding:56px 16px;text-align:center;color:#fff;">
    <div class="container">
        <h2 style="font-size:1.6rem;font-weight:700;margin-bottom:10px;">Are You a Supplier?</h2>
        <p style="opacity:0.8;max-width:440px;margin:0 auto 24px;font-size:1rem;">List your business, reach new buyers, and grow your sales in Addis Ababa.</p>
        <a href="register.html" class="btn btn-lg btn-accent">List Your Business for Free</a>
    </div>
</section>
""" + footer

with open('preview/index.html','w',encoding='utf-8') as f:
    f.write(index)
print('index redesigned')
# ------------------------------------------------------------------
# CATEGORIES PAGE (clean version)
# ------------------------------------------------------------------
cat_links_all = "\n".join([cat_link(name, icon) for name, icon in CATEGORIES])

categories = head + '<title>All Categories - EthioMarket</title></head><body>' + nav + """
<section style="background:linear-gradient(180deg,#f8fafc 0%,#ffffff 100%);padding:48px 16px 32px;text-align:center;border-bottom:1px solid #e5e7eb;">
    <div class="container">
        <h1 style="font-size:2rem;font-weight:800;color:#0f172a;margin-bottom:8px;">Browse All Categories</h1>
        <p style="color:#64748b;font-size:1rem;">Find suppliers across every industry in Addis Ababa.</p>
    </div>
</section>

<section style="padding:36px 16px;">
    <div class="container">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:10px;">
""" + cat_links_all + """
        </div>
    </div>
</section>

<section style="background:#0f2a43;padding:48px 16px;text-align:center;color:#fff;">
    <div class="container">
        <h2 style="font-size:1.4rem;font-weight:700;margin-bottom:8px;">Don't see your category?</h2>
        <p style="opacity:0.8;max-width:440px;margin:0 auto 20px;">Post a request anyway - suppliers from all industries browse the request board.</p>
        <a href="post-request.html" class="btn btn-accent">Post a Request</a>
    </div>
</section>
""" + footer

with open('preview/categories.html','w',encoding='utf-8') as f:
    f.write(categories)
print('categories done')
