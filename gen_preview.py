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

iSearch = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path></svg>'
iMail = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>'
iCheck = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--success)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'
iCheckSmall = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:inline;vertical-align:middle;margin-right:6px;"><polyline points="20 6 9 17 4 12"></polyline></svg>'

# ------------------------------------------------------------------
# INDEX
# ------------------------------------------------------------------
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
            <a href="directory.html">View All &rarr;</a>
        </div>
        <div class="grid-4">
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#128663;</div><div class="card-title">Car Parts &amp; Accessories</div></div></a>
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#127959;</div><div class="card-title">Construction Materials</div></div></a>
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#128230;</div><div class="card-title">Printing &amp; Packaging</div></div></a>
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#127976;</div><div class="card-title">Hotel &amp; Restaurant Supplies</div></div></a>
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#128221;</div><div class="card-title">Office Supplies</div></div></a>
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#128719;</div><div class="card-title">Furniture</div></div></a>
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#128187;</div><div class="card-title">Electronics</div></div></a>
            <a href="directory.html" class="card"><div class="card-body" style="text-align:center;"><div style="font-size:2rem;margin-bottom:10px;">&#128295;</div><div class="card-title">Machinery &amp; Tools</div></div></a>
        </div>
    </div>
</section>

<section class="section" style="background:#fff;">
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
                            <div style="display:flex;gap:6px;">
                                <span class="badge badge-verified">&#10003; Verified</span>
                            </div>
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
        <div class="section-header">
            <h2>How It Works</h2>
        </div>
        <div class="grid-3" style="text-align:center;">
            <div class="card" style="padding:32px 24px;">
                <div style="margin-bottom:14px;">""" + iSearch + """</div>
                <div class="card-title">Search or Post</div>
                <p class="card-text">Find verified suppliers by category and location, or post exactly what you need.</p>
            </div>
            <div class="card" style="padding:32px 24px;">
                <div style="margin-bottom:14px;">""" + iMail + """</div>
                <div class="card-title">Get Quotes</div>
                <p class="card-text">Suppliers browse your request and reply with prices and delivery details via WhatsApp or Telegram.</p>
            </div>
            <div class="card" style="padding:32px 24px;">
                <div style="margin-bottom:14px;">""" + iCheck + """</div>
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
# DIRECTORY
# ------------------------------------------------------------------
directory = head + '<title>Supplier Directory - EthioMarket</title></head><body>' + nav + """
<div class="search-bar">
    <div class="container">
        <form method="GET" class="search-form">
            <input type="text" name="q" placeholder="Search suppliers..." value="">
            <select name="category">
                <option value="">All Categories</option>
                <option value="1">Car Parts &amp; Accessories</option>
                <option value="2">Construction Materials</option>
                <option value="3">Printing &amp; Packaging</option>
            </select>
            <select name="location">
                <option value="">All Locations</option>
                <option value="1">Bole</option>
                <option value="2">Kazanchis</option>
                <option value="3">Piassa</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        <div class="filters" style="margin-top:12px;">
            <label style="display:flex;align-items:center;gap:6px;font-size:0.88rem;">
                <input type="checkbox" name="verified" value="1"> Verified Only
            </label>
            <label style="display:flex;align-items:center;gap:6px;font-size:0.88rem;">
                <input type="checkbox" name="delivery" value="1"> Delivery Available
            </label>
        </div>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>8 Suppliers Found</h2>
        </div>
        <div class="grid-3">
            <div class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">A</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">ABC Auto Parts</div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <span class="badge badge-verified">&#10003; Verified</span>
                                <span class="badge badge-premium">Featured</span>
                                <span class="badge badge-success">&#10003; Delivery</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Car Parts &amp; Accessories</span>
                        <span>&#128205; Bole</span>
                        <span>&#11088; 4.5 (12)</span>
                    </div>
                    <div class="card-text">Genuine and aftermarket Toyota, Hyundai, and Kia parts. Specializing in body parts, engines, and electrical...</div>
                    <div class="card-actions">
                        <a href="supplier.html" class="btn btn-sm btn-primary">View Profile</a>
                        <a href="#" class="btn btn-sm btn-success">WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">M</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">Megenagna Cement Supply</div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <span class="badge badge-verified">&#10003; Verified</span>
                                <span class="badge badge-success">&#10003; Delivery</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Construction Materials</span>
                        <span>&#128205; Megenagna</span>
                        <span>&#11088; 4.2 (8)</span>
                    </div>
                    <div class="card-text">Wholesale cement, rebar, and construction materials. Delivery available across Addis Ababa...</div>
                    <div class="card-actions">
                        <a href="supplier.html" class="btn btn-sm btn-primary">View Profile</a>
                        <a href="#" class="btn btn-sm btn-success">WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">P</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">PrintPro Ethiopia</div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <span class="badge badge-verified">&#10003; Verified</span>
                                <span class="badge badge-premium">Featured</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Printing &amp; Packaging</span>
                        <span>&#128205; Piassa</span>
                        <span>&#11088; 4.8 (24)</span>
                    </div>
                    <div class="card-text">Custom packaging, paper bags, labels, and commercial printing. From design to delivery...</div>
                    <div class="card-actions">
                        <a href="supplier.html" class="btn btn-sm btn-primary">View Profile</a>
                        <a href="#" class="btn btn-sm btn-success">WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">O</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">OfficeMax Supplies</div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <span class="badge badge-success">&#10003; Delivery</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Office Supplies</span>
                        <span>&#128205; Kazanchis</span>
                        <span>&#11088; 3.9 (5)</span>
                    </div>
                    <div class="card-text">Complete office supplies including furniture, stationery, printers, and IT equipment...</div>
                    <div class="card-actions">
                        <a href="supplier.html" class="btn btn-sm btn-primary">View Profile</a>
                        <a href="#" class="btn btn-sm btn-success">WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">F</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">Furniture Palace</div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <span class="badge badge-verified">&#10003; Verified</span>
                                <span class="badge badge-success">&#10003; Delivery</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Furniture</span>
                        <span>&#128205; Sarbet</span>
                        <span>&#11088; 4.3 (15)</span>
                    </div>
                    <div class="card-text">Home and office furniture. Custom-made sofas, dining sets, office desks, and cabinets...</div>
                    <div class="card-actions">
                        <a href="supplier.html" class="btn btn-sm btn-primary">View Profile</a>
                        <a href="#" class="btn btn-sm btn-success">WhatsApp</a>
                    </div>
                </div>
            </div>
            <div class="card supplier-card">
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                        <div class="profile-logo" style="width:48px;height:48px;font-size:1.2rem;">E</div>
                        <div>
                            <div class="card-title" style="margin-bottom:2px;">Ethio Electronics</div>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <span class="badge badge-success">&#10003; Delivery</span>
                            </div>
                        </div>
                    </div>
                    <div class="supplier-meta">
                        <span>Electronics</span>
                        <span>&#128205; Bole</span>
                        <span>&#11088; 4.0 (7)</span>
                    </div>
                    <div class="card-text">Laptops, phones, accessories, and home appliances. Authorized dealer for major brands...</div>
                    <div class="card-actions">
                        <a href="supplier.html" class="btn btn-sm btn-primary">View Profile</a>
                        <a href="#" class="btn btn-sm btn-success">WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
""" + footer
with open('preview/directory.html','w',encoding='utf-8') as f:
    f.write(directory)
