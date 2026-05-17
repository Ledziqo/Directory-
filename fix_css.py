import re

with open(r"C:\Users\mudim\Music\directory\assets\css\style.css", "r", encoding="utf-8") as f:
    css = f.read()

# Bigger card radius and shadows
css = css.replace("border-radius: var(--radius);", "border-radius: 14px;")
css = css.replace("--shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);", "--shadow: 0 8px 24px -4px rgba(0,0,0,0.08); --shadow-lg: 0 20px 40px -8px rgba(0,0,0,0.12);")

# Hero gradient
css = css.replace("background: linear-gradient(180deg, var(--primary) 0%, #0a1f33 100%);", "background: linear-gradient(135deg, var(--primary) 0%, #1a3d5c 50%, #0a1f33 100%); position: relative; overflow: hidden;")

# Bigger hero heading
css = css.replace("font-size: 2.4rem;", "font-size: 2.8rem; letter-spacing: -1px;")

# Search bar glass effect
css = css.replace("background: var(--card-bg);\n    padding: 24px 0;", "background: var(--card-bg);\n    padding: 28px 0;\n    box-shadow: 0 2px 12px rgba(0,0,0,0.04);")

# Section padding
css = css.replace("padding: 48px 16px;", "padding: 64px 16px;")

# Button hover lift
css = css.replace(".btn-primary:hover { background: var(--primary-light); }", ".btn-primary:hover { background: var(--primary-light); transform: translateY(-1px); box-shadow: 0 8px 16px -4px rgba(15,42,67,0.25); }")
css = css.replace(".btn-accent:hover { background: var(--accent-hover); }", ".btn-accent:hover { background: var(--accent-hover); transform: translateY(-1px); box-shadow: 0 8px 16px -4px rgba(37,99,235,0.25); }")

# Card hover
css = css.replace(".grid-3 {", ".grid-3 > a > div, .grid-4 > a > div { transition: all 0.3s ease; }\n.grid-3 > a:hover > div, .grid-4 > a:hover > div { box-shadow: 0 20px 40px -12px rgba(37,99,235,0.1); transform: translateY(-3px); border-color: #2563EB; }\n.grid-3 {")

# Footer gradient
css = css.replace("background: var(--primary);", "background: linear-gradient(135deg, var(--primary) 0%, #1a3d5c 100%);")

with open(r"C:\Users\mudim\Music\directory\assets\css\style.css", "w", encoding="utf-8") as f:
    f.write(css)
print("css modernized")

