with open('/home/ahmad/web_gym/frontend/views/site/membership.php', 'r') as f:
    lines = f.readlines()

start_idx = -1
end_idx = -1
for i, line in enumerate(lines):
    if '<div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100 max-w-6xl mx-auto">' in line:
        start_idx = i
    if '<!-- END PAGE CONTENT -->' in line and start_idx != -1:
        end_idx = i - 1
        break

new_html = """        <!-- Benefits with Tabs Section -->
        <div class="bg-gradient-to-b from-slate-800 to-slate-600 rounded-3xl p-6 md:p-10 shadow-2xl max-w-6xl mx-auto relative overflow-hidden">
            <div class="text-center mb-8 relative z-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white">Our Gym Benefits</h2>
            </div>
            
            <!-- Tabs -->
            <div class="flex flex-wrap justify-center gap-2 md:gap-4 mb-6 relative z-10">
                <button class="px-4 py-2 text-sm md:text-base font-semibold text-slate-300 hover:text-white transition">Membership</button>
                <button class="px-4 py-2 text-sm md:text-base font-bold text-teal-600 bg-white rounded-lg shadow-md">Fasilitas</button>
                <button class="px-4 py-2 text-sm md:text-base font-semibold text-slate-300 hover:text-white transition">Kelas</button>
                <button class="px-4 py-2 text-sm md:text-base font-semibold text-slate-300 hover:text-white transition">Alat Gym</button>
                <button class="px-4 py-2 text-sm md:text-base font-semibold text-slate-300 hover:text-white transition">Sertifikasi PT</button>
            </div>
            
            <!-- Tab Content -->
            <div class="bg-white rounded-xl shadow-inner relative z-10 overflow-hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 border-b border-slate-100">
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-users text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Social & Community Area</h4>
                        <p class="text-xs text-slate-500">Area multifungsi untuk bersantai & sosialisasi</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-medal text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Functional Area</h4>
                        <p class="text-xs text-slate-500">Lebih leluasa latihan di area dengan gym turf</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-door-open text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Akses Ruang Kelas</h4>
                        <p class="text-xs text-slate-500">Bisa digunakan saat tidak ada kelas berlangsung</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-tint text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Dispenser</h4>
                        <p class="text-xs text-slate-500">Isi ulang air minum gratis kapan saja</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-couch text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Lounge</h4>
                        <p class="text-xs text-slate-500">Lebih nyaman untuk istirahat sebelum & setelah nge-gym</p>
                    </div>
                    
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-weight text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Cek Komposisi Tubuh</h4>
                        <p class="text-xs text-slate-500">Bantu pantau progress otot & lemak tubuh</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-shower text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Toilet & Shower</h4>
                        <p class="text-xs text-slate-500">Nikmati air panas & hair dryer setelah nge-gym</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-lock text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Loker</h4>
                        <p class="text-xs text-slate-500">Simpan barang bawaanmu dengan lebih aman</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-mosque text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Mushola</h4>
                        <p class="text-xs text-slate-500">Ketersediaan bervariasi antar klub</p>
                    </div>
                    
                    <div class="p-6 hover:bg-slate-50 transition">
                        <i class="fas fa-parking text-xl text-slate-700 mb-3"></i>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Area Parkir Luas</h4>
                        <p class="text-xs text-slate-500">Parkir mobil & motor dengan lebih praktis</p>
                    </div>
                    
                </div>
            </div>
            
            <div class="mt-6 relative z-10 px-2">
                <p class="text-[10px] text-white/70 italic">* Ketersediaan bervariasi antar club</p>
            </div>
            
            <!-- Bottom glow decoration -->
            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-teal-200/40 to-transparent z-0 pointer-events-none"></div>
        </div>\n"""

lines[start_idx:end_idx] = [new_html]

with open('/home/ahmad/web_gym/frontend/views/site/membership.php', 'w') as f:
    f.writelines(lines)

print("Benefits section replaced with tabs layout")