print('directory done')
# ------------------------------------------------------------------
# SUPPLIER PROFILE
# ------------------------------------------------------------------
supplier = head + '<title>ABC Auto Parts - EthioMarket</title></head><body>' + nav + """
<section class="profile-hero">
    <div class="container">
        <div class="profile-hero-inner">
            <div class="profile-logo">A</div>
            <div class="profile-info">
                <h1>
                    ABC Auto Parts
                    <span class="badge badge-verified">&#10003; Verified</span>
                    <span class="badge badge-premium">Featured</span>
                </h1>
                <div class="profile-meta">
                    <span>Car Parts &amp; Accessories</span>
                    <span>&#128205; Bole, Addis Ababa</span>
                    <span>&#11088; 4.5 (12 reviews)</span>
                    <span>&#128065; 1,247 views</span>
                </div>
                <div class="profile-actions">
                    <a href="#" class="contact-btn contact-whatsapp">&#128241; WhatsApp</a>
                    <a href="#" class="contact-btn contact-telegram">&#9992; Telegram</a>
                    <a href="#" class="contact-btn contact-phone">&#9742; Call</a>
                    <button class="btn btn-outline">&#9825; Save</button>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="padding-top:20px;">
    <div class="container">
        <div class="grid-2">
            <div>
                <div class="profile-section">
                    <h3>About</h3>
                    <p>ABC Auto Parts is your trusted source for genuine and aftermarket auto parts in Addis Ababa. We specialize in Toyota, Hyundai, and Kia parts with over 10 years of experience serving mechanics, garages, and individual car owners.</p>
                    <p>Our inventory includes body parts, engine components, electrical systems, brake pads, suspension parts, and accessories. We source directly from manufacturers and authorized distributors to ensure quality and competitive pricing.</p>
                </div>
                <div class="profile-section">
                    <h3>Opening Hours</h3>
                    <p>Monday - Saturday: 8:00 AM - 6:00 PM<br>Sunday: Closed</p>
                </div>
                <div class="profile-section">
                    <h3>Photos</h3>
                    <div class="photo-grid">
                        <img src="https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=300&h=225&fit=crop" alt="Shop front">
                        <img src="https://images.unsplash.com/photo-1619642757334-771b9e9b4bb1?w=300&h=225&fit=crop" alt="Parts shelf">
                        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=300&h=225&fit=crop" alt="Toyota parts">
                        <img src="https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=300&h=225&fit=crop" alt="Workshop">
                    </div>
                </div>
                <div class="profile-section">
                    <h3>Reviews (12)</h3>
                    <div class="review-item">
                        <div class="review-header">
                            <div>
                                <strong>Abebe Kebede</strong>
                                <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                            </div>
                            <span style="font-size:0.82rem;color:var(--text-light);">3 days ago</span>
                        </div>
                        <p>Found the exact bumper I needed for my Corolla. Price was fair and delivery was same day. Highly recommended!</p>
                    </div>
                    <div class="review-item">
                        <div class="review-header">
                            <div>
                                <strong>Meskerem Tadesse</strong>
                                <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</div>
                            </div>
                            <span style="font-size:0.82rem;color:var(--text-light);">1 week ago</span>
                        </div>
                        <p>Good selection of parts. Had to wait 2 days for a special order but quality was excellent. Will buy again.</p>
                    </div>
                    <div class="review-item">
                        <div class="review-header">
                            <div>
                                <strong>Dawit Hailu</strong>
                                <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                            </div>
                            <span style="font-size:0.82rem;color:var(--text-light);">2 weeks ago</span>
                        </div>
                        <p>Best auto parts supplier in Bole. They always have what I need for my garage. WhatsApp response is fast too.</p>
                    </div>
                </div>
            </div>
            <div>
                <div class="profile-section">
                    <h3>Business Details</h3>
                    <div style="display:grid;gap:14px;font-size:0.92rem;">
                        <div>
                            <div style="font-size:0.8rem;color:var(--text-light);margin-bottom:2px;">Category</div>
                            <strong>Car Parts &amp; Accessories</strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem;color:var(--text-light);margin-bottom:2px;">Location</div>
                            <strong>Bole Medhanialem, Behind Dashen Bank, Addis Ababa</strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem;color:var(--text-light);margin-bottom:2px;">Phone</div>
                            <strong>0911-234-567</strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem;color:var(--text-light);margin-bottom:2px;">Email</div>
                            <strong>info@abcautoparts.et</strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem;color:var(--text-light);margin-bottom:2px;">Website</div>
                            <a href="#" style="color:var(--accent);">www.abcautoparts.et</a>
                        </div>
                        <div>
                            <div style="font-size:0.8rem;color:var(--text-light);margin-bottom:2px;">Delivery</div>
                            <strong>&#10003; Available</strong>
                        </div>
                        <div>
                            <div style="font-size:0.8rem;color:var(--text-light);margin-bottom:2px;">Bulk Orders</div>
                            <strong>&#10003; Available</strong>
                        </div>
                    </div>
                </div>
                <div class="profile-section" style="background:#eff6ff;">
                    <h3>Request a Quote</h3>
                    <p style="font-size:0.9rem;color:var(--text-light);">Send a direct quote request to this supplier for any product or service.</p>
                    <a href="#" class="btn btn-accent" style="width:100%;margin-top:10px;">Request Quote</a>
                </div>
            </div>
        </div>
    </div>
</section>
""" + footer
with open('preview/supplier.html','w',encoding='utf-8') as f:
    f.write(supplier)
