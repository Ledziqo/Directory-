import os, re, sys
sys.path.insert(0, r'C:\Users\mudim\Music\directory')
from gen4 import ICONS

# Helper to build a category link with SVG icon
def cat_link(name, icon_key):
    icon = ICONS.get(icon_key, ICONS.get('services'))
    return (
        '<a href="directory.html" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fff;border:1px solid #e5e7eb;border-radius:10px;transition:all 0.2s;text-decoration:none;color:#111827;">'
        '<span style="flex-shrink:0;">' + icon + '</span>'
        '<span style="font-size:0.92rem;font-weight:500;">' + name + '</span>'
        '</a>'
    )

# Categories list (25)
categories = [
    ("Car Parts & Auto", "car"),
    ("Construction Materials", "construction"),
    ("Printing & Packaging", "print"),
    ("Hotel & Restaurant Supplies", "hotel"),
    ("Office Supplies", "office"),
    ("Furniture", "furniture"),
    ("Electronics", "electronics"),
    ("Machinery & Tools", "tools"),
    ("Importers & Wholesale", "importer"),
    ("Medical & Health", "medical"),
    ("Cleaning & Janitorial", "cleaning"),
    ("Security Systems", "security"),
    ("IT & Software", "tech"),
    ("Wedding & Events", "wedding"),
    ("Beauty & Salon", "beauty"),
    ("Gym & Fitness", "gym"),
    ("Food & Beverage", "food"),
    ("Transport & Logistics", "logistics"),
    ("Plumbing & Water", "water"),
    ("Metal & Steel", "metal"),
    ("Repair & Maintenance", "repair"),
    ("Solar & Energy", "energy"),
    ("Education & Training", "education"),
    ("Legal & Accounting", "legal"),
    ("Pest Control & Cleaning", "cleaning"),
]

# Read existing index.html
with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'r', encoding='utf-8') as f:
    html = f.read()

# Inject style block before </head>
style_block = '''    <style>
        .hero-modern {
            background: linear-gradient(135deg, #0f2a43 0%, #1a3d5c 50%, #0b1f33 100%);
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .hero-modern::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-modern::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(5,150,105,0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-grid-overlay {
            position: absolute; inset: 0;
            background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }
        .stats-bar {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 24px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            text-align: center;
        }
        .stat-number {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f2a43;
            line-height: 1;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 6px;
        }
        .trust-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: center;
            margin-top: 20px;
        }
        .trust-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 999px;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.9);
        }
        .step-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 28px 24px;
            text-align: center;
            transition: all 0.2s ease;
        }
        .step-card:hover {
            border-color: #2563eb;
            box-shadow: 0 8px 24px rgba(37,99,235,0.08);
            transform: translateY(-2px);
        }
        .step-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            color: #2563eb;
        }
        .section-dots {
            background-color: #f8fafc;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .section-lines {
            background-color: #ffffff;
            background-image: repeating-linear-gradient(45deg, #f1f5f9 0px, #f1f5f9 1px, transparent 1px, transparent 11px);
        }
        .cta-section {
            background: linear-gradient(135deg, #0f2a43 0%, #1e3a5f 100%);
            color: #fff;
            text-align: center;
            padding: 60px 16px;
            border-radius: 16px;
            margin: 0 16px;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
            margin: 0 auto;
            max-width: 200px;
        }
        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
'''

html = html.replace('</head>', style_block + '</head>')

print('style injected')

# Replace hero section
old_hero = re.search(r'<!-- Hero -->.*?<!-- Categories -->', html, re.DOTALL)
if old_hero:
    new_hero = '''<!-- Hero -->
<section class="hero-modern" style="padding: 80px 16px 64px; text-align: center;">
    <div class="hero-grid-overlay"></div>
    <div class="container" style="position: relative; z-index: 1;">
        <h1 style="font-size: 2.5rem; font-weight: 800; line-height: 1.15; margin-bottom: 14px; max-width: 720px; margin-left: auto; margin-right: auto;">
            Find Suppliers. Post Requests. Compare Quotes.
        </h1>
        <p style="font-size: 1.1rem; opacity: 0.85; max-width: 560px; margin: 0 auto 32px;">
            Search verified Ethiopian businesses in Addis Ababa. Or post what you need and let sellers come to you.
        </p>
        <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap; margin-bottom:28px;">
            <a href="directory.html" class="btn btn-lg" style="background:#2563eb; color:#fff;">Search Suppliers</a>
            <a href="post-request.html" class="btn btn-lg" style="background:transparent; color:#fff; border:1.5px solid rgba(255,255,255,0.3);">Post a Request</a>
            <a href="register.html" class="btn btn-lg" style="background:rgba(255,255,255,0.12); color:#fff; border:1.5px solid rgba(255,255,255,0.2);">List Your Business</a>
        </div>
        <div class="trust-row">
            <span class="trust-badge">&#10003; Verified Suppliers</span>
            <span class="trust-badge">&#10003; No Commission</span>
            <span class="trust-badge">&#10003; WhatsApp / Telegram</span>
            <span class="trust-badge">&#10003; Addis Ababa Focused</span>
        </div>
    </div>
</section>

<!-- Search -->
<div class="search-bar">
    <div class="container">
        <form class="search-form" onsubmit="event.preventDefault(); window.location.href='directory.html';">
            <input type="text" placeholder="What do you need? (e.g. cement, car parts, printing)">
            <select><option>Addis Ababa</option><option>Bole</option><option>Merkato</option><option>CMC</option><option>Kazanchis</option><option>Saris</option><option>Piasa</option></select>
            <button type="submit" class="btn btn-accent">Search</button>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="stats-bar">
    <div class="container">
        <div class="stats-grid">
            <div><div class="stat-number">1,200+</div><div class="stat-label">Verified Suppliers</div></div>
            <div><div class="stat-number">3,400+</div><div class="stat-label">Buyer Requests</div></div>
            <div><div class="stat-number">25+</div><div class="stat-label">Categories</div></div>
            <div><div class="stat-number">8,500+</div><div class="stat-label">Quotes Sent</div></div>
        </div>
    </div>
</div>

<!-- Categories -->
'''
    html = html[:old_hero.start()] + new_hero + html[old_hero.end():]
    print('hero replaced')
else:
    print('hero NOT found')

with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'w', encoding='utf-8') as f:
    f.write(html)
print('index.html written')
