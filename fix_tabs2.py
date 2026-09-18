with open('/home/ahmad/web_gym/frontend/views/site/membership.php', 'r') as f:
    lines = f.readlines()

start_idx = -1
end_idx = -1
for i, line in enumerate(lines):
    if '<!-- Benefits with Tabs Section -->' in line:
        start_idx = i
    if '<!-- END PAGE CONTENT -->' in line and start_idx != -1:
        end_idx = i - 1
        break

new_html = """        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100 max-w-6xl mx-auto mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-slate-800 mb-2">Semua Pilihan Paket Sudah Termasuk:</h2>
                <p class="text-slate-500">Fasilitas standar (baseline amenities) yang didapatkan semua member tanpa memandang jenis paket.</p>
            </div>
            
            <!-- Toggle Tabs -->
            <div class="flex justify-center mb-10">
                <div class="bg-slate-100 p-1.5 rounded-xl inline-flex relative border border-slate-200">
                    <button class="px-8 py-2.5 rounded-lg font-bold text-sm bg-white text-slate-800 shadow-sm transition-all relative z-10 w-36 text-center">Fasilitas</button>
                    <button class="px-8 py-2.5 rounded-lg font-semibold text-sm text-slate-500 hover:text-slate-800 transition-all relative z-10 w-36 text-center">Membership</button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Benefit 1 -->
                <div class="border border-slate-200 p-6 rounded-xl hover:border-brand-gold hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 bg-slate-50 group-hover:bg-brand-gold/10 text-slate-700 group-hover:text-brand-gold rounded-lg flex items-center justify-center text-xl mb-4 transition-colors">
                        <i class="fas fa-dumbbell"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">Loker & Shower Air Hangat</h4>
                    <p class="text-sm text-slate-500">Fasilitas loker aman dan shower air hangat untuk kenyamanan dasar harian Anda setelah berlatih.</p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="border border-slate-200 p-6 rounded-xl hover:border-brand-gold hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 bg-slate-50 group-hover:bg-brand-gold/10 text-slate-700 group-hover:text-brand-gold rounded-lg flex items-center justify-center text-xl mb-4 transition-colors">
                        <i class="fas fa-shower"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">Membership Freeze</h4>
                    <p class="text-sm text-slate-500">Dapatkan kemudahan cuti/jeda waktu membership jika Anda sedang bepergian ke luar kota atau sedang sakit.</p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="border border-slate-200 p-6 rounded-xl hover:border-brand-gold hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 bg-slate-50 group-hover:bg-brand-gold/10 text-slate-700 group-hover:text-brand-gold rounded-lg flex items-center justify-center text-xl mb-4 transition-colors">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">Free Wi-Fi & Charging Station</h4>
                    <p class="text-sm text-slate-500">Koneksi internet cepat dan area pengisian daya yang tersebar di seluruh area gym untuk kenyamanan Anda.</p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="border border-slate-200 p-6 rounded-xl hover:border-brand-gold hover:shadow-lg transition-all group">
                    <div class="w-12 h-12 bg-slate-50 group-hover:bg-brand-gold/10 text-slate-700 group-hover:text-brand-gold rounded-lg flex items-center justify-center text-xl mb-4 transition-colors">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h4 class="font-bold text-slate-800 mb-2">Konsultasi & Pengenalan Alat Awal</h4>
                    <p class="text-sm text-slate-500">Bingung mulai dari mana? Instruktur kami siap memberikan orientasi alat dasar di hari pertama Anda.</p>
                </div>
            </div>
            <div class="text-center mt-10">
                <p class="text-xs text-slate-400">* Syarat & Ketentuan Berlaku</p>
            </div>
        </div>\n"""

if start_idx != -1:
    lines[start_idx:end_idx] = [new_html]
    with open('/home/ahmad/web_gym/frontend/views/site/membership.php', 'w') as f:
        f.writelines(lines)
    print("Reverted to original Opsi A styling with toggle")
else:
    print("Could not find section to replace")
