<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Personal Trainer - Hercules Fitness Centre';
$this->params['meta_description'] = 'Layanan personal trainer di Hercules Fitness Centre Batam. Bimbingan latihan 1-on-1 dari coach bersertifikat untuk program kebugaran yang terukur dan aman.';
?>

<!-- BEGIN: Personal Trainer Page -->
<div class="bg-[#0b0c10] text-slate-100 min-h-screen pb-20 font-sans relative overflow-hidden">

  <!-- ==================== HERO SECTION ==================== -->
  <section class="relative h-[85vh] min-h-[600px] w-full flex items-center justify-center overflow-hidden bg-slate-950" id="trainer-hero">

    <!-- Background Image -->
    <div class="absolute inset-0 z-0 w-full h-full">
      <img src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=1920&h=1080&fit=crop" alt="Personal training session di Hercules Fitness" class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-black/30 z-[1]"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-transparent to-transparent z-[1]"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 w-full text-center">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-4 max-w-2xl mx-auto">
        Latihan Terarah <br/>
        <span class="text-brand-gold">Bersama Coach Kami.</span>
      </h1>
      <p class="text-base sm:text-lg text-slate-200 font-light leading-relaxed max-w-xl mx-auto mb-8">
        Program latihan 1-on-1 yang dirancang khusus untuk tujuan kebugaran Anda, dibimbing oleh coach berpengalaman di Hercules Fitness.
      </p>
      <a href="https://wa.me/6282286680539?text=Halo%20Hercules%2C%20saya%20ingin%20tanya%20soal%20personal%20trainer" target="_blank" class="px-8 py-3.5 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors inline-block">
        Konsultasi via WhatsApp
      </a>
    </div>

  </section>
  <!-- ==================== END HERO ==================== -->

  <!-- Content Container -->
  <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-10">

    <!-- ==================== HOW IT WORKS ==================== -->
    <section class="py-16 md:py-24 border-t border-white/10" id="cara-kerja">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

        <!-- Left: Explanation -->
        <div>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-6">
            Bagaimana <span class="text-brand-gold">Prosesnya?</span>
          </h2>
          <div class="space-y-6 text-slate-300 text-sm sm:text-base leading-relaxed font-light">
            <p>
              Anda akan memulai dengan sesi konsultasi singkat bersama coach. Di sesi ini, kami mendengarkan tujuan Anda, baik itu menurunkan berat badan, membentuk otot, atau memulihkan cedera.
            </p>
            <p>
              Berdasarkan kondisi fisik dan target Anda, coach akan merancang program latihan yang <strong class="text-white font-medium">spesifik dan terukur</strong>. Setiap sesi diarahkan langsung oleh coach untuk memastikan form yang benar dan progres yang konsisten.
            </p>
            <p>
              Program dievaluasi secara berkala. Jika ada yang perlu disesuaikan, coach akan menyesuaikan beban, frekuensi, dan variasi latihan bersama Anda.
            </p>
          </div>
        </div>

        <!-- Right: Steps (vertical timeline, no cards) -->
        <div class="space-y-10 pt-2">
          <div class="flex gap-5">
            <div class="flex flex-col items-center">
              <span class="w-10 h-10 rounded-full bg-brand-gold text-slate-950 flex items-center justify-center text-sm font-bold shrink-0">1</span>
              <div class="w-px flex-1 bg-white/10 mt-2"></div>
            </div>
            <div class="pb-2">
              <strong class="text-white text-base block mb-1">Konsultasi Awal</strong>
              <p class="text-slate-400 text-sm leading-relaxed">
                Ceritakan tujuan, riwayat latihan, dan kondisi kesehatan Anda. Coach akan mendengarkan sebelum merancang apapun.
              </p>
            </div>
          </div>

          <div class="flex gap-5">
            <div class="flex flex-col items-center">
              <span class="w-10 h-10 rounded-full bg-brand-gold text-slate-950 flex items-center justify-center text-sm font-bold shrink-0">2</span>
              <div class="w-px flex-1 bg-white/10 mt-2"></div>
            </div>
            <div class="pb-2">
              <strong class="text-white text-base block mb-1">Program Disusun</strong>
              <p class="text-slate-400 text-sm leading-relaxed">
                Jadwal, jenis latihan, dan target mingguan disusun berdasarkan kemampuan Anda saat ini, bukan template umum.
              </p>
            </div>
          </div>

          <div class="flex gap-5">
            <div class="flex flex-col items-center">
              <span class="w-10 h-10 rounded-full bg-brand-gold text-slate-950 flex items-center justify-center text-sm font-bold shrink-0">3</span>
              <div class="w-px flex-1 bg-white/10 mt-2"></div>
            </div>
            <div class="pb-2">
              <strong class="text-white text-base block mb-1">Latihan Bersama Coach</strong>
              <p class="text-slate-400 text-sm leading-relaxed">
                Setiap gerakan dipandu langsung. Coach memastikan teknik yang benar untuk menghindari cedera dan memaksimalkan hasil.
              </p>
            </div>
          </div>

          <div class="flex gap-5">
            <div class="flex flex-col items-center">
              <span class="w-10 h-10 rounded-full bg-brand-gold text-slate-950 flex items-center justify-center text-sm font-bold shrink-0">4</span>
            </div>
            <div class="pb-2">
              <strong class="text-white text-base block mb-1">Evaluasi Berkala</strong>
              <p class="text-slate-400 text-sm leading-relaxed">
                Progres dinilai tiap beberapa minggu. Program disesuaikan agar Anda terus berkembang, bukan stagnan.
              </p>
            </div>
          </div>
        </div>

      </div>
    </section>
    <!-- ==================== END HOW IT WORKS ==================== -->






    <!-- ==================== TIM PT ==================== -->
    <section class="py-16 md:py-24 border-t border-white/10" id="tim-trainer">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-4 text-right">
        Capai Target Fisik Lebih Terukur <br> Bersama <span class="text-brand-gold">Personal Trainer Profesional.</span>
      </h2>
      <p class="text-slate-400 text-sm sm:text-base max-w-2xl mb-12 font-light leading-relaxed ml-auto text-right">
        Personal Trainer bersertifikat yang berpengalaman membimbing berbagai level member, dari pemula hingga atlet kompetitif.
      </p>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- PT 1 -->
        <div class="bg-[#12141a] rounded-2xl overflow-hidden border border-white/10">
          <div class="aspect-[3/4] bg-[#1a1c24] flex items-center justify-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=400&h=500&fit=crop" alt="PT Rizal" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" />
          </div>
          <div class="p-6">
            <strong class="text-white text-lg block">PT Rizal A.</strong>
            <span class="text-brand-gold text-xs font-bold uppercase tracking-wide block mt-1 mb-3">Strength &amp; Conditioning</span>
            <p class="text-slate-400 text-sm leading-relaxed">Spesialisasi di program pembentukan kekuatan dan peningkatan performa fisik. Berpengalaman 5+ tahun melatih member di Batam.</p>
          </div>
        </div>

        <!-- PT 2 -->
        <div class="bg-[#12141a] rounded-2xl overflow-hidden border border-white/10">
          <div class="aspect-[3/4] bg-[#1a1c24] flex items-center justify-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=400&h=500&fit=crop" alt="PT Dimas" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" />
          </div>
          <div class="p-6">
            <strong class="text-white text-lg block">PT Dimas F.</strong>
            <span class="text-brand-gold text-xs font-bold uppercase tracking-wide block mt-1 mb-3">Fat Loss &amp; Functional</span>
            <p class="text-slate-400 text-sm leading-relaxed">Fokus pada program penurunan berat badan dan latihan fungsional. Pendekatan yang sabar dan terstruktur untuk pemula.</p>
          </div>
        </div>

        <!-- PT 3 -->
        <div class="bg-[#12141a] rounded-2xl overflow-hidden border border-white/10">
          <div class="aspect-[3/4] bg-[#1a1c24] flex items-center justify-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1594381898411-846e7d193883?w=400&h=500&fit=crop" alt="PT Andi" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" />
          </div>
          <div class="p-6">
            <strong class="text-white text-lg block">PT Andi S.</strong>
            <span class="text-brand-gold text-xs font-bold uppercase tracking-wide block mt-1 mb-3">Bodybuilding &amp; Hypertrophy</span>
            <p class="text-slate-400 text-sm leading-relaxed">Ahli dalam program hypertrophy dan persiapan kompetisi bodybuilding. Membimbing teknik isolasi dan split training.</p>
          </div>
        </div>

      </div>

      <p class="text-slate-500 text-xs mt-6 italic">* Foto PT di atas adalah ilustrasi sementara. Hubungi kami untuk informasi lebih lanjut tentang masing-masing PT.</p>
    </section>
    <!-- ==================== END TIM PT ==================== -->


    <!-- ==================== PAKET SESI ==================== -->
    <section class="py-16 md:py-24 border-t border-white/10" id="paket-sesi">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-4 text-center">
        Pilihan <span class="text-brand-gold">Paket Sesi</span>
      </h2>
      <p class="text-slate-400 text-sm sm:text-base max-w-2xl mb-12 font-light leading-relaxed text-center mx-auto">
        Pilih jumlah sesi yang sesuai dengan jadwal dan target latihan Anda. Semakin banyak sesi, semakin hemat per pertemuan.
      </p>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <!-- Paket 1 -->
        <div class="bg-[#12141a] rounded-2xl p-8 border border-white/10">
          <span class="text-brand-gold text-xs font-bold uppercase tracking-wider block mb-2">Paket Coba</span>
          <strong class="text-white text-3xl font-extrabold font-condensed block mb-1">4 Sesi</strong>
          <span class="text-slate-500 text-sm block mb-6">Sekitar 1 bulan (1x/minggu)</span>
          <ul class="space-y-3 text-sm text-slate-300 mb-8">
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Konsultasi awal</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Program latihan personal</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>4x bimbingan langsung</span>
            </li>
          </ul>
          <a href="https://wa.me/6282286680539?text=Halo%20Hercules%2C%20saya%20tertarik%20paket%204%20sesi%20PT" target="_blank" class="block w-full py-3 rounded bg-white/10 hover:bg-white/15 text-white text-sm font-bold uppercase tracking-wide text-center transition-colors border border-white/10">
            Tanya Paket Ini
          </a>
        </div>

        <!-- Paket 2 -->
        <div class="bg-[#12141a] rounded-2xl p-8 border-2 border-brand-gold/40">
          <span class="text-brand-gold text-xs font-bold uppercase tracking-wider block mb-2">Paket Reguler</span>
          <strong class="text-white text-3xl font-extrabold font-condensed block mb-1">12 Sesi</strong>
          <span class="text-slate-500 text-sm block mb-6">Sekitar 1 bulan (3x/minggu)</span>
          <ul class="space-y-3 text-sm text-slate-300 mb-8">
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Konsultasi awal + evaluasi</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Program latihan personal</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>12x bimbingan langsung</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Panduan nutrisi dasar</span>
            </li>
          </ul>
          <a href="https://wa.me/6282286680539?text=Halo%20Hercules%2C%20saya%20tertarik%20paket%2012%20sesi%20PT" target="_blank" class="block w-full py-3 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide text-center transition-colors">
            Tanya Paket Ini
          </a>
        </div>

        <!-- Paket 3 -->
        <div class="bg-[#12141a] rounded-2xl p-8 border border-white/10">
          <span class="text-brand-gold text-xs font-bold uppercase tracking-wider block mb-2">Paket Intensif</span>
          <strong class="text-white text-3xl font-extrabold font-condensed block mb-1">24 Sesi</strong>
          <span class="text-slate-500 text-sm block mb-6">Sekitar 2 bulan (3x/minggu)</span>
          <ul class="space-y-3 text-sm text-slate-300 mb-8">
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Konsultasi + evaluasi berkala</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Program latihan personal</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>24x bimbingan langsung</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Panduan nutrisi lengkap</span>
            </li>
            <li class="flex items-start gap-2">
              <i class="fas fa-check text-brand-gold mt-0.5 shrink-0 text-xs"></i>
              <span>Tracking progres bulanan</span>
            </li>
          </ul>
          <a href="https://wa.me/6282286680539?text=Halo%20Hercules%2C%20saya%20tertarik%20paket%2024%20sesi%20PT" target="_blank" class="block w-full py-3 rounded bg-white/10 hover:bg-white/15 text-white text-sm font-bold uppercase tracking-wide text-center transition-colors border border-white/10">
            Tanya Paket Ini
          </a>
        </div>

      </div>

      <p class="text-slate-500 text-xs mt-6 italic">* Harga untuk setiap paket dapat ditanyakan langsung melalui WhatsApp. Harga dapat berubah sewaktu-waktu.</p>
    </section>
    <!-- ==================== END PAKET SESI ==================== -->



    <!-- ==================== CTA FINAL ==================== -->
    <section class="py-16 md:py-24 border-t border-white/10 text-center" id="trainer-cta">
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed mb-4">
        Siap Memulai?
      </h2>
      <p class="text-slate-400 text-sm sm:text-base max-w-xl mx-auto mb-8 font-light leading-relaxed">
        Hubungi kami untuk menjadwalkan sesi konsultasi pertama Anda. Tidak ada komitmen jangka panjang yang diwajibkan.
      </p>
      <a href="https://wa.me/6282286680539?text=Halo%20Hercules%2C%20saya%20ingin%20booking%20sesi%20personal%20trainer" target="_blank" class="px-10 py-4 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors inline-block">
        Hubungi via WhatsApp
      </a>
    </section>
    <!-- ==================== END CTA ==================== -->

  </div>

</div>
<!-- END: Personal Trainer Page -->
