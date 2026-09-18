<?php
$this->title = 'Hercules Fitness Centre';
?>
<!-- BEGIN: HeroSection -->
<section class="relative overflow-hidden bg-slate-900 h-[100vh]" data-purpose="hero-section" id="beranda">

  <!-- Background Images (stacked, fade) -->
  <div id="hero-bg-container" class="absolute inset-0 w-full h-full">
    <img id="hero-bg-0" class="hero-bg-img absolute inset-0 w-full h-full object-cover opacity-100 transition-opacity duration-1000" style="transform: scaleX(-1);" src="/img/hero.jpg" />
    <img id="hero-bg-1" class="hero-bg-img absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=1400&q=80" />
    <img id="hero-bg-2" class="hero-bg-img absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" src="/img/hero2.jpg" />
    <img id="hero-bg-3" class="hero-bg-img absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAHYMnKblZR0lMa7wNPitWU8JjE0A-9ggcokyPWmiWxHYmCCacoPG0-HZVfR9_8ExjJD-5mpqiuxYBxd7gAojaWFgD4rGHww4vqnV6dSha2sbDaDvhIyxnlOY22pmzjTLazBS-i44mKxW9pnQuizqKQ7peyPtijKO5RBtij6I5QjvpOzc1EWfu6RHf2YnQXEl5cDh7jOHoWGevj0ZaPl1RsyXkVj9Ky6eh_GiAFlub6BrCS3YsNQ6dkog" />
    <img id="hero-bg-4" class="hero-bg-img absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000" src="/img/whyinfitnova.jpg" />
  </div>

  <!-- Dark overlays -->
  <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/20 to-transparent z-[1]"></div>
  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-black/10 z-[1]"></div>


  <!-- Content -->
  <div class="relative z-10 w-full h-[100vh] pb-8 sm:pb-12 flex flex-col justify-end">
    <div class="w-full px-6 sm:px-10 lg:px-16 relative flex flex-col justify-end h-full">

      <!-- Xbox-style Card Row: Big active card + small thumbnails -->
            <!-- Bottom Row Container -->
      <div class="flex flex-col lg:flex-row justify-between items-end w-full mt-auto z-20 pb-4 sm:pb-6 gap-8">
        
        <!-- Xbox-style Card Row (Left) -->
        <div class="flex items-end justify-start gap-2 sm:gap-4 w-full md:w-auto overflow-visible">
        <!-- Left Arrow -->
        <button id="hero-prev" class="w-10 h-10 sm:w-12 sm:h-12 shrink-0 rounded-full bg-black/40 hover:bg-brand-gold text-white flex items-center justify-center transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.5)] backdrop-blur-md z-20 border-2 border-white hover:border-brand-gold hover:scale-110 mb-4 sm:mb-6" aria-label="Previous Hero Card">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
        </button>

        <div id="hero-card-row" class="relative w-[200px] sm:w-[550px] shrink-0 h-[220px] sm:h-[280px] overflow-hidden" style="-webkit-mask-image: linear-gradient(to right, black 80%, transparent 100%); mask-image: linear-gradient(to right, black 80%, transparent 100%);">

          <!-- Card 0 -->
          <div class="hero-card-wrapper absolute bottom-0 left-0 flex flex-col items-center gap-2 transition-all duration-500 ease-out origin-bottom w-[160px] sm:w-[200px]">
            <button class="hero-select-card shrink-0 rounded-xl overflow-hidden relative transition-all duration-500 ease-out cursor-pointer border-2 w-full h-[200px] sm:h-[240px] border-brand-gold ring-2 ring-brand-gold/50 shadow-[0_0_25px_rgba(212,175,55,0.4)] pointer-events-none" data-index="0">
              <img class="w-full h-full object-cover pointer-events-none" style="transform: scaleX(-1);" src="/img/hero.jpg" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
            </button>
            <span class="hero-card-label text-xs font-semibold text-white/90 transition-opacity duration-300 whitespace-nowrap">Transform Body</span>
          </div>

          <!-- Card 1 -->
          <div class="hero-card-wrapper absolute bottom-0 left-0 flex flex-col items-center gap-2 transition-all duration-500 ease-out origin-bottom w-[160px] sm:w-[200px]">
            <button class="hero-select-card shrink-0 rounded-xl overflow-hidden relative transition-all duration-500 ease-out cursor-pointer border-2 w-full h-[200px] sm:h-[240px] border-white/20 hover:border-white/50" data-index="1">
              <img class="w-full h-full object-cover pointer-events-none" src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=400&q=80" />
              <div class="absolute inset-0 bg-black/20 transition"></div>
            </button>
            <span class="hero-card-label text-[10px] font-medium text-white/50 transition-opacity duration-300 whitespace-nowrap">Push Limits</span>
          </div>

          <!-- Card 2 -->
          <div class="hero-card-wrapper absolute bottom-0 left-0 flex flex-col items-center gap-2 transition-all duration-500 ease-out origin-bottom w-[160px] sm:w-[200px]">
            <button class="hero-select-card shrink-0 rounded-xl overflow-hidden relative transition-all duration-500 ease-out cursor-pointer border-2 w-full h-[200px] sm:h-[240px] border-white/20 hover:border-white/50" data-index="2">
              <img class="w-full h-full object-cover pointer-events-none" src="/img/hero2.jpg" />
              <div class="absolute inset-0 bg-black/20 transition"></div>
            </button>
            <span class="hero-card-label text-[10px] font-medium text-white/50 transition-opacity duration-300 whitespace-nowrap">Elevate Routine</span>
          </div>

          <!-- Card 3 -->
          <div class="hero-card-wrapper absolute bottom-0 left-0 flex flex-col items-center gap-2 transition-all duration-500 ease-out origin-bottom w-[160px] sm:w-[200px]">
            <button class="hero-select-card shrink-0 rounded-xl overflow-hidden relative transition-all duration-500 ease-out cursor-pointer border-2 w-full h-[200px] sm:h-[240px] border-white/20 hover:border-white/50" data-index="3">
              <img class="w-full h-full object-cover pointer-events-none" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAHYMnKblZR0lMa7wNPitWU8JjE0A-9ggcokyPWmiWxHYmCCacoPG0-HZVfR9_8ExjJD-5mpqiuxYBxd7gAojaWFgD4rGHww4vqnV6dSha2sbDaDvhIyxnlOY22pmzjTLazBS-i44mKxW9pnQuizqKQ7peyPtijKO5RBtij6I5QjvpOzc1EWfu6RHf2YnQXEl5cDh7jOHoWGevj0ZaPl1RsyXkVj9Ky6eh_GiAFlub6BrCS3YsNQ6dkog" />
              <div class="absolute inset-0 bg-black/20 transition"></div>
            </button>
            <span class="hero-card-label text-[10px] font-medium text-white/50 transition-opacity duration-300 whitespace-nowrap">Battle Rope</span>
          </div>

          <!-- Card 4 -->
          <div class="hero-card-wrapper absolute bottom-0 left-0 flex flex-col items-center gap-2 transition-all duration-500 ease-out origin-bottom w-[160px] sm:w-[200px]">
            <button class="hero-select-card shrink-0 rounded-xl overflow-hidden relative transition-all duration-500 ease-out cursor-pointer border-2 w-full h-[200px] sm:h-[240px] border-white/20 hover:border-white/50" data-index="4">
              <img class="w-full h-full object-cover pointer-events-none" src="/img/whyinfitnova.jpg" />
              <div class="absolute inset-0 bg-black/20 transition"></div>
            </button>
            <span class="hero-card-label text-[10px] font-medium text-white/50 transition-opacity duration-300 whitespace-nowrap">Kickboxing</span>
          </div>

        </div>

        <!-- Right Arrow -->
        <button id="hero-next" class="w-10 h-10 sm:w-12 sm:h-12 shrink-0 rounded-full bg-black/40 hover:bg-brand-gold text-white flex items-center justify-center transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.5)] backdrop-blur-md z-20 border-2 border-white hover:border-brand-gold hover:scale-110 mb-4 sm:mb-6" aria-label="Next Hero Card">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
          </svg>
        </button>
        </div> <!-- End of Cards Row -->

        <!-- Title & Info (Right) -->
        <div class="flex flex-col items-end gap-4 sm:gap-6 shrink-0 text-right z-10 max-w-[80%] sm:max-w-xl pointer-events-none">
        <div class="pointer-events-auto">
          <h1 id="hero-title" class="text-3xl sm:text-6xl lg:text-7xl font-extrabold text-white tracking-tight leading-[1.08] mb-4 transition-opacity duration-500 drop-shadow-2xl font-condensed uppercase">
            Tempat <span class="italic-serif font-normal text-brand-gold normal-case">Para Legenda</span> Tercipta
          </h1>
          <p id="hero-desc" class="text-sm sm:text-lg text-white/80 font-normal leading-relaxed max-w-md ml-auto transition-opacity duration-500 drop-shadow-md">
            Hercules Fitness Centre Batam — program latihan profesional untuk semua level. Buka 07.00–24.00.
          </p>
        </div>
        <a id="hero-cta-btn" class="group relative overflow-hidden pointer-events-auto px-8 py-4 rounded-full bg-brand-gold text-white text-sm font-bold tracking-wide transition-all duration-300 shadow-[0_10px_30px_rgba(212,175,55,0.4)] hover:shadow-[0_15px_40px_rgba(212,175,55,0.6)] hover:-translate-y-0.5 inline-flex items-center justify-center" href="<?= \yii\helpers\Url::to(['site/join']) ?>">
          <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <span id="hero-cta-fill" class="w-64 h-64 rounded-full bg-black/20 scale-0 group-hover:scale-100 group-[.is-held]:scale-100 transition-transform duration-500 group-[.is-held]:duration-[1500ms] ease-out group-[.is-held]:ease-linear z-0"></span>
          </span>
          <span class="relative z-10">MULAI SEKARANG</span>
        </a>
        </div> <!-- End of Title Info -->
        
      </div> <!-- End of Bottom Row Container -->

    </div>
  </div>

