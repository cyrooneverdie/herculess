import re

with open('frontend/views/site/membership.php', 'r') as f:
    content = f.read()

# Define the new tabs UI
new_tabs = """<!-- Tabs -->
                <div class="relative z-10 flex flex-wrap items-center justify-center gap-2 px-8 py-8 border-b border-white/5">
                    <div class="flex p-1.5 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 shadow-inner">
                        <button onclick="switchTab('membership')" id="tab-membership"
                            class="gym-tab px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-400 hover:text-white hover:bg-white/5">
                            Membership
                        </button>
                        <button onclick="switchTab('fasilitas')" id="tab-fasilitas"
                            class="gym-tab px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-400 hover:text-white hover:bg-white/5">
                            Fasilitas
                        </button>
                        <button onclick="switchTab('alat')" id="tab-alat"
                            class="gym-tab px-6 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 bg-brand-gold text-slate-900 shadow-lg scale-105">
                            Alat Gym
                        </button>
                    </div>
                </div>"""

# Replace the tabs section
content = re.sub(
    r'<!-- Tabs -->.*?</div>\s*<!-- Tab: Membership -->',
    new_tabs + '\n\n                <!-- Tab: Membership -->',
    content,
    flags=re.DOTALL
)

# Enhance the cards
card_pattern = r'<div class="bg-white/5 rounded-2xl p-7 hover:bg-white/10 transition border border-white/5 hover:border-brand-gold/30">\s*<i class="([^"]+)"></i>\s*<h4 class="font-bold text-white text-base mb-2">([^<]+)</h4>\s*<p class="text-sm text-slate-400 leading-relaxed">([^<]+)</p>\s*</div>'

def card_replacement(match):
    icon = match.group(1).replace(' text-2xl mb-4', ' text-xl group-hover:text-slate-900 transition-colors')
    title = match.group(2)
    desc = match.group(3)
    
    return f"""<div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="{icon}"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">{title}</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">{desc}</p>
                            </div>
                        </div>"""

content = re.sub(card_pattern, card_replacement, content)

# Update padding in panels to breathe better
content = content.replace('class="gym-panel hidden relative z-10 p-8"', 'class="gym-panel hidden relative z-10 p-8 md:p-12"')
content = content.replace('class="gym-panel relative z-10 p-8"', 'class="gym-panel relative z-10 p-8 md:p-12"')
content = content.replace('class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"', 'class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8"')

# Also update the switchTab script to use scale-105 for the active tab (antislop UI)
script_replacement = """function switchTab(tab) {
                document.querySelectorAll('.gym-panel').forEach(p => p.classList.add('hidden'));
                document.querySelectorAll('.gym-tab').forEach(t => {
                    t.classList.remove('bg-brand-gold', 'text-slate-900', 'shadow-lg', 'scale-105');
                    t.classList.add('text-slate-400');
                    t.classList.remove('hover:bg-white/5');
                    t.classList.add('hover:bg-white/5');
                });
                document.getElementById('panel-' + tab).classList.remove('hidden');
                const activeTab = document.getElementById('tab-' + tab);
                activeTab.classList.add('bg-brand-gold', 'text-slate-900', 'shadow-lg', 'scale-105');
                activeTab.classList.remove('text-slate-400', 'hover:bg-white/5');
            }"""

content = re.sub(r'function switchTab\(tab\) \{.*?(?=\s*<\/script>)', script_replacement, content, flags=re.DOTALL)

with open('frontend/views/site/membership.php', 'w') as f:
    f.write(content)

print("Redesign applied successfully.")
