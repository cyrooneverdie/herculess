with open('/home/ahmad/web_gym/frontend/views/site/index.php', 'r') as f:
    content = f.read()

content = content.replace(
    'class="italic-serif font-normal text-amber-600"',
    'class="italic-serif font-normal text-brand-accent" style="text-shadow: 0px 2px 4px rgba(0,0,0,0.15), 0px 1px 2px rgba(0,0,0,0.1);"'
)

with open('/home/ahmad/web_gym/frontend/views/site/index.php', 'w') as f:
    f.write(content)

print("Headline text shadow added")
