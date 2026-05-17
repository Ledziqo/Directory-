import sys, re
sys.path.insert(0, r'C:\Users\mudim\Music\directory')
from gen4 import ICONS

with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'r', encoding='utf-8') as f:
    html = f.read()

# Fix hero size
html = html.replace('font-size:2.4rem;font-weight:800', 'font-size:2.8rem;font-weight:900;letter-spacing:-1px')

# Fix View All categories link
html = html.replace('href="directory.html">View All', 'href="categories.html">View All', 1)

# Replace emoji category icons with SVG icon + styled layout
# Find all emoji category cards
emojis = [
    '&#128663;',
    '&#127959;',
    '&#128230;',
    '&#127976;',
    '&#128221;',
    '&#128719;',
    '&#128187;',
    '&#128295;',
]

# Find the grid-4 categories section
cat_section_start = html.find('<div class="grid-4">')
cat_section_end = html.find('</div>', cat_section_start)
cat_section = html[cat_section_start:cat_section_end+6]

# Rebuild category cards with SVG icons
cat_data = [
    ('Car Parts &amp; Accessories', 'car'),
    ('Construction Materials', 'construction'),
    ('Printing &amp; Packaging', 'print'),
    ('Hotel &amp; Restaurant Supplies', 'hotel'),
    ('Office Supplies', 'office'),
    ('Furniture', 'furniture'),
    ('Electronics', 'electronics'),
    ('Machinery &amp; Tools', 'tools'),
]

new_cards = ''
for name, key in cat_data:
    icon = ICONS.get(key, ICONS.get('services'))
    new_cards += '            <a href="directory.html" class="card"><div class="card-body" style="display:flex;align-items:center;gap:14px;padding:16px 18px;"><span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">' + icon + '</span><span style="font-weight:600;font-size:0.95rem;color:#0f172a;">' + name + '</span></div></a>\n'

# Find the old grid-4 content and replace
old_grid_start = html.find('<div class="grid-4">')
old_grid_end = html.find('</div>', old_grid_start + 20) + 6
html = html[:old_grid_start] + '<div class="grid-4">\n' + new_cards + '            </div>' + html[old_grid_end:]

# Add Why EthioMarket section before Latest Buyer Requests
why = '''<section class="section" style="background:#fff;">
    <div class="container">
        <div class="section-header">
            <h2>Why EthioMarket?</h2>
            <p>The smarter way to find suppliers and get quotes in Addis Ababa</p>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;">
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;">
                <span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">&#10003;</span>
                <div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Verified Businesses</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Every supplier is manually reviewed and verified before appearing.</p></div>
            </div>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;">
                <span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">&#9993;</span>
                <div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Direct Communication</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Contact suppliers directly via WhatsApp, Telegram, or phone.</p></div>
            </div>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;">
                <span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">&#9998;</span>
                <div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Compare Quotes</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Receive offers from multiple suppliers and choose the best deal.</p></div>
            </div>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px;display:flex;gap:14px;align-items:flex-start;">
                <span style="flex-shrink:0;width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;">&#9675;</span>
                <div><h4 style="font-weight:700;color:#0f172a;margin-bottom:4px;">Save Time</h4><p style="font-size:0.88rem;color:#64748b;line-height:1.5;">Stop calling 20 shops. Post once and let verified sellers come to you.</p></div>
            </div>
        </div>
    </div>
</section>
'''

if 'Why EthioMarket' not in html:
    br_pos = html.find('<!-- Latest Buyer Requests -->')
    if br_pos > 0:
        html = html[:br_pos] + why + html[br_pos:]

with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'w', encoding='utf-8') as f:
    f.write(html)
print('done')
