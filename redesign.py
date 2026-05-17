import sys
sys.path.insert(0, r"C:\Users\mudim\Music\directory")
from gen4 import ICONS

with open(r"C:\Users\mudim\Music\directory\preview\index.html", "r", encoding="utf-8") as f:
    html = f.read()

old_style_start = html.find("<style>")
old_style_end = html.find("</style>") + len("</style>")

new_style = """<style>
:root{--navy:#0B1F33;--accent:#2563EB;--card:#fff;--text:#0f172a;--text2:#64748b;--border:#e2e8f0;--bg2:#F0F4F8;}
body{font-family:Inter,sans-serif;background:var(--bg2);color:var(--text);}
.hero-new{background:linear-gradient(135deg,#0B1F33 0%,#0F2A43 40%,#1a3d5c 70%,#0B1F33 100%);position:relative;overflow:hidden;padding:100px 16px 80px;text-align:center;}
.hero-new::before{content:"";position:absolute;top:-200px;right:-200px;width:600px;height:600px;background:radial-gradient(circle,rgba(37,99,235,0.25) 0%,transparent 60%);border-radius:50%;animation:float 8s ease-in-out infinite;}
.hero-new::after{content:"";position:absolute;bottom:-150px;left:-150px;width:500px;height:500px;background:radial-gradient(circle,rgba(5,150,105,0.15) 0%,transparent 60%);border-radius:50%;animation:float 10s ease-in-out infinite reverse;}
@keyframes float{0%,100%{transform:translate(0,0) scale(1);}50%{transform:translate(30px,-30px) scale(1.05);}}
.hero-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.04) 1px,transparent 1px);background-size:60px 60px;pointer-events:none;}
.hero-search{position:relative;z-index:2;max-width:680px;margin:32px auto 0;background:rgba(255,255,255,0.1);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.2);border-radius:16px;padding:6px;display:flex;gap:6px;flex-wrap:wrap;}
.hero-search input{flex:1;min-width:220px;padding:14px 18px;border:none;border-radius:12px;font-size:1rem;background:rgba(255,255,255,0.95);outline:none;}
.hero-search select{padding:14px 16px;border:none;border-radius:12px;font-size:0.95rem;background:rgba(255,255,255,0.95);outline:none;cursor:pointer;}
.hero-search button{padding:14px 28px;border:none;border-radius:12px;font-size:0.95rem;font-weight:600;background:var(--accent);color:#fff;cursor:pointer;transition:all 0.2s;}
.hero-search button:hover{background:#1d4ed8;transform:translateY(-1px);}
.trust-pills{display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-top:28px;position:relative;z-index:2;}
.trust-pill{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:999px;font-size:0.82rem;color:rgba(255,255,255,0.9);backdrop-filter:blur(4px);}
.section-pad{padding:72px 16px;}
.section-header{text-align:center;max-width:600px;margin:0 auto 48px;}
.section-header h2{font-size:2rem;font-weight:800;color:var(--text);margin-bottom:12px;letter-spacing:-0.5px;}
.section-header p{color:var(--text2);font-size:1.05rem;line-height:1.6;}
.stats-new{background:var(--card);border-bottom:1px solid var(--border);padding:32px 16px;position:relative;}
.stats-new::after{content:"";position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:80%;height:1px;background:linear-gradient(90deg,transparent,var(--border),transparent);}
.stats-grid-new{display:grid;grid-template-columns:repeat(4,1fr);gap:24px;max-width:900px;margin:0 auto;text-align:center;}
.stat-num{font-size:2rem;font-weight:800;background:linear-gradient(135deg,var(--navy),var(--accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;}
.stat-desc{font-size:0.88rem;color:var(--text2);margin-top:6px;font-weight:500;}
.card-modern{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:24px;transition:all 0.3s cubic-bezier(0.4,0,0.2,1);position:relative;overflow:hidden;}
.card-modern:hover{border-color:var(--accent);box-shadow:0 20px 40px -12px rgba(37,99,235,0.15),0 4px 12px -4px rgba(0,0,0,0.05);transform:translateY(-4px);}
.card-modern::before{content:"";position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--accent),#3b82f6);opacity:0;transition:opacity 0.3s;}
.card-modern:hover::before{opacity:1;}
.cat-card{display:flex;align-items:center;gap:14px;padding:16px 18px;background:var(--card);border:1px solid var(--border);border-radius:14px;text-decoration:none;color:var(--text);transition:all 0.25s ease;font-weight:500;font-size:0.95rem;}
.cat-card:hover{border-color:var(--accent);box-shadow:0 8px 24px -8px rgba(37,99,235,0.15);transform:translateY(-2px);color:var(--accent);}
.cat-icon-wrap{width:42px;height:42px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all 0.25s;}
.cat-card:hover .cat-icon-wrap{background:linear-gradient(135deg,var(--accent),#3b82f6);}
.cat-card:hover .cat-icon-wrap svg{stroke:#fff !important;}
.step-new{background:var(--card);border:1px solid var(--border);border-radius:20px;padding:36px 28px;text-align:center;transition:all 0.3s ease;position:relative;}
.step-new:hover{box-shadow:0 20px 40px -12px rgba(0,0,0,0.08);transform:translateY(-4px);border-color:var(--accent);}
.step-num{width:52px;height:52px;background:linear-gradient(135deg,var(--accent),#3b82f6);border-radius:14px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.2rem;margin-bottom:20px;box-shadow:0 8px 20px -4px rgba(37,99,235,0.4);}
.req-tag{display:inline-flex;align-items:center;padding:4px 12px;border-radius:999px;font-size:0.78rem;font-weight:600;}
.req-tag-urgent{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;}
.req-tag-week{background:#fff7ed;color:#ea580c;border:1px solid #fed7aa;}
.req-tag-flex{background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;}
.req-meta{display:flex;flex-wrap:wrap;gap:10px;font-size:0.82rem;color:var(--text2);margin:10px 0;}
.req-meta span{display:flex;align-items:center;gap:4px;}
.sup-avatar{width:48px;height:48px;background:linear-gradient(135deg,#f1f5f9,#e2e8f0);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;color:var(--navy);}
.badge-verified{display:inline-flex;align-items:center;gap:3px;padding:3px 10px;background:linear-gradient(135deg,#fffbeb,#fef3c7);color:#b45309;border:1px solid #fcd34d;border-radius:999px;font-size:0.72rem;font-weight:600;}
.badge-featured{display:inline-flex;align-items:center;gap:3px;padding:3px 10px;background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#1e40af;border:1px solid #bfdbfe;border-radius:999px;font-size:0.72rem;font-weight:600;}
.cta-modern{background:linear-gradient(135deg,#0B1F33 0%,#1a3d5c 50%,#0F2A43 100%);border-radius:24px;padding:64px 40px;text-align:center;position:relative;overflow:hidden;margin:0 16px;}
.cta-modern::before{content:"";position:absolute;top:-80px;right:-80px;width:300px;height:300px;background:radial-gradient(circle,rgba(37,99,235,0.3) 0%,transparent 70%);border-radius:50%;}
.cta-modern::after{content:"";position:absolute;bottom:-60px;left:-60px;width:200px;height:200px;background:radial-gradient(circle,rgba(5,150,105,0.2) 0%,transparent 70%);border-radius:50%;}
.why-card{background:var(--card);border:1px solid var(--border);border-radius:16px;padding:28px;display:flex;gap:16px;align-items:flex-start;transition:all 0.25s;}
.why-card:hover{box-shadow:0 12px 32px -8px rgba(0,0,0,0.08);transform:translateY(-2px);}
.why-icon{width:44px;height:44px;background:linear-gradient(135deg,#eff6ff,#dbeafe);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--accent);}
.btn-pill{display:inline-flex;align-items:center;gap:6px;padding:12px 28px;border-radius:12px;font-weight:600;font-size:0.95rem;cursor:pointer;border:none;transition:all 0.2s;text-decoration:none;}
.btn-pill:hover{transform:translateY(-2px);box-shadow:0 8px 20px -4px rgba(0,0,0,0.15);}
.btn-blue{background:var(--accent);color:#fff;}
.btn-blue:hover{background:#1d4ed8;}
.btn-outline-new{background:transparent;color:var(--text);border:1.5px solid var(--border);}
.btn-outline-new:hover{border-color:var(--accent);color:var(--accent);background:#eff6ff;}
.bg-white{background:var(--card);}
.bg-soft{background:var(--bg2);}
@media(max-width:768px){.hero-new{padding:64px 16px 48px;}.stats-grid-new{grid-template-columns:repeat(2,1fr);}.section-header h2{font-size:1.5rem;}.cta-modern{padding:48px 24px;border-radius:20px;}}
</style>"""

html = html[:old_style_start] + new_style + html[old_style_end:]

nav_end = html.find("<div class=\"main-content\">") + len("<div class=\"main-content\">")
footer_start = html.rfind("<footer")

body_content = """""
