<?php
use yii\helpers\Html;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>

<html class="scroll-smooth" lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Hercules Fitness</title>
<link rel="icon" type="image/jpeg" href="/img/hercules.jpeg">
<!-- Google Fonts: Plus Jakarta Sans & Playfair Display for editorial italic accents -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Barlow+Condensed:ital,wght@0,600;0,700;0,800;0,900;1,600;1,700;1,800;1,900&family=Permanent+Marker&family=Orbitron:wght@700&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<!-- Leaflet.js Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Tailwind CSS v3 with Plugins -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              gold: '#D4AF37',
              'gold-hover': '#B8960C',
              light: '#FFFBEB',
              dark: '#0A0A0A',
              gray: '#64748B',
              border: '#E2E8F0',
              accent: '#F5D060'
            }
          },
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            serif: ['"Playfair Display"', 'serif'],
            condensed: ['"Barlow Condensed"', 'sans-serif'],
          }
        }
      }
    }
  </script>
<!-- Custom Typography and Micro-Interactions -->
<style data-purpose="typography">
    @font-face {
      font-family: 'Road Rage';
      src: url('/fonts/Road_Rage.otf') format('opentype');
      font-weight: normal;
      font-style: normal;
    }
    .font-logo-main {
      font-family: 'Road Rage', cursive;
      letter-spacing: 2px;
    }
    .font-logo-sub {
      font-family: 'Orbitron', sans-serif;
    }
    .italic-serif {
      font-family: 'Barlow Condensed', sans-serif;
      font-style: italic;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.02em;
    }
    #hero-slider img { pointer-events: none; }
  </style>
<style data-purpose="card-shadows">
    .card-soft-shadow {
      box-shadow: 0 20px 45px -15px rgba(0, 0, 0, 0.07), 0 0 1px 1px rgba(0, 0, 0, 0.03);
    }
    .hero-glass {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }
    @keyframes marquee {
      0% { transform: translateX(0%); }
      100% { transform: translateX(-50%); }
    }
    .animate-marquee {
      animation: marquee 25s linear infinite;
    }
  
    #hero-card-selector::-webkit-scrollbar {
      display: none;
    }
</style>

<!-- Choices.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
/* Custom Choices.js Styling to match Tailwind form fields */
.choices {
    margin-bottom: 0 !important;
}
.choices__inner {
    background-color: #f8fafc !important;
    border: 1px solid #cbd5e1 !important;
    border-radius: 0.5rem !important;
    padding: 0.35rem 1rem 0.1rem 1rem !important;
    min-height: 46px !important;
    font-size: 0.875rem !important;
    color: #000000 !important;
    box-shadow: none !important;
}
.choices.is-disabled .choices__inner,
.choices.is-disabled .choices__inner .choices__item,
.choices.is-disabled .choices__inner .choices__placeholder {
    background-color: #f1f5f9 !important;
    color: #000000 !important;
    cursor: not-allowed !important;
    opacity: 1 !important;
}
.choices.is-focused .choices__inner {
    border-color: #D4AF37 !important; /* brand-gold */
    box-shadow: 0 0 0 1px #D4AF37 !important;
}
.choices__list--dropdown {
    border-radius: 0.5rem !important;
    border-color: #cbd5e1 !important;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1) !important;
    margin-top: 4px;
    z-index: 50 !important;
}
.choices__list--dropdown .choices__item {
    font-size: 0.875rem !important;
    padding: 0.75rem 1rem !important;
}
.choices__list--dropdown .choices__item[data-value=""] {
    display: none !important;
}
.choices__list--dropdown .choices__item--selectable.is-highlighted {
    background-color: #f1f5f9 !important;
    color: #000000 !important;
}
.choices[data-type*="select-one"]::after {
    border: none !important;
    content: "" !important;
    height: 8px !important;
    width: 8px !important;
    border-bottom: 2px solid #94a3b8 !important;
    border-right: 2px solid #94a3b8 !important;
    transform: translateY(-50%) rotate(45deg) !important;
    right: 1.25rem !important;
    top: 45% !important;
    margin: 0 !important;
}
.choices[data-type*="select-one"].is-open::after {
    transform: translateY(-50%) rotate(225deg) !important;
    top: 55% !important;
}
</style>

    <?php $this->head() ?>
</head>
<?php $this->beginBody() ?>
<body class="bg-[#FAF9F6] text-slate-900 font-sans antialiased overflow-x-hidden">

<!-- ===== MEMBERSHIP SIDEBAR PANEL ===== -->
<!-- Overlay backdrop -->
<div id="member-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[90] opacity-0 pointer-events-none transition-opacity duration-300" onclick="closeMemberPanel()"></div>

<!-- Sidebar Tab Trigger (vertical pill on right edge) -->
<button id="member-tab-btn" onclick="toggleMemberPanel()" aria-label="Daftar Member"
  class="fixed right-0 top-1/2 -translate-y-1/2 z-[91] flex flex-col items-center justify-center gap-1.5
         bg-brand-gold hover:bg-brand-gold-hover text-white font-bold text-[11px] uppercase tracking-[0.15em]
         px-3 py-5 rounded-l-2xl shadow-xl shadow-brand-gold/30 transition-transform duration-300 hover:-translate-x-0.5 hover:shadow-2xl
         writing-mode-vertical cursor-pointer translate-x-full">
  <svg class="w-4 h-4 rotate-180 transition-transform duration-300" id="member-tab-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"/>
  </svg>
  <span style="writing-mode: vertical-rl; text-orientation: mixed;">BERGABUNG</span>
</button>