print('supplier done')
# ------------------------------------------------------------------
# REQUESTS
# ------------------------------------------------------------------
requests = head + '<title>Buyer Request Board - EthioMarket</title></head><body>' + nav + """
<section class="section" style="background:#fff;padding-bottom:20px;">
    <div class="container">
        <div class="section-header">
            <h2>Buyer Request Board</h2>
            <a href="post-request.html" class="btn btn-sm btn-accent">+ Post a Request</a>
        </div>
        <form method="GET" class="filters">
            <div class="form-group"><input type="text" name="q" placeholder="Search requests..." value=""></div>
            <div class="form-group">
                <select name="category">
                    <option value="">All Categories</option>
                    <option value="1">Car Parts &amp; Accessories</option>
                    <option value="2">Construction Materials</option>
                    <option value="3">Printing &amp; Packaging</option>
                </select>
            </div>
            <div class="form-group">
                <select name="location">
                    <option value="">All Locations</option>
                    <option value="1">Bole</option>
                    <option value="2">Kazanchis</option>
                    <option value="3">Piassa</option>
                </select>
            </div>
            <div class="form-group">
                <select name="urgency">
                    <option value="">Any Urgency</option>
                    <option value="today">Urgent: Today</option>
                    <option value="this_week">This Week</option>
                    <option value="flexible">Flexible</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="requests.html" class="btn btn-outline">Clear</a>
        </form>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="grid-2">
            <div class="card request-card urgent-today">
                <div class="card-body">
                    <div class="req-header">
                        <div>
                            <div class="card-title">Need Toyota Vitz 2012 front bumper, new or used</div>
                            <div style="display:flex;gap:6px;margin-top:4px;">
                                <span class="badge urgent-today">Urgent: Today</span>
                                <span class="badge badge-premium">&#11088; Pinned</span>
                            </div>
                        </div>
                    </div>
                    <div class="req-meta">
                        <span><strong>Car Parts &amp; Accessories</strong></span>
                        <span>&#128205; Bole</span>
                        <span>&#128207; 1 piece</span>
                        <span>&#128176; Budget: 3,000 - 5,000 ETB</span>
                        <span>&#9201; 2 hours ago</span>
                    </div>
                    <div class="card-text">Looking for a genuine or good quality aftermarket front bumper for Toyota Vitz 2012 model. Color silver preferred but can repaint. Need it delivered to Bole today if possible.</div>
                    <div style="display:flex;gap:10px;margin-top:16px;">
                        <a href="request-detail.html" class="btn btn-primary">View Details</a>
                        <span style="font-size:0.85rem;color:var(--text-light);display:flex;align-items:center;">3 quotes</span>
                    </div>
                </div>
            </div>
            <div class="card request-card urgent-week">
                <div class="card-body">
                    <div class="req-header">
                        <div>
                            <div class="card-title">Looking for 500 custom paper bags with logo printing</div>
                            <div style="display:flex;gap:6px;margin-top:4px;"><span class="badge urgent-week">This Week</span></div>
                        </div>
                    </div>
                    <div class="req-meta">
                        <span><strong>Printing &amp; Packaging</strong></span>
                        <span>&#128205; Merkato</span>
                        <span>&#128207; 500 bags</span>
                        <span>&#128176; Budget: 15,000 ETB</span>
                        <span>&#9201; 5 hours ago</span>
                    </div>
                    <div class="card-text">Need kraft paper bags with our company logo printed in 2 colors. Size 30x40cm with handles. Delivery to Kazanchis by Friday.</div>
                    <div style="display:flex;gap:10px;margin-top:16px;">
                        <a href="request-detail.html" class="btn btn-primary">View Details</a>
                        <span style="font-size:0.85rem;color:var(--text-light);display:flex;align-items:center;">1 quote</span>
                    </div>
                </div>
            </div>
            <div class="card request-card urgent-flex">
                <div class="card-body">
                    <div class="req-header">
                        <div>
                            <div class="card-title">Need 100 bags of cement near CMC</div>
                            <div style="display:flex;gap:6px;margin-top:4px;"><span class="badge urgent-flex">Flexible</span></div>
                        </div>
                    </div>
                    <div class="req-meta">
                        <span><strong>Construction Materials</strong></span>
                        <span>&#128205; CMC</span>
                        <span>&#128207; 100 bags</span>
                        <span>&#128176; Budget: negotiable</span>
                        <span>&#9201; 1 day ago</span>
                    </div>
                    <div class="card-text">Looking for Dangote or Dashen cement. Need delivery to construction site near CMC. Will need regular supply (200+ bags monthly).</div>
                    <div style="display:flex;gap:10px;margin-top:16px;">
                        <a href="request-detail.html" class="btn btn-primary">View Details</a>
                        <span style="font-size:0.85rem;color:var(--text-light);display:flex;align-items:center;">5 quotes</span>
                    </div>
                </div>
            </div>
            <div class="card request-card urgent-flex">
                <div class="card-body">
                    <div class="req-header">
                        <div>
                            <div class="card-title">Need 30 office chairs for a company</div>
                            <div style="display:flex;gap:6px;margin-top:4px;"><span class="badge urgent-flex">Flexible</span></div>
                        </div>
                    </div>
                    <div class="req-meta">
                        <span><strong>Furniture</strong></span>
                        <span>&#128205; Kazanchis</span>
                        <span>&#128207; 30 chairs</span>
                        <span>&#128176; Budget: 45,000 ETB</span>
                        <span>&#9201; 2 days ago</span>
                    </div>
                    <div class="card-text">Looking for ergonomic office chairs with wheels and adjustable height. Black or grey color. Need delivery and assembly at our office in Kazanchis.</div>
                    <div style="display:flex;gap:10px;margin-top:16px;">
                        <a href="request-detail.html" class="btn btn-primary">View Details</a>
                        <span style="font-size:0.85rem;color:var(--text-light);display:flex;align-items:center;">2 quotes</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
""" + footer
with open('preview/requests.html','w',encoding='utf-8') as f:
    f.write(requests)
