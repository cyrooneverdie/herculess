with open('/home/ahmad/web_gym/frontend/views/site/index.php', 'r') as f:
    content = f.read()

content = content.replace(
    'class="italic-serif font-normal text-brand-accent text-slate-600"',
    'class="italic-serif font-normal text-amber-600"'
)

with open('/home/ahmad/web_gym/frontend/views/site/index.php', 'w') as f:
    f.write(content)

print("Headline color fixed")