<!-- Slide-in Panel -->
<aside id="member-panel"
  class="fixed top-0 right-0 h-full w-full max-w-md z-[92] bg-white shadow-2xl shadow-black/20
         translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.32,0.72,0,1)]
         flex flex-col overflow-y-auto">

  <!-- Panel Header -->
  <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 px-8 pt-10 pb-8 relative overflow-hidden shrink-0">
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-brand-gold/20 rounded-full blur-3xl pointer-events-none"></div>
    <!-- Close Button -->
    <button onclick="closeMemberPanel()" class="absolute top-5 right-5 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition" aria-label="Tutup">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
      </svg>
    </button>
    <div class="flex items-center gap-3 mb-4">
      <img src="/img/hercules.jpeg" alt="Hercules Fitness Logo" class="w-9 h-9 rounded-full object-cover">
      <div class="flex flex-col leading-none">
        <span class="text-white text-xl font-logo-main uppercase">HERCULES</span>
        <span class="text-[9px] text-brand-gold tracking-[0.3em] font-logo-sub uppercase">Fitness</span>
      </div>
    </div>
    <h2 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight">
      Bergabung Bersama <span class="text-brand-gold">Hercules Fitness</span><br>
    </h2>
    <p class="text-slate-400 text-sm mt-2">Isi form di bawah ini dan tim kami akan segera menghubungi Anda dalam 1×24 jam.</p>
  </div>

  <!-- Form Body -->
  <form id="member-form" onsubmit="handleMemberSubmit(event)" class="flex-1 px-8 py-6 flex flex-col gap-4 bg-white overflow-y-auto">

    <!-- Full Name -->
    <div class="flex flex-col gap-1.5">
      <label class="text-xs font-bold text-slate-700 uppercase tracking-wider" for="m-name">Nama Lengkap <span class="text-red-500">*</span></label>
      <input id="m-name" type="text" required placeholder="Sesuai KTP"
        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
               focus:outline-none focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold transition"/>
    </div>

    <!-- Phone -->
    <div class="flex flex-col gap-1.5">
      <label class="text-xs font-bold text-slate-700 uppercase tracking-wider" for="m-phone">Nomor HP / WA <span class="text-red-500">*</span></label>
      <div class="flex">
        <span class="inline-flex items-center px-3 bg-slate-100 border border-slate-200 border-r-0 rounded-l-xl text-slate-600 text-sm font-medium">+62</span>
        <input id="m-phone" type="tel" required placeholder="812345678"
          class="flex-1 px-4 py-2.5 rounded-r-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
                 focus:outline-none focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold transition"/>
      </div>
    </div>

    <!-- Email -->
    <div class="flex flex-col gap-1.5">
      <label class="text-xs font-bold text-slate-700 uppercase tracking-wider" for="m-email">Email <span class="text-red-500">*</span></label>
      <input id="m-email" type="email" required placeholder="nama@email.com"
        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
               focus:outline-none focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold transition"/>
    </div>

    <!-- Kota -->
    <div class="flex flex-col gap-1.5">
      <label class="text-xs font-bold text-slate-700 uppercase tracking-wider" for="m-city">Kota <span class="text-red-500">*</span></label>
      <select id="m-city" required onchange="updateSidebarBranches()"
        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
               focus:outline-none focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold transition appearance-none cursor-pointer">
        <option value="" disabled selected hidden>Pilih Kota</option>
        <option value="Batam">Batam</option>
        <option value="Bali">Bali</option>
      </select>
    </div>

    <!-- Cabang -->
    <div class="flex flex-col gap-1.5">
      <label class="text-xs font-bold text-slate-700 uppercase tracking-wider" for="m-branch">Pilih Cabang <span class="text-red-500">*</span></label>
      <select id="m-branch" required disabled
        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
               focus:outline-none focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold transition appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
        <option value="" disabled selected hidden>Pilih kota terlebih dahulu</option>
      </select>
      <p class="text-[10px] text-slate-500">Kamu tetap bisa akses semua lokasi klub</p>
    </div>

    <!-- Target Fitness -->
    <div class="flex flex-col gap-1.5">
      <label class="text-xs font-bold text-slate-700 uppercase tracking-wider" for="m-goal">Target Fitness <span class="text-red-500">*</span></label>
      <select id="m-goal" required
        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-400
               focus:outline-none focus:ring-2 focus:ring-brand-gold/40 focus:border-brand-gold transition appearance-none cursor-pointer">
        <option value="">Pilih Target Anda</option>
        <option value="Menurunkan Berat Badan / Fat Loss">Menurunkan Berat Badan / Fat Loss</option>
        <option value="Membentuk Otot & Body Building">Membentuk Otot & Body Building</option>
        <option value="Meningkatkan Stamina & Kebugaran">Meningkatkan Stamina & Kebugaran</option>
        <option value="Gaya Hidup Sehat & Kebugaran Harian">Gaya Hidup Sehat & Kebugaran Harian</option>
        <option value="Masih Pemula / Ingin Konsultasi Dulu">Masih Pemula / Ingin Konsultasi Dulu</option>
      </select>
    </div>

    <!-- Agreement -->
    <div class="mt-2">
      <label class="flex items-start gap-2 text-[11px] text-slate-500 cursor-pointer">
        <input type="checkbox" required name="m-agreement" class="mt-0.5 w-3.5 h-3.5 rounded border-slate-300 text-brand-gold focus:ring-brand-gold transition-colors accent-brand-gold">
        <span>Saya menyetujui <a href="#" class="text-brand-gold font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-brand-gold font-semibold hover:underline">Kebijakan Privasi</a> dari Hercules Fitness.</span>
      </label>
    </div>

    <!-- Submit -->
    <button type="submit"
      class="w-full mt-2 py-3.5 rounded-xl bg-brand-gold hover:bg-brand-gold-hover text-white font-bold text-sm uppercase tracking-wider
             shadow-lg shadow-brand-gold/30 hover:shadow-xl hover:shadow-brand-gold/40
             active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2">
      Daftar Sekarang
    </button>
  </form>

  <!-- Success State (hidden) -->
  <div id="member-success" class="hidden flex-1 flex flex-col items-center justify-center px-8 py-16 text-center bg-white">
    <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mb-6">
      <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
      </svg>
    </div>
    <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Pendaftaran Berhasil!</h3>
    <p class="text-slate-500 text-sm mb-8">Tim kami akan segera menghubungi Anda dalam 1×24 jam untuk konfirmasi membership.</p>
    <button onclick="closeMemberPanel()" class="px-8 py-3 rounded-full bg-brand-gold text-white font-bold text-sm hover:bg-brand-gold-hover transition">
      Kembali ke Halaman
    </button>
  </div>
</aside>

<script>
  function toggleMemberPanel() {
    const panel = document.getElementById('member-panel');
    const isOpen = !panel.classList.contains('translate-x-full');
    isOpen ? closeMemberPanel() : openMemberPanel();
  }
  function openMemberPanel() {
    const panel = document.getElementById('member-panel');
    const overlay = document.getElementById('member-overlay');
    const icon = document.getElementById('member-tab-icon');
    panel.classList.remove('translate-x-full');
    overlay.classList.remove('opacity-0', 'pointer-events-none');
    icon.classList.add('rotate-0');
    icon.classList.remove('rotate-180');
    document.body.style.overflow = 'hidden';
  }
  function closeMemberPanel() {
    const panel = document.getElementById('member-panel');
    const overlay = document.getElementById('member-overlay');
    const icon = document.getElementById('member-tab-icon');
    panel.classList.add('translate-x-full');
    overlay.classList.add('opacity-0', 'pointer-events-none');
    icon.classList.remove('rotate-0');
    icon.classList.add('rotate-180');
    document.body.style.overflow = '';
  }
  function handleMemberSubmit(e) {
    e.preventDefault();
    document.getElementById('member-form').classList.add('hidden');
    document.getElementById('member-success').classList.remove('hidden');
    document.getElementById('member-success').classList.add('flex');
  }
  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeMemberPanel();
  });
</script>

<!-- BEGIN: PromoBanner -->
<!-- <div id="promo-banner" class="fixed top-0 left-0 right-0 z-[60] bg-[#fb923c] text-slate-900 text-[13px] font-medium h-10 flex items-center justify-center px-4 shadow-sm transition-all duration-300">
  <span>Diskon tambahan s/d 15% + Gratis s/d 2 sesi PT <a href="#" class="font-bold underline hover:text-brand-gold transition-colors">Klaim di sini</a></span>
  <button onclick="document.getElementById('promo-banner').style.display='none'; document.getElementById('main-header').classList.remove('top-10'); document.getElementById('main-header').classList.add('top-0');" class="absolute right-4 hover:text-slate-600">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
  </button>
</div> -->
<!-- END: PromoBanner -->

<?php if (Yii::$app->controller->route !== 'site/join' && Yii::$app->controller->route !== 'site/trial'): ?>
<!-- BEGIN: MainHeader -->
<header class="fixed top-0 left-0 right-0 z-50" data-purpose="site-navigation" id="main-header">
  <!-- Background Layer -->
  <div id="header-bg" class="absolute inset-0 bg-[#121316]/95 backdrop-blur-md border-b border-white/10 opacity-0 transition-opacity duration-300 pointer-events-none"></div>
  
<div class="relative max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 h-20 flex items-center justify-between z-10">
<!-- Brand Logo -->
<a class="flex items-center gap-3 group" href="/">
<img src="/img/hercules.jpeg" alt="Hercules Fitness Logo" class="w-10 h-10 rounded-full object-cover">
<div class="flex flex-col leading-none">
  <span class="text-2xl text-white font-logo-main uppercase mt-1">HERCULES</span>
  <span class="text-[10px] text-brand-gold font-logo-sub uppercase tracking-[0.3em] -mt-1">FITNESS</span>
</div>
</a>
<!-- Desktop Nav Items -->
<nav class="hidden md:flex items-center gap-8 text-sm font-medium text-white/80">
<a class="text-white hover:text-brand-gold transition-colors" href="<?= \yii\helpers\Url::to(['site/membership']) ?>">Membership</a>
<a class="hover:text-brand-gold transition-colors" href="#filosofi">About Us</a>
<a class="hover:text-brand-gold transition-colors" href="#layanan">Services</a>
<a class="hover:text-brand-gold transition-colors" href="#paket">Testimonials</a>
</nav>
<!-- Primary Action CTA & User Area -->
<div class="flex items-center gap-6">
  <?php 
  $isMockLogin = Yii::$app->session->has('mock_login');
  $isGuest = Yii::$app->user->isGuest && !$isMockLogin;
  ?>

  <?php if ($isGuest): ?>
      <a id="header-cta-btn" class="group relative overflow-hidden px-6 py-2.5 md:px-8 md:py-3 rounded-full bg-brand-gold text-white text-xs font-bold tracking-wide transition-all duration-300 shadow-[0_10px_25px_rgba(212,175,55,0.4)] hover:shadow-[0_15px_30px_rgba(212,175,55,0.6)] hover:-translate-y-0.5 inline-flex items-center justify-center opacity-0 pointer-events-none -translate-y-2" href="<?= \yii\helpers\Url::to(['site/join']) ?>">
        <span class="absolute inset-0 flex items-center justify-center pointer-events-none">
          <span class="w-[300px] h-[300px] rounded-full bg-black/20 scale-0 group-hover:scale-100 transition-transform duration-500 ease-out z-0"></span>
        </span>
        <span class="relative z-10 uppercase">Daftar Membership</span>
      </a>
  <?php endif; ?>

  <!-- User Profile / Login -->
  <div class="flex items-center gap-3 <?php echo $isGuest ? '' : 'border-l border-white/20 pl-6'; ?>">
      <?php if (!$isGuest): 
          $displayUsername = $isMockLogin ? 'AHMAD (TEST)' : Html::encode(Yii::$app->user->identity->username);
      ?>
          <div class="relative group cursor-pointer">
              <div class="text-brand-gold hover:text-white transition-colors flex items-center gap-2">
                  <i class="fa-solid fa-circle-user text-2xl group-hover:scale-110 transition-transform"></i>
                  <span class="text-xs font-bold uppercase tracking-wider hidden md:inline-block"><?= $displayUsername ?></span>
                  <i class="fa-solid fa-chevron-down text-[10px] opacity-70"></i>
              </div>
              
              <!-- Dropdown Menu -->
              <div class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.3)] border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right scale-95 group-hover:scale-100 z-50 overflow-hidden">
                  <div class="p-2">
                      <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-brand-gold rounded-lg transition-colors">
                          <i class="fa-solid fa-user-gear w-4 text-center"></i> Pengaturan
                      </a>
                      <div class="h-px bg-slate-100 my-1"></div>
                      <?= Html::a('<i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Logout', ['site/logout'], ['data' => ['method' => 'post'], 'class' => 'flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 rounded-lg transition-colors']) ?>
                  </div>
              </div>
          </div>
      <?php endif; ?>
  </div>