print('requests done')
# ------------------------------------------------------------------
# REQUEST DETAIL
# ------------------------------------------------------------------
rdetail = head + '<title>Request Details - EthioMarket</title></head><body>' + nav + """
<section class="section" style="background:#fff;">
    <div class="container">
        <div class="grid-2" style="align-items:start;">
            <div>
                <div style="margin-bottom:16px;">
                    <a href="requests.html" style="font-size:0.9rem;color:var(--text-light);">&#8592; Back to Request Board</a>
                </div>
                <h1 style="font-size:1.6rem;font-weight:800;margin-bottom:10px;">Need Toyota Vitz 2012 front bumper, new or used</h1>
                <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
                    <span class="badge urgent-today">Urgent: Today</span>
                    <span class="badge badge-open">Open</span>
                    <span class="badge badge-premium">&#11088; Pinned</span>
                </div>
                <div class="profile-section">
                    <div style="display:grid;gap:14px;font-size:0.95rem;">
                        <div><strong style="color:var(--text-light);font-weight:500;">Category:</strong> Car Parts &amp; Accessories</div>
                        <div><strong style="color:var(--text-light);font-weight:500;">Location:</strong> Bole, Addis Ababa</div>
                        <div><strong style="color:var(--text-light);font-weight:500;">Quantity:</strong> 1 piece</div>
                        <div><strong style="color:var(--text-light);font-weight:500;">Budget:</strong> 3,000 - 5,000 ETB</div>
                        <div><strong style="color:var(--text-light);font-weight:500;">Posted:</strong> 2 hours ago</div>
                    </div>
                </div>
                <div class="profile-section">
                    <h3>Description</h3>
                    <p style="white-space:pre-wrap;">Looking for a genuine or good quality aftermarket front bumper for Toyota Vitz 2012 model. Color silver preferred but can repaint if needed.</p>
                    <p>Need it delivered to Bole today if possible. Please include installation if available. Also interested in left headlight if you have it in stock.</p>
                </div>
                <div class="profile-section" style="background:#eff6ff;">
                    <p><strong>Contact hidden.</strong> Log in to see buyer contact details.</p>
                </div>
            </div>
            <div>
                <div class="profile-section" style="background:#eff6ff;">
                    <p><a href="login.html">Log in</a> as a supplier to send a quote.</p>
                </div>
                <div class="profile-section">
                    <h3>Quotes Received (3)</h3>
                    <div style="padding:16px 0;border-bottom:1px solid var(--border);">
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                            <strong><a href="supplier.html">ABC Auto Parts</a></strong>
                            <span style="font-size:0.82rem;color:var(--text-light);">45 min ago</span>
                        </div>
                        <div style="font-size:0.9rem;margin-bottom:6px;">
                            <span style="color:var(--success);font-weight:700;">4,500 ETB</span>
                            <span style="color:var(--text-light);margin-left:10px;">Same day delivery</span>
                        </div>
                        <p style="font-size:0.88rem;color:var(--text);">We have genuine Toyota Vitz 2012 front bumper in silver color in stock. Price includes delivery to Bole. Installation available for additional 500 ETB.</p>
                        <div style="display:flex;gap:8px;margin-top:10px;"><a href="#" class="btn btn-sm btn-success">WhatsApp</a></div>
                    </div>
                    <div style="padding:16px 0;border-bottom:1px solid var(--border);">
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                            <strong><a href="#">Toyota Parts Merkato</a></strong>
                            <span style="font-size:0.82rem;color:var(--text-light);">1 hour ago</span>
                        </div>
                        <div style="font-size:0.9rem;margin-bottom:6px;">
                            <span style="color:var(--success);font-weight:700;">3,200 ETB</span>
                            <span style="color:var(--text-light);margin-left:10px;">2 hours delivery</span>
                        </div>
                        <p style="font-size:0.88rem;color:var(--text);">Aftermarket bumper available in black. We can arrange painting to silver for 400 ETB extra. Located in Merkato, fast delivery to Bole.</p>
                        <div style="display:flex;gap:8px;margin-top:10px;"><a href="#" class="btn btn-sm btn-success">WhatsApp</a></div>
                    </div>
                    <div style="padding:16px 0;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
                            <strong><a href="#">Bole Garage Supply</a></strong>
                            <span style="font-size:0.82rem;color:var(--text-light);">2 hours ago</span>
                        </div>
                        <div style="font-size:0.9rem;margin-bottom:6px;">
                            <span style="color:var(--success);font-weight:700;">5,000 ETB</span>
                            <span style="color:var(--text-light);margin-left:10px;">Immediate pickup</span>
                        </div>
                        <p style="font-size:0.88rem;color:var(--text);">Genuine Toyota part with warranty. Silver color available. Can also supply headlight for 2,800 ETB. Package deal available.</p>
                        <div style="display:flex;gap:8px;margin-top:10px;"><a href="#" class="btn btn-sm btn-success">WhatsApp</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
""" + footer
with open('preview/request-detail.html','w',encoding='utf-8') as f:
    f.write(rdetail)
