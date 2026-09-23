<?php

declare(strict_types=1);

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Lokasi Bali - Hercules Fitness Centre';
$this->params['meta_description'] = 'Temukan lokasi cabang Hercules Fitness di Bali. 2 cabang di kawasan strategis: Canggu dan Kuta.';
?>

<div class="bg-[#0b0c10] text-slate-100 min-h-screen pb-20 font-sans relative overflow-hidden">

  <!-- ==================== HERO SECTION ==================== -->
  <section class="relative h-[70vh] min-h-[500px] w-full flex items-center justify-center overflow-hidden bg-slate-950" id="location-hero">
    <div class="absolute inset-0 z-0 w-full h-full">
      <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1920&h=1080&fit=crop" alt="Hercules Fitness Bali" class="w-full h-full object-cover" />
      <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-black/30 z-[1]"></div>
      <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-transparent to-transparent z-[1]"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 w-full text-center">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-4 max-w-3xl mx-auto">
        Lokasi <span class="text-brand-gold">Bali</span>
      </h1>
      <p class="text-base sm:text-lg text-slate-300 font-light leading-relaxed max-w-xl mx-auto">
        2 cabang di pusat kegiatan Bali untuk mendampingi rutinitas latihan Anda.
      </p>
    </div>
  </section>

  <!-- ==================== BRANCHES ==================== -->
  <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-10 lg:px-16 pt-10">

    <section class="py-16 md:py-24" id="cabang-bali">
      <div class="text-center mb-16">
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white uppercase font-condensed leading-[1.1] mb-4">
          Pilih <span class="text-brand-gold">Cabang Anda</span>
        </h2>
        <p class="text-slate-400 text-sm sm:text-base max-w-lg mx-auto">Fasilitas modern di tengah suasana Bali yang khas.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">

        <!-- Cabang 1: Canggu -->
        <div class="group bg-[#141418] border border-white/5 rounded-2xl overflow-hidden hover:border-brand-gold/30 transition-all duration-300">
          <div class="relative h-56 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1558611848-73f7eb4001a1?w=600&h=400&fit=crop" alt="Hercules Fitness Canggu" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#141418] to-transparent"></div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-white mb-2">Canggu</h3>
            <p class="text-slate-400 text-sm leading-relaxed mb-4">Cabang Canggu hadir di kawasan favorit para pekerja digital dan wisatawan. Suasana latihan outdoor-indoor yang unik.</p>
            <div class="space-y-3 text-xs text-slate-400">
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📍</span>
                <span>Canggu, Badung, Bali</span>
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
              <a href="<?= Url::to(['site/location-detail', 'id' => 'canggu']) ?>" class="w-full block text-center py-3 rounded-lg bg-brand-gold text-slate-900 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition-colors">
                Lihat Detail Cabang
              </a>

            </div>
          </div>
        </div>

        <!-- Cabang 2: Kuta -->
        <div class="group bg-[#141418] border border-white/5 rounded-2xl overflow-hidden hover:border-brand-gold/30 transition-all duration-300">
          <div class="relative h-56 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1623874514711-0f321325f318?w=600&h=400&fit=crop" alt="Hercules Fitness Kuta" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div class="absolute inset-0 bg-gradient-to-t from-[#141418] to-transparent"></div>
          </div>
          <div class="p-6">
            <h3 class="text-xl font-bold text-white mb-2">Kuta</h3>
            <p class="text-slate-400 text-sm leading-relaxed mb-4">Terletak di jantung Kuta, cabang ini mudah diakses dari mana saja. Cocok untuk Anda yang tinggal atau berlibur di area selatan Bali.</p>
            <div class="space-y-3 text-xs text-slate-400">
              <div class="flex items-start gap-2">
                <span class="text-brand-gold mt-0.5">📍</span>
                <span>Kuta, Badung, Bali</span>
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
              <a href="<?= Url::to(['site/location-detail', 'id' => 'kuta']) ?>" class="w-full block text-center py-3 rounded-lg bg-brand-gold text-slate-900 text-xs font-bold uppercase tracking-wider hover:bg-amber-400 transition-colors">
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
        <a href="<?= Url::to(['site/join']) ?>" class="px-8 py-3.5 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors inline-block">
          Daftar Membership
        </a>
        <a href="<?= Url::to(['site/location-batam']) ?>" class="px-8 py-3.5 rounded border border-slate-600 text-slate-300 hover:text-white hover:border-white text-sm font-bold uppercase tracking-wide transition-colors inline-block">
          Lihat Cabang Batam →
        </a>
      </div>
    </section>

  </div>
</div>