</section>

<!-- BEGIN: ProgramSection -->
<section class="py-16 md:py-24 relative bg-gradient-to-b from-amber-100 via-amber-50/30 to-white" data-purpose="fasilitas-section" id="fasilitas">
  <div class="max-w-7xl mx-auto px-6">
    <!-- Program Header Statement -->
    <div class="flex flex-col items-center text-center gap-6 mb-12">
      <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-[1.2] max-w-2xl">
        Lebih Seru Latihan <span class="italic-serif font-normal text-brand-accent" style="text-shadow: 0px 2px 4px rgba(0,0,0,0.15), 0px 1px 2px rgba(0,0,0,0.1);">Bareng Mereka.</span>
      </h2>
      <p class="text-slate-500 text-sm max-w-lg leading-relaxed">"Bukan sekadar tempat angkat beban, temukan ruang latihan yang suportif dan bikin betah."</p>
    </div>
  </div> <!-- Close max-w-7xl container -->
  <!-- Workout Category Slider Full Width -->
  <div class="relative w-full h-[550px] sm:h-[750px] flex justify-center items-center mt-8 mb-16">

    <!-- Subtle Grid Background -->
    <div class="absolute inset-0 z-0 pointer-events-none animate-grid-up" style="
    background-image: linear-gradient(to right, rgba(212, 175, 55, 0.15) 1px, transparent 1px), linear-gradient(to bottom, rgba(212, 175, 55, 0.15) 1px, transparent 1px); 
    background-size: 64px 64px;
    mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent);
    -webkit-mask-image: linear-gradient(to bottom, transparent, black 15%, black 85%, transparent);
  "></div>

    <!-- Marquee Container -->
    <style>
      @keyframes marquee {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
      }
      .animate-marquee {
        display: flex;
        width: max-content;
        animation: marquee 30s linear infinite;
      }
    </style>
    
    <div class="relative w-full overflow-hidden py-10">
      
      <div class="animate-marquee">
        <!-- Original 6 Cards -->
        
        <!-- Card 1 (Video) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover transition duration-700">
            <source src="/videos/clip.mp4" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Rizky (28)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Batu Ampar</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Fasilitas lengkap dan vibes-nya mendukung banget buat fokus latihan beban. Udah 6 bulan gabung, progress kerasa banget!"</p>
          </div>
        </div>

        <!-- Card 2 (Image) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <img alt="Member Workout" class="absolute inset-0 w-full h-full object-cover transition duration-700" src="/img/clip4.jpeg" />
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Sarah (24)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member Standard - Cabang Canggu</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Tempatnya asik buat workout sambil ketemu temen-temen baru. Kelasnya seru dan instrukturnya super ramah."</p>
          </div>
        </div>

        <!-- Card 3 (Video) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover transition duration-700">
            <source src="/videos/clip2.mp4" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Tania (31)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Seminyak</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Suka banget cardio disini, peralatannya premium dan pemandangannya bikin semangat lari terus."</p>
          </div>
        </div>

        <!-- Card 4 (Image) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <img alt="Member Workout" class="absolute inset-0 w-full h-full object-cover transition duration-700" src="/img/clip5.jpeg" />
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Yoga (29)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member Standard - Cabang Nongsa</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Suasananya asri dan tenang. Area fungsionalnya luar biasa luas buat HIIT atau sekadar peregangan santai."</p>
          </div>
        </div>

        <!-- Card 5 (Video) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover transition duration-700">
            <source src="/videos/clip3.mp4" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Hendra (35)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Batu Ampar</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Peralatannya benar-benar standar profesional. Saya bisa mencapai personal best baru berkat alat yang mumpuni di sini."</p>
          </div>
        </div>
        
        <!-- Card 6 (Image) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <img alt="Member Workout" class="absolute inset-0 w-full h-full object-cover transition duration-700" src="/img/clip6.jpeg" />
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Dina & Tiara</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Canggu</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Langganan bareng bestie selalu bikin motivasi nge-gym ga pernah luntur. Loker room-nya pw banget!"</p>
          </div>
        </div>

        <!-- Duplicated 6 Cards for infinite scroll -->
        <!-- Card 1 (Video) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover transition duration-700">
            <source src="/videos/clip.mp4" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Rizky (28)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Batu Ampar</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Fasilitas lengkap dan vibes-nya mendukung banget buat fokus latihan beban. Udah 6 bulan gabung, progress kerasa banget!"</p>
          </div>
        </div>

        <!-- Card 2 (Image) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <img alt="Member Workout" class="absolute inset-0 w-full h-full object-cover transition duration-700" src="/img/clip4.jpeg" />
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Sarah (24)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member Standard - Cabang Canggu</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Tempatnya asik buat workout sambil ketemu temen-temen baru. Kelasnya seru dan instrukturnya super ramah."</p>
          </div>
        </div>

        <!-- Card 3 (Video) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover transition duration-700">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-young-woman-running-on-a-treadmill-at-the-gym-44163-large.mp4" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Tania (31)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Seminyak</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Suka banget cardio disini, peralatannya premium dan pemandangannya bikin semangat lari terus."</p>
          </div>
        </div>

        <!-- Card 4 (Image) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <img alt="Member Workout" class="absolute inset-0 w-full h-full object-cover transition duration-700" src="/img/clip5.jpeg" />
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Yoga (29)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member Standard - Cabang Nongsa</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Suasananya asri dan tenang. Area fungsionalnya luar biasa luas buat HIIT atau sekadar peregangan santai."</p>
          </div>
        </div>

        <!-- Card 5 (Video) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover transition duration-700">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-strong-man-lifting-heavy-barbell-in-gym-44161-large.mp4" type="video/mp4">
          </video>
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Hendra (35)</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Batu Ampar</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Peralatannya benar-benar standar profesional. Saya bisa mencapai personal best baru berkat alat yang mumpuni di sini."</p>
          </div>
        </div>
        
        <!-- Card 6 (Image) -->
        <div class="w-[280px] h-[400px] sm:w-[350px] sm:h-[500px] shrink-0 relative shadow-[0_10px_40px_rgba(0,0,0,0.6)] rounded-xl overflow-hidden flex flex-col group transition-transform duration-300 hover:scale-105 select-none mx-3">
          <img alt="Member Workout" class="absolute inset-0 w-full h-full object-cover transition duration-700" src="/img/clip6.jpeg" />
          <div class="absolute inset-0 bg-black/40 transition duration-700 pointer-events-none"></div>
          <div class="absolute inset-0 bg-gradient-to-t from-[#121316] via-black/50 to-transparent pointer-events-none"></div>
          <div class="relative z-10 p-5 flex flex-col justify-end flex-grow text-white">
            <h3 class="text-xl font-bold mb-1">Dina & Tiara</h3>
            <p class="text-xs text-brand-gold font-medium mb-2">Member VIP - Cabang Canggu</p>
            <p class="text-xs text-gray-300 line-clamp-3 leading-relaxed">"Langganan bareng bestie selalu bikin motivasi nge-gym ga pernah luntur. Loker room-nya pw banget!"</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- END: ProgramSection -->

