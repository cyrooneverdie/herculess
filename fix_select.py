import re

with open('frontend/views/site/join.php', 'r') as f:
    content = f.read()

# Add !bg-none to all selects in join.php to ensure native chevron is dead
content = content.replace(
    'class="w-full bg-slate-50',
    'class="w-full !bg-none bg-slate-50'
)

# And specifically ensure choices__input--hidden is thoroughly hidden in main.php
with open('frontend/views/layouts/main.php', 'r') as f:
    main_content = f.read()

if '.choices__input--hidden' not in main_content:
    main_content = main_content.replace(
        '.choices.is-disabled .choices__inner {',
        '.choices__input--hidden { display: none !important; opacity: 0 !important; visibility: hidden !important; }\n.choices.is-disabled .choices__inner {'
    )
    with open('frontend/views/layouts/main.php', 'w') as f:
        f.write(main_content)

with open('frontend/views/site/join.php', 'w') as f:
    f.write(content)

print("Applied !bg-none to selects and enforced hiding native select.")
