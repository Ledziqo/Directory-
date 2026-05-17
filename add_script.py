html = open(r"C:\Users\mudim\Music\directory\preview\index.html", "r", encoding="utf-8").read()
html = html.replace("<title>EthioMarket - Find Suppliers or Post What You Need</title>", "<title>EthioMarket - Find Suppliers or Post What You Need</title><script src=\"../assets/js/modern.js\"></script>")
open(r"C:\Users\mudim\Music\directory\preview\index.html", "w", encoding="utf-8").write(html)
print("script added")

