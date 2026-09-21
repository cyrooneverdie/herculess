<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Tentang Kami - Hercules Fitness Centre';
$this->params['meta_description'] = 'Profil Hercules Fitness Centre Batam — Pusat kebugaran 4.9★ di Komplek Macadam Batu Ampar dan Batu Besar dengan fasilitas alat terlengkap dan komunitas suportif.';
?>

<!-- BEGIN: About Us Main Page -->
<div class="bg-[#0b0c10] text-slate-100 min-h-screen pb-20 font-sans relative overflow-hidden">

  <!-- ==================== HERO SECTION (FULL SIZE MATCHING INDEX PAGE) ==================== -->
  <section class="relative h-[100vh] min-h-[680px] w-full flex flex-col justify-center overflow-hidden bg-slate-950" id="about-hero">

    <!-- Background Image with Overlays (Exact Match to Main Index Page True Size) -->
    <div class="absolute inset-0 z-0 w-full h-full">
      <img src="/img/hero.jpg" alt="Hercules Fitness Centre Gym" class="w-full h-full object-cover" style="transform: scaleX(-1);" />
      <!-- Dark overlays matching index.php: true image visibility -->
      <div class="absolute inset-0 bg-gradient-to-r from-black/65 via-black/30 to-transparent z-[1]"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-black/20 to-black/10 z-[1]"></div>
    </div>

    <!-- Content Container (Left-Aligned Minimal with Navbar Typography) -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 w-full text-left pt-20">
      
      <!-- Main Title (ABOUT US) -->
      <h1 class="text-6xl sm:text-7xl lg:text-8xl xl:text-9xl font-black text-white tracking-tight uppercase font-condensed mb-2 drop-shadow-2xl leading-none">
        ABOUT US
      </h1>

      <!-- Cool Hercules Fitness Text (Matching Navbar Font, NO 'CENTRE') -->
      <div class="flex items-center gap-3 sm:gap-4 mb-6">
        <span class="text-4xl sm:text-5xl lg:text-6xl text-white font-logo-main uppercase tracking-wider drop-shadow-2xl">HERCULES</span>
        <span class="text-xs sm:text-sm lg:text-base text-brand-gold font-logo-sub uppercase tracking-[0.35em] drop-shadow font-bold mt-1">FITNESS</span>
      </div>

      <!-- Description (Refined Scale) -->
      <p class="text-sm sm:text-base lg:text-lg text-slate-200/90 font-normal leading-relaxed max-w-2xl mb-8 drop-shadow-md">
        Pusat kebugaran terlengkap di Batam dengan fasilitas modern, suasana nyaman bebas intimidasi, dan bimbingan coach berpengalaman.
      </p>

      <!-- Explore Memberships Button (Matching Scale) -->
      <div>
        <a href="<?= Url::to(['site/join']) ?>" class="px-8 sm:px-10 py-4 sm:py-4.5 rounded-lg bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm sm:text-base font-extrabold uppercase tracking-wider transition-all duration-300 shadow-xl hover:shadow-brand-gold/40 hover:-translate-y-0.5 inline-flex items-center gap-3">
          <span>Explore Memberships</span>
          <i class="fas fa-arrow-right text-sm"></i>
        </a>
      </div>

    </div>

  </section>
  <!-- ==================== END HERO SECTION ==================== -->

  <!-- Content Container for Below Sections -->
  <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-10">

    <!-- ==================== STORY & PHILOSOPHY (REDUCED TEXT) ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10" id="filosofi">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
        
        <!-- Left: Image Visual -->
        <div class="lg:col-span-6 relative">
          <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-white/15 aspect-[4/3] bg-slate-900">
            <img src="/img/hero2.jpg" alt="Hercules Fitness Atmosphere" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            
            <div class="absolute bottom-5 left-5 right-5 p-4 rounded-xl bg-black/60 backdrop-blur-md border border-white/10 flex items-center justify-between">
              <div>
                <p class="text-brand-gold text-xs font-bold uppercase">Komplek Macadam, Batu Ampar</p>
                <p class="text-white text-sm font-semibold">Fasilitas Lengkap &amp; Bebas Intimidasi</p>
              </div>
              <span class="text-amber-400 text-sm font-bold shrink-0">4.9 ★</span>
            </div>
          </div>
        </div>

        <!-- Right: Story Narrative (Concise) -->
        <div class="lg:col-span-6">
          <span class="text-brand-gold text-xs font-extrabold uppercase tracking-widest block mb-2">Tentang Kami</span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed leading-tight mb-4">
            Dedikasi Untuk <span class="italic-serif font-normal text-brand-gold normal-case">Kebugaran Anda.</span>
          </h2>
          
          <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-6">
            Hercules Fitness Centre didirikan untuk menghadirkan tempat latihan yang lengkap, nyaman, dan bersahabat bagi seluruh warga Batam. Tanpa rasa canggung, kami menyambut pemula hingga atlet dengan fasilitas standar profesional dan harga yang terjangkau.
          </p>

          <!-- 3 Compact Highlights -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="p-3.5 rounded-xl bg-[#13151c] border border-white/10">
              <i class="fas fa-dumbbell text-brand-gold text-lg mb-2 block"></i>
              <h4 class="text-white font-bold text-xs">Alat Lengkap</h4>
              <p class="text-slate-400 text-[11px] mt-0.5">Free weight &amp; mesin komplit</p>
            </div>

            <div class="p-3.5 rounded-xl bg-[#13151c] border border-white/10">
              <i class="fas fa-user-check text-brand-gold text-lg mb-2 block"></i>
              <h4 class="text-white font-bold text-xs">Coach Ramah</h4>
              <p class="text-slate-400 text-[11px] mt-0.5">Bimbingan form latihan</p>
            </div>

            <div class="p-3.5 rounded-xl bg-[#13151c] border border-white/10">
              <i class="fas fa-tags text-brand-gold text-lg mb-2 block"></i>
              <h4 class="text-white font-bold text-xs">Biaya Hemat</h4>
              <p class="text-slate-400 text-[11px] mt-0.5">Tiket harian &amp; member murah</p>
            </div>
          </div>
        </div>

      </div>
    </section>
    <!-- ==================== END STORY SECTION ==================== -->

    <!-- ==================== CORE VALUES (ALWAYS VISIBLE & BUG-FREE) ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10" id="nilai-utama">
      
      <div class="text-center mb-10">
        <span class="text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Filosofi &amp; Komitmen</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed">
          4 Alasan Memilih <span class="italic-serif font-normal text-brand-gold normal-case">Hercules Fitness</span>
        </h2>
      </div>

      <!-- 4 Pillars Grid (Guaranteed Render) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1 -->
        <div class="bg-[#12141a] hover:bg-[#161820] border border-white/10 hover:border-brand-gold/60 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 shadow-lg">
          <div class="w-12 h-12 rounded-xl bg-brand-gold/15 text-brand-gold flex items-center justify-center text-xl mb-4">
            <i class="fas fa-dumbbell"></i>
          </div>
          <h3 class="text-lg font-bold text-white mb-2">Alat Lengkap &amp; Berat</h3>
          <p class="text-slate-400 text-xs leading-relaxed">
            Dumbbell set lengkap, Olympic barbells, cable crossover, dan squat rack tanpa antre lama.
          </p>
        </div>

        <!-- Card 2 -->
        <div class="bg-[#12141a] hover:bg-[#161820] border border-white/10 hover:border-brand-gold/60 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 shadow-lg">
          <div class="w-12 h-12 rounded-xl bg-brand-gold/15 text-brand-gold flex items-center justify-center text-xl mb-4">
            <i class="fas fa-people-group"></i>
          </div>
          <h3 class="text-lg font-bold text-white mb-2">Komunitas Suportif</h3>
          <p class="text-slate-400 text-xs leading-relaxed">
            Kultur ramah tanpa tatapan intimidasi. Cocok bagi pemula yang baru mulai nge-gym.
          </p>
        </div>

        <!-- Card 3 -->
        <div class="bg-[#12141a] hover:bg-[#161820] border border-white/10 hover:border-brand-gold/60 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 shadow-lg">
          <div class="w-12 h-12 rounded-xl bg-brand-gold/15 text-brand-gold flex items-center justify-center text-xl mb-4">
            <i class="fas fa-wallet"></i>
          </div>
          <h3 class="text-lg font-bold text-white mb-2">Harga Terjangkau</h3>
          <p class="text-slate-400 text-xs leading-relaxed">
            Pilihan tiket harian ramah kantong dan paket membership bulanan hemat tanpa biaya tersembunyi.
          </p>
        </div>

        <!-- Card 4 -->
        <div class="bg-[#12141a] hover:bg-[#161820] border border-white/10 hover:border-brand-gold/60 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 shadow-lg">
          <div class="w-12 h-12 rounded-xl bg-brand-gold/15 text-brand-gold flex items-center justify-center text-xl mb-4">
            <i class="fas fa-clock"></i>
          </div>
          <h3 class="text-lg font-bold text-white mb-2">Buka Hingga 23.00</h3>
          <p class="text-slate-400 text-xs leading-relaxed">
            Jadwal fleksibel 07.00–23.00 WIB di hari kerja, memberi keleluasaan latihan setelah pulang kantor.
          </p>
        </div>

      </div>

    </section>
    <!-- ==================== END CORE VALUES SECTION ==================== -->

    <!-- ==================== PROFESSIONAL TEAM SECTION (ACCORDION GSAP) ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10" id="professional-team">
      
      <!-- Section Header Matching Other Sub-title Sections -->
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Tim Pelatih &amp; Instruktur</span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed">
            Tim Profesional <span class="italic-serif font-normal text-brand-gold normal-case">Siap Membimbing Anda</span>
          </h2>
        </div>
        <p class="text-slate-400 text-xs sm:text-sm max-w-sm sm:text-right">
          Bimbingan langsung dari pelatih berpengalaman untuk memastikan program latihan Anda terarah, efektif, dan aman.
        </p>
      </div>

      <!-- Horizontal Accordion Gallery -->
      <div class="team-accordion-wrap flex flex-col md:flex-row gap-2.5 sm:gap-3 w-full h-[480px] sm:h-[520px] lg:h-[560px] overflow-hidden select-none">
        
        <!-- Card 1 (Active by default) -->
        <div class="team-card relative overflow-hidden rounded-xl bg-black cursor-pointer flex-[3.5] transition-[flex-grow] duration-500 ease-out border border-white/10 hover:border-brand-gold/50 shadow-2xl group" data-index="0">
          <img src="/img/clip4.jpeg" alt="Coach Sadako Smith" class="team-img absolute inset-0 w-full h-full object-cover object-top filter grayscale-0 brightness-95 transition-all duration-700 pointer-events-none" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent pointer-events-none"></div>
          
          <div class="team-info absolute bottom-0 left-0 right-0 p-6 sm:p-8 flex flex-col justify-end z-10 opacity-100 transform translate-y-0">
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Pelatih Kepala</span>
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white uppercase font-condensed tracking-tight leading-tight mb-2">Sadako Smith</h3>
            <div class="flex items-center gap-4 text-xs font-semibold tracking-wider text-white/70">
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">FB</a>
              <a href="https://wa.me/6282286680539" target="_blank" class="hover:text-brand-gold transition-colors">WA</a>
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">IG</a>
            </div>
          </div>
        </div>

        <!-- Card 2 -->
        <div class="team-card relative overflow-hidden rounded-xl bg-black cursor-pointer flex-[1] transition-[flex-grow] duration-500 ease-out border border-white/10 hover:border-brand-gold/50 shadow-2xl group" data-index="1">
          <img src="/img/clip5.jpeg" alt="Coach Marcus Vance" class="team-img absolute inset-0 w-full h-full object-cover object-top filter grayscale brightness-75 transition-all duration-700 pointer-events-none" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent pointer-events-none"></div>
          
          <div class="team-info absolute bottom-0 left-0 right-0 p-6 sm:p-8 flex flex-col justify-end z-10 opacity-0 transform translate-y-4 pointer-events-none">
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Kekuatan &amp; Pengondisian</span>
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white uppercase font-condensed tracking-tight leading-tight mb-2">Marcus Vance</h3>
            <div class="flex items-center gap-4 text-xs font-semibold tracking-wider text-white/70">
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">FB</a>
              <a href="https://wa.me/6282286680539" target="_blank" class="hover:text-brand-gold transition-colors">WA</a>
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">IG</a>
            </div>
          </div>
        </div>

        <!-- Card 3 -->
        <div class="team-card relative overflow-hidden rounded-xl bg-black cursor-pointer flex-[1] transition-[flex-grow] duration-500 ease-out border border-white/10 hover:border-brand-gold/50 shadow-2xl group" data-index="2">
          <img src="/img/clip6.jpeg" alt="Coach Elena Rostova" class="team-img absolute inset-0 w-full h-full object-cover object-top filter grayscale brightness-75 transition-all duration-700 pointer-events-none" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent pointer-events-none"></div>
          
          <div class="team-info absolute bottom-0 left-0 right-0 p-6 sm:p-8 flex flex-col justify-end z-10 opacity-0 transform translate-y-4 pointer-events-none">
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Mobilitas &amp; HIIT</span>
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white uppercase font-condensed tracking-tight leading-tight mb-2">Elena Rostova</h3>
            <div class="flex items-center gap-4 text-xs font-semibold tracking-wider text-white/70">
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">FB</a>
              <a href="https://wa.me/6282286680539" target="_blank" class="hover:text-brand-gold transition-colors">WA</a>
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">IG</a>
            </div>
          </div>
        </div>

        <!-- Card 4 -->
        <div class="team-card relative overflow-hidden rounded-xl bg-black cursor-pointer flex-[1] transition-[flex-grow] duration-500 ease-out border border-white/10 hover:border-brand-gold/50 shadow-2xl group" data-index="3">
          <img src="/img/whyinfitnova.jpg" alt="Coach David Chen" class="team-img absolute inset-0 w-full h-full object-cover object-center filter grayscale brightness-75 transition-all duration-700 pointer-events-none" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent pointer-events-none"></div>
          
          <div class="team-info absolute bottom-0 left-0 right-0 p-6 sm:p-8 flex flex-col justify-end z-10 opacity-0 transform translate-y-4 pointer-events-none">
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Bina Raga &amp; Teknik Latihan</span>
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white uppercase font-condensed tracking-tight leading-tight mb-2">David Chen</h3>
            <div class="flex items-center gap-4 text-xs font-semibold tracking-wider text-white/70">
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">FB</a>
              <a href="https://wa.me/6282286680539" target="_blank" class="hover:text-brand-gold transition-colors">WA</a>
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">IG</a>
            </div>
          </div>
        </div>

        <!-- Card 5 -->
        <div class="team-card relative overflow-hidden rounded-xl bg-black cursor-pointer flex-[1] transition-[flex-grow] duration-500 ease-out border border-white/10 hover:border-brand-gold/50 shadow-2xl group" data-index="4">
          <img src="/img/hero2.jpg" alt="Coach Arthur Pendelton" class="team-img absolute inset-0 w-full h-full object-cover object-center filter grayscale brightness-75 transition-all duration-700 pointer-events-none" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent pointer-events-none"></div>
          
          <div class="team-info absolute bottom-0 left-0 right-0 p-6 sm:p-8 flex flex-col justify-end z-10 opacity-0 transform translate-y-4 pointer-events-none">
            <span class="text-[10px] sm:text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Preparasi Atletik</span>
            <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white uppercase font-condensed tracking-tight leading-tight mb-2">Arthur Pendelton</h3>
            <div class="flex items-center gap-4 text-xs font-semibold tracking-wider text-white/70">
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">FB</a>
              <a href="https://wa.me/6282286680539" target="_blank" class="hover:text-brand-gold transition-colors">WA</a>
              <a href="https://instagram.com/hercules.fitnesscentre" target="_blank" class="hover:text-brand-gold transition-colors">IG</a>
            </div>
          </div>
        </div>

      </div>
    </section>
    <!-- ==================== END PROFESSIONAL TEAM SECTION ==================== -->

    <!-- ==================== FACILITIES & ZONES (ALWAYS VISIBLE & BUG-FREE) ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10" id="fasilitas-lengkap">
      
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
          <span class="text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">Zona Latihan &amp; Sarana</span>
          <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed">
            Fasilitas Standar <span class="italic-serif font-normal text-brand-gold normal-case">Pusat Kebugaran Modern</span>
          </h2>
        </div>
        <p class="text-slate-400 text-xs sm:text-sm max-w-sm sm:text-right">
          Ruangan ber-AC, sirkulasi lega, dan lantai berperedam getaran yang aman.
        </p>
      </div>

      <!-- Facility 3 Cards Grid (Guaranteed Render) -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        
        <!-- Zone 1: Free Weights -->
        <div class="group rounded-2xl overflow-hidden bg-[#13151c] border border-white/10 hover:border-brand-gold/50 transition-all flex flex-col">
          <div class="h-48 overflow-hidden relative">
            <img src="/img/hero.jpg" alt="Free Weights Area" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-black/70 text-brand-gold text-[10px] font-bold uppercase tracking-wider">
              Free Weights
            </span>
          </div>
          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-lg font-bold text-white mb-1.5">Area Beban Bebas</h3>
              <p class="text-slate-400 text-xs leading-relaxed mb-3">
                Dumbbell beragam bobot, barbel Olympic, bench press datar &amp; miring, serta deadlift platform.
              </p>
            </div>
            <p class="text-[11px] text-brand-gold font-semibold">✓ Dumbbells • Olympic Bars • Racks</p>
          </div>
        </div>

        <!-- Zone 2: Machine & Isolation -->
        <div class="group rounded-2xl overflow-hidden bg-[#13151c] border border-white/10 hover:border-brand-gold/50 transition-all flex flex-col">
          <div class="h-48 overflow-hidden relative">
            <img src="/img/whyinfitnova.jpg" alt="Machine Sector" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-black/70 text-blue-400 text-[10px] font-bold uppercase tracking-wider">
              Machine Sector
            </span>
          </div>
          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-lg font-bold text-white mb-1.5">Mesin Beban &amp; Kabel</h3>
              <p class="text-slate-400 text-xs leading-relaxed mb-3">
                Cable crossover, leg press 45°, lat pulldown, dan mesin isolasi otot terawat.
              </p>
            </div>
            <p class="text-[11px] text-brand-gold font-semibold">✓ Cable Station • Leg Press • Smith Machine</p>
          </div>
        </div>

        <!-- Zone 3: Cardio & Functional -->
        <div class="group rounded-2xl overflow-hidden bg-[#13151c] border border-white/10 hover:border-brand-gold/50 transition-all flex flex-col">
          <div class="h-48 overflow-hidden relative">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=600&q=80" alt="Cardio & Functional" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md bg-black/70 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">
              Cardio Arena
            </span>
          </div>
          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <h3 class="text-lg font-bold text-white mb-1.5">Kardio &amp; Fungsional</h3>
              <p class="text-slate-400 text-xs leading-relaxed mb-3">
                Treadmill komersial, sepeda statis, battle rope, kettlebell, dan area matras stretching.
              </p>
            </div>
            <p class="text-[11px] text-brand-gold font-semibold">✓ Treadmills • Battle Rope • Matras</p>
          </div>
        </div>

      </div>

      <!-- Amenities Strip (Compact) -->
      <div class="bg-[#12141a] border border-white/10 rounded-xl p-4 sm:p-5 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
        <div class="flex items-center justify-center gap-2 text-xs text-slate-300">
          <i class="fas fa-lock text-brand-gold"></i>
          <span>Loker Penitipan</span>
        </div>
        <div class="flex items-center justify-center gap-2 text-xs text-slate-300">
          <i class="fas fa-shower text-brand-gold"></i>
          <span>Kamar Bilas Bersih</span>
        </div>
        <div class="flex items-center justify-center gap-2 text-xs text-slate-300">
          <i class="fas fa-wifi text-brand-gold"></i>
          <span>Free High-Speed Wi-Fi</span>
        </div>
        <div class="flex items-center justify-center gap-2 text-xs text-slate-300">
          <i class="fas fa-square-parking text-brand-gold"></i>
          <span>Parkir Luas &amp; Aman</span>
        </div>
      </div>

    </section>
    <!-- ==================== END FACILITIES SECTION ==================== -->

    <!-- ==================== GOOGLE REVIEWS PROOF ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10" id="ulasan-google">
      
      <!-- Verified Rating Card -->
      <div class="bg-gradient-to-r from-[#15171f] via-[#1b1e27] to-[#15171f] border border-brand-gold/40 rounded-2xl p-6 sm:p-8 mb-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4 text-center sm:text-left">
          <div class="w-16 h-16 rounded-xl bg-black/60 border border-brand-gold/40 flex flex-col items-center justify-center shrink-0">
            <span class="text-2xl font-black text-brand-gold font-condensed">4.9</span>
            <span class="text-amber-400 text-[10px]">★★★★★</span>
          </div>
          <div>
            <h3 class="text-xl font-extrabold text-white font-condensed uppercase">Rating 4.9 di Google Maps</h3>
            <p class="text-slate-300 text-xs mt-0.5">Dinilai dari 175+ ulasan asli pengunjung &amp; member di Batam.</p>
          </div>
        </div>

        <a href="https://maps.app.goo.gl/dghCRwrLjwhYoE4H8" target="_blank" class="shrink-0 px-6 py-3 rounded-full bg-brand-gold hover:bg-brand-gold-hover text-slate-950 text-xs font-bold uppercase tracking-wider transition-colors inline-flex items-center gap-2">
          <span>Lihat Ulasan di Maps</span>
          <i class="fas fa-arrow-up-right-from-square text-[10px]"></i>
        </a>
      </div>

      <!-- 3 Short Testimonials -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        <div class="bg-[#12141a] border border-white/10 rounded-xl p-4 flex flex-col justify-between">
          <p class="text-slate-300 text-xs italic mb-3">"Tempatnya luas, alatnya super lengkap untuk beban maupun cardio. Suasananya bikin betah latihan!"</p>
          <div class="flex items-center justify-between text-[11px] text-slate-400 border-t border-white/5 pt-2">
            <span class="font-bold text-white">Rian Z.</span>
            <span class="text-amber-400">★★★★★</span>
          </div>
        </div>

        <div class="bg-[#12141a] border border-white/10 rounded-xl p-4 flex flex-col justify-between">
          <p class="text-slate-300 text-xs italic mb-3">"Staff dan coach ramah banget, diajarin gerakan yang benar. Biaya member juga sangat ramah kantong."</p>
          <div class="flex items-center justify-between text-[11px] text-slate-400 border-t border-white/5 pt-2">
            <span class="font-bold text-white">Dina S.</span>
            <span class="text-amber-400">★★★★★</span>
          </div>
        </div>

        <div class="bg-[#12141a] border border-white/10 rounded-xl p-4 flex flex-col justify-between">
          <p class="text-slate-300 text-xs italic mb-3">"Buka sampai jam 11 malam ngebantu banget buat yang kerja. Parkir gampang di Komplek Macadam."</p>
          <div class="flex items-center justify-between text-[11px] text-slate-400 border-t border-white/5 pt-2">
            <span class="font-bold text-white">Andre H.</span>
            <span class="text-amber-400">★★★★★</span>
          </div>
        </div>

      </div>

    </section>
    <!-- ==================== END GOOGLE REVIEWS SECTION ==================== -->

    <!-- ==================== 2 CABANG BATAM ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10" id="cabang-operasional">
      
      <div class="text-center mb-10">
        <span class="text-xs font-bold uppercase tracking-widest text-brand-gold block mb-1">📍 Cabang Kami</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed">
          Lokasi &amp; Jam Operasional
        </h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Cabang 1 -->
        <div class="bg-[#12141a] border-2 border-brand-gold/40 rounded-2xl p-6 flex flex-col justify-between shadow-xl">
          <div>
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-xl font-bold text-white font-condensed uppercase">Cabang I — Batu Ampar (Utama)</h3>
              <span class="text-[10px] font-bold uppercase bg-brand-gold text-slate-950 px-2.5 py-0.5 rounded-full">Pusat</span>
            </div>
            
            <p class="text-xs text-slate-300 mb-2 flex items-start gap-2">
              <i class="fas fa-location-dot text-brand-gold mt-0.5 shrink-0"></i>
              <span>Komplek Macadam, Batu Ampar, Batam 29444</span>
            </p>
            <p class="text-xs text-slate-300 mb-2 flex items-start gap-2">
              <i class="fas fa-clock text-brand-gold mt-0.5 shrink-0"></i>
              <span>Sen–Jum: 07.00–23.00 | Sab–Min: 07.00–22.00 WIB</span>
            </p>
            <p class="text-xs text-slate-300 flex items-start gap-2">
              <i class="fab fa-whatsapp text-emerald-400 mt-0.5 shrink-0"></i>
              <span>WA: +62 822-8668-0539</span>
            </p>
          </div>

          <div class="flex gap-3 mt-6 pt-4 border-t border-white/10">
            <a href="https://maps.app.goo.gl/dghCRwrLjwhYoE4H8" target="_blank" class="flex-1 py-2.5 rounded-xl bg-brand-gold text-slate-950 text-xs font-bold uppercase tracking-wider text-center hover:bg-brand-gold-hover transition-colors">
              Buka di Maps
            </a>
            <a href="https://wa.me/6282286680539?text=Halo%20Hercules%20Batu%20Ampar" target="_blank" class="flex-1 py-2.5 rounded-xl bg-white/10 text-white text-xs font-bold uppercase tracking-wider text-center hover:bg-white/15 border border-white/20 transition-colors">
              Chat WA
            </a>
          </div>
        </div>

        <!-- Cabang 2 -->
        <div class="bg-[#12141a] border border-white/15 rounded-2xl p-6 flex flex-col justify-between shadow-xl">
          <div>
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-xl font-bold text-white font-condensed uppercase">Cabang II — Batu Besar</h3>
              <span class="text-[10px] font-bold uppercase bg-white/10 text-slate-300 px-2.5 py-0.5 rounded-full">Nongsa</span>
            </div>
            
            <p class="text-xs text-slate-300 mb-2 flex items-start gap-2">
              <i class="fas fa-location-dot text-brand-gold mt-0.5 shrink-0"></i>
              <span>Jl. Dang Merdu Blok K1 No. 1, Batu Besar, Batam</span>
            </p>
            <p class="text-xs text-slate-300 mb-2 flex items-start gap-2">
              <i class="fas fa-clock text-brand-gold mt-0.5 shrink-0"></i>
              <span>Sen–Jum: 07.00–23.00 | Sab–Min: 07.00–22.00 WIB</span>
            </p>
            <p class="text-xs text-slate-300 flex items-start gap-2">
              <i class="fab fa-whatsapp text-emerald-400 mt-0.5 shrink-0"></i>
              <span>WA: +62 822-8668-0539</span>
            </p>
          </div>

          <div class="mt-6 pt-4 border-t border-white/10">
            <a href="https://wa.me/6282286680539?text=Halo%20Hercules%20Batu%20Besar" target="_blank" class="block w-full py-2.5 rounded-xl bg-white/10 text-white text-xs font-bold uppercase tracking-wider text-center hover:bg-white/15 border border-white/20 transition-colors">
              Hubungi Admin Cabang II
            </a>
          </div>
        </div>

      </div>

    </section>
    <!-- ==================== END 2 CABANG BATAM ==================== -->

    <!-- ==================== INSTAGRAM COMMUNITY ==================== -->
    <section class="py-10 border-t border-white/10" id="instagram-community">
      <div class="bg-gradient-to-r from-[#171922] via-[#1f1a26] to-[#171922] border border-white/10 rounded-2xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-4 text-center sm:text-left">
          <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 via-rose-500 to-purple-600 flex items-center justify-center text-white text-2xl shadow-lg shrink-0">
            <i class="fab fa-instagram"></i>
          </div>
          <div>
            <span class="text-pink-400 text-xs font-bold block">@hercules.fitnesscentre</span>
            <h3 class="text-lg font-bold text-white">Komunitas Fitness Batam</h3>
            <p class="text-slate-400 text-xs">1.860+ Followers • 800+ Postingan Tips &amp; Workout</p>
          </div>
        </div>

        <a href="https://www.instagram.com/hercules.fitnesscentre/?hl=en" target="_blank" class="shrink-0 px-6 py-3 rounded-full bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-500 hover:to-purple-500 text-white text-xs font-bold uppercase tracking-wider transition-all inline-flex items-center gap-2 shadow-md">
          <i class="fab fa-instagram"></i>
          <span>Buka Instagram</span>
        </a>
      </div>
    </section>

    <!-- ==================== FINAL CALL TO ACTION ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10 text-center" id="about-cta">
      <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl sm:text-5xl font-extrabold text-white uppercase font-condensed leading-tight mb-3">
          Siap Memulai <span class="italic-serif font-normal text-brand-gold normal-case">Transformasimu?</span>
        </h2>
        <p class="text-slate-400 text-xs sm:text-sm mb-8">
          Daftar sekarang atau kunjungi langsung cabang Hercules Fitness terdekat di Batam.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3">
          <a href="<?= Url::to(['site/join']) ?>" class="px-7 py-3.5 rounded-full bg-brand-gold hover:bg-brand-gold-hover text-slate-950 font-extrabold text-xs sm:text-sm uppercase tracking-wider transition-all shadow-lg hover:shadow-brand-gold/30">
            Daftar Membership
          </a>
          <a href="https://wa.me/6282286680539?text=Halo%20Admin%20Hercules,%20saya%20mau%20tanya%20membership" target="_blank" class="px-6 py-3.5 rounded-full bg-white/10 hover:bg-white/15 border border-white/20 text-white font-bold text-xs sm:text-sm uppercase tracking-wider transition-all inline-flex items-center gap-2">
            <i class="fab fa-whatsapp text-emerald-400"></i>
            <span>Konsultasi WA</span>
          </a>
        </div>
      </div>
    </section>

  </div>