</div>
</div>
</header>
<!-- END: MainHeader -->
<?php endif; ?>

<?= $content ?>

<?php if (strpos(Yii::$app->request->url, '/join') === false && strpos(Yii::$app->request->url, '/trial') === false && Yii::$app->controller->route !== 'site/join' && Yii::$app->controller->route !== 'site/trial'): ?>
<!-- BEGIN: LocationMap -->
<section class="relative bg-[#0d0f13] overflow-hidden" id="lokasi">
  <!-- Section Header -->
  <div class="max-w-7xl mx-auto px-6 pt-16 pb-8">
    <div class="text-center">
      <span class="text-xs font-bold uppercase tracking-widest text-brand-gold">📍 Temukan Kami</span>
      <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mt-2">
        Kunjungi <span class="italic-serif font-normal text-brand-gold">Cabang Kami</span>
      </h2>
      <p class="text-slate-400 text-sm mt-3 max-w-lg mx-auto">Hercules Fitness hadir di Batam dan Bali untuk melayani Anda.</p>
    </div>
  </div>

  <!-- 2-Column: Map + Gallery -->
  <div class="w-full px-2 md:px-8 pb-16 max-w-[1600px] mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 lg:gap-8 max-w-none" style="height: 700px;">
      
      <!-- LEFT: Tabs & Map (60%) -->
      <div class="lg:col-span-3 flex flex-col gap-4 pl-0 sm:pl-2">
        
        <!-- Region Toggle Tabs -->
        <div class="flex gap-4 w-full border-b border-white/10 pb-2 mb-2">
          <button onclick="switchRegion('batam')" id="region-btn-batam" class="region-btn pb-2 border-b-2 border-brand-gold text-brand-gold font-bold transition-all">Wilayah Batam</button>
          <button onclick="switchRegion('bali')" id="region-btn-bali" class="region-btn pb-2 border-b-2 border-transparent text-slate-400 font-bold hover:text-white transition-all">Wilayah Bali</button>
        </div>
        
        <!-- Branch Selector Tabs (Above Map) -->
        <div class="flex flex-wrap gap-3 w-full" id="branch-buttons-container">
          <!-- Batam Branches -->
          <button onclick="flyToBranch(0)" id="branch-btn-0" data-region="batam" class="branch-btn flex-1 min-w-[140px] py-3 px-4 rounded-xl transition-all duration-300 border-2 border-brand-gold bg-brand-gold/10 flex items-center justify-center gap-3">
            <div class="w-8 h-8 rounded-full bg-brand-gold/20 flex items-center justify-center shrink-0 hidden sm:flex">
              <i class="fas fa-dumbbell text-brand-gold text-xs"></i>
            </div>
            <div class="text-left">
              <p class="text-white font-bold text-sm">Batu Ampar</p>
              <p class="text-slate-400 text-[10px] hidden sm:block">Jl. Engku Putri</p>
            </div>
          </button>
          
          <button onclick="flyToBranch(1)" id="branch-btn-1" data-region="batam" class="branch-btn flex-1 min-w-[140px] py-3 px-4 rounded-xl transition-all duration-300 border-2 border-white/10 hover:border-white/30 flex items-center justify-center gap-3">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0 hidden sm:flex">
              <i class="fas fa-dumbbell text-slate-400 text-xs"></i>
            </div>
            <div class="text-left">
              <p class="text-white font-bold text-sm">Batu Besar</p>
              <p class="text-slate-400 text-[10px] hidden sm:block">Jl. Barelang</p>
            </div>
          </button>

          <!-- Bali Branches (Hidden by default) -->
          <button onclick="flyToBranch(2)" id="branch-btn-2" data-region="bali" class="branch-btn flex-1 min-w-[140px] py-3 px-4 rounded-xl transition-all duration-300 border-2 border-white/10 hover:border-white/30 items-center justify-center gap-3 hidden">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0 hidden sm:flex">
              <i class="fas fa-dumbbell text-slate-400 text-xs"></i>
            </div>
            <div class="text-left">
              <p class="text-white font-bold text-sm">Canggu</p>
              <p class="text-slate-400 text-[10px] hidden sm:block">Jl. Raya Canggu</p>
            </div>
          </button>

          <button onclick="flyToBranch(3)" id="branch-btn-3" data-region="bali" class="branch-btn flex-1 min-w-[140px] py-3 px-4 rounded-xl transition-all duration-300 border-2 border-white/10 hover:border-white/30 items-center justify-center gap-3 hidden">
            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0 hidden sm:flex">
              <i class="fas fa-dumbbell text-slate-400 text-xs"></i>
            </div>
            <div class="text-left">
              <p class="text-white font-bold text-sm">Kuta</p>
              <p class="text-slate-400 text-[10px] hidden sm:block">Jl. Raya Kuta</p>
            </div>
          </button>
        </div>

        <!-- Map Container -->
        <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl relative flex-1 min-h-[400px]">
          <div id="hercules-map" style="height: 100%; width: 100%; z-index: 1;"></div>
        </div>
      </div>

      <!-- RIGHT: Auto-scroll Gallery (40%) -->
      <div class="lg:col-span-2 overflow-hidden relative h-full">
        <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-[#0d0f13] to-transparent z-10 pointer-events-none"></div>
        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#0d0f13] to-transparent z-10 pointer-events-none"></div>
        
        <!-- Scrollable Container -->
        <div class="h-full w-full overflow-y-auto overflow-x-hidden scrollbar-hide" id="gallery-scroll-container" style="scrollbar-width: none;">
          <div class="gallery-scroll-strip grid grid-cols-2 gap-3 p-3">
          <!-- Item 1 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=600&q=80" alt="Gym Interior" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">💪 Area Beban</p>
          </div>
          <!-- Item 2 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=600&q=80" alt="Cardio Zone" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">🏃 Zona Cardio</p>
          </div>
          <!-- Item 3 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=600&q=80" alt="Group Class" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">🔥 Kelas Grup</p>
          </div>
          <!-- Item 4 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=600&q=80" alt="Personal Training" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">🎯 Personal Training</p>
          </div>
          <!-- Duplicate for infinite loop -->
          <!-- Item 5 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=600&q=80" alt="Gym Interior" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">💪 Area Beban</p>
          </div>
          <!-- Item 6 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=600&q=80" alt="Cardio Zone" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">🏃 Zona Cardio</p>
          </div>
          <!-- Item 7 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?auto=format&fit=crop&w=600&q=80" alt="Group Class" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">🔥 Kelas Grup</p>
          </div>
          <!-- Item 8 -->
          <div class="rounded-xl overflow-hidden relative group aspect-[9/16]">
            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=600&q=80" alt="Personal Training" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <p class="absolute bottom-3 left-3 text-white text-[11px] font-bold drop-shadow-lg leading-tight">🎯 Personal Training</p>
          </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Leaflet CSS & JS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    (function() {
      const branches = [
        { name: 'Hercules Fitness — Batu Ampar', lat: 1.166568987207872, lng: 104.0090237477667, address: 'Jl. Engku Putri, Batu Ampar, Batam', hours: 'Senin–Jumat 07.00–24.00 | Sabtu–Minggu 07.00–23.00', region: 'batam' },
        { name: 'Hercules Fitness — Batu Besar', lat: 1.1402346807509265, lng: 104.11291618410677, address: 'Jl. Barelang, Batu Besar, Batam', hours: 'Senin–Jumat 07.00–24.00 | Sabtu–Minggu 07.00–23.00', region: 'batam' },
        { name: 'Hercules Fitness — Canggu', lat: -8.635666883372355, lng: 115.14250261072259, address: 'Jl. Raya Canggu, Bali', hours: 'Senin–Jumat 06.00–24.00 | Sabtu–Minggu 06.00–22.00', region: 'bali' },
        { name: 'Hercules Fitness — Kuta', lat: -8.725696688872542, lng: 115.17655350887125, address: 'Jl. Raya Kuta No.20, Badung, Bali', hours: 'Senin–Jumat 06.00–24.00 | Sabtu–Minggu 06.00–22.00', region: 'bali' }
      ];

      const map = L.map('hercules-map', {
        center: [1.1533, 104.0609], /* Midpoint */
        zoom: 12,
        zoomControl: false,
        scrollWheelZoom: false
      });

      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
      }).addTo(map);

      L.control.zoom({ position: 'bottomright' }).addTo(map);

      const goldIcon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="width:36px;height:36px;background:#D4AF37;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 20px rgba(212,175,55,0.5),0 4px 12px rgba(0,0,0,0.4);border:3px solid #fff"><i class='fas fa-dumbbell' style='color:#fff;font-size:14px'></i></div>`,
        iconSize: [36, 36],
        iconAnchor: [18, 18],
        popupAnchor: [0, -22]
      });

      const markers = branches.map((b, i) => {
        const marker = L.marker([b.lat, b.lng], { icon: goldIcon }).addTo(map);
        marker.bindPopup(`
          <div style="font-family:'Plus Jakarta Sans',sans-serif;min-width:200px">
            <h4 style="font-weight:800;font-size:13px;margin:0 0 4px 0;color:#0a0a0a">${b.name}</h4>
            <p style="font-size:11px;color:#64748B;margin:0 0 4px 0">${b.address}</p>
            <p style="font-size:10px;color:#D4AF37;font-weight:600;margin:0">🕐 ${b.hours}</p>
          </div>
        `, { className: 'hercules-popup' });
        return marker;
      });

      markers[0].openPopup();

      window.flyToBranch = function(index) {
        const b = branches[index];
        map.flyTo([b.lat, b.lng], 15, { duration: 1.2 });
        markers[index].openPopup();

        document.querySelectorAll('.branch-btn').forEach((btn) => {
          const branchIndex = parseInt(btn.id.replace('branch-btn-', ''));
          const iconContainer = btn.querySelector('.w-8');
          const icon = btn.querySelector('i');
          if (branchIndex === index) {
            btn.classList.add('border-brand-gold', 'bg-brand-gold/10');
            btn.classList.remove('border-white/10', 'hover:border-white/30');
            if(iconContainer) {
              iconContainer.classList.add('bg-brand-gold/20');
              iconContainer.classList.remove('bg-white/10');
            }
            if(icon) {
              icon.classList.add('text-brand-gold');
              icon.classList.remove('text-slate-400');
            }
          } else {
            btn.classList.remove('border-brand-gold', 'bg-brand-gold/10');
            btn.classList.add('border-white/10', 'hover:border-white/30');
            if(iconContainer) {
              iconContainer.classList.remove('bg-brand-gold/20');
              iconContainer.classList.add('bg-white/10');
            }
            if(icon) {
              icon.classList.remove('text-brand-gold');
              icon.classList.add('text-slate-400');
            }
          }
        });
      };

      window.switchRegion = function(region) {
        document.querySelectorAll('.region-btn').forEach(btn => {
          if(btn.id === 'region-btn-' + region) {
            btn.classList.add('border-brand-gold', 'text-brand-gold');
            btn.classList.remove('border-transparent', 'text-slate-400', 'hover:text-white');
          } else {
            btn.classList.remove('border-brand-gold', 'text-brand-gold');
            btn.classList.add('border-transparent', 'text-slate-400', 'hover:text-white');
          }
        });

        let firstBranchIndex = -1;
        document.querySelectorAll('.branch-btn').forEach(btn => {
          if(btn.dataset.region === region) {
            btn.classList.add('flex');
            btn.classList.remove('hidden');
            if(firstBranchIndex === -1) {
              firstBranchIndex = parseInt(btn.id.replace('branch-btn-', ''));
            }
          } else {
            btn.classList.remove('flex');
            btn.classList.add('hidden');
          }
        });

        if(firstBranchIndex !== -1) {
          flyToBranch(firstBranchIndex);
        }
      };

      // Auto-scroll gallery
      const container = document.getElementById('gallery-scroll-container');
      const strip = document.querySelector('.gallery-scroll-strip');
      if (container && strip) {
        let paused = false;
        const speed = 0.5;

        function animateGallery() {
          if (!paused) {
            container.scrollTop += speed;
            if (container.scrollTop >= strip.scrollHeight / 2) {
              container.scrollTop = 0;
            }
          }
          requestAnimationFrame(animateGallery);
        }
        animateGallery();

        container.addEventListener('mouseenter', () => { paused = true; });
        container.addEventListener('mouseleave', () => { paused = false; });
        
        // Ensure manual scroll wraps around smoothly
        container.addEventListener('scroll', () => {
          if (container.scrollTop >= strip.scrollHeight / 2) {
            container.scrollTop = 0;
          }
        });
      }
    })();
  </script>

  <style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .hercules-popup .leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .hercules-popup .leaflet-popup-tip { box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    .custom-marker { background: transparent !important; border: none !important; }
    
    /* Ultimate fix for Leaflet tile white grid lines / seams */
    .leaflet-tile { 
      border: none !important; 
      outline: none !important; 
      margin: 0 !important; 
      padding: 0 !important;
      /* Box shadow helps fill the 1px subpixel gap */
      box-shadow: 0 0 1px rgba(0, 0, 0, 0.1) !important;
    }
    .leaflet-container { background: #aad3df !important; } /* Match OSM water color */
  </style>
</section>
<!-- END: LocationMap -->
<?php endif; ?>
      

<!-- BEGIN: Footer -->
<footer class="bg-white border-t border-slate-200 pt-16 pb-12" data-purpose="page-footer">
<div class="max-w-7xl mx-auto px-6">
<!-- Footer Newsletter Compact Bar -->
<div class="pb-16 mb-16 border-b border-slate-100 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
<div>
<span class="text-xs font-bold uppercase tracking-wider text-brand-gold">• Daftar Newsletter</span>
<h4 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-1">
            Perjalanan Anda menuju tubuh yang lebih kuat <br class="hidden sm:block"/>
            dan sehat <span class="italic-serif font-normal text-brand-gold">dimulai di sini.</span>
</h4>
</div>
<div class="w-full lg:w-auto">
<p class="text-xs font-semibold text-slate-700 mb-2">Dapatkan Berita & Update Kami</p>
<div class="flex flex-col sm:flex-row gap-2">
<input class="px-4 py-2.5 rounded-xl border border-slate-300 text-xs focus:ring-brand-gold focus:border-brand-gold w-full sm:w-64" placeholder="Masukkan email Anda" type="email"/>
<button class="px-6 py-2.5 rounded-xl bg-brand-gold text-white text-xs font-bold hover:bg-brand-gold-hover transition" type="button">
              Berlangganan
            </button>
</div>
<p class="text-[11px] text-slate-400 mt-2">Dengan mendaftar, Anda menyetujui <a class="underline" href="#">Kebijakan Privasi</a> kami</p>
</div>
</div>
<!-- Main Footer 4-Columns Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-16">
<!-- Col 1: Brand & Social -->
<div class="lg:col-span-2">
<a class="flex items-center gap-3 mb-4" href="#">
<img src="/img/hercules.jpeg" alt="Hercules Fitness Logo" class="w-10 h-10 rounded-full object-cover shadow-lg shadow-brand-gold/30">
<div class="flex flex-col leading-none">
  <span class="text-2xl text-slate-900 font-logo-main uppercase mt-1">HERCULES</span>
  <span class="text-[10px] text-brand-gold font-logo-sub uppercase tracking-[0.3em] -mt-1">FITNESS</span>
</div>
</a>
<p class="text-xs text-slate-500 leading-relaxed max-w-sm mb-4">
  Pusat kebugaran terbaik di Batam dan Bali. Kami hadir untuk membantu Anda mencapai target fisik dengan program latihan profesional dan tenaga ahli bersertifikat.
</p>
<p class="text-xs text-amber-700 font-semibold mb-1">📍 Tersedia di Batam & Bali</p>
<p class="text-xs text-slate-500">🕐 Senin–Jumat: 07.00–24.00 | Sabtu–Minggu: 07.00–23.00</p>
<div class="flex items-center gap-3 text-slate-400">
<a class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:text-brand-gold transition" href="#">
<svg class="w-4 h-4 fill-current" viewbox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
</a>
<a class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:text-brand-gold transition" href="#">
<svg class="w-4 h-4 fill-current" viewbox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"></path></svg>
</a>
<a class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center hover:text-brand-gold transition" href="#">
<svg class="w-4 h-4 fill-current" viewbox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
</a>
</div>
</div>

<!-- Col 2: Quick Links -->
<div>
<p class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Beranda</p>
<ul class="space-y-2.5 text-xs text-slate-600">
<li><a class="hover:text-brand-gold transition" href="#">Gaya Hidup</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Harga Membership</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Jadwal Kelas</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Fasilitas</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Unduh Aplikasi</a></li>
</ul>
</div>
<!-- Col 3: About -->
<div>
<p class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Tentang Kami</p>
<ul class="space-y-2.5 text-xs text-slate-600">
<li><a class="hover:text-brand-gold transition" href="#">Karir</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Kepatuhan</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Misi Kami</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Layanan Kami</a></li>
<li><a class="hover:text-brand-gold transition" href="#">Program Komunitas</a></li>
</ul>
</div>
<!-- Col 4: Contact -->
<div>
<p class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Kontak Kami</p>
<ul class="space-y-2.5 text-xs text-slate-600">
<li class="flex items-start gap-1.5"><span class="text-brand-gold mt-0.5">📍</span> Batam & Bali</li>
<li class="flex items-start gap-1.5"><span class="text-brand-gold mt-0.5">📞</span> <a href="tel:+62" class="hover:text-brand-gold transition">+62 xxx-xxxx-xxxx</a></li>
<li class="flex items-start gap-1.5"><span class="text-brand-gold mt-0.5">📷</span> <a href="https://instagram.com/hercules.fitnesscentre" class="hover:text-brand-gold transition" target="_blank">@hercules.fitnesscentre</a></li>
<li class="flex items-start gap-1.5"><span class="text-brand-gold mt-0.5">🕐</span> Sen–Jum: 07.00–24.00</li>
<li class="flex items-start gap-1.5"><span class="text-brand-gold mt-0.5">🕐</span> Sab–Min: 07.00–23.00</li>
</ul>
</div>
</div>
<!-- Copyright Bar -->
<div class="pt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
<p>© 2026 Hercules Fitness. Batam & Bali. Hak Cipta Dilindungi.</p>
<div class="flex items-center gap-6">
<a class="hover:text-slate-600 transition" href="#">Kebijakan Privasi</a>
<a class="hover:text-slate-600 transition" href="#">Syarat & Ketentuan</a>
</div>
</div>
</div>
</footer>
<!-- END: Footer -->
<!-- GSAP + ScrollTrigger + Draggable CDN -->
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/ScrollTrigger.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/gsap@3/dist/Draggable.min.js"></script>
<script>
  gsap.registerPlugin(ScrollTrigger, Draggable);

  // ===== HERO ENTRANCE ANIMATION =====
  const heroTl = gsap.timeline({ defaults: { ease: "power3.out" } });

  // Navbar fade in from top
  heroTl.fromTo("#main-header", 
    { y: -40, opacity: 0 },
    { y: 0, opacity: 1, duration: 0.8 }
  );


  // Hero heading
  heroTl.fromTo("#hero-title", 
    { y: 60, opacity: 0 },
    { y: 0, opacity: 1, duration: 0.9 },
    "-=0.4"
  );

  // Hero paragraph
  heroTl.fromTo("#hero-desc", 
    { y: 40, opacity: 0 },
    { y: 0, opacity: 1, duration: 0.7 },
    "-=0.5"
  );

  // Card selector at bottom (staggered)
  heroTl.fromTo(".hero-select-card", 
    { y: 40, opacity: 0 },
    { y: 0, opacity: 1, duration: 0.5, stagger: 0.1, ease: "power2.out" },
    "-=0.3"
  );

  // ===== CONTROL HINTS BLOCK REVEAL ANIMATION =====
  heroTl.add("hintsReveal", "-=0.2");
  
  // 1. Animate the hint buttons (Horizontal Wireframe Stretch)
  const hintBtns = document.querySelectorAll(".hint-btn");
  hintBtns.forEach((btn, index) => {
    // Delay each button row (note: arrow keys have 2 buttons, we can stagger them slightly)
    const rowDelay = Math.floor(index / 1.5) * 0.15; // approximate grouping for the 3 rows
    
    heroTl.fromTo(btn, 
      { scaleX: 0, transformOrigin: "center" },
      { scaleX: 1, duration: 0.4, ease: "power3.out" },
      `hintsReveal+=${rowDelay}`
    );
    
    const content = btn.querySelector(".hint-btn-content");
    if (content) {
      heroTl.to(content, { opacity: 1, duration: 0.2 }, `hintsReveal+=${rowDelay + 0.3}`);
    }
  });

  // 2. Animate the hint text (Block Reveal & Stagger)
  const hintTexts = document.querySelectorAll(".block-reveal-text");
  hintTexts.forEach((el, index) => {
    const text = el.innerText;
    el.innerHTML = ""; // Clear existing text
    
    // Create wrapper
    const wrapper = document.createElement("span");
    wrapper.className = "relative inline-block overflow-hidden align-middle";
    
    // Create sliding block
    const block = document.createElement("span");
    block.className = "absolute inset-0 bg-white z-10 origin-left scale-x-0 pointer-events-none";
    wrapper.appendChild(block);

    // Create text container
    const textContainer = document.createElement("span");
    textContainer.className = "opacity-0 flex";
    
    // Split text into spans for staggered typing effect
    text.split("").forEach(char => {
      const charSpan = document.createElement("span");
      charSpan.innerText = char === " " ? "\u00A0" : char;
      textContainer.appendChild(charSpan);
    });
    
    wrapper.appendChild(textContainer);
    el.appendChild(wrapper);

    // Add to hero timeline with stagger delay based on index
    // Synchronize with the button animations (0.2 delay to start slightly after button appears)
    const delay = (index * 0.15) + 0.2;
    
    heroTl.to(block, { scaleX: 1, duration: 0.35, ease: "power3.inOut" }, `hintsReveal+=${delay}`)
          .set(textContainer, { opacity: 1 }, `hintsReveal+=${delay + 0.35}`)
          .to(block, { scaleX: 0, duration: 0.35, ease: "power3.inOut", transformOrigin: "right" }, `hintsReveal+=${delay + 0.35}`)
          .from(textContainer.children, { opacity: 0, x: -3, duration: 0.05, stagger: 0.015 }, `hintsReveal+=${delay + 0.35}`);
  });

  // ===== NAVBAR SCROLL EFFECT (Active only past Hero section) =====
  const headerElem = document.getElementById("main-header");
  let isHideHeaderForced = false;

  ScrollTrigger.create({
    start: "top -80px", // Activates when scrolled 80px down
    end: "max", // Keeps active until the very bottom of the page
    onUpdate: (self) => {
      if (isHideHeaderForced) return; // Skip if in Services section
      if (self.direction === 1) { // Scrolling down
        gsap.to(headerElem, { yPercent: -100, duration: 0.3, overwrite: "auto" });
      } else { // Scrolling up
        gsap.to(headerElem, { yPercent: 0, duration: 0.3, overwrite: "auto" });
      }
    },
    onLeaveBack: () => {
      if (isHideHeaderForced) return;
      // Ensure header is fully visible when scrolling back to top
      gsap.to(headerElem, { yPercent: 0, duration: 0.3, overwrite: "auto" });
    }
  });

  // ===== BERGABUNG BUTTON VISIBILITY =====
  ScrollTrigger.create({
    trigger: "[data-purpose='hero-section']",
    start: "bottom 70%", // Triggers when the bottom of the hero section reaches 70% from the top of viewport
    onEnter: () => document.getElementById("member-tab-btn").classList.remove("translate-x-full"),
    onLeaveBack: () => document.getElementById("member-tab-btn").classList.add("translate-x-full"),
  });

  // ===== HELPER: Create scroll-triggered fade-in =====
  function animateOnScroll(selector, fromVars, triggerVars = {}) {
    gsap.from(selector, {
      scrollTrigger: {
        trigger: selector,
        start: triggerVars.start || "top 85%",
        toggleActions: "play none none none",
        ...triggerVars,
      },
      duration: 0.8,
      ease: "power2.out",
      ...fromVars,
    });
  }

  // ===== MISSION / PHILOSOPHY SECTION =====
  animateOnScroll("[data-purpose='mission-philosophy'] h2", { y: 50, opacity: 0, duration: 0.9 });
  animateOnScroll("[data-purpose='mission-philosophy'] .max-w-md", { y: 40, opacity: 0, duration: 0.7 }, { start: "top 80%" });

  // Workout category cards - stagger
  gsap.from("[data-purpose='mission-philosophy'] .grid > div", {
    scrollTrigger: {
      trigger: "[data-purpose='mission-philosophy']",
      start: "top 95%",
      toggleActions: "play none none none",
    },
    y: 50,
    opacity: 0,
    duration: 0.7,
    stagger: 0.15,
    ease: "power2.out",
  });



  // ===== WORKOUT SHOWCASE – DIAGONAL SPLIT SCREEN =====
  (function() {
    const diagSection = document.getElementById("layanan");
    if (!diagSection) return;

    // Create a master timeline pinned to the section
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: diagSection,
        start: "top top",
        end: "+=4000",
        scrub: 1,
        pin: true,
        anticipatePin: 1,
        onEnter: () => { isHideHeaderForced = true; gsap.to(headerElem, { yPercent: -100, duration: 0.3, overwrite: "auto" }); },
        onLeave: () => { isHideHeaderForced = false; },
        onEnterBack: () => { isHideHeaderForced = true; gsap.to(headerElem, { yPercent: -100, duration: 0.3, overwrite: "auto" }); },
        onLeaveBack: () => { isHideHeaderForced = false; gsap.to(headerElem, { yPercent: 0, duration: 0.3, overwrite: "auto" }); }
      }
    });

    // Define proxy objects with initial numeric values for the polygons
    const p1 = { t: 30, b: 20 };
    const p2 = { tl: 31, tr: 55, br: 45, bl: 21 };
    const p3 = { tl: 56, tr: 80, br: 70, bl: 46 };
    const p4 = { tl: 81, bl: 71 };

    const panel1 = document.getElementById("diag-panel-1");
    const panel2 = document.getElementById("diag-panel-2");
    const panel3 = document.getElementById("diag-panel-3");
    const panel4 = document.getElementById("diag-panel-4");

    // Unified onUpdate to render all panels each frame
    const renderPanels = () => {
      panel1.style.clipPath = `polygon(0% 0%, ${p1.t}% 0%, ${p1.b}% 100%, 0% 100%)`;
      panel1.style.webkitClipPath = `polygon(0% 0%, ${p1.t}% 0%, ${p1.b}% 100%, 0% 100%)`;
      
      panel2.style.clipPath = `polygon(${p2.tl}% 0%, ${p2.tr}% 0%, ${p2.br}% 100%, ${p2.bl}% 100%)`;
      panel2.style.webkitClipPath = `polygon(${p2.tl}% 0%, ${p2.tr}% 0%, ${p2.br}% 100%, ${p2.bl}% 100%)`;
      
      panel3.style.clipPath = `polygon(${p3.tl}% 0%, ${p3.tr}% 0%, ${p3.br}% 100%, ${p3.bl}% 100%)`;
      panel3.style.webkitClipPath = `polygon(${p3.tl}% 0%, ${p3.tr}% 0%, ${p3.br}% 100%, ${p3.bl}% 100%)`;

      panel4.style.clipPath = `polygon(${p4.tl}% 0%, 100% 0%, 100% 100%, ${p4.bl}% 100%)`;
      panel4.style.webkitClipPath = `polygon(${p4.tl}% 0%, 100% 0%, 100% 100%, ${p4.bl}% 100%)`;
    };

    // 1. Intro fades out
    tl.to("#services-intro", { opacity: 0, y: -30, duration: 0.5 });

    // Accordion State 1: Panel 1 Active
    // P1 pushes P2, P3, P4 into thin strips on the right
    tl.to(p1, { t: 76, b: 66, duration: 1, onUpdate: renderPanels }, "-=0.2")
      .to(p2, { tl: 76, tr: 84, br: 74, bl: 66, duration: 1 }, "<")
      .to(p3, { tl: 84, tr: 92, br: 82, bl: 74, duration: 1 }, "<")
      .to(p4, { tl: 92, bl: 82, duration: 1 }, "<")
      .to("#diag-overlay-1", { opacity: 0, duration: 1 }, "<")
      .to("#diag-content-1", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

    tl.to({}, { duration: 0.3 }); // Pause

    // Accordion State 2: Panel 2 Active
    // P2 left edge sweeps to 0, completely covering P1 on the left side
    tl.to(p2, { tl: 0, bl: 0, duration: 1, onUpdate: renderPanels })
      .to("#diag-overlay-1", { opacity: 1, duration: 1 }, "<")
      .to("#diag-content-1", { opacity: 0, y: 10, duration: 0.3 }, "<")
      .to("#diag-overlay-2", { opacity: 0, duration: 1 }, "<")
      .to("#diag-content-2", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

    tl.to({}, { duration: 0.3 }); // Pause

    // Accordion State 3: Panel 3 Active
    // P3 left edge sweeps to 0, completely covering P2 and P1
    tl.to(p3, { tl: 0, bl: 0, duration: 1, onUpdate: renderPanels })
      .to("#diag-overlay-2", { opacity: 1, duration: 1 }, "<")
      .to("#diag-content-2", { opacity: 0, y: 10, duration: 0.3 }, "<")
      .to("#diag-overlay-3", { opacity: 0, duration: 1 }, "<")
      .to("#diag-content-3", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

    tl.to({}, { duration: 0.3 }); // Pause

    // Accordion State 4: Panel 4 Active
    // P4 left edge sweeps to 0, completely covering everything on the left
    tl.to(p4, { tl: 0, bl: 0, duration: 1, onUpdate: renderPanels })
      .to("#diag-overlay-3", { opacity: 1, duration: 1 }, "<")
      .to("#diag-content-3", { opacity: 0, y: 10, duration: 0.3 }, "<")
      .to("#diag-overlay-4", { opacity: 0, duration: 1 }, "<")
      .to("#diag-content-4", { opacity: 1, y: 0, duration: 0.3 }, "-=0.3");

  })();

  // ===== WHY HERCULES — STICKY STACKING CARDS =====
  // Header fade in
  animateOnScroll("[data-purpose='why-hercules-fitness-centre'] .max-w-7xl > div", { y: 50, opacity: 0, duration: 0.9 });

  // Scale-down effect: each card shrinks as the next card covers it
  const stackCards = gsap.utils.toArray(".why-stack-card");
  const stackWrappers = gsap.utils.toArray(".why-stack-wrapper");
  stackCards.forEach((card, i) => {
    // Don't apply scale-out to the last card
    if (i < stackCards.length - 1) {
      gsap.to(card, {
        scale: 0.92,
        opacity: 0.6,
        borderRadius: "2.5rem",
        ease: "none",
        scrollTrigger: {
          trigger: stackWrappers[i + 1],
          start: "top 80%",
          end: "top top",
          scrub: true,
        }
      });
    }
  });


  // ===== PRICING SECTION =====

  animateOnScroll("[data-purpose='pricing-membership'] .text-center", { y: 40, opacity: 0, duration: 0.8 });

  gsap.from("[data-purpose='pricing-membership'] .grid > div", {
    scrollTrigger: {
      trigger: "[data-purpose='pricing-membership'] .grid",
      start: "top 80%",
      toggleActions: "play none none none",
    },
    y: 70,
    opacity: 0,
    scale: 0.92,
    duration: 0.7,
    stagger: 0.18,
    ease: "back.out(1.4)",
  });

  // ===== NEWSLETTER SECTION =====
  animateOnScroll("[data-purpose='newsletter-exclusive-perks'] .rounded-3xl", {
    y: 50,
    opacity: 0,
    scale: 0.97,
    duration: 0.9,
  });

  // ===== BOTTOM CTA =====
  animateOnScroll("[data-purpose='final-call-to-action'] h2", {
    y: 40,
    opacity: 0,
    duration: 0.8,
  });
  animateOnScroll("[data-purpose='final-call-to-action'] a", {
    y: 20,
    opacity: 0,
    scale: 0.9,
    duration: 0.6,
  }, { start: "top 85%" });

  // ===== FOOTER =====
  gsap.from("footer .grid > div", {
    scrollTrigger: {
      trigger: "footer .grid",
      start: "top 90%",
      toggleActions: "play none none none",
    },
    y: 30,
    opacity: 0,
    duration: 0.5,
    stagger: 0.1,
    ease: "power2.out",
  });

  // ===== PS CONSOLE HERO CARD SELECTOR =====
  const heroCards = document.querySelectorAll(".hero-select-card");
  const heroBgImgs = document.querySelectorAll(".hero-bg-img");
  const heroTitle = document.getElementById("hero-title");
  const heroDesc = document.getElementById("hero-desc");
  const heroLabels = document.querySelectorAll(".hero-card-label");

  const heroData = [
    {
      title: 'Ubah <span class="italic-serif font-normal text-brand-accent">Tubuhmu</span>',
      desc: 'Ambil kendali kesehatan Anda dengan program kebugaran yang dirancang khusus untuk mencapai bentuk tubuh ideal dan gaya hidup sehat Anda.'
    },
    {
      title: 'Lampaui <span class="italic-serif font-normal text-brand-accent">Batasmu</span>',
      desc: 'Bergabunglah dengan komunitas kebugaran kami, dan temukan seberapa jauh batas kemampuan fisik Anda dapat dilampaui setiap harinya.'
    },
    {
      title: 'Tingkatkan <span class="italic-serif font-normal text-brand-accent">Rutinitasmu</span>',
      desc: 'Temukan keseimbangan sempurna antara sesi latihan beban intensif dan teknik pemulihan yang tepat untuk hasil kebugaran yang maksimal.'
    },
    {
      title: 'Kuasai <span class="italic-serif font-normal text-brand-accent">Setiap Gerakan</span>',
      desc: 'Tingkatkan daya tahan kardiovaskular dan bakar lemak dengan sesi battle rope yang dinamis dan berintensitas tinggi secara menyeluruh.'
    },
    {
      title: 'Bertarung Penuh <span class="italic-serif font-normal text-brand-accent">Semangat</span>',
      desc: 'Lepaskan stres dan tingkatkan ketangkasan Anda melalui perpaduan teknik tinju dan tendangan yang menantang namun sangat menyenangkan.'
    }
  ];

  let activeHeroCard = 0;
  let heroAutoSlide;

  function setActiveHeroCard(index) {
    activeHeroCard = index;

    // Reset Auto Slide
    clearInterval(heroAutoSlide);
    heroAutoSlide = setInterval(() => {
      setActiveHeroCard((activeHeroCard + 1) % heroData.length);
    }, 5000);

    // Fade backgrounds
    heroBgImgs.forEach((img, i) => {
      img.style.opacity = i === index ? "1" : "0";
    });

    // Resize cards + glow + labels + left-aligned slide effect
    heroCards.forEach((card, i) => {
      const label = heroLabels[i];
      const wrapper = card.parentElement;
      
      let diff = i - index;
      if (diff < -1) diff += heroCards.length;
      if (diff > 3) diff -= heroCards.length;
      
      // Calculate positions for left-aligned track
      let isMobile = window.innerWidth < 640;
      let activeW = isMobile ? 180 : 200;
      let inactiveW = isMobile ? 100 : 120;
      let gap = isMobile ? 12 : 16;

      let x = 0;
      if (diff === -1) {
        x = -(inactiveW + gap);
      } else if (diff === 0) {
        x = 0;
      } else {
        x = activeW + gap + (diff - 1) * (inactiveW + gap);
      }

      const prevDiff = wrapper.dataset.diff !== undefined ? parseInt(wrapper.dataset.diff) : diff;
      wrapper.dataset.diff = diff;

      // Disable transition when jumping from end to beginning
      if (Math.abs(prevDiff - diff) > 2) {
        wrapper.style.transition = "none";
        wrapper.style.transform = `translateX(${x}px)`;
        void wrapper.offsetWidth; // Force reflow
      }
      
      // Apply to wrapper
      wrapper.style.transition = ""; // Restore CSS transition
      wrapper.style.transform = `translateX(${x}px)`;
      wrapper.style.width = diff === 0 ? `${activeW}px` : `${inactiveW}px`;
      wrapper.style.zIndex = 50 - Math.abs(diff);
      wrapper.style.opacity = diff === -1 ? "0" : "1";

      if (diff === 0) {
        // Active: big card with glow
        card.style.height = isMobile ? "220px" : "240px";
        card.classList.remove("border-white/20");
        card.classList.add("border-brand-gold", "ring-2", "ring-brand-gold/50", "pointer-events-none");
        card.style.boxShadow = "0 0 25px rgba(212,175,55,0.4)";
        if (label) {
          label.classList.remove("text-white/50", "text-[10px]", "font-medium");
          label.classList.add("text-white/90", "text-xs", "font-semibold");
        }
      } else {
        // Inactive: small thumbnail, no glow
        card.style.height = isMobile ? "130px" : "150px";
        card.classList.add("border-white/20");
        card.classList.remove("border-brand-gold", "ring-2", "ring-brand-gold/50", "pointer-events-none");
        card.style.boxShadow = "none";
        if (label) {
          label.classList.add("text-white/50", "text-[10px]", "font-medium");
          label.classList.remove("text-white/90", "text-xs", "font-semibold");
        }
      }
    });

    // Animate text
    if (heroTitle && heroDesc) {
      heroTitle.style.opacity = "0";
      heroDesc.style.opacity = "0";
      
      setTimeout(() => {
        heroTitle.innerHTML = heroData[index].title;
        heroDesc.textContent = heroData[index].desc;
        heroTitle.style.opacity = "1";
        heroDesc.style.opacity = "1";
      }, 350);
    }
  }

  // Bind click
  heroCards.forEach((card) => {
    card.addEventListener("click", () => {
      const idx = parseInt(card.dataset.index);
      if (idx !== activeHeroCard) {
        setActiveHeroCard(idx);
      }
    });
  });

  // Hero Arrow Navigation
  const heroPrev = document.getElementById("hero-prev");
  const heroNext = document.getElementById("hero-next");
  if (heroPrev) heroPrev.addEventListener("click", () => setActiveHeroCard((activeHeroCard - 1 + heroData.length) % heroData.length));
  if (heroNext) heroNext.addEventListener("click", () => setActiveHeroCard((activeHeroCard + 1) % heroData.length));

  // Initialize first card immediately
  setActiveHeroCard(0);

  // Keyboard navigation
  document.addEventListener("keydown", (e) => {
    const heroSection = document.getElementById("beranda");
    const rect = heroSection.getBoundingClientRect();
    if (rect.top > window.innerHeight || rect.bottom < 0) return;

    if (e.key === "ArrowRight") {
      setActiveHeroCard((activeHeroCard + 1) % heroData.length);
    } else if (e.key === "ArrowLeft") {
      setActiveHeroCard((activeHeroCard - 1 + heroData.length) % heroData.length);
    }
  });

  // FAQ Accordion Logic
  window.toggleFaq = function(btn) {
    const answer = btn.nextElementSibling;
    const icon = btn.querySelector('.faq-icon');
    
    // Check if currently open
    const isOpen = answer.style.gridTemplateRows === '1fr';
    
    // Close all other FAQs (accordion style)
    document.querySelectorAll('.faq-answer').forEach(el => {
      el.style.gridTemplateRows = '0fr';
    });
    document.querySelectorAll('.faq-icon').forEach(el => {
      el.style.transform = 'rotate(0deg)';
      el.classList.remove('text-brand-gold');
    });
    document.querySelectorAll('.faq-item').forEach(el => {
      el.classList.remove('border-brand-gold/50');
    });

    if (!isOpen) {
      answer.style.gridTemplateRows = '1fr';
      icon.style.transform = 'rotate(135deg)';
      icon.classList.add('text-brand-gold');
      btn.parentElement.classList.add('border-brand-gold/50');
    }
  };

  // FAQ Spotlight Effect
  const faqSection = document.getElementById("faq");
  
  if (faqSection) {
    faqSection.addEventListener("mousemove", (e) => {
      const rect = faqSection.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      
      faqSection.style.setProperty("--mouse-x", `${x}px`);
      faqSection.style.setProperty("--mouse-y", `${y}px`);
    });
  }

  // Hold Enter to Start Journey Logic
  const heroCtaFill = document.getElementById("hero-cta-fill");
  const heroCtaBtn = document.getElementById("hero-cta-btn");
  let enterHoldTimer;
  let isEnterHeld = false;
  
  if (heroCtaFill && heroCtaBtn) {
    document.addEventListener("keydown", (e) => {
      if (e.key === "Enter" && !e.repeat && !isEnterHeld) {
        // Prevent default enter behavior if needed
        isEnterHeld = true;
        
        // Use Tailwind class to trigger the exact same scale animation
        heroCtaBtn.classList.add("is-held");
        
        // Add active push-down effect to button
        heroCtaBtn.style.transform = "translateY(2px)";
        heroCtaBtn.style.boxShadow = "0 5px 15px rgba(212,175,55,0.3)";
        
        enterHoldTimer = setTimeout(() => {
          // Completed
          window.location.href = "<?= \yii\helpers\Url::to(['site/join']) ?>";
        }, 1500);
      }
    });

    document.addEventListener("keyup", (e) => {
      if (e.key === "Enter") {
        isEnterHeld = false;
        clearTimeout(enterHoldTimer);
        
        // Revert styles
        heroCtaBtn.classList.remove("is-held");
        
        // Revert button
        heroCtaBtn.style.transform = "";
        heroCtaBtn.style.boxShadow = "";
      }
    });
  }

  window.addEventListener("load", () => {
    ScrollTrigger.refresh();
  });
</script>

<!-- Program Info Modal -->
<div id="program-info-modal" class="fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
  <div class="absolute inset-0 bg-black/80 backdrop-blur-sm cursor-pointer" onclick="closeProgramModal()"></div>
  <div class="relative bg-white rounded-xl shadow-2xl w-[90%] max-w-4xl flex flex-col md:flex-row overflow-hidden transform scale-95 transition-transform duration-300" id="program-modal-content">
    
    <!-- Close button -->
    <button onclick="closeProgramModal()" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/20 hover:bg-black/60 text-white flex items-center justify-center transition-colors backdrop-blur-md">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    
    <!-- Left: Text -->
    <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center order-2 md:order-1">
      <h2 id="modal-title" class="text-3xl font-extrabold text-slate-900 mb-2 leading-tight">Program Title</h2>
      <p id="modal-subtitle" class="text-brand-gold font-bold text-sm mb-4">High Intensity • 45 min</p>
      <p id="modal-desc" class="text-slate-600 mb-6 leading-relaxed">Detailed description goes here...</p>
      
      <!-- Info Tambahan -->
      <ul class="text-sm text-slate-700 space-y-3 mb-8 bg-slate-50 p-4 rounded-xl border border-slate-100">
        <li class="flex items-start gap-3">
          <i class="fa-regular fa-clock text-brand-gold mt-0.5"></i>
          <div><strong class="text-slate-900">Jadwal:</strong> Senin & Rabu (19.00 WIB)</div>
        </li>
        <li class="flex items-start gap-3">
          <i class="fa-solid fa-shirt text-brand-gold mt-0.5"></i>
          <div><strong class="text-slate-900">Perlengkapan:</strong> Pakaian olahraga nyaman & membawa botol minum.</div>
        </li>
        <li class="flex items-start gap-3">
          <i class="fa-solid fa-bolt text-brand-gold mt-0.5"></i>
          <div><strong class="text-slate-900">Manfaat Utama:</strong> Meningkatkan kekuatan inti tubuh & membakar lemak.</div>
        </li>
      </ul>

      <a href="#paket" onclick="closeProgramModal()" class="inline-flex w-max items-center justify-center px-8 py-3.5 bg-brand-gold hover:bg-brand-gold-hover text-white rounded-full font-bold transition-colors shadow-lg shadow-brand-gold/30">
        JOIN PROGRAM
      </a>
    </div>
    
    <!-- Right: Image -->
    <div class="w-full md:w-1/2 h-64 md:h-auto relative order-1 md:order-2">
      <img id="modal-img" src="" alt="Program Image" class="absolute inset-0 w-full h-full object-cover">
    </div>
  </div>
</div>

<script>
function openProgramModal(btn, e) {
  e.stopPropagation();
  const card = btn.closest('.cf-card');
  const title = card.querySelector('h3').innerText;
  const subtitle = card.querySelector('.text-brand-gold').innerText;
  const desc = card.querySelector('.line-clamp-2').innerText;
  const imgSrc = card.querySelector('img').src;
  
  document.getElementById('modal-title').innerText = title;
  document.getElementById('modal-subtitle').innerText = subtitle;
  document.getElementById('modal-desc').innerText = desc;
  document.getElementById('modal-img').src = imgSrc;
  
  const modal = document.getElementById('program-info-modal');
  modal.classList.remove('opacity-0', 'pointer-events-none');
  document.getElementById('program-modal-content').classList.remove('scale-95');
}

function closeProgramModal() {
  const modal = document.getElementById('program-info-modal');
  modal.classList.add('opacity-0', 'pointer-events-none');
  document.getElementById('program-modal-content').classList.add('scale-95');
}
</script>

<!-- Floating WhatsApp Button -->
<a id="wa-button" href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" 
   class="fixed bottom-6 right-6 z-[60] bg-[#25D366] hover:bg-[#20b858] text-white w-14 h-14 rounded-full shadow-lg shadow-[#25D366]/40 transition-all duration-300 transform scale-0 opacity-0 flex items-center justify-center">
  <i class="fab fa-whatsapp text-3xl"></i>
</a>

<script>
  // Transparent to Solid Header transition & WhatsApp Button
  const headerBg = document.getElementById("header-bg");
  const programSection = document.getElementById("program");
  const waButton = document.getElementById("wa-button");
  const heroSection = document.getElementById("beranda");
  const headerCtaBtn = document.getElementById("header-cta-btn");
  
  const isTrialPage = window.location.href.includes('site%2Fjoin') || window.location.href.includes('site/join');
  const hasNoHero = !heroSection;

  if (isTrialPage || hasNoHero) {
    if (headerBg) {
      headerBg.classList.remove("opacity-0");
      headerBg.classList.add("opacity-100");
    }
    if (isTrialPage && headerCtaBtn) {
      headerCtaBtn.style.display = 'none';
    }
  }

  window.addEventListener("scroll", () => {
    if (isTrialPage || hasNoHero) return; // Keep it solid on trial page or pages without hero

    const threshold = programSection ? programSection.offsetTop - 80 : 100;
    
    if (window.scrollY >= threshold) {
      if (headerBg) {
        headerBg.classList.remove("opacity-0");
        headerBg.classList.add("opacity-100");
      }
      if (headerCtaBtn) {
        headerCtaBtn.classList.remove("opacity-0", "pointer-events-none", "-translate-y-2");
        headerCtaBtn.classList.add("opacity-100", "pointer-events-auto", "translate-y-0");
      }
    } else {
      if (headerBg) {
        headerBg.classList.add("opacity-0");
        headerBg.classList.remove("opacity-100");
      }
      if (headerCtaBtn) {
        headerCtaBtn.classList.add("opacity-0", "pointer-events-none", "-translate-y-2");
        headerCtaBtn.classList.remove("opacity-100", "pointer-events-auto", "translate-y-0");
      }
    }

    if (waButton) {
      const waThreshold = heroSection ? (heroSection.offsetHeight * 0.8) : 500;
      if (window.scrollY >= waThreshold) {
        waButton.classList.remove("scale-0", "opacity-0");
        waButton.classList.add("scale-100", "opacity-100");
      } else {
        waButton.classList.add("scale-0", "opacity-0");
        waButton.classList.remove("scale-100", "opacity-100");
      }
    }
  }, { passive: true });
</script>

<!-- Choices.js Library -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Choices for Goal
    if(document.getElementById('m-goal')) {
        new Choices('#m-goal', {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false
        });
    }

    // Initialize City
    if(document.getElementById('m-city')) {
        window.sidebarCityChoice = new Choices('#m-city', {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false,
            placeholderValue: 'Pilih Kota'
        });
    }

    // Initialize Branch
    if(document.getElementById('m-branch')) {
        window.sidebarBranchChoice = new Choices('#m-branch', {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false
        });
    }
});

// Update branches dynamically for the sidebar
function updateSidebarBranches() {
    if(!window.sidebarBranchChoice) return;
    
    const city = document.getElementById('m-city').value;
    window.sidebarBranchChoice.clearChoices();
    window.sidebarBranchChoice.clearStore();
    
    if (city === 'Batam') {
        window.sidebarBranchChoice.enable();
        window.sidebarBranchChoice.setChoices([
            { value: 'Batam Centre', label: 'Batam Centre', selected: false },
            { value: 'Batu Aji', label: 'Batu Aji', selected: false },
            { value: 'Nagoya', label: 'Nagoya', selected: false }
        ], 'value', 'label', true);
    } else if (city === 'Bali') {
        window.sidebarBranchChoice.enable();
        window.sidebarBranchChoice.setChoices([
            { value: 'Kuta', label: 'Kuta', selected: false },
            { value: 'Denpasar', label: 'Denpasar', selected: false },
            { value: 'Canggu', label: 'Canggu', selected: false }
        ], 'value', 'label', true);
    } else {
        window.sidebarBranchChoice.setChoices([
            { value: '', label: 'Pilih kota terlebih dahulu', disabled: true, selected: true }
        ], 'value', 'label', true);
        window.sidebarBranchChoice.disable();
    }
}
</script>

<?php $this->endBody() ?>
</body></html><?php $this->endPage() ?>