<!-- BEGIN: WhyHercules FitnessSection -->
<section class="bg-[#0E0F13] relative" data-purpose="why-hercules-fitness-centre" id="mengapa">

  <!-- Background Elements (Grid & Ambient Glows) -->
  <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">

    <!-- Subtle Cyber Grid Background -->
    <div class="absolute inset-0" style="
      background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px); 
      background-size: 64px 64px;
      mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
      -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
    "></div>

    <div class="sticky top-0 w-full h-screen">
      <!-- Left Side: Soft Slate/White Glow -->
      <div class="absolute top-0 left-0 w-[500px] sm:w-[800px] h-[500px] sm:h-[800px] bg-slate-500/10 rounded-full blur-[100px] sm:blur-[150px] -translate-x-1/2 -translate-y-1/4"></div>
      <!-- Right Side: Soft Orange Glow -->
      <div class="absolute top-1/2 right-0 w-[600px] sm:w-[900px] h-[600px] sm:h-[900px] bg-brand-gold/10 rounded-full blur-[120px] sm:blur-[180px] translate-x-1/3 -translate-y-1/2"></div>
    </div>
  </div>

  <!-- Section Header -->
  <div class="max-w-7xl mx-auto px-6 pt-24 md:pt-32 pb-16">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
      <div>
        <!-- <span class="inline-flex items-center gap-2 text-brand-gold text-xs font-bold uppercase tracking-[0.2em] mb-5">
          <span class="w-8 h-px bg-brand-gold"></span>
          Keunggulan Kami
        </span> -->
        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-[1.1]">
          Mengapa Harus<br>
          <span class="italic-serif font-normal text-slate-400 text-brand-accent">di Hercules Fitness?</span>
        </h2>
      </div>
      <p class="text-slate-400 text-sm sm:text-base leading-relaxed max-w-sm lg:text-right">
        Kami bukan sekadar gym biasa — ekosistem fitness terlengkap yang mendukung perjalanan sehat Anda dari awal hingga hasil nyata.
      </p>
    </div>
  </div>

  <!-- Sticky Stacking Cards Container -->
  <div id="why-stacking-container" class="relative max-w-7xl mx-auto px-6">

    <!-- CARD 1 — Trainer Bersertifikasi -->
    <div class="why-stack-wrapper sticky top-16 h-[85vh] mb-6" style="z-index:1;">
      <div class="why-stack-card w-full h-full relative rounded-3xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.8)] origin-top">
        <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80"
          alt="Certified Personal Trainer" class="absolute inset-0 w-full h-full object-cover object-center scale-105" />
        <div class="absolute inset-0 bg-gradient-to-r from-black/85 via-black/50 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        <!-- Content -->
        <div class="relative h-full flex flex-col justify-between p-8 sm:p-12 lg:p-16">
          <!-- <div class="flex items-center justify-between">
          <span class="text-6xl font-black text-white/100 select-none">01</span>
        </div> -->
          <div class="max-w-lg">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-5">
              Didampingi Coach<br><span class="italic-serif font-normal text-orange-300">Profesional Bersertifikat</span>
            </h3>
            <p class="text-white/70 text-sm sm:text-base leading-relaxed mb-8 max-w-md">
              Setiap anggota dipandu oleh instruktur berlisensi AAAI-ISFI &amp; ACE dengan pengalaman lebih dari 5 tahun. Bukan hanya latihan — tapi pendampingan menyeluruh untuk hasil terbaik Anda.
            </p>
            <div class="flex items-center gap-6">
              <div class="text-center">
                <p class="text-2xl font-black text-brand-gold">50+</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Coach Aktif</p>
              </div>
              <div class="w-px h-10 bg-white/20"></div>
              <div class="text-center">
                <p class="text-2xl font-black text-brand-gold">AAAI-ISFI</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Bersertifikat</p>
              </div>
              <div class="w-px h-10 bg-white/20"></div>
              <div class="text-center">
                <p class="text-2xl font-black text-brand-gold">5★</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Rating</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CARD 3 — Komunitas Aktif -->
    <div class="why-stack-wrapper sticky top-16 h-[85vh] mb-6" style="z-index:3;">
      <div class="why-stack-card w-full h-full relative rounded-3xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.8)] origin-top">
        <img src="/img/whyinfitnova.jpg"
          alt="Active Community" class="absolute inset-0 w-full h-full object-cover object-center scale-105" />
        <div class="absolute inset-0 bg-gradient-to-r from-blue-950/90 via-black/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        <div class="relative h-full flex flex-col justify-between p-8 sm:p-12 lg:p-16">
          <!-- <div class="flex items-center justify-between">
          <span class="text-6xl font-black text-white/100 select-none">03</span>
        </div> -->
          <div class="max-w-lg">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-5">
              25.000+ Member<br><span class="italic-serif font-normal text-blue-300">Saling Mendukung</span>
            </h3>
            <p class="text-white/70 text-sm sm:text-base leading-relaxed mb-8 max-w-md">
              Bergabung dengan komunitas fitness terbesar di kota ini. Group workout mingguan, challenge bulanan berhadiah, dan social run bersama setiap akhir pekan.
            </p>
            <div class="flex items-center gap-6">
              <div class="text-center">
                <p class="text-2xl font-black text-blue-400">25K+</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Member Aktif</p>
              </div>
              <div class="w-px h-10 bg-white/20"></div>
              <div class="text-center">
                <p class="text-2xl font-black text-blue-400">12x</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Event/Bulan</p>
              </div>
              <div class="w-px h-10 bg-white/20"></div>
              <div class="text-center">
                <p class="text-2xl font-black text-blue-400">98%</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Kepuasan</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CARD 4 — Hasil Terukur -->
    <div class="why-stack-wrapper sticky top-16 h-[85vh] mb-12" style="z-index:4;">
      <div class="why-stack-card w-full h-full relative rounded-3xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.8)] origin-top">
        <img src="https://images.unsplash.com/photo-1576678927484-cc907957088c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80"
          alt="Measurable Results Dashboard" class="absolute inset-0 w-full h-full object-cover object-center scale-105" />
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-950/90 via-black/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>
        <div class="relative h-full flex flex-col justify-between p-8 sm:p-12 lg:p-16">
          <!-- <div class="flex items-center justify-between">
          <span class="text-6xl font-black text-white/100 select-none">04</span>
        </div> -->
          <div class="max-w-lg">
            <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-5">
              Progress Real-Time<br><span class="italic-serif font-normal text-emerald-300">Terhubung Smart Device</span>
            </h3>
            <p class="text-white/70 text-sm sm:text-base leading-relaxed mb-8 max-w-md">
              Pantau kalori, berat badan, kekuatan otot, dan VO2 max Anda secara real-time melalui dashboard Hercules Fitness yang terhubung langsung ke smartwatch dan smart band Anda.
            </p>
            <div class="flex items-center gap-6">
              <div class="text-center">
                <p class="text-2xl font-black text-emerald-400">Real</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">-Time Data</p>
              </div>
              <div class="w-px h-10 bg-white/20"></div>
              <div class="text-center">
                <p class="text-2xl font-black text-emerald-400">10+</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Metrik Tubuh</p>
              </div>
              <div class="w-px h-10 bg-white/20"></div>
              <div class="text-center">
                <p class="text-2xl font-black text-emerald-400">Smart</p>
                <p class="text-[11px] text-white/50 uppercase tracking-wider">Device Sync</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bottom padding -->
  <div class="h-16 md:h-24"></div>