</div>
<!-- END: About Us Main Container -->

<script>
window.addEventListener('load', function() {
  const cards = document.querySelectorAll('.team-card');
  if (!cards.length) return;

  cards.forEach((card) => {
    card.addEventListener('mouseenter', () => {
      cards.forEach((c) => {
        const isTarget = c === card;
        const img = c.querySelector('.team-img');
        const info = c.querySelector('.team-info');

        if (window.gsap) {
          gsap.to(c, {
            flexGrow: isTarget ? 3.5 : 1,
            duration: 0.55,
            ease: "power3.out",
            overwrite: "auto"
          });

          if (img) {
            gsap.to(img, {
              scale: isTarget ? 1.05 : 1.0,
              filter: isTarget ? "grayscale(0%) brightness(0.95)" : "grayscale(100%) brightness(0.65)",
              duration: 0.55,
              ease: "power3.out"
            });
          }

          if (info) {
            gsap.to(info, {
              opacity: isTarget ? 1 : 0,
              y: isTarget ? 0 : 16,
              duration: isTarget ? 0.4 : 0.25,
              delay: isTarget ? 0.08 : 0,
              ease: "power2.out",
              overwrite: "auto"
            });
            info.style.pointerEvents = isTarget ? 'auto' : 'none';
          }
        }
      });
    });
  });
});
</script>