print('request-detail done')
# ------------------------------------------------------------------
# PRICING
# ------------------------------------------------------------------
pricing = head + '<title>Pricing - EthioMarket</title></head><body>' + nav + """
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
                    <li>&#10003; Basic business listing</li>
                    <li>&#10003; 1 photo</li>
                    <li>&#10003; Appear in search</li>
                    <li>&#10003; Receive quote requests</li>
                    <li>&#10007; No verification badge</li>
                </ul>
                <a href="register.html" class="btn btn-outline" style="width:100%;">Get Started</a>
            </div>
            <div class="pricing-card featured">
                <div class="plan-name">Verified</div>
                <div class="plan-price">500 ETB</div>
                <div class="plan-period">per month</div>
                <ul>
                    <li>&#10003; Verified badge</li>
                    <li>&#10003; Up to 5 photos</li>
                    <li>&#10003; Priority in search</li>
                    <li>&#10003; Phone &amp; WhatsApp buttons</li>
                    <li>&#10003; Access to request board</li>
                    <li>&#10003; Review collection</li>
                </ul>
                <a href="register.html" class="btn btn-accent" style="width:100%;">Get Verified</a>
            </div>
            <div class="pricing-card">
                <div class="plan-name">Premium</div>
                <div class="plan-price">1,500 ETB</div>
                <div class="plan-period">per month</div>
                <ul>
                    <li>&#10003; Everything in Verified</li>
                    <li>&#10003; Featured in category</li>
                    <li>&#10003; Up to 10 photos</li>
                    <li>&#10003; Top search results</li>
                    <li>&#10003; Analytics dashboard</li>
                    <li>&#10003; Dedicated support</li>
                </ul>
                <a href="register.html" class="btn btn-primary" style="width:100%;">Go Premium</a>
            </div>
            <div class="pricing-card">
                <div class="plan-name">Enterprise</div>
                <div class="plan-price">Custom</div>
                <div class="plan-period">Contact us</div>
                <ul>
                    <li>&#10003; Everything in Premium</li>
                    <li>&#10003; Multiple branches</li>
                    <li>&#10003; Unlimited photos</li>
                    <li>&#10003; API access (future)</li>
                    <li>&#10003; Account manager</li>
                    <li>&#10003; Custom branding</li>
                </ul>
                <a href="#" class="btn btn-outline" style="width:100%;">Contact on Telegram</a>
            </div>
        </div>
        <div style="max-width:600px;margin:40px auto;text-align:center;color:var(--text-light);">
            <h3 style="color:var(--text);margin-bottom:12px;">How to Pay</h3>
            <p style="margin-bottom:16px;">We accept payment via Telebirr, bank transfer, or cash. Contact us on Telegram or WhatsApp to activate your plan after payment.</p>
            <div style="display:flex;gap:12px;justify-content:center;">
                <a href="https://t.me/yourusername" target="_blank" class="contact-btn contact-telegram">Telegram</a>
                <a href="https://wa.me/251xxxxxxxxx" target="_blank" class="contact-btn contact-whatsapp">WhatsApp</a>
            </div>
        </div>
    </div>
</section>
""" + footer
with open('preview/pricing.html','w',encoding='utf-8') as f:
    f.write(pricing)