</section>
<!-- END: WhyHercules FitnessSection -->






<!-- BEGIN: ServicesSection (Diagonal Split-Screen) -->
<section class="bg-[#0b0c10] text-white h-screen overflow-hidden relative" data-purpose="workout-showcase" id="layanan">

  <!-- Intro Text Overlay -->
  <div id="services-intro" class="absolute inset-0 flex flex-col items-center justify-center z-50 pointer-events-none px-6 transition-all duration-500 bg-black/40">
    <h2 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight leading-tight mb-6 drop-shadow-[0_5px_15px_rgba(0,0,0,0.8)] text-center">
      Exclusive Benefits <br class="sm:hidden"><span class="italic-serif font-normal text-brand-gold">Included in Your</span> Membership
    </h2>
    <p class="text-slate-200 text-sm md:text-base leading-relaxed mb-8 drop-shadow-[0_2px_10px_rgba(0,0,0,0.8)] max-w-lg text-center font-medium">
      Program latihan kami didesain terstruktur agar Anda tetap fit, makan bergizi seimbang, dan merasa percaya diri setiap hari.
    </p>
  </div>

  <!-- Panels container -->
  <div class="absolute inset-0 w-full h-full">

    <!-- Panel 1: Personal Training -->
    <div id="diag-panel-1" class="absolute inset-0 overflow-hidden" style="clip-path: polygon(0% 0%, 30% 0%, 20% 100%, 0% 100%); -webkit-clip-path: polygon(0% 0%, 30% 0%, 20% 100%, 0% 100%); z-index: 10;">
      <div id="diag-overlay-1" class="absolute inset-0 bg-black/60 z-10 pointer-events-none"></div>
      <img alt="Personal Training" class="absolute inset-0 w-full h-full object-cover object-center" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCvTcFSvgH3D6K12uLcg-FiNkQZGpcELAtSngnakOFabKg7txyXohqGpvXYiEkokL07B7OQHh4FoNqpiueatLVZswgi1PJR2AkbTzsKmEURHlZWsi5HHRRE0Qw1phl_f8RNbZI0aVLsUC93ySJnFi914dgcf7GfFVAtfxg1ffHiJIAQ-GzVwc8NzWlGZVHYlCcwQ4d-yEvwNO66FVPOK5hZKX1LVd-OY6xekYJxxFXUghmRoONYfRhc1w" />
      <!-- Permanent Scrim for Text Legibility -->
      <div class="absolute inset-0 bg-gradient-to-t sm:bg-gradient-to-tr from-black/90 via-black/40 to-transparent z-10 pointer-events-none"></div>

      <div id="diag-content-1" class="absolute bottom-16 sm:bottom-32 left-8 sm:left-16 lg:left-32 opacity-0 translate-y-10 w-full max-w-sm z-20">
        <span class="px-3 py-1 rounded-full bg-brand-gold text-[11px] font-semibold text-white mb-4 inline-block shadow-lg">1-on-1 Session</span>
        <h3 class="text-4xl md:text-5xl font-extrabold text-white leading-snug mb-4 drop-shadow-xl">Personal<br>Training</h3>
        <p class="text-base md:text-lg text-slate-100 drop-shadow-lg leading-relaxed">Pendampingan instruktur tersertifikasi untuk koreksi postur dan percepatan target.</p>
      </div>
    </div>

    <!-- Panel 2: Nutrition Plans -->
    <div id="diag-panel-2" class="absolute inset-0 overflow-hidden" style="clip-path: polygon(31% 0%, 55% 0%, 45% 100%, 21% 100%); -webkit-clip-path: polygon(31% 0%, 55% 0%, 45% 100%, 21% 100%); z-index: 20;">
      <div id="diag-overlay-2" class="absolute inset-0 bg-black/60 z-10 pointer-events-none"></div>
      <img alt="Nutrition Plans" class="absolute inset-0 w-full h-full object-cover object-center" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTZLsNojdfpcgtMvCBd9K3cyaRPllgaNOwHa2QMdJqJA0NzL2nFFWbJJmzJh4W3H4tjIIXOu6pRKJIILn0zr5lr99kvSCim-cGqR1ejOTyvN3rllUx9J8kv03JPrYdTRkuW7HuYx9Xc3zOBeRP-1V8fCslzXVjEJMgXZv78yCrYHqwRkIsSmehUplZPIKGNypMj4KuwnUU0qZpPg82Ap3VHujtNbqmwDPlrF62d8yOwiBQ6FOX0dmxkA" />
      <!-- Permanent Scrim for Text Legibility -->
      <div class="absolute inset-0 bg-gradient-to-t sm:bg-gradient-to-tr from-black/90 via-black/40 to-transparent z-10 pointer-events-none"></div>

      <div id="diag-content-2" class="absolute bottom-16 sm:bottom-32 left-8 sm:left-16 lg:left-32 opacity-0 translate-y-10 w-full max-w-sm z-20">
        <span class="px-3 py-1 rounded-full bg-emerald-500 text-[11px] font-semibold text-white mb-4 inline-block shadow-lg">Dietary Support</span>
        <h3 class="text-4xl md:text-5xl font-extrabold text-white leading-snug mb-4 drop-shadow-xl">Nutrition<br>Plans</h3>
        <p class="text-base md:text-lg text-slate-100 drop-shadow-lg leading-relaxed">Menu makronutrisi harian yang lezat dan disesuaikan dengan metabolisme tubuh Anda.</p>
      </div>
    </div>

    <!-- Panel 3: Yoga & Mindfulness -->
    <div id="diag-panel-3" class="absolute inset-0 overflow-hidden" style="clip-path: polygon(56% 0%, 80% 0%, 70% 100%, 46% 100%); -webkit-clip-path: polygon(56% 0%, 80% 0%, 70% 100%, 46% 100%); z-index: 30;">
      <div id="diag-overlay-3" class="absolute inset-0 bg-black/60 z-10 pointer-events-none"></div>
      <img alt="Yoga and Mindfulness" class="absolute inset-0 w-full h-full object-cover object-center" src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80" />
      <!-- Permanent Scrim for Text Legibility -->
      <div class="absolute inset-0 bg-gradient-to-t sm:bg-gradient-to-tr from-black/90 via-black/40 to-transparent z-10 pointer-events-none"></div>

      <div id="diag-content-3" class="absolute bottom-16 sm:bottom-32 left-8 sm:left-16 lg:left-32 opacity-0 translate-y-10 w-full max-w-md z-20">
        <span class="px-3 py-1 rounded-full bg-blue-500 text-[11px] font-semibold text-white mb-4 inline-block shadow-lg">Mind & Body</span>
        <h3 class="text-4xl md:text-5xl font-extrabold text-white leading-snug mb-4 drop-shadow-xl">Yoga &<br>Mindfulness</h3>
        <p class="text-base md:text-lg text-slate-100 drop-shadow-lg leading-relaxed">Pusatkan pikiran dan lenturkan tubuh melalui sesi yoga intensif untuk keseimbangan jiwa.</p>
      </div>
    </div>

    <!-- Panel 4: Cardio & Endurance -->
    <div id="diag-panel-4" class="absolute inset-0 overflow-hidden" style="clip-path: polygon(81% 0%, 100% 0%, 100% 100%, 71% 100%); -webkit-clip-path: polygon(81% 0%, 100% 0%, 100% 100%, 71% 100%); z-index: 40;">
      <div id="diag-overlay-4" class="absolute inset-0 bg-black/60 z-10 pointer-events-none"></div>
      <img alt="Cardio & Endurance" class="absolute inset-0 w-full h-full object-cover object-center" src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1400&auto=format&fit=crop" />
      <!-- Permanent Scrim for Text Legibility -->
      <div class="absolute inset-0 bg-gradient-to-t sm:bg-gradient-to-tr from-black/90 via-black/40 to-transparent z-10 pointer-events-none"></div>

      <div id="diag-content-4" class="absolute bottom-16 sm:bottom-32 left-8 sm:left-16 lg:left-32 opacity-0 translate-y-10 w-full max-w-md z-20">
        <span class="px-3 py-1 rounded-full bg-red-600 text-[11px] font-semibold text-white mb-4 inline-block shadow-lg">High Intensity</span>
        <h3 class="text-4xl md:text-5xl font-extrabold text-white leading-snug mb-4 drop-shadow-xl">Cardio &<br>Endurance</h3>
        <p class="text-base md:text-lg text-slate-100 drop-shadow-lg leading-relaxed">Tingkatkan stamina dan bakar kalori maksimal dengan program kardio intensif kami.</p>
      </div>
    </div>

  </div>
