text = open(r'C:\Users\mudim\Music\directory\assets\css\style.css', 'r', encoding='utf-8').read()
text = text.replace("content: '\\u2713';", "content: '\\2713';")
open(r'C:\Users\mudim\Music\directory\assets\css\style.css', 'w', encoding='utf-8').write(text)
print('fixed css')