print('pricing done')
# ------------------------------------------------------------------
# LOGIN
# ------------------------------------------------------------------
login = head + '<title>Log In - EthioMarket</title></head><body>' + nav + """
<section class="section">
    <div class="auth-box">
        <h2>Log In</h2>
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
            Don't have an account? <a href="register.html">Sign up</a>
        </div>
    </div>
</section>
""" + footer
with open('preview/login.html','w',encoding='utf-8') as f:
    f.write(login)

# ------------------------------------------------------------------
# REGISTER
# ------------------------------------------------------------------
register = head + '<title>Sign Up - EthioMarket</title></head><body>' + nav + """
<section class="section">
    <div class="auth-box">
        <h2>Create Account</h2>
        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="tel" name="phone" placeholder="e.g., 0911xxxxxx" required>
            </div>
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required>
                <div class="form-hint">Min 6 characters</div>
            </div>
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;">Create Account</button>
        </form>
        <div class="auth-links">
            Already have an account? <a href="login.html">Log in</a>
        </div>
    </div>
</section>
""" + footer
with open('preview/register.html','w',encoding='utf-8') as f:
    f.write(register)

# ------------------------------------------------------------------
# POST REQUEST
# ------------------------------------------------------------------
postreq = head + '<title>Post a Request - EthioMarket</title></head><body>' + nav + """
<section class="section" style="max-width:720px;margin:0 auto;">
    <div class="auth-box">
        <h2>Post a Request</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>What are you looking for? *</label>
                <input type="text" name="title" placeholder="e.g., Need Toyota Vitz 2012 front bumper" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="">Select category</option>
                        <option value="1">Car Parts &amp; Accessories</option>
                        <option value="2">Construction Materials</option>
                        <option value="3">Printing &amp; Packaging</option>
                        <option value="4">Hotel &amp; Restaurant Supplies</option>
                        <option value="5">Office Supplies</option>
                        <option value="6">Furniture</option>
                        <option value="7">Electronics</option>
                        <option value="8">Machinery &amp; Tools</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Location</label>
                    <select name="location">
                        <option value="">Select location</option>
                        <option value="1">Bole</option>
                        <option value="2">Kazanchis</option>
                        <option value="3">Piassa</option>
                        <option value="4">Merkato</option>
                        <option value="5">CMC</option>
                        <option value="6">Megenagna</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Quantity (optional)</label>
                    <input type="text" name="quantity" placeholder="e.g., 500 bags, 30 chairs">
                </div>
                <div class="form-group">
                    <label>Budget (optional)</label>
                    <input type="text" name="budget" placeholder="e.g., 50,000 ETB">
                </div>
            </div>
            <div class="form-group">
                <label>Urgency</label>
                <select name="urgency">
                    <option value="flexible" selected>Flexible</option>
                    <option value="this_week">This Week</option>
                    <option value="today">Urgent: Today</option>
                </select>
            </div>
            <div class="form-group">
                <label>Description *</label>
                <textarea name="description" rows="5" placeholder="Describe exactly what you need, specifications, quality, etc." required></textarea>
            </div>
            <div class="form-group">
                <label>Upload Photo (optional)</label>
                <input type="file" name="photo" accept="image/*">
                <div class="form-hint">JPG, PNG up to 5MB</div>
            </div>
            <div class="form-group">
                <label>Contact Method *</label>
                <select name="contact_method">
                    <option value="phone">Phone</option>
                    <option value="whatsapp">WhatsApp</option>
                    <option value="telegram">Telegram</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="form-group">
                <label>Your Phone *</label>
                <input type="text" name="contact_value" placeholder="e.g., 0911xxxxxx or @username" required>
            </div>
            <div class="form-group">
                <label>Privacy</label>
                <select name="privacy">
                    <option value="public">Public - anyone can see (contact hidden until logged in)</option>
                    <option value="private">Private - only verified suppliers can respond</option>
                </select>
            </div>
            <button type="submit" class="btn btn-accent" style="width:100%;">Post Request</button>
        </form>
    </div>
</section>
""" + footer
with open('preview/post-request.html','w',encoding='utf-8') as f:
    f.write(postreq)