</section>
<!-- END: ServicesSection -->

<!-- BEGIN: HowItWorksSection -->
<section class="bg-[#F3F6F8] relative overflow-hidden" data-purpose="how-it-works" id="how-it-works" style="height: 100vh; display: flex; align-items: center;">
  <div class="max-w-7xl mx-auto px-6 w-full py-12">
    <div class="text-center mb-10 relative z-10">
      <h2 class="text-sm font-bold uppercase tracking-widest text-brand-gold mb-2">Simple Steps</h2>
      <h3 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-2">How it works?</h3>
      <p class="text-slate-600 max-w-2xl mx-auto text-base">Tidak perlu bingung atau ragu. Berikut alur mudah untuk memulai sesi latihan pertama Anda di Hercules Fitness.</p>
    </div>

    <div class="flex flex-col md:flex-row gap-10 md:gap-20 items-center relative h-full">
      
      <!-- Left side: Sticky Image Container -->
      <div class="w-full md:w-1/2 rounded-3xl overflow-hidden aspect-[4/3] bg-slate-100 shadow-2xl relative" id="hiw-image-container">
         <img src="https://images.unsplash.com/photo-1512428559087-560fa5ceab42?w=800&q=80" alt="Step 1" class="absolute inset-0 w-full h-full object-cover hiw-img opacity-100" data-step="0">
         <img src="/img/barcode.jpeg" alt="Step 2" class="absolute inset-0 w-full h-full object-cover hiw-img opacity-0" data-step="1">
         <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80" alt="Step 3" class="absolute inset-0 w-full h-full object-cover hiw-img opacity-0" data-step="2">
      </div>

      <!-- Right side: Steps -->
      <div class="w-full md:w-1/2 relative py-4" id="hiw-steps-container">
        <!-- Vertical Line Track -->
        <div class="absolute left-[28px] md:left-[38px] top-8 bottom-8 w-1 bg-slate-200 hidden md:block rounded-full z-0"></div>
        <!-- Progress Line (Animated) -->
        <div class="absolute left-[28px] md:left-[38px] top-8 w-1 bg-brand-gold hidden md:block origin-top rounded-full z-0" id="hiw-progress-line" style="height: 0%;"></div>

        <!-- Step 1 -->
        <div class="hiw-step flex gap-6 md:gap-8 mb-12 md:mb-16 relative z-10 opacity-100" data-step="0">
           <div class="w-14 h-14 md:w-20 md:h-20 rounded-2xl bg-[#fefce8] border border-[#fef08a] flex items-center justify-center shrink-0 shadow-sm hiw-icon-container relative">
               <i class="fas fa-mobile-alt text-xl md:text-3xl text-brand-gold hiw-icon absolute"></i>
               <i class="fas fa-check text-xl md:text-3xl text-brand-gold hiw-check absolute opacity-0"></i>
           </div>
           <div>
               <h4 class="text-lg md:text-2xl font-bold text-slate-900 mb-1 mt-1">Klaim Pass / Registrasi</h4>
               <p class="text-slate-600 leading-relaxed text-xs md:text-sm">Pilih cabang terdekat dan isi formulir online singkat melalui halaman pendaftaran, lalu dapatkan tiket membership anda.</p>
           </div>
        </div>

        <!-- Step 2 -->
        <div class="hiw-step flex gap-6 md:gap-8 mb-12 md:mb-16 relative z-10 opacity-30" data-step="1">
           <div class="w-14 h-14 md:w-20 md:h-20 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm hiw-icon-container relative">
               <i class="fas fa-qrcode text-xl md:text-3xl text-slate-400 hiw-icon absolute"></i>
               <i class="fas fa-check text-xl md:text-3xl text-brand-gold hiw-check absolute opacity-0"></i>
           </div>
           <div>
               <div class="bg-brand-gold text-slate-900 text-[9px] font-black uppercase tracking-widest px-2 py-0.5 md:px-3 md:py-1 rounded-full shadow-md whitespace-nowrap inline-block mb-1">Akses Instan</div>
               <h4 class="text-lg md:text-2xl font-bold text-slate-900 mb-1">Datang & Scan QR</h4>
               <p class="text-slate-600 leading-relaxed text-xs md:text-sm">Kunjungi cabang pilihan Anda dan cukup <i>scan</i> QR code pada kartu keanggotaan digital yang Anda dapatkan saat pendaftaran untuk mulai berlatih.</p>
           </div>
        </div>

        <!-- Step 3 -->
        <div class="hiw-step flex gap-6 md:gap-8 relative z-10 opacity-30" data-step="2">
           <div class="w-14 h-14 md:w-20 md:h-20 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm hiw-icon-container relative">
               <i class="fas fa-dumbbell text-xl md:text-3xl text-slate-400 hiw-icon absolute"></i>
               <i class="fas fa-check text-xl md:text-3xl text-brand-gold hiw-check absolute opacity-0"></i>
           </div>
           <div>
               <h4 class="text-lg md:text-2xl font-bold text-slate-900 mb-1 mt-1">Mulai Berkeringat!</h4>
               <p class="text-slate-600 leading-relaxed text-xs md:text-sm mb-4">Nikmati sesi latihan Anda dengan nyaman. Fasilitas kami super lengkap mulai dari alat fitness standar internasional hingga shower premium.</p>
               
               <a href="<?= \yii\helpers\Url::to(['site/join']) ?>" class="inline-block px-5 py-2.5 rounded-full bg-slate-900 text-brand-gold font-bold tracking-widest uppercase hover:bg-black transition-colors shadow-lg hover:shadow-xl hover:-translate-y-0.5 text-xs">
                   Mulai <i class="fas fa-arrow-right ml-1"></i>
               </a>
           </div>
        </div>

      </div>
    </div>
  </div>
  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
        
        // Pinned ScrollTrigger Timeline
        const steps = gsap.utils.toArray('.hiw-step');
        const images = gsap.utils.toArray('.hiw-img');
        const progressLine = document.getElementById('hiw-progress-line');
        const section = document.getElementById('how-it-works');
        
        // Only run on desktop/tablet to prevent weird mobile pinning issues if viewport is too small
        if (window.innerWidth >= 768 && section && progressLine) {
            
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: section,
                    start: "top top", // Pin when section reaches top of viewport
                    end: "+=2000",    // Total scroll distance for the animation
                    pin: true,
                    scrub: 1,         // Smooth scrubbing
                }
            });
            
            // Helper function to animate step activation/deactivation
            function activateStep(index) {
                // Return a sub-timeline to inject into the main timeline
                const stl = gsap.timeline();
                
                steps.forEach((step, i) => {
                    const iconContainer = step.querySelector('.hiw-icon-container');
                    const icon = step.querySelector('.hiw-icon');
                    
                    const check = step.querySelector('.hiw-check');
                    
                    if (i === index) { // CURRENT
                        stl.to(step, { opacity: 1, duration: 0.3 }, 0);
                        stl.to(images[i], { opacity: 1, duration: 0.3 }, 0);
                        
                        stl.to(iconContainer, { backgroundColor: '#fefce8', borderColor: '#fef08a', duration: 0.3 }, 0);
                        stl.to(icon, { opacity: 1, color: '#d4af37', duration: 0.3 }, 0); // brand-gold
                        if (check) stl.to(check, { opacity: 0, duration: 0.3 }, 0);
                    } else if (i < index) { // PASSED
                        stl.to(step, { opacity: 1, duration: 0.3 }, 0); // Keep text visible (not dimmed)
                        stl.to(images[i], { opacity: 0, duration: 0.3 }, 0); // Hide image
                        
                        stl.to(iconContainer, { backgroundColor: '#fefce8', borderColor: '#d4af37', duration: 0.3 }, 0); // Make it slightly stronger gold
                        stl.to(icon, { opacity: 0, duration: 0.3 }, 0); // Hide original icon
                        if (check) stl.to(check, { opacity: 1, color: '#d4af37', duration: 0.3 }, 0); // Show checkmark
                    } else { // UPCOMING
                        stl.to(step, { opacity: 0.3, duration: 0.3 }, 0); // Dimmed
                        stl.to(images[i], { opacity: 0, duration: 0.3 }, 0);
                        
                        stl.to(iconContainer, { backgroundColor: '#ffffff', borderColor: '#e2e8f0', duration: 0.3 }, 0);
                        stl.to(icon, { opacity: 1, color: '#94a3b8', duration: 0.3 }, 0); // slate-400
                        if (check) stl.to(check, { opacity: 0, duration: 0.3 }, 0);
                    }
                });
                
                return stl;
            }

            // --- Timeline Sequence ---
            // Ensure step 0 is active initially
            tl.add(activateStep(0), 0);
            
            // 1. Line goes to 50%
            tl.to(progressLine, { height: "50%", duration: 1, ease: "none" }, 0);
            // Switch to step 1 halfway through the line's journey to 50%
            tl.add(activateStep(1), 0.5);
            
            // 2. Line goes to 100%
            tl.to(progressLine, { height: "100%", duration: 1, ease: "none" }, 1);
            // Switch to step 2 halfway through the line's journey to 100%
            tl.add(activateStep(2), 1.5);
            
        } else if (window.innerWidth < 768) {
            // For mobile, just do simple fade on scroll without pinning
            steps.forEach((step, i) => {
                ScrollTrigger.create({
                    trigger: step,
                    start: "top center+=100", 
                    end: "bottom center+=100",
                    onToggle: self => {
                        if (self.isActive) {
                            gsap.to(step, {opacity: 1, duration: 0.3});
                            gsap.to(images[i], {opacity: 1, duration: 0.3});
                            const iconContainer = step.querySelector('.hiw-icon-container');
                            const icon = step.querySelector('.hiw-icon');
                            gsap.to(iconContainer, { backgroundColor: '#fefce8', borderColor: '#fef08a', duration: 0.3 });
                            gsap.to(icon, { color: '#d4af37', duration: 0.3 });
                        } else {
                            gsap.to(step, {opacity: 0.3, duration: 0.3});
                            gsap.to(images[i], {opacity: 0, duration: 0.3});
                            const iconContainer = step.querySelector('.hiw-icon-container');
                            const icon = step.querySelector('.hiw-icon');
                            gsap.to(iconContainer, { backgroundColor: '#ffffff', borderColor: '#e2e8f0', duration: 0.3 });
                            gsap.to(icon, { color: '#94a3b8', duration: 0.3 });
                        }
                    }
                });
            });
        }
    });
  </script>
