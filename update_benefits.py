import re

with open('/home/ahmad/web_gym/frontend/views/site/membership.php', 'r') as f:
    content = f.read()

old_title = 'Our Gym Benefits'
new_title = 'Semua Pilihan Paket Sudah Termasuk:'
content = content.replace(old_title, new_title)
content = content.replace('Fasilitas yang bisa Anda nikmati dengan bergabung bersama kami.', 'Fasilitas standar (baseline amenities) yang didapatkan semua member tanpa memandang jenis paket.')

old_cards = [
    ('Gym Equipment Tutorial', 'Bingung pakai alat? Instruktur kami siap membantu mengajarkan bentuk gerakan yang aman.'),
    ('Fasilitas Lengkap', 'Nikmati ruang ganti eksklusif, loker, dan shower air hangat setelah sesi olahraga yang intens.'),
    ('Instruktur Profesional', 'Didukung oleh Personal Trainer tersertifikasi yang akan memastikan Anda mencapai target fitness Anda.'),
    ('Membership Fleksibel', 'Dapatkan kemudahan jeda waktu (freeze) jika Anda sedang bepergian atau tidak bisa nge-gym.')
]

new_cards = [
    ('Loker & Shower Air Hangat', 'Fasilitas loker aman dan shower air hangat untuk kenyamanan dasar harian Anda setelah berlatih.'),
    ('Membership Freeze', 'Dapatkan kemudahan cuti/jeda waktu membership jika Anda sedang bepergian ke luar kota atau sedang sakit.'),
    ('Free Wi-Fi & Charging Station', 'Koneksi internet cepat dan area pengisian daya yang tersebar di seluruh area gym untuk kenyamanan Anda.'),
    ('Konsultasi & Pengenalan Alat Awal', 'Bingung mulai dari mana? Instruktur kami siap memberikan orientasi alat dasar di hari pertama Anda.')
]

for i in range(4):
    content = content.replace(old_cards[i][0], new_cards[i][0])
    content = content.replace(old_cards[i][1], new_cards[i][1])

with open('/home/ahmad/web_gym/frontend/views/site/membership.php', 'w') as f:
    f.write(content)

print("Benefits updated")
