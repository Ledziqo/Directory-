import re

files = [
    r'C:\Users\mudim\Music\directory\preview\register.html',
    r'C:\Users\mudim\Music\directory\preview\login.html',
    r'C:\Users\mudim\Music\directory\preview\post-request.html',
]

for path in files:
    with open(path, 'r', encoding='utf-8') as f:
        text = f.read()

    # Find the form tag and add onsubmit handler if not already present
    if 'onsubmit=' not in text:
        if 'register.html' in path:
            redirect = 'login.html?registered=1'
            msg = 'Account created successfully! Redirecting...'
        elif 'login.html' in path:
            redirect = 'dashboard.html'
            msg = 'Welcome back! Redirecting...'
        else:
            redirect = 'requests.html?posted=1'
            msg = 'Request posted successfully! Redirecting...'

        # Add JS before </body>
        js = f'''
<script>
    document.querySelector('form').addEventListener('submit', function(e) {{
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        if(btn) {{ btn.textContent = '{msg}'; btn.disabled = true; btn.style.opacity = '0.7'; }}
        setTimeout(function() {{ window.location.href = '{redirect}'; }}, 1500);
    }});
</script>
'''
        text = text.replace('</body>', js + '\n</body>')

        with open(path, 'w', encoding='utf-8') as f:
            f.write(text)
        print(path + ' fixed')
    else:
        print(path + ' already has onsubmit')
