import re

with open('frontend/views/layouts/main.php', 'r') as f:
    content = f.read()

# Add !bg-none to selects in main.php
content = content.replace(
    'class="w-full px-4 py-2.5',
    'class="w-full !bg-none px-4 py-2.5'
)

with open('frontend/views/layouts/main.php', 'w') as f:
    f.write(content)

print("Applied !bg-none to selects in main.php")
