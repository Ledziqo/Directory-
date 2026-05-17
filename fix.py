import sys
sys.path.insert(0, r"C:\Users\mudim\Music\directory")
from gen4 import ICONS

with open(r"C:\Users\mudim\Music\directory\preview\index.html", "r", encoding="utf-8") as fh:
    html = fh.read()

cat_icons = [
    ("Car Parts", "car"), ("Construction Materials", "construction"),
    ("Printing", "print"), ("Hotel", "hotel"),
    ("Office Supplies", "office"), ("Furniture", "furniture"),
    ("Electronics", "electronics"), ("Machinery", "tools"),
]
