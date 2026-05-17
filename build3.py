import re, html as htmlmod

with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'r', encoding='utf-8') as f:
    text = f.read()

# Replace emojis in supplier cards with cleaner text
text = text.replace('&#128205;', '&#127968;')  # keep for now, maybe user just meant ? icons
# Actually let's just remove emoji prefixes and use text separators
# Replace pattern: <span>&#128205; Bole</span> with <span>in Bole</span>
text = re.sub(r'<span>&#128205;\s*([^<<]+)</span>', r'<span>\1</span>', text)
text = re.sub(r'<span>&#11088;\s*([^<<]+)</span>', r'<span>\1</span>', text)

# Replace request card emoji prefixes too
text = text.replace('&#128207;', '')
text = text.replace('&#128176;', '')

# Replace old Featured Suppliers header style and add section background
old_fs = re.search(r'<!-- Featured Suppliers -->.*?(?=<!-- How It Works|<!-- CTA|<footer)', text, re.DOTALL)
if old_fs:
    fs_content = text[old_fs.start():old_fs.end()]
    # Change section background to white with subtle styling
    fs_content = fs_content.replace('style="padding:48px 16px;"', 'style="background:#fff;padding:56px 16px;border-top:1px solid #e5e7eb;"')
    fs_content = fs_content.replace('font-size:1.3rem;', 'font-size:1.6rem;')
    text = text[:old_fs.start()] + fs_content + text[old_fs.end():]
    print('featured suppliers styled')
else:
    print('featured suppliers not found')

# Replace old Buyer Requests header style
old_br = re.search(r'<!-- Buyer Requests -->.*?(?=<!-- Featured Suppliers)', text, re.DOTALL)
if old_br:
    br_content = text[old_br.start():old_br.end()]
    br_content = br_content.replace('background:#fff;padding:48px 16px;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;', 'background:#fff;padding:56px 16px;border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;')
    br_content = br_content.replace('font-size:1.3rem;', 'font-size:1.6rem;')
    text = text[:old_br.start()] + br_content + text[old_br.end():]
    print('buyer requests styled')
else:
    print('buyer requests not found')

# Insert CTA section before footer
cta = '''
<!-- CTA -->
<section style="padding:40px 0 56px;">
    <div class="container">
        <div class="cta-section">
            <h2 style="font-size:1.6rem;font-weight:700;margin-bottom:10px;position:relative;z-index:1;">Are You a Supplier?</h2>
            <p style="opacity:0.85;max-width:460px;margin:0 auto 24px;position:relative;z-index:1;">List your business, get verified, and start receiving quote requests from serious buyers in Addis Ababa.</p>
            <a href="register.html" class="btn btn-lg" style="background:#2563eb;color:#fff;position:relative;z-index:1;">Create Free Profile</a>
        </div>
    </div>
</section>
'''
text = text.replace('<footer class="footer">', cta + '<footer class="footer">')
print('cta inserted')

with open(r'C:\Users\mudim\Music\directory\preview\index.html', 'w', encoding='utf-8') as f:
    f.write(text)
print('index.html done')
