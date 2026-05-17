import re, sys
sys.path.insert(0, r'C:\Users\mudim\Music\directory')
from gen4 import ICONS

def cat_link(name, icon_key):
    icon = ICONS.get(icon_key, ICONS.get('services'))
    return (
        '<a href="directory.html" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;transition:all 0.2s;text-decoration:none;color:#111827;">'
        '<span style="flex-shrink:0;">' + icon + '</span>'
        '<span style="font-size:0.92rem;font-weight:500;">' + name + '</span>'
        '</a>'
    )

categories = [
    ("Car Parts & Auto", "car"),
    ("Construction Materials", "construction"),
    ("Printing & Packaging", "print"),
    ("Hotel & Restaurant", "hotel"),
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

with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'r', encoding='utf-8') as f:
    html = f.read()

# Replace Categories section (from <!-- Categories --> to <!-- Buyer Requests -->)
old_cat = re.search(r'<!-- Categories -->.*?<!-- Buyer Requests -->', html, re.DOTALL)
if old_cat:
    cat_items = '\n'.join([cat_link(name, key) for name, key in categories[:12]])
    new_cat = '''<!-- Categories -->
<section class="section-dots" style="padding:56px 16px;">
    <div class="container">
        <div style="text-align:center;max-width:560px;margin:0 auto 36px;">
            <h2 style="font-size:1.6rem;font-weight:700;color:#0f172a;margin-bottom:8px;">Popular Categories</h2>
            <p style="color:#64748b;font-size:0.95rem;">Find verified suppliers across products and services in Addis Ababa</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;">
''' + cat_items + '''
        </div>
        <div style="text-align:center;margin-top:28px;">
            <a href="categories.html" class="btn btn-outline">View All Categories</a>
        </div>
    </div>
</section>

<!-- Buyer Requests -->'''
    html = html[:old_cat.start()] + new_cat + html[old_cat.end():]
    print('categories replaced')
else:
    print('categories NOT found')

# Replace How It Works section
old_hiw = re.search(r'<!-- How It Works -->.*?(?=<!--|$)', html, re.DOTALL)
if old_hiw:
    new_hiw = '''<!-- How It Works -->
<section class="section-lines" style="padding:56px 16px;">
    <div class="container">
        <div style="text-align:center;max-width:560px;margin:0 auto 36px;">
            <h2 style="font-size:1.6rem;font-weight:700;color:#0f172a;margin-bottom:8px;">How It Works</h2>
            <p style="color:#64748b;font-size:0.95rem;">Get what you need in three simple steps</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;">
            <div class="step-card">
                <div class="step-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <h3 style="font-size:1.1rem;font-weight:600;color:#0f172a;margin-bottom:8px;">Search or Post</h3>
                <p style="font-size:0.9rem;color:#64748b;line-height:1.5;">Browse verified suppliers or post exactly what you need with your budget and timeline.</p>
            </div>
            <div class="step-card">
                <div class="step-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <h3 style="font-size:1.1rem;font-weight:600;color:#0f172a;margin-bottom:8px;">Get Quotes</h3>
                <p style="font-size:0.9rem;color:#64748b;line-height:1.5;">Verified suppliers view your request and reply with prices, availability, and delivery options.</p>
            </div>
            <div class="step-card">
                <div class="step-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h3 style="font-size:1.1rem;font-weight:600;color:#0f172a;margin-bottom:8px;">Compare & Choose</h3>
                <p style="font-size:0.9rem;color:#64748b;line-height:1.5;">Compare quotes, check reviews, and contact your chosen supplier via WhatsApp or Telegram.</p>
            </div>
        </div>
    </div>
</section>

<div class="section-divider" style="max-width:200px;margin:0 auto;"></div>
'''
    # Find the end of How It Works section - it goes until the next major section or footer
    end_match = re.search(r'\n\s*(?=<!-- (?:Featured|CTA|Footer)|<footer)', html[old_hiw.start():], re.DOTALL)
    if end_match:
        end_pos = old_hiw.start() + end_match.start()
        html = html[:old_hiw.start()] + new_hiw + html[end_pos:]
        print('how it works replaced')
    else:
        print('how it works end NOT found')
else:
    print('how it works NOT found')

with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'w', encoding='utf-8') as f:
    f.write(html)
print('index.html updated')
