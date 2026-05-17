import os
pages = ["directory.html", "requests.html", "pricing.html", "login.html", "register.html", "post-request.html", "supplier.html", "request-detail.html", "categories.html", "dashboard.html"]
for p in pages:
    path = os.path.join(r"C:\Users\mudim\Music\directory\preview", p)
    if os.path.exists(path):
        with open(path, "r", encoding="utf-8") as f:
            html = f.read()
        if "modern.js" not in html:
            html = html.replace("</head>", "<script src=\"../assets/js/modern.js\"></script></head>")
            with open(path, "w", encoding="utf-8") as f:
                f.write(html)
            print(p + " updated")
        else:
            print(p + " already has it")

