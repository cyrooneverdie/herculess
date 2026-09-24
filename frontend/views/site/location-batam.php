<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Lokasi Batam - Hercules Fitness Centre';
$this->params['meta_description'] = 'Temukan lokasi cabang Hercules Fitness di Batam. 3 cabang strategis: Batu Ampar, Batu Besar, dan MTC Batam.';
?>

<div class="bg-[#0b0c10] text-slate-100 min-h-screen pb-20 font-sans relative overflow-hidden">

  <!-- ==================== HERO SECTION ==================== -->
  <section class="relative h-[70vh] min-h-[500px] w-full flex items-center justify-center overflow-hidden bg-slate-950" id="location-hero">
    <div class="absolute inset-0 z-0 w-full h-full">
      <img src="/img/lokasibatam.jpeg" alt="Hercules Fitness Batam" class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-black/30 z-[1]"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-transparent to-transparent z-[1]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 w-full text-center">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-4 max-w-3xl mx-auto">
        Lokasi <span class="text-brand-gold">Batam</span>
      </h1>
      <p class="text-base sm:text-lg text-slate-300 font-light leading-relaxed max-w-xl mx-auto">
        3 cabang strategis di Batam untuk mendukung perjalanan kebugaran Anda.
      </p>
    </div>
  </section>

  <!-- ==================== BRANCHES ==================== -->
  <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-10">

    <section class="py-16 md:py-24" id="cabang-batam">
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-4">
          Pilih <span class="text-brand-gold">Cabang Anda</span>
        </h2>
        <p class="text-slate-400 text-sm sm:text-base max-w-lg mx-auto">Semua cabang dilengkapi fasilitas lengkap dan coach berpengalaman.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Cabang 1: Batu Ampar -->
        <div class="group bg-[#141418] border border-white/5 rounded-2xl overflow-hidden hover:border-brand-gold/30 transition-all duration-300">
          <div class="relative h-52 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?w=600&h=400&fit=crop" alt="Hercules Fitness Batu Ampar" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#141418] to-transparent"></div>
            <span class="absolute top-4 left-4 bg-brand-gold/90 text-slate-950 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full">Pusat</span>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-white mb-2">Batu Ampar</h3>
            <p class="text-slate-400 text-sm leading-relaxed mb-4">Cabang utama Hercules Fitness. Terletak di Komplek Macadam dengan area latihan luas dan fasilitas terlengkap.</p>
            <div class="space-y-3 text-xs text-slate-400">
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📍</span>
                <span>Komplek Macadam, Batu Ampar, Batam</span>
              </div>
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">🕐</span>
                <span>Senin - Sabtu: 07:00 - 22:00<br/>Minggu: 08:00 - 20:00</span>
              </div>
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📞</span>
                <span>0822-8668-0539</span>
              </div>
            </div>
            <div class="mt-6 flex flex-col gap-2">
              <a href="<?= Url::to(['site/location-detail', 'id' => 'batu-ampar']) ?>" class="w-full block text-center py-3 rounded-lg bg-brand-gold text-slate-900 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition-colors">
                Lihat Detail Cabang
              </a>

            </div>
          </div>
        </div>

        <!-- Cabang 2: Batu Besar -->
        <div class="group bg-[#141418] border border-white/5 rounded-2xl overflow-hidden hover:border-brand-gold/30 transition-all duration-300">
          <div class="relative h-52 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1571902943202-507ec2618e8f?w=600&h=400&fit=crop" alt="Hercules Fitness Batu Besar" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#141418] to-transparent"></div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-white mb-2">Batu Besar</h3>
            <p class="text-slate-400 text-sm leading-relaxed mb-4">Cabang Batu Besar hadir untuk Anda di kawasan Nongsa. Suasana nyaman dengan peralatan modern.</p>
            <div class="space-y-3 text-xs text-slate-400">
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📍</span>
                <span>Jl. Dang Merdu, Batu Besar, Batam</span>
              </div>
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">🕐</span>
                <span>Senin - Sabtu: 07:00 - 22:00<br/>Minggu: 08:00 - 20:00</span>
              </div>
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📞</span>
                <span>0822-8668-0539</span>
              </div>
            </div>
            <div class="mt-6 flex flex-col gap-2">
              <a href="<?= Url::to(['site/location-detail', 'id' => 'batu-besar']) ?>" class="w-full block text-center py-3 rounded-lg bg-brand-gold text-slate-900 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition-colors">
                Lihat Detail Cabang
              </a>

            </div>
          </div>
        </div>

        <!-- Cabang 3: MTC -->
        <div class="group bg-[#141418] border border-white/5 rounded-2xl overflow-hidden hover:border-brand-gold/30 transition-all duration-300">
          <div class="relative h-52 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1593079831268-3381b0db4a77?w=600&h=400&fit=crop" alt="Hercules Fitness MTC" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#141418] to-transparent"></div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-white mb-2">MTC Batam</h3>
            <p class="text-slate-400 text-sm leading-relaxed mb-4">Cabang MTC terletak di pusat kota Batam. Akses mudah dan cocok untuk Anda yang berkegiatan di area sekitar.</p>
            <div class="space-y-3 text-xs text-slate-400">
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📍</span>
                <span>MTC, Batam Centre, Batam</span>
              </div>
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">🕐</span>
                <span>Senin - Sabtu: 07:00 - 22:00<br/>Minggu: 08:00 - 20:00</span>
              </div>
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📞</span>
                <span>0822-8668-0539</span>
              </div>
            </div>
            <div class="mt-6 flex flex-col gap-2">
              <a href="<?= Url::to(['site/location-detail', 'id' => 'mtc']) ?>" class="w-full block text-center py-3 rounded-lg bg-brand-gold text-slate-900 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition-colors">
                Lihat Detail Cabang
              </a>

            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- ==================== CTA ==================== -->
    <section class="py-16 border-t border-white/10 text-center">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-white uppercase font-condensed mb-4">Tertarik Bergabung?</h2>
      <p class="text-slate-400 text-sm sm:text-base mb-8 max-w-md mx-auto">Daftar sekarang dan mulai latihan di cabang terdekat Anda.</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <?php 
        $isMockLogin = Yii::$app->session->has('mock_login');
        $isGuest = Yii::$app->user->isGuest && !$isMockLogin;
        if ($isGuest): 
        ?>
          <a href="<?= Url::to(['site/join']) ?>" class="px-8 py-3.5 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors inline-block">
            Daftar Membership
          </a>
        <?php else: ?>
          <a href="<?= Url::to(['site/membership']) ?>" class="px-8 py-3.5 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors inline-block">
            Lihat Paket Membership
          </a>
        <?php endif; ?>
        <a href="<?= Url::to(['site/location-bali']) ?>" class="px-8 py-3.5 rounded border border-slate-600 text-slate-300 hover:text-white hover:border-white text-sm font-bold uppercase tracking-wide transition-colors inline-block">
          Lihat Cabang Bali →
        </a>
      </div>
    </section>

  </div>
</div>
