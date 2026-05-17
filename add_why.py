h=open(r"C:\Users\mudim\Music\directory\preview\index.html","r",encoding="utf-8").read()
if "Why EthioMarket" not in h:
    p=h.find("Latest Buyer Requests")
    if p>0:
        sec=h.rfind("<section",0,p)
        why="<section class=\"section\" style=\"background:#fff;\">"
        h=h[:sec]+why+h[sec:]
        open(r"C:\Users\mudim\Music\directory\preview\index.html","w",encoding="utf-8").write(h)
        print("ok")