</section>
<!-- END: HowItWorksSection -->

<!-- BEGIN: FAQSection -->
<section class="py-20 md:py-32 bg-[#121316] relative overflow-hidden group" data-purpose="faq-section" id="faq">
  <style>
    @keyframes moveGridUpDiagonal {
      0% {
        background-position: 0 0;
      }

      100% {
        background-position: 40px -40px;
      }
    }

    .animate-grid-diagonal {
      animation: moveGridUpDiagonal 4s linear infinite;
    }

    @keyframes moveGridUp {
      0% {
        background-position: 0 0;
      }

      100% {
        background-position: 0 -64px;
      }
    }

    .animate-grid-up {
      animation: moveGridUp 4s linear infinite;
    }
  </style>
  <!-- Grid Line Background with Top/Bottom Fade -->
  <div class="pointer-events-none absolute inset-0 z-0 animate-grid-diagonal" style="
    background-image: 
      linear-gradient(to right, rgba(212, 175, 55, 0.1) 1px, transparent 1px),
      linear-gradient(to bottom, rgba(212, 175, 55, 0.1) 1px, transparent 1px);
    background-size: 40px 40px;
    mask-image: linear-gradient(to bottom, transparent 0%, black 15%, black 85%, transparent 100%);
    -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 15%, black 85%, transparent 100%);
  "></div>

  <div class="max-w-4xl mx-auto px-6 relative z-10">
    <h2 class="text-3xl sm:text-5xl font-extrabold text-white text-center tracking-tight mb-12">
      Pertanyaan yang Sering Diajukan
    </h2>

    <div class="space-y-4">
      <!-- FAQ Item 1 -->
      <div class="faq-item border border-white/20 rounded-xl overflow-hidden transition-colors duration-300 hover:border-brand-gold/50 bg-[#1a1c23]">
        <button class="faq-button w-full flex items-center justify-between p-6 text-left focus:outline-none" onclick="toggleFaq(this)">
          <span class="font-bold text-white text-lg">Apa yang harus saya bawa saat pertama kali ke gym?</span>
          <svg class="faq-icon w-6 h-6 text-white shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
        </button>
        <div class="faq-answer grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-in-out">
          <div class="overflow-hidden">
            <div class="px-6 pb-6 text-slate-400 text-sm leading-relaxed">
              Pakaian olahraga yang nyaman, sepatu khusus olahraga (sebaiknya sepatu training), handuk kecil untuk keringat, dan botol minum. Jangan lupa membawa gembok kecil jika Anda ingin menggunakan loker kami.
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Item 2 -->
      <div class="faq-item border border-white/20 rounded-xl overflow-hidden transition-colors duration-300 hover:border-brand-gold/50 bg-[#1a1c23]">
        <button class="faq-button w-full flex items-center justify-between p-6 text-left focus:outline-none" onclick="toggleFaq(this)">
          <span class="font-bold text-white text-lg">Berapa kali seminggu pemula sebaiknya berlatih?</span>
          <svg class="faq-icon w-6 h-6 text-white shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
        </button>
        <div class="faq-answer grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-in-out">
          <div class="overflow-hidden">
            <div class="px-6 pb-6 text-slate-400 text-sm leading-relaxed">
              Untuk pemula, kami menyarankan 2-3 kali seminggu dengan durasi 45-60 menit per sesi. Hal ini penting agar otot tubuh Anda memiliki waktu yang cukup untuk beradaptasi dan melakukan pemulihan.
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Item 3 -->
      <div class="faq-item border border-white/20 rounded-xl overflow-hidden transition-colors duration-300 hover:border-brand-gold/50 bg-[#1a1c23]">
        <button class="faq-button w-full flex items-center justify-between p-6 text-left focus:outline-none" onclick="toggleFaq(this)">
          <span class="font-bold text-white text-lg">Apakah saya perlu menyewa Personal Trainer (PT)?</span>
          <svg class="faq-icon w-6 h-6 text-white shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
        </button>
        <div class="faq-answer grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-in-out">
          <div class="overflow-hidden">
            <div class="px-6 pb-6 text-slate-400 text-sm leading-relaxed">
              Tidak wajib, namun <strong>sangat disarankan</strong> di bulan pertama. Personal Trainer akan membantu Anda mempelajari postur tubuh yang benar, cara menggunakan alat yang aman, serta menyusun program latihan yang sesuai dengan target Anda untuk mencegah cedera.
            </div>
          </div>
        </div>
      </div>

      <!-- FAQ Item 4 -->
      <div class="faq-item border border-white/20 rounded-xl overflow-hidden transition-colors duration-300 hover:border-brand-gold/50 bg-[#1a1c23]">
        <button class="faq-button w-full flex items-center justify-between p-6 text-left focus:outline-none" onclick="toggleFaq(this)">
          <span class="font-bold text-white text-lg">Badan saya terasa sangat sakit setelah latihan, apakah itu normal?</span>
          <svg class="faq-icon w-6 h-6 text-white shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
        </button>
        <div class="faq-answer grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-in-out">
          <div class="overflow-hidden">
            <div class="px-6 pb-6 text-slate-400 text-sm leading-relaxed">
              Sangat normal! Ini disebut <em>DOMS (Delayed Onset Muscle Soreness)</em>, biasa terjadi 24-48 jam setelah otot menerima stimulasi baru. Istirahatlah yang cukup, perbanyak minum air, penuhi asupan protein, dan rasa sakit tersebut perlahan akan hilang seiring tubuh Anda beradaptasi.
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  function toggleFaq(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('.faq-icon');

    if (answer.classList.contains('grid-rows-[0fr]')) {
      answer.classList.remove('grid-rows-[0fr]');
      answer.classList.add('grid-rows-[1fr]');
      icon.style.transform = 'rotate(45deg)'; // Turn '+' into 'x'
    } else {
      answer.classList.remove('grid-rows-[1fr]');
      answer.classList.add('grid-rows-[0fr]');
      icon.style.transform = 'rotate(0deg)';
    }
  }
