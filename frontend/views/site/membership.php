<?php
/** @var yii\web\View $this */
use yii\helpers\Url;

$this->title = 'Membership - Hercules Fitness Centre';
?>

<style>
    @keyframes moveGridUp {
        0% { background-position: 0 40px; }
        100% { background-position: 0 0; }
    }
    .animate-grid {
        animation: moveGridUp 2s linear infinite;
    }
</style>

<div class="bg-gradient-to-b from-[#121316] to-[#0d0f13] min-h-screen pt-32 pb-20 px-6 font-sans relative overflow-hidden">
    <!-- Grid Pattern Background -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-[0.03] animate-grid" style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 40px 40px; mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%); -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%);"></div>

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

        <!-- ==================== BRANCH SELECTOR ==================== -->
        <div class="max-w-5xl mx-auto mb-12">
            <div class="text-center mb-6">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-[0.2em] mb-4">Pilih Cabang Latihan Anda</p>
                
                <!-- City Toggle -->
                <div class="inline-flex items-center bg-[#1a1c23] p-1 rounded-lg border border-white/10 mb-4">
                    <button onclick="selectCity('batam')" id="city-batam" class="city-btn px-6 py-2 text-xs font-bold uppercase tracking-wider rounded-md transition-all duration-300 bg-white text-slate-900">Batam</button>
                    <button onclick="selectCity('bali')" id="city-bali" class="city-btn px-6 py-2 text-xs font-bold uppercase tracking-wider rounded-md transition-all duration-300 text-slate-400 hover:text-white">Bali</button>
                </div>

                <!-- Branch Pills -->
                <div class="flex items-center justify-center gap-2 flex-wrap" id="branch-container"></div>
            </div>

            <!-- Active Branch Indicator -->
            <div class="text-center">
                <p class="text-slate-500 text-xs">Menampilkan harga untuk cabang: <span id="active-branch-label" class="text-brand-gold font-bold">Batu Ampar</span></p>
            </div>
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
                        <div id="slider-std" class="absolute top-1 bottom-1 w-[calc(33.333%-2px)] bg-white rounded-md transition-transform duration-300 ease-out z-0 shadow-md" style="left: 4px; transform: translateX(100%);"></div>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="std" data-index="0">6 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-900 relative z-10 active-pill" data-target="std" data-index="1">12 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="std" data-index="2">18 Bln</button>
                    </div>
                </div>

                <!-- Price Display -->
                <div class="mb-8 border-t border-white/10 pt-6">
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-4xl font-extrabold text-white" id="price-std" style="transition: opacity 0.2s, transform 0.2s;">Rp 225.000</span>
                        <span class="text-slate-400 font-medium text-sm">/bulan</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 line-through mb-1" id="strike-std">Rp 3.970.588</p>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold text-slate-200" id="total-std" style="transition: opacity 0.2s;">Rp 2.700.000</span>
                            <span class="bg-red-500/20 text-red-400 text-[10px] font-bold px-2 py-1 rounded" id="discount-std">Diskon 32%</span>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="mb-10 flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Benefit:</p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3"><span class="text-brand-gold font-black mt-0.5">•</span><span class="text-slate-300 text-sm">1 Cabang Tetap</span></li>
                        <li class="flex items-start gap-3"><span class="text-brand-gold font-black mt-0.5">•</span><span class="text-slate-300 text-sm">Loker Reguler Harian</span></li>
                        <li class="flex items-start gap-3"><span class="text-brand-gold font-black mt-0.5">•</span><span class="text-slate-300 text-sm">1x Sesi Orientasi Alat</span></li>
                        <li class="flex items-start gap-3"><span class="text-brand-gold font-black mt-0.5">•</span><span class="text-slate-300 text-sm">Shower & Wi-Fi</span></li>
                    </ul>
                </div>

                <a id="link-std" href="<?= Url::to(['site/checkout', 'package' => 'std', 'branch' => 'batu-ampar', 'duration' => '12']) ?>" class="block w-full py-4 text-center rounded-xl font-bold uppercase tracking-widest transition-colors border-2 border-white/20 text-white hover:bg-white hover:text-slate-900 text-sm">
                    Daftar Standard
                </a>
            </div>

            <!-- VIP CARD -->
            <div class="bg-[#121316] border-2 border-brand-gold rounded-2xl p-8 flex flex-col shadow-[0_10px_40px_rgba(212,175,55,0.15)] relative transform md:-translate-y-2">
                <div class="absolute -top-3 right-6 bg-brand-gold text-slate-900 text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full shadow-lg">Terlaris</div>

                <div class="mb-6">
                    <h3 class="text-xl font-bold uppercase tracking-widest text-brand-gold mb-2 flex items-center gap-2"><i class="fas fa-star text-sm"></i> VIP All-Access</h3>
                    <p class="text-slate-400 text-sm">Bebas tanpa batas 2 cabang</p>
                </div>

                <!-- Duration Selection -->
                <div class="mb-8">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Pilih Durasi:</p>
                    <div class="relative flex items-center bg-[#1a1c23] p-1 rounded-lg border border-white/10" id="vip-selector-container">
                        <div id="slider-vip" class="absolute top-1 bottom-1 w-[calc(33.333%-2px)] bg-brand-gold rounded-md transition-transform duration-300 ease-out z-0 shadow-md" style="left: 4px; transform: translateX(100%);"></div>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="vip" data-index="0">6 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-900 relative z-10 active-pill" data-target="vip" data-index="1">12 Bln</button>
                        <button class="dur-btn flex-1 py-2 text-xs font-bold rounded-md transition-colors duration-300 text-slate-400 hover:text-white relative z-10" data-target="vip" data-index="2">18 Bln</button>
                    </div>
                </div>

                <!-- Price Display -->
                <div class="mb-8 border-t border-white/10 pt-6">
                    <div class="flex items-baseline gap-2 mb-4">
                        <span class="text-4xl font-extrabold text-white" id="price-vip" style="transition: opacity 0.2s, transform 0.2s;">Rp 275.000</span>
                        <span class="text-slate-400 font-medium text-sm">/bulan</span>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 line-through mb-1" id="strike-vip">Rp 4.852.941</p>
                        <div class="flex items-center gap-3">
                            <span class="text-xl font-bold text-slate-200" id="total-vip" style="transition: opacity 0.2s;">Rp 3.300.000</span>
                            <span class="bg-red-500/20 text-red-400 text-[10px] font-bold px-2 py-1 rounded" id="discount-vip">Diskon 32%</span>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="mb-10 flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Benefit:</p>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1 text-sm"></i><span class="text-slate-200 text-sm">Akses Batu Ampar & B. Besar</span></li>
                        <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1 text-sm"></i><span class="text-slate-200 text-sm">Free Handuk Setiap Latihan</span></li>
                        <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1 text-sm"></i><span class="text-slate-200 text-sm">4x Sesi PT Pribadi + InBody</span></li>
                        <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1 text-sm"></i><span class="text-slate-200 text-sm">1 Free Pass Teman / Bulan</span></li>
                        <li class="flex items-start gap-3"><i class="fas fa-check text-brand-gold mt-1 text-sm"></i><span class="text-slate-200 text-sm">Prioritas Reservasi Kelas</span></li>
                    </ul>
                </div>

                <a id="link-vip" href="<?= Url::to(['site/checkout', 'package' => 'vip', 'branch' => 'batu-ampar', 'duration' => '12']) ?>" class="block w-full py-4 text-center rounded-xl font-bold uppercase tracking-widest transition-all bg-brand-gold text-slate-900 hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] hover:-translate-y-1 text-sm">
                    Daftar VIP All-Access
                </a>
            </div>
            
        </div>
        
        <script>
        (function() {
            var PRICING = {
                'batu-ampar': {
                    label: 'Batu Ampar',
                    std: [
                        { price: 'Rp 325.000', strike: 'Rp 2.437.500', total: 'Rp 1.950.000', discount: 'Diskon 20%' },
                        { price: 'Rp 225.000', strike: 'Rp 3.970.588', total: 'Rp 2.700.000', discount: 'Diskon 32%' },
                        { price: 'Rp 199.000', strike: 'Rp 5.777.419', total: 'Rp 3.582.000', discount: 'Diskon 38%' }
                    ],
                    vip: [
                        { price: 'Rp 399.000', strike: 'Rp 2.992.500', total: 'Rp 2.394.000', discount: 'Diskon 20%' },
                        { price: 'Rp 275.000', strike: 'Rp 4.852.941', total: 'Rp 3.300.000', discount: 'Diskon 32%' },
                        { price: 'Rp 249.000', strike: 'Rp 7.229.032', total: 'Rp 4.482.000', discount: 'Diskon 38%' }
                    ]
                },
                'batu-besar': {
                    label: 'Batu Besar',
                    std: [
                        { price: 'Rp 300.000', strike: 'Rp 2.250.000', total: 'Rp 1.800.000', discount: 'Diskon 20%' },
                        { price: 'Rp 210.000', strike: 'Rp 3.705.882', total: 'Rp 2.520.000', discount: 'Diskon 32%' },
                        { price: 'Rp 185.000', strike: 'Rp 5.370.968', total: 'Rp 3.330.000', discount: 'Diskon 38%' }
                    ],
                    vip: [
                        { price: 'Rp 375.000', strike: 'Rp 2.812.500', total: 'Rp 2.250.000', discount: 'Diskon 20%' },
                        { price: 'Rp 260.000', strike: 'Rp 4.588.235', total: 'Rp 3.120.000', discount: 'Diskon 32%' },
                        { price: 'Rp 235.000', strike: 'Rp 6.822.581', total: 'Rp 4.230.000', discount: 'Diskon 38%' }
                    ]
                },
                'mtc': {
                    label: 'MTC Batam',
                    std: [
                        { price: 'Rp 350.000', strike: 'Rp 2.625.000', total: 'Rp 2.100.000', discount: 'Diskon 20%' },
                        { price: 'Rp 240.000', strike: 'Rp 4.235.294', total: 'Rp 2.880.000', discount: 'Diskon 32%' },
                        { price: 'Rp 215.000', strike: 'Rp 6.241.935', total: 'Rp 3.870.000', discount: 'Diskon 38%' }
                    ],
                    vip: [
                        { price: 'Rp 425.000', strike: 'Rp 3.187.500', total: 'Rp 2.550.000', discount: 'Diskon 20%' },
                        { price: 'Rp 295.000', strike: 'Rp 5.205.882', total: 'Rp 3.540.000', discount: 'Diskon 32%' },
                        { price: 'Rp 265.000', strike: 'Rp 7.693.548', total: 'Rp 4.770.000', discount: 'Diskon 38%' }
                    ]
                },
                'canggu': {
                    label: 'Canggu',
                    std: [
                        { price: 'Rp 375.000', strike: 'Rp 2.812.500', total: 'Rp 2.250.000', discount: 'Diskon 20%' },
                        { price: 'Rp 260.000', strike: 'Rp 4.588.235', total: 'Rp 3.120.000', discount: 'Diskon 32%' },
                        { price: 'Rp 230.000', strike: 'Rp 6.677.419', total: 'Rp 4.140.000', discount: 'Diskon 38%' }
                    ],
                    vip: [
                        { price: 'Rp 450.000', strike: 'Rp 3.375.000', total: 'Rp 2.700.000', discount: 'Diskon 20%' },
                        { price: 'Rp 315.000', strike: 'Rp 5.558.824', total: 'Rp 3.780.000', discount: 'Diskon 32%' },
                        { price: 'Rp 280.000', strike: 'Rp 8.129.032', total: 'Rp 5.040.000', discount: 'Diskon 38%' }
                    ]
                },
                'kuta': {
                    label: 'Kuta',
                    std: [
                        { price: 'Rp 350.000', strike: 'Rp 2.625.000', total: 'Rp 2.100.000', discount: 'Diskon 20%' },
                        { price: 'Rp 245.000', strike: 'Rp 4.323.529', total: 'Rp 2.940.000', discount: 'Diskon 32%' },
                        { price: 'Rp 220.000', strike: 'Rp 6.387.097', total: 'Rp 3.960.000', discount: 'Diskon 38%' }
                    ],
                    vip: [
                        { price: 'Rp 430.000', strike: 'Rp 3.225.000', total: 'Rp 2.580.000', discount: 'Diskon 20%' },
                        { price: 'Rp 300.000', strike: 'Rp 5.294.118', total: 'Rp 3.600.000', discount: 'Diskon 32%' },
                        { price: 'Rp 270.000', strike: 'Rp 7.838.710', total: 'Rp 4.860.000', discount: 'Diskon 38%' }
                    ]
                }
            };

            var CITIES = { batam: ['batu-ampar', 'batu-besar', 'mtc'], bali: ['canggu', 'kuta'] };
            var currentBranch = 'batu-ampar';
            var durIndex = { std: 1, vip: 1 };

            function updatePrice(type) {
                var d = PRICING[currentBranch][type][durIndex[type]];
                var pEl = document.getElementById('price-' + type);
                var sEl = document.getElementById('strike-' + type);
                var tEl = document.getElementById('total-' + type);
                var dEl = document.getElementById('discount-' + type);
                pEl.style.opacity = '0'; pEl.style.transform = 'translateY(6px)';
                tEl.style.opacity = '0';
                setTimeout(function() {
                    pEl.textContent = d.price; sEl.textContent = d.strike;
                    tEl.textContent = d.total; dEl.textContent = d.discount;
                    pEl.style.opacity = '1'; pEl.style.transform = 'translateY(0)';
                    tEl.style.opacity = '1';
                }, 150);
            }

            function renderBranches(city) {
                var c = document.getElementById('branch-container');
                c.innerHTML = '';
                CITIES[city].forEach(function(key) {
                    var b = document.createElement('button');
                    var isActive = key === currentBranch;
                    b.className = 'branch-btn px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-300 border ' +
                        (isActive ? 'bg-brand-gold text-slate-900 border-brand-gold shadow-[0_4px_15px_rgba(212,175,55,0.3)]' : 'bg-transparent text-slate-400 border-slate-700 hover:text-white hover:border-slate-500');
                    b.textContent = PRICING[key].label;
                    b.onclick = function() { selectBranch(key); };
                    c.appendChild(b);
                });
            }

            window.selectCity = function(city, specificBranch) {
                document.querySelectorAll('.city-btn').forEach(function(b) {
                    b.classList.remove('bg-white', 'text-slate-900');
                    b.classList.add('text-slate-400', 'hover:text-white');
                });
                var el = document.getElementById('city-' + city);
                if (el) {
                    el.classList.add('bg-white', 'text-slate-900');
                    el.classList.remove('text-slate-400', 'hover:text-white');
                }
                currentBranch = specificBranch || CITIES[city][0];
                renderBranches(city);
                selectBranch(currentBranch);
            };

            window.selectBranch = function(key) {
                currentBranch = key;
                document.querySelectorAll('#branch-container .branch-btn').forEach(function(b) {
                    if (b.textContent === PRICING[key].label) {
                        b.className = 'branch-btn px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-300 border bg-brand-gold text-slate-900 border-brand-gold shadow-[0_4px_15px_rgba(212,175,55,0.3)]';
                    } else {
                        b.className = 'branch-btn px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-300 border bg-transparent text-slate-400 border-slate-700 hover:text-white hover:border-slate-500';
                    }
                });
                document.getElementById('active-branch-label').textContent = PRICING[key].label;
                updatePrice('std');
                updatePrice('vip');
                updateCheckoutLinks();
            };

            function updateCheckoutLinks() {
                var durMonths = [6, 12, 18];
                var base = '<?= Url::to(["site/checkout"]) ?>';
                var stdLink = document.getElementById('link-std');
                var vipLink = document.getElementById('link-vip');
                if (stdLink) stdLink.href = base + '?package=std&branch=' + currentBranch + '&duration=' + durMonths[durIndex.std];
                if (vipLink) vipLink.href = base + '?package=vip&branch=' + currentBranch + '&duration=' + durMonths[durIndex.vip];
            }

            document.addEventListener('DOMContentLoaded', function() {
                var urlParams = new URLSearchParams(window.location.search);
                var initialCity = urlParams.get('city') || 'batam';
                var initialBranch = urlParams.get('branch') || 'batu-ampar';
                
                if (!CITIES[initialCity] || !CITIES[initialCity].includes(initialBranch)) {
                    initialCity = 'batam';
                    initialBranch = 'batu-ampar';
                }
                
                selectCity(initialCity, initialBranch);

                document.querySelectorAll('.dur-btn').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        var target = this.getAttribute('data-target');
                        var index = parseInt(this.getAttribute('data-index'));
                        var siblings = this.parentElement.querySelectorAll('.dur-btn');
                        var slider = document.getElementById('slider-' + target);
                        slider.style.transform = 'translateX(' + (index * 100) + '%)';
                        siblings.forEach(function(s) {
                            s.classList.remove('active-pill', 'text-slate-900');
                            s.classList.add('text-slate-400', 'hover:text-white');
                        });
                        this.classList.add('active-pill', 'text-slate-900');
                        this.classList.remove('text-slate-400', 'hover:text-white');
                        durIndex[target] = index;
                        updatePrice(target);
                        updateCheckoutLinks();
                    });
                });
            });
        })();
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
                    <div class="absolute -top-32 -right-32 w-96 h-96 bg-brand-gold/20 rounded-full blur-[100px]"></div>
                    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-teal-500/20 rounded-full blur-[100px]"></div>
                    <i class="fas fa-dumbbell absolute -bottom-12 -right-12 text-[280px] text-white/[0.03] -rotate-12"></i>
                </div>

                <!-- Tabs -->
                <div class="relative z-10 flex flex-col items-center justify-center gap-4 px-8 pt-8 pb-4 border-b border-white/5">
                    <div class="relative flex p-1.5 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 shadow-inner w-[450px] max-w-full">
                        <div id="tab-slider" class="absolute top-1.5 bottom-1.5 w-[calc(33.333%-4px)] bg-brand-gold rounded-xl transition-transform duration-300 ease-out z-0 shadow-lg" style="left: 6px; transform: translateX(0%);"></div>
                        <button onclick="switchTab('membership', 0)" id="tab-membership"
                            class="gym-tab flex-1 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-900 relative z-10">
                            Membership
                        </button>
                        <button onclick="switchTab('fasilitas', 1)" id="tab-fasilitas"
                            class="gym-tab flex-1 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-400 hover:text-white hover:bg-white/5 relative z-10">
                            Fasilitas
                        </button>
                        <button onclick="switchTab('alat', 2)" id="tab-alat"
                            class="gym-tab flex-1 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-slate-400 hover:text-white hover:bg-white/5 relative z-10">
                            Alat Gym
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-500 italic">* Ketersediaan bervariasi antar cabang</p>
                </div>

                <!-- Tab: Membership -->
                <div id="panel-membership" class="gym-panel relative z-10 p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-building text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Akses Cabang</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Standard: 1 cabang tetap. VIP All-Access: bebas 2 cabang pilihan.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-snowflake text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Membership Freeze</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Jeda / cuti membership sementara jika Anda sedang bepergian atau sakit.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-user-friends text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Free Pass Teman</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: ajak 1 teman gratis setiap bulannya ke gym bersama Anda.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-calendar-check text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Prioritas Reservasi Kelas</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: slot kelas grup diprioritaskan sebelum member Standard.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-hand-paper text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Handuk Setiap Latihan</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: handuk bersih disediakan setiap kali Anda datang berlatih.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-dumbbell text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Sesi Personal Trainer</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Khusus VIP: 4x sesi PT tersertifikasi + tes komposisi tubuh InBody.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Fasilitas -->
                <div id="panel-fasilitas" class="gym-panel hidden relative z-10 p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-lock text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Loker</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Simpan barang bawaan Anda dengan lebih aman selama sesi latihan.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-shower text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Toilet & Shower</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Nikmati shower air panas dan fasilitas hair dryer setelah nge-gym.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-wifi text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Free Wi-Fi & Charging</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Koneksi internet cepat dan area pengisian daya tersebar di seluruh area gym.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-tint text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Dispenser Air Minum</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Isi ulang botol minuman gratis kapan saja selama jam operasional.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-couch text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Lounge & Area Santai</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Tempat nyaman untuk beristirahat sebelum atau setelah berlatih.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-parking text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Area Parkir Luas</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Parkir motor dan mobil yang luas dan aman di area gym.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab: Alat Gym -->
                <div id="panel-alat" class="gym-panel hidden relative z-10 p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-cogs text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Machine</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Smith Machine, Leg Press, Leg Extension, Leg Curl, Pec Fly, Chest Press, Power Rack, Cable Cross, Pulldown, Row</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-weight-hanging text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Free Weight</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Dumbbell, Barbell, Kettlebell, Plates dalam berbagai variasi berat.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-bicycle text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Cardio</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Treadmill, Spinning Bike, Elliptical dengan fitur detak jantung.</p>
                            </div>
                        </div>

                        <div class="group relative bg-white/[0.02] rounded-3xl p-6 hover:bg-white/[0.04] transition-all duration-300 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-1 hover:shadow-[0_15px_40px_-15px_rgba(212,175,55,0.2)] overflow-hidden flex items-start gap-5">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/5 rounded-full blur-3xl group-hover:bg-brand-gold/20 transition-colors duration-500 pointer-events-none"></div>
                            <div class="w-14 h-14 shrink-0 rounded-2xl bg-gradient-to-br from-brand-gold/20 to-brand-gold/5 flex items-center justify-center group-hover:scale-110 group-hover:from-brand-gold group-hover:to-yellow-300 transition-all duration-300 border border-brand-gold/20 group-hover:border-transparent group-hover:shadow-[0_0_20px_rgba(212,175,55,0.4)] relative z-10">
                                <i class="fas fa-grip-lines text-brand-gold text-xl group-hover:text-slate-900 transition-colors"></i>
                            </div>
                            <div class="relative z-10 pt-1">
                                <h4 class="font-bold text-white text-lg tracking-wide mb-2 uppercase font-condensed">Lifting Bar</h4>
                                <p class="text-sm text-slate-400 leading-relaxed group-hover:text-slate-300 transition-colors">Olympic Bar, EZ Curl Bar, Hex Bar standar kompetisi.</p>
                            </div>
                        </div>
                    </div>
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