# ------------------------------------------------------------------
# DASHBOARD
# ------------------------------------------------------------------
dash = head + '<title>Dashboard - EthioMarket</title></head><body>' + nav + """
<section class="section" style="padding-top:20px;">
    <div class="container">
        <div class="dashboard-grid">
            <div class="sidebar">
                <div style="margin-bottom:20px;padding-bottom:16px;border-bottom:1px solid var(--border);">
                    <div style="font-weight:700;">Abebe Kebede</div>
                    <div style="font-size:0.82rem;color:var(--text-light);text-transform:capitalize;">buyer</div>
                </div>
                <a href="#" class="active">&#128202; Overview</a>
                <a href="requests.html">&#128230; My Requests</a>
                <a href="directory.html">&#10084; Saved Suppliers</a>
                <a href="#">&#127970; Business Profile</a>
                <a href="pricing.html">&#128179; Upgrade Plan</a>
            </div>
            <div class="dashboard-content">
                <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:20px;">Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">3</div>
                        <div class="stat-label">Requests Posted</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">5</div>
                        <div class="stat-label">Saved Suppliers</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">12</div>
                        <div class="stat-label">Quotes Received</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">0</div>
                        <div class="stat-label">Profile Views</div>
                    </div>
                </div>
                <div class="profile-section" style="background:#eff6ff;">
                    <h3>Start Selling</h3>
                    <p>You haven't listed your business yet. <a href="#">Create your supplier profile</a> to start receiving buyer requests.</p>
                </div>
            </div>
        </div>
    </div>
</section>
""" + footer
with open('preview/dashboard.html','w',encoding='utf-8') as f:
    f.write(dash)

print('login register post-request dashboard done')
