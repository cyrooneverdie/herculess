import re

with open('frontend/views/layouts/main.php', 'r') as f:
    content = f.read()

# 1. Fix HTML: empty option
content = content.replace(
    '<option value="" disabled selected hidden>Pilih kota terlebih dahulu</option>',
    '<option value=""></option>'
)

# 2. Fix JS init: add placeholder config
init_pattern = r"""    if\(document\.getElementById\('m-branch'\)\) \{
        window\.sidebarBranchChoice = new Choices\('#m-branch', \{
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false
        \}\);
    \}"""

new_init = """    if(document.getElementById('m-branch')) {
        window.sidebarBranchChoice = new Choices('#m-branch', {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false,
            placeholder: true,
            placeholderValue: 'Pilih kota terlebih dahulu'
        });
    }"""

content = re.sub(init_pattern, new_init, content)

# 3. Fix JS fallback: empty label in setChoices
content = content.replace(
    "{ value: '', label: 'Pilih kota terlebih dahulu', disabled: true, selected: true }",
    "{ value: '', label: '', disabled: true, selected: true }"
)

with open('frontend/views/layouts/main.php', 'w') as f:
    f.write(content)

print("Sidebar branch choice fixed.")
