import os
pages = ["index.html", "directory.html", "requests.html", "pricing.html", "login.html", "register.html", "post-request.html", "supplier.html", "request-detail.html", "categories.html", "dashboard.html"]
for p in pages:
    path = os.path.join(r"C:\Users\mudim\Music\directory\preview", p)
    if os.path.exists(path):
        with open(path, "r", encoding="utf-8") as f:
            html = f.read()
        html = html.replace("family=Inter:wght@400;500;600;700", "family=Inter:wght@300;400;500;600;700;800;900")
        with open(path, "w", encoding="utf-8") as f:
            f.write(html)
        print(p + " font updated")

