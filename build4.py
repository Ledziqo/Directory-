import sys
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

items = '\n'.join([cat_link(name, key) for name, key in categories])

with open(r'C:\Users\mudim\Music\directory\preview\categories.html', 'r', encoding='utf-8') as f:
    old = f.read()

# Find the grid div in categories and replace its contents
import re
m = re.search(r'(<div style="display:grid;grid-template-columns:repeat\(auto-fill,minmax\(260px,1fr\)\);gap:10px;">)(.*?)(</div>)', old, re.DOTALL)
if m:
    new_html = old[:m.start(2)] + '\n' + items + '\n        ' + old[m.end(2):]
    with open(r'C:\Users\mudim\Music\directory\preview\categories.html', 'w', encoding='utf-8') as f:
        f.write(new_html)
    print('categories.html updated with', len(categories), 'categories')
else:
    print('grid not found')