</script>
<!-- END: FAQSection -->
<!-- BEGIN: InstagramSection -->
<section class="py-16 md:py-24 bg-[#f8f9fa] relative overflow-hidden" data-purpose="instagram-feed">

  <!-- Subtle Grid Background -->
  <div class="absolute inset-0 z-0 pointer-events-none animate-grid-up" style="
    background-image: linear-gradient(to right, rgba(212, 175, 55, 0.35) 1px, transparent 1px), linear-gradient(to bottom, rgba(212, 175, 55, 0.35) 1px, transparent 1px); 
    background-size: 80px 80px; 
    mask-image: radial-gradient(circle at center, black 30%, transparent 90%);
    -webkit-mask-image: radial-gradient(circle at center, black 30%, transparent 90%);">
  </div>

  <div id="ig-header" class="text-center mb-12 md:mb-16 px-6 relative z-10">
    <h2 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight" style="font-family: 'Inter', sans-serif;">
      Follow Us On <span class="text-[#D4AF37]">Instagram</span>
    </h2>
    <p class="text-slate-500 mt-4 max-w-xl mx-auto">Boost your motivation with high-impact daily posts from our expert trainers.</p>
  </div>

  <div id="ig-grid-container" class="w-full h-[500px] md:h-[600px] relative overflow-hidden shadow-[0_-15px_40px_rgba(212,175,55,0.3),_0_15px_40px_rgba(212,175,55,0.3)] z-20">
    <!-- Skewed Flex Container -->
    <div class="flex w-full h-full transform -skew-x-[8deg] scale-[1.05]">

      <!-- Post 1 -->
      <a href="https://instagram.com" target="_blank" class="ig-grid-item group relative flex-1 hover:flex-[3] overflow-hidden border-r-[3px] border-white cursor-pointer bg-slate-900" style="transition: flex 0.5s cubic-bezier(0.25,1,0.5,1);">
        <!-- Unskew wrapper to keep image upright -->
        <div class="absolute top-0 bottom-0 left-[-25%] w-[150%] transform skew-x-[8deg]">
          <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="IG 1">
          <!-- Overlay -->
          <div class="absolute inset-0 bg-black/50 transition-colors duration-500 group-hover:bg-gradient-to-t group-hover:from-black/90 group-hover:via-black/40 group-hover:to-transparent"></div>
        </div>
        <!-- Hover Content -->
        <div class="absolute inset-0 flex flex-col justify-end px-10 md:px-14 py-6 md:py-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 transform skew-x-[8deg]">
          <div class="translate-y-8 group-hover:translate-y-0 transition-transform duration-500 delay-100">
            <span class="font-extrabold text-white text-lg block mb-2">@herculesfitness</span>
            <p class="text-slate-200 text-sm md:text-base leading-relaxed line-clamp-2 md:line-clamp-3 mb-4">Push your limits today! 💪 Mulai perjalanan fitness Anda bersama kami dengan peralatan standar internasional.</p>
            <div class="flex items-center text-xs text-[#fcb045] font-bold">
              <i class="fab fa-instagram text-xl mr-2"></i> View on Instagram
            </div>
          </div>
        </div>
      </a>

      <!-- Post 2 -->
      <a href="https://instagram.com" target="_blank" class="ig-grid-item group relative flex-1 hover:flex-[3] overflow-hidden border-r-[3px] border-white cursor-pointer bg-slate-900" style="transition: flex 0.5s cubic-bezier(0.25,1,0.5,1);">
        <div class="absolute top-0 bottom-0 left-[-25%] w-[150%] transform skew-x-[8deg]">
          <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="IG 2">
          <div class="absolute inset-0 bg-black/50 transition-colors duration-500 group-hover:bg-gradient-to-t group-hover:from-black/90 group-hover:via-black/40 group-hover:to-transparent"></div>
        </div>
        <div class="absolute inset-0 flex flex-col justify-end px-10 md:px-14 py-6 md:py-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 transform skew-x-[8deg]">
          <div class="translate-y-8 group-hover:translate-y-0 transition-transform duration-500 delay-100">
            <span class="font-extrabold text-white text-lg block mb-2">@herculesfitness</span>
            <p class="text-slate-200 text-sm md:text-base leading-relaxed line-clamp-2 md:line-clamp-3 mb-4">Area beban yang luas menanti Anda. Tidak perlu lagi antri alat saat jam sibuk! 🔥</p>
            <div class="flex items-center text-xs text-[#fcb045] font-bold">
              <i class="fab fa-instagram text-xl mr-2"></i> View on Instagram
            </div>
          </div>
        </div>
      </a>

      <!-- Post 3 -->
      <a href="https://instagram.com" target="_blank" class="ig-grid-item group relative flex-1 hover:flex-[3] overflow-hidden border-r-[3px] border-white cursor-pointer bg-slate-900" style="transition: flex 0.5s cubic-bezier(0.25,1,0.5,1);">
        <div class="absolute top-0 bottom-0 left-[-25%] w-[150%] transform skew-x-[8deg]">
          <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="IG 3">
          <div class="absolute inset-0 bg-black/50 transition-colors duration-500 group-hover:bg-gradient-to-t group-hover:from-black/90 group-hover:via-black/40 group-hover:to-transparent"></div>
        </div>
        <div class="absolute inset-0 flex flex-col justify-end px-10 md:px-14 py-6 md:py-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 transform skew-x-[8deg]">
          <div class="translate-y-8 group-hover:translate-y-0 transition-transform duration-500 delay-100">
            <span class="font-extrabold text-white text-lg block mb-2">@herculesfitness</span>
            <p class="text-slate-200 text-sm md:text-base leading-relaxed line-clamp-2 md:line-clamp-3 mb-4">Butuh bimbingan? Personal Trainer kami siap merancang program khusus untuk goal spesifik Anda! 🎯</p>
            <div class="flex items-center text-xs text-[#fcb045] font-bold">
              <i class="fab fa-instagram text-xl mr-2"></i> View on Instagram
            </div>
          </div>
        </div>
      </a>

      <!-- Post 4 -->
      <a href="https://instagram.com" target="_blank" class="ig-grid-item group relative flex-1 hover:flex-[3] overflow-hidden border-r-[3px] border-white cursor-pointer bg-slate-900" style="transition: flex 0.5s cubic-bezier(0.25,1,0.5,1);">
        <div class="absolute top-0 bottom-0 left-[-25%] w-[150%] transform skew-x-[8deg]">
          <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=800&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="IG 4">
          <div class="absolute inset-0 bg-black/50 transition-colors duration-500 group-hover:bg-gradient-to-t group-hover:from-black/90 group-hover:via-black/40 group-hover:to-transparent"></div>
        </div>
        <div class="absolute inset-0 flex flex-col justify-end px-10 md:px-14 py-6 md:py-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 transform skew-x-[8deg]">
          <div class="translate-y-8 group-hover:translate-y-0 transition-transform duration-500 delay-100">
            <span class="font-extrabold text-white text-lg block mb-2">@herculesfitness</span>
            <p class="text-slate-200 text-sm md:text-base leading-relaxed line-clamp-2 md:line-clamp-3 mb-4">Kelas BodyPump malam ini PECAH banget! 🎉 Terima kasih buat semua member yang sudah hadir.</p>
            <div class="flex items-center text-xs text-[#fcb045] font-bold">
              <i class="fab fa-instagram text-xl mr-2"></i> View on Instagram
            </div>
          </div>
        </div>
      </a>

      <!-- Post 5 (No right border) -->
      <a href="https://instagram.com" target="_blank" class="ig-grid-item group relative flex-1 hover:flex-[3] overflow-hidden cursor-pointer bg-slate-900" style="transition: flex 0.5s cubic-bezier(0.25,1,0.5,1);">
        <div class="absolute top-0 bottom-0 left-[-25%] w-[150%] transform skew-x-[8deg]">
          <img src="https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?w=800&q=80" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="IG 5">
          <div class="absolute inset-0 bg-black/50 transition-colors duration-500 group-hover:bg-gradient-to-t group-hover:from-black/90 group-hover:via-black/40 group-hover:to-transparent"></div>
        </div>
        <div class="absolute inset-0 flex flex-col justify-end px-10 md:px-14 py-6 md:py-8 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100 transform skew-x-[8deg]">
          <div class="translate-y-8 group-hover:translate-y-0 transition-transform duration-500 delay-100">
            <span class="font-extrabold text-white text-lg block mb-2">@herculesfitness</span>
            <p class="text-slate-200 text-sm md:text-base leading-relaxed line-clamp-2 md:line-clamp-3 mb-4">Never skip leg day! 🦵🔥 Mesin leg press baru sudah mendarat di cabang Batu Ampar.</p>
            <div class="flex items-center text-xs text-[#fcb045] font-bold">
              <i class="fab fa-instagram text-xl mr-2"></i> View on Instagram
            </div>
          </div>
        </div>
      </a>

    </div>
  </div>

  <div id="ig-btn-wrap" class="text-center mt-12 md:mt-16 px-6 relative z-10">
    <a href="https://www.instagram.com/hercules.fitnesscentre/" target="_blank" class="group relative overflow-hidden inline-flex items-center justify-center px-8 py-3.5 rounded-full bg-[#D4AF37] text-slate-900 text-sm font-extrabold tracking-widest uppercase transition-all duration-300 shadow-[0_4px_15px_rgba(212,175,55,0.4)] hover:shadow-[0_6px_25px_rgba(212,175,55,0.6)] hover:-translate-y-1">
      <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
        <span class="w-64 h-64 rounded-full bg-black/20 scale-0 group-hover:scale-100 transition-transform duration-500 ease-out z-0"></span>
      </span>
      <span class="relative z-10 flex items-center">
        DISCOVER MORE <i class="fas fa-arrow-right ml-2"></i>
      </span>
    </a>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        const igTl = gsap.timeline({
          scrollTrigger: {
            trigger: "[data-purpose='instagram-feed']",
            start: "top 75%",
            toggleActions: "play none none none"
          }
        });

        // 1. Header fade up
        igTl.from("#ig-header h2, #ig-header p", {
            y: 40,
            opacity: 0,
            duration: 0.8,
            stagger: 0.2,
            ease: "power3.out"
          })
          // 2. Posts stagger in (left to right)
          .from(".ig-grid-item", {
            y: 60,
            opacity: 0,
            duration: 0.7,
            stagger: 0.15,
            ease: "power2.out"
          }, "-=0.4")
          // 3. Button pop in
          .from("#ig-btn-wrap", {
            scale: 0.8,
            opacity: 0,
            duration: 0.6,
            ease: "back.out(1.5)"
          }, "-=0.3");
      }
    });
  </script>
</section>
<!-- END: InstagramSection -->