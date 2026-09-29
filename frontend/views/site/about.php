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

  <!-- Subtle Background Grid Pattern -->
  <div class="absolute inset-0 z-0 pointer-events-none opacity-40" style="background-image: linear-gradient(to right, rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 80px 80px;"></div>

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


    <!-- ==================== GOOGLE REVIEWS PROOF ==================== -->
    <style>
      .hide-scrollbar::-webkit-scrollbar { display: none; }
      .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <section class="py-14 md:py-24 border-t border-white/10 overflow-hidden" id="ulasan-google">
      
      <!-- Header (Centered) -->
      <div class="max-w-4xl mx-auto px-6 text-center mb-16">
        <h2 class="text-3xl md:text-5xl font-extrabold text-white font-condensed uppercase mb-4">
          Apa Pengalaman Mereka Di <br><span class="text-brand-gold">Hercules Fitness?</span>
        </h2>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-4">
          <div class="flex items-center gap-2">
            <span class="text-2xl font-black text-white font-condensed">4.9/5</span>
            <span class="text-amber-400 text-lg">★★★★★</span>
          </div>
          <span class="text-slate-400 text-sm hidden sm:inline">|</span>
          <span class="text-slate-400 text-sm">Berdasarkan 175+ ulasan di Google Maps</span>
        </div>
      </div>

      <!-- Content (Split) -->
      <div class="max-w-[1600px] mx-auto px-6 sm:px-10 flex flex-col lg:flex-row gap-12 lg:gap-16">
        
        <!-- Left Column: Title & Controls -->
        <div class="lg:w-1/3 shrink-0 flex flex-col justify-between">
          <div>
            <h3 class="text-5xl md:text-6xl lg:text-7xl font-black text-white font-condensed uppercase leading-[0.95] tracking-tight">Ini Kata <span class="block text-brand-gold text-6xl md:text-8xl lg:text-[7.5rem] leading-[0.8] mt-2">Mereka</span></h3>
          </div>
          
          <!-- Arrows & Progress -->
          <div class="flex items-center gap-4 mt-8 lg:mt-0">
            <button id="scroll-left" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="flex-1 h-px bg-white/10 relative">
              <div id="scroll-progress" class="absolute left-0 top-0 h-full bg-brand-gold w-1/4 transition-all duration-300"></div>
            </div>
            <button id="scroll-right" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center text-white hover:bg-white/10 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>
        </div>

        <!-- Right Column: Horizontal Scroll -->
        <div class="lg:w-3/4 overflow-x-auto snap-x snap-mandatory hide-scrollbar flex gap-6 pb-8" id="testimonial-container">
          <?php 
            $allTestimonials = [
              ['name' => 'Rian Z.', 'text' => 'Tempatnya luas, alatnya super lengkap untuk beban maupun cardio. Suasananya bikin betah latihan!'],
              ['name' => 'Dina S.', 'text' => 'Staff dan coach ramah banget, diajarin gerakan yang benar. Biaya member juga sangat ramah kantong.'],
              ['name' => 'Andre H.', 'text' => 'Buka sampai jam 11 malam ngebantu banget buat yang kerja. Parkir gampang di Komplek Macadam.'],
              ['name' => 'Kevin T.', 'text' => 'Gym paling nyaman di area Batu Ampar. Komunitasnya saling support dan nggak intimidatif buat pemula.'],
            ];
            foreach ($allTestimonials as $testi): 
          ?>
            <div class="snap-start shrink-0 w-[280px] md:w-[320px] flex flex-col gap-6">
              
              <!-- Chat Bubble Card -->
              <div class="bg-[#12141a] border border-white/10 rounded-2xl p-6 sm:p-8 flex flex-col shadow-xl relative">
                <p class="text-slate-300 text-xs sm:text-sm italic leading-relaxed mb-6">"<?= $testi['text'] ?>"</p>
                <span class="text-amber-400 text-sm md:text-base tracking-widest block">★★★★★</span>
                
                <!-- Bubble Tail (Triangle) -->
                <div class="absolute -bottom-2.5 left-10 w-5 h-5 bg-[#12141a] border-b border-r border-white/10 rotate-45 z-10"></div>
              </div>

              <!-- Profile Area -->
              <div class="flex items-center gap-4 px-4">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($testi['name']) ?>&background=random&color=fff&size=56" alt="<?= $testi['name'] ?>" class="w-14 h-14 rounded-full border border-white/10" />
                <div class="flex flex-col">
                  <span class="font-bold text-white text-base"><?= $testi['name'] ?></span>
                  <span class="text-xs text-slate-500 mt-0.5">@<?= strtolower(str_replace([' ', '.'], '', $testi['name'])) . rand(10,99) ?></span>
                </div>
              </div>

            </div>
          <?php endforeach; ?>
        </div>
      </div>
      
      <script>
        document.addEventListener('DOMContentLoaded', () => {
          const container = document.getElementById('testimonial-container');
          const btnLeft = document.getElementById('scroll-left');
          const btnRight = document.getElementById('scroll-right');
          const progress = document.getElementById('scroll-progress');

          if(container && btnLeft && btnRight && progress) {
            const updateProgress = () => {
              const scrollLeft = container.scrollLeft;
              const maxScroll = container.scrollWidth - container.clientWidth;
              const percent = maxScroll > 0 ? (scrollLeft / maxScroll) * 100 : 0;
              progress.style.width = Math.max(10, percent) + '%';
            };

            container.addEventListener('scroll', updateProgress);
            
            btnLeft.addEventListener('click', () => {
              container.scrollBy({ left: -340, behavior: 'smooth' });
            });
            
            btnRight.addEventListener('click', () => {
              container.scrollBy({ left: 340, behavior: 'smooth' });
            });
            
            updateProgress();
          }
        });
      </script>
    </section>
    <!-- ==================== END GOOGLE REVIEWS SECTION ==================== -->

  <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-10">



    <!-- ==================== FINAL CALL TO ACTION ==================== -->
    <?php if (Yii::$app->user->isGuest): ?>
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
    <?php endif; ?>

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
