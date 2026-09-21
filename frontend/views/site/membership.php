<?php
/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = 'Membership - Hercules Fitness Centre';
?>

<div class="bg-gradient-to-b from-[#121316] to-[#0d0f13] min-h-screen pt-32 pb-20 px-6 font-sans relative overflow-hidden">
    <!-- Grid Pattern Background -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-[0.03]" style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 40px 40px; mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%); -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);"></div>

    <div class="max-w-6xl mx-auto relative z-10">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tight font-condensed mb-4">
                Pilih Paket <span class="text-brand-gold">Membership</span> Anda
            </h1>
            <p class="text-slate-400 max-w-xl mx-auto">
                All You Can Fit with One Membership. Bergabunglah dengan Hercules Fitness dan nikmati fasilitas kelas dunia.
            </p>
        </div>

                        <!-- Pricing Cards (Side-by-side with Duration Selector) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-20">
            
            <!-- STANDARD CARD -->
            <div class="bg-[#121316] border border-white/20 rounded-2xl p-8 flex flex-col shadow-xl text-white relative">
                <div class="mb-6">
                    <h3 class="text-xl font-bold uppercase tracking-widest text-slate-200 mb-2">Standard</h3>
                    <p class="text-slate-400 text-sm">Akses latihan 1 cabang fokus</p>
                </div>

                <!-- Duration Selection -->
                <div class="mb-8">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Pilih Durasi:</p>
                    <div class="relative flex items-center bg-[#1a1c23] p-1 rounded-lg border border-white/10" id="std-selector-container">
                        <!-- Sliding Background -->
                        <div id="slider-std" class="absolute top-1 bottom-1 w-[calc(33.333%-2px)] bg-white rounded-md transition-transform duration-300 ease-out z-0 shadow-md" style="left: 4px; transform: translateX(100%);"></div>
                        
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="std" data-index="0" data-price="Rp 325.000" data-strike="Rp 2.437.500" data-total="Rp 1.950.000" data-discount="Diskon 20%">6 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-900 relative z-10 active-pill" data-target="std" data-index="1" data-price="Rp 225.000" data-strike="Rp 3.970.588" data-total="Rp 2.700.000" data-discount="Diskon 32%">12 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="std" data-index="2" data-price="Rp 199.000" data-strike="Rp 5.777.419" data-total="Rp 3.582.000" data-discount="Diskon 38%">18 Bln</button>
                    </div>
                </div>

                <!-- Price Display -->
                <div class="mb-8 border-t border-white/10 pt-6">
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-4xl font-extrabold text-white" id="price-std">Rp 225.000</span>
                        <span class="text-slate-400 font-medium text-sm">/bulan</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 line-through mb-1" id="strike-std">Rp 3.970.588</p>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold text-slate-200" id="total-std">Rp 2.700.000</span>
                            <span class="bg-red-500/20 text-red-400 text-[10px] font-bold px-2 py-1 rounded" id="discount-std">Diskon 32%</span>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="mb-10 flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Benefit:</p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <span class="text-brand-gold font-black mt-0.5">•</span>
                            <span class="text-slate-300 text-sm">1 Cabang Tetap</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-brand-gold font-black mt-0.5">•</span>
                            <span class="text-slate-300 text-sm">Loker Reguler Harian</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-brand-gold font-black mt-0.5">•</span>
                            <span class="text-slate-300 text-sm">1x Sesi Orientasi Alat</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="text-brand-gold font-black mt-0.5">•</span>
                            <span class="text-slate-300 text-sm">Shower & Wi-Fi</span>
                        </li>
                    </ul>
                </div>

                <a href="<?= Url::to(['site/join']) ?>" class="block w-full py-4 text-center rounded-xl font-bold uppercase tracking-widest transition-colors border-2 border-white/20 text-white hover:bg-white hover:text-slate-900 text-sm">
                    Daftar Standard
                </a>
            </div>

            <!-- VIP CARD -->
            <div class="bg-[#121316] border-2 border-brand-gold rounded-2xl p-8 flex flex-col shadow-[0_10px_40px_rgba(212,175,55,0.15)] relative transform md:-translate-y-2">
                <div class="absolute -top-3 right-6 bg-brand-gold text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-lg">
                    Terlaris
                </div>

                <div class="mb-6">
                    <h3 class="text-xl font-bold uppercase tracking-widest text-brand-gold mb-2 flex items-center gap-2">
                        <i class="fas fa-star text-sm"></i> VIP All-Access
                    </h3>
                    <p class="text-slate-400 text-sm">Bebas tanpa batas 2 cabang</p>
                </div>

                <!-- Duration Selection -->
                <div class="mb-8">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Pilih Durasi:</p>
                    <div class="relative flex items-center bg-[#1a1c23] p-1 rounded-lg border border-white/10" id="vip-selector-container">
                        <!-- Sliding Background -->
                        <div id="slider-vip" class="absolute top-1 bottom-1 w-[calc(33.333%-2px)] bg-brand-gold rounded-md transition-transform duration-300 ease-out z-0 shadow-md" style="left: 4px; transform: translateX(100%);"></div>
                        
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="vip" data-index="0" data-price="Rp 399.000" data-strike="Rp 2.992.500" data-total="Rp 2.394.000" data-discount="Diskon 20%">6 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-900 relative z-10 active-pill" data-target="vip" data-index="1" data-price="Rp 275.000" data-strike="Rp 4.852.941" data-total="Rp 3.300.000" data-discount="Diskon 32%">12 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="vip" data-index="2" data-price="Rp 249.000" data-strike="Rp 7.229.032" data-total="Rp 4.482.000" data-discount="Diskon 38%">18 Bln</button>
                    </div>
                </div>

                <!-- Price Display -->
                <div class="mb-8 border-t border-white/10 pt-6">
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-4xl font-extrabold text-white" id="price-vip">Rp 275.000</span>
                        <span class="text-slate-400 font-medium text-sm">/bulan</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 line-through mb-1" id="strike-vip">Rp 4.852.941</p>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold text-slate-200" id="total-vip">Rp 3.300.000</span>
                            <span class="bg-red-500/20 text-red-400 text-[10px] font-bold px-2 py-1 rounded" id="discount-vip">Diskon 32%</span>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="mb-10 flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Benefit:</p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-brand-gold mt-1 text-sm"></i>
                            <span class="text-slate-200 text-sm">Akses Batu Ampar & B. Besar</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-brand-gold mt-1 text-sm"></i>
                            <span class="text-slate-200 text-sm">Free Handuk Setiap Latihan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-brand-gold mt-1 text-sm"></i>
                            <span class="text-slate-200 text-sm">4x Sesi PT Pribadi + InBody</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-brand-gold mt-1 text-sm"></i>
                            <span class="text-slate-200 text-sm">1 Free Pass Teman / Bulan</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check text-brand-gold mt-1 text-sm"></i>
                            <span class="text-slate-200 text-sm">Prioritas Reservasi Kelas</span>
                        </li>
                    </ul>
                </div>

                <a href="<?= Url::to(['site/join']) ?>" class="block w-full py-4 text-center rounded-xl font-bold uppercase tracking-widest transition-all bg-brand-gold text-slate-900 hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] hover:-translate-y-1 text-sm">
                    Daftar VIP All-Access
                </a>
            </div>
            
        </div>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const buttons = document.querySelectorAll('.dur-btn');
                buttons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const target = this.getAttribute('data-target'); // 'std' or 'vip'
                        const index = parseInt(this.getAttribute('data-index'));
                        const siblings = this.parentElement.querySelectorAll('.dur-btn');
                        const slider = document.getElementById('slider-' + target);
                        
                        // Move slider background
                        // We use index * 100% to slide across 3 elements of equal width
                        slider.style.transform = `translateX(${index * 100}%)`;
                        
                        // Update text colors for buttons
                        siblings.forEach(s => {
                            s.classList.remove('active-pill', 'text-slate-900');
                            s.classList.add('text-slate-400', 'hover:text-white'); // inactive styles
                        });
                        
                        this.classList.add('active-pill', 'text-slate-900');
                        this.classList.remove('text-slate-400', 'hover:text-white'); // active styles
                        
                        // Update price and total information
                        const priceEl = document.getElementById('price-' + target);
                        const strikeEl = document.getElementById('strike-' + target);
                        const totalEl = document.getElementById('total-' + target);
                        const discountEl = document.getElementById('discount-' + target);
                        
                        priceEl.textContent = this.getAttribute('data-price');
                        strikeEl.textContent = this.getAttribute('data-strike');
                        totalEl.textContent = this.getAttribute('data-total');
                        discountEl.textContent = this.getAttribute('data-discount');
                    });
                });
            });
        </script>

        <!-- Our Gym Benefits Section -->
        <div class="max-w-6xl mx-auto mt-8 mb-16">
            <div class="text-center mb-12">
                <p class="text-brand-gold text-xs font-bold uppercase tracking-[0.3em] mb-3">● Sudah Termasuk Semua Paket</p>
                <h2 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight uppercase tracking-tight font-condensed">Yang Anda Dapat <br class="hidden md:block"><span class="text-brand-gold">Lebih dari Sekadar Gym.</span></h2>
                <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base">Setiap paket membership memberikan Anda akses penuh ke ekosistem latihan kami — dari alat, fasilitas, hingga komunitas.</p>
            </div>

            <!-- Outer card container -->
            <div class="rounded-3xl overflow-hidden shadow-2xl relative" style="background: linear-gradient(135deg, #1a2e3a 0%, #0f1e28 100%);">
                
                <!-- Decorative Background Elements -->
                <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
                    <!-- Glowing Orbs -->
                    <div class="absolute -top-32 -right-32 w-96 h-96 bg-brand-gold/20 rounded-full blur-[100px]"></div>
                    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-teal-500/20 rounded-full blur-[100px]"></div>
                    <!-- Giant subtle watermark icon -->
                    <i class="fas fa-dumbbell absolute -bottom-12 -right-12 text-[280px] text-white/[0.03] -rotate-12"></i>
                </div>

                <!-- Tabs -->
                <div class="relative z-10 flex flex-wrap items-center justify-center gap-2 px-8 py-8 border-b border-white/5">
                    <div class="relative flex p-1.5 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 shadow-inner w-[450px] max-w-full">
                        <div id="tab-slider" class="absolute top-1.5 bottom-1.5 w-[calc(33.333%-4px)] bg-brand-gold rounded-xl transition-transform duration-300 ease-out z-0 shadow-lg" style="left: 6px; transform: translateX(200%);"></div>
                        <button onclick="switchTab('membership', 0)" id="tab-membership"
                            class="gym-tab flex-1 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-400 hover:text-white hover:bg-white/5 relative z-10">
                            Membership
                        </button>
                        <button onclick="switchTab('fasilitas', 1)" id="tab-fasilitas"
                            class="gym-tab flex-1 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-400 hover:text-white hover:bg-white/5 relative z-10">
                            Fasilitas
                        </button>
                        <button onclick="switchTab('alat', 2)" id="tab-alat"
                            class="gym-tab flex-1 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-900 relative z-10">
                            Alat Gym
                        </button>
                    </div>
                </div>

                <!-- Tab: Membership -->
                <div id="panel-membership" class="gym-panel hidden relative z-10 p-8 md:p-12">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-building text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Akses Cabang</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Standard: 1 cabang tetap. VIP All-Access: bebas 2 cabang pilihan.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-snowflake text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Membership Freeze</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Jeda / cuti membership sementara jika Anda sedang bepergian atau sakit.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-user-friends text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Free Pass Teman</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: ajak 1 teman gratis setiap bulannya ke gym bersama Anda.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-calendar-check text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Prioritas Reservasi Kelas</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: slot kelas grup diprioritaskan sebelum member Standard.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-hand-paper text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Handuk Setiap Latihan</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: handuk bersih disediakan setiap kali Anda datang berlatih.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-dumbbell text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Sesi Personal Trainer</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: 4x sesi PT tersertifikasi + tes komposisi tubuh InBody.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Fasilitas -->
                <div id="panel-fasilitas" class="gym-panel hidden relative z-10 p-8 md:p-12">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-lock text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Loker</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Simpan barang bawaan Anda dengan lebih aman selama sesi latihan.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-shower text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Toilet & Shower Air Hangat</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Nikmati shower air panas dan fasilitas hair dryer setelah nge-gym.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-wifi text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Free Wi-Fi & Charging</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Koneksi internet cepat dan area pengisian daya tersebar di seluruh area gym.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-tint text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Dispenser Air Minum</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Isi ulang botol minuman gratis kapan saja selama jam operasional.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-couch text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Lounge & Area Santai</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Tempat nyaman untuk beristirahat sebelum atau setelah berlatih.</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-parking text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Area Parkir Luas</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Parkir motor dan mobil yang luas dan aman di area gym.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Alat Gym (default shown) -->
                <div id="panel-alat" class="gym-panel relative z-10 p-8 md:p-12">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-cogs text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Machine</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Smith Machine, Leg Press, Leg Extension, Leg Curl, Abductor, Adductor, Pec Fly, Pec Deck, Chest Press, Power Rack, Cable Cross, Pulldown, Row, Hang Bar, Dip Chin, Bicep Curl, Lateral Raise, Torso Rotation</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-weight-hanging text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Free Weight</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Dumbbell, Barbell, Kettlebell, Plates</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-bicycle text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Cardio</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Treadmill, Spinning Bike</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-grip-lines text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Lifting Bar</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Olympic Bar, EZ Curl Bar, Hex Bar</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-layer-group text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Mattress</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Yoga mat anti-slip</p>
                            </div>
                        </div>
                        <div class="group relative bg-white/[0.02] rounded-3xl p-7 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500"></div>
                            <div class="relative z-10">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)]">
                                    <i class="fas fa-ellipsis-h text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                                </div>
                                <h4 class="font-bold text-white text-lg tracking-wide mb-3">Lain-lain</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Bosu Ball, Medicine Ball, Slam Ball, Stability Ball, TRX, ViPR, Battle Rope, Aerobic Step, Resistance Band, Resistance Tube, Equalizer dan lainnya</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer hint -->
                <div class="relative z-10 px-6 py-4 border-t border-white/10">
                    <p class="text-xs text-slate-500 italic">* Ketersediaan bervariasi antar cabang</p>
                </div>
            </div>
        </div>

        <script>
            function switchTab(tab, index) {
                document.querySelectorAll('.gym-panel').forEach(p => p.classList.add('hidden'));
                
                // Reset all tabs
                document.querySelectorAll('.gym-tab').forEach(t => {
                    t.classList.add('text-slate-400', 'hover:bg-white/5', 'hover:text-white');
                    t.classList.remove('text-slate-900');
                });
                
                // Show selected panel
                document.getElementById('panel-' + tab).classList.remove('hidden');
                
                // Highlight active tab
                const activeTab = document.getElementById('tab-' + tab);
                activeTab.classList.remove('text-slate-400', 'hover:bg-white/5', 'hover:text-white');
                activeTab.classList.add('text-slate-900');
                
                // Move slider
                document.getElementById('tab-slider').style.transform = `translateX(${index * 100}%)`;
            }
        </script>

    </div>
</div>

