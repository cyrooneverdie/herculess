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
      <img src="/img/lingkungan_gym.png" alt="Hercules Fitness Centre Gym" class="w-full h-full object-cover" />
      <!-- Dark overlays matching index.php: true image visibility -->
      <div class="absolute inset-0 bg-gradient-to-r from-black/65 via-black/30 to-transparent z-[1]"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-black/20 to-black/10 z-[1]"></div>
    </div>

    <!-- Content Container (Clean & Grounded) -->
    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 w-full text-center pt-20">
      
      <!-- Main Title -->
      <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white uppercase font-condensed mb-4 drop-shadow-lg leading-[1.1] max-w-3xl mx-auto">
        Lebih Dari Sekadar <br/>
        <span class="text-brand-gold">Tempat Latihan.</span>
      </h1>

      <!-- Description (Grounded, NO buzzwords) -->
      <p class="text-base sm:text-lg text-slate-200 font-light leading-relaxed max-w-2xl mx-auto mb-8 drop-shadow-md">
        Kami membangun komunitas di mana setiap orang dapat berlatih dengan aman. Mulai dari pemula hingga atlet, Anda akan didukung oleh fasilitas memadai dan lingkungan yang saling menghargai.
      </p>

      <!-- CTA Button (Specific action, no decorative arrow, solid color) -->
      <!-- <div class="flex justify-center">
        <a href="<?= Url::to(['site/join']) ?>" class="px-8 py-3.5 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors inline-block shadow-lg">
          Lihat Paket Membership
        </a>
      </div> -->

    </div>

  </section>
  <!-- ==================== END HERO SECTION ==================== -->

  <!-- Content Container for Below Sections -->
  <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-10">

    <!-- ==================== STORY & PHILOSOPHY (CLEAN & GROUNDED) ==================== -->
    <section class="py-16 md:py-24 border-t border-white/10" id="filosofi">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        
        <!-- Left: Image Visual -->
        <div class="relative">
          <div class="rounded-2xl overflow-hidden aspect-[4/5] bg-[#12141a]">
            <img src="/img/hero2.jpg" alt="Suasana latihan di Hercules Fitness Batam" class="w-full h-full object-cover" />
          </div>
          <!-- Solid accent block instead of glowing glassmorphism -->
          <div class="absolute -bottom-6 -right-6 w-48 h-48 bg-brand-gold rounded-2xl -z-10 hidden sm:block"></div>
        </div>

        <!-- Right: Story Narrative -->
        <div class="lg:pr-8">
          <h2 class="text-3xl sm:text-5xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-8">
            Dedikasi Total Untuk <span class="text-brand-gold">Kebugaran Anda.</span>
          </h2>
          
          <div class="space-y-6 text-slate-300 text-sm sm:text-base leading-relaxed font-light">
            <p>
              Berawal dari sebuah visi sederhana, Hercules Fitness Centre didirikan untuk menghadirkan tempat latihan yang memadai, nyaman, dan bersahabat bagi seluruh warga Batam. Kami percaya bahwa ruang kebugaran harus menjadi tempat yang ramah untuk semua kalangan.
            </p>
            <p>
              <strong class="text-white font-medium">Tanpa rasa canggung dan intimidasi</strong>, kami membangun lingkungan di mana seorang pemula yang baru pertama kali menyentuh barbel dapat berlatih dengan nyaman berdampingan dengan para atlet berpengalaman.
            </p>
            <p>
              Fokus kami sejak awal hingga saat ini tetap sama: menyediakan fasilitas berstandar profesional dan membangun komunitas yang saling mendukung, namun dengan komitmen harga yang masuk akal dan terjangkau untuk warga Batam.
            </p>
          </div>

        </div>
      </div>
    </section>
    <!-- ==================== END STORY SECTION ==================== -->



    <!-- ==================== PROFESSIONAL TEAM SECTION ==================== -->
    <section class="py-14 md:py-20 border-t border-white/10" id="professional-team">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
        
        <!-- Left: Narrative -->
        <div class="order-2 lg:order-1 lg:pr-8">
          <span class="text-xs font-bold uppercase tracking-widest text-brand-gold block mb-3">Tim Pelatih &amp; Instruktur</span>
          <h2 class="text-3xl sm:text-5xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-8">
            Tim Profesional <span class="text-brand-gold">Siap Membimbing Anda.</span>
          </h2>
          
          <div class="space-y-6 text-slate-300 text-sm sm:text-base leading-relaxed font-light">
            <p>
              Bimbingan langsung dari pelatih berpengalaman untuk memastikan program latihan Anda terarah, efektif, dan aman. Kami memiliki pelatih dengan spesialisasi yang beragam mulai dari kekuatan &amp; pengondisian, mobilitas &amp; HIIT, hingga binaraga &amp; teknik latihan.
            </p>
            <p>
              <strong class="text-white font-medium">Bukan sekadar mengawasi</strong>, pelatih kami merancang program yang disesuaikan dengan kapasitas dan tujuan spesifik Anda. Setiap sesi akan menjadi langkah yang terstruktur menuju hasil yang Anda inginkan.
            </p>
            <p>
              Temukan pelatih yang tepat untuk Anda, dan mulailah perjalanan kebugaran Anda dengan bimbingan profesional yang suportif tanpa tekanan berlebihan.
            </p>
            
            <div class="pt-4">
              <a href="/site/trainer" class="px-8 py-3.5 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors inline-block">
                Lihat Profil Pelatih Kami
              </a>
            </div>
          </div>
        </div>

        <!-- Right: Image Visual -->
        <div class="relative order-1 lg:order-2 pl-0 sm:pl-6">
          <div class="rounded-2xl overflow-hidden aspect-[4/5] bg-[#12141a]">
            <img src="/img/clip4.jpeg" alt="Pelatih Hercules Fitness membimbing member" class="w-full h-full object-cover" />
          </div>
          <!-- Solid accent block on the bottom left (since image is on the right) -->
          <div class="absolute -bottom-6 -left-6 w-48 h-48 bg-brand-gold rounded-2xl -z-10 hidden sm:block"></div>
        </div>

      </div>
    </section>
    <!-- ==================== END PROFESSIONAL TEAM SECTION ==================== -->



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
