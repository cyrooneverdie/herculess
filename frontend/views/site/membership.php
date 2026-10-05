<?php
/** @var yii\web\View $this */
use yii\helpers\Url;
use common\models\Product;

$this->title = 'Membership - Hercules Fitness Centre';

$allProducts = Product::find()->where(['not', ['sellprice' => null]])->all();
$stdPackages = [];
$vipPackages = [];

foreach($allProducts as $p) {
    // Filter out Personal Trainer packages (where coaching_mode is enabled)
    if (!empty($p->coaching_mode) && !in_array(strtolower($p->coaching_mode), ['tidak ada', 'none', '0'])) {
        continue;
    }

    $name = strtolower($p->productname);
    if (strpos($name, 'drop-in') !== false) continue;
    
    $label = trim(str_ireplace(['paket', 'vip', 'standard', 'all-access', 'all access'], '', $p->productname));
    if ($label == '') {
        $label = $p->duration_days ? round($p->duration_days/30) . ' Bulan' : '1 Bulan';
    } else {
        $label = ucwords(strtolower($label));
    }
    
    $basePrice = (float)$p->sellprice;
    preg_match('/(\d+)/', $label, $matches);
    $months = !empty($matches[1]) ? (int)$matches[1] : 1;
    
    $total = $basePrice * $months;
    $strike = $total / 0.68;
    
    $pkg = [
        'id' => $p->productid,
        'label' => $label,
        'price' => 'Rp ' . number_format($basePrice, 0, ',', '.'),
        'strike' => 'Rp ' . number_format($strike, 0, ',', '.'),
        'total' => 'Rp ' . number_format($total, 0, ',', '.'),
        'discount' => 'Diskon 32%',
        'rawPrice' => $basePrice
    ];

    if (strpos($name, 'vip') !== false) {
        $vipPackages[] = $pkg;
    } else {
        $stdPackages[] = $pkg;
    }
}

usort($stdPackages, fn($a, $b) => $a['rawPrice'] <=> $b['rawPrice']);
usort($vipPackages, fn($a, $b) => $a['rawPrice'] <=> $b['rawPrice']);

if (empty($stdPackages)) {
    $stdPackages = [
        ['label'=>'1 Minggu', 'price'=>'Rp 100.000', 'strike'=>'Rp 150.000', 'total'=>'Rp 100.000', 'discount'=>'Diskon 33%'],
        ['label'=>'2 Minggu', 'price'=>'Rp 175.000', 'strike'=>'Rp 250.000', 'total'=>'Rp 175.000', 'discount'=>'Diskon 30%'],
        ['label'=>'1 Bulan', 'price'=>'Rp 300.000', 'strike'=>'Rp 450.000', 'total'=>'Rp 300.000', 'discount'=>'Diskon 33%'],
        ['label'=>'3 Bulan', 'price'=>'Rp 275.000', 'strike'=>'Rp 1.350.000', 'total'=>'Rp 825.000', 'discount'=>'Diskon 38%'],
        ['label'=>'6 Bulan', 'price'=>'Rp 225.000', 'strike'=>'Rp 3.970.588', 'total'=>'Rp 2.700.000', 'discount'=>'Diskon 32%'],
        ['label'=>'1 Tahun', 'price'=>'Rp 210.000', 'strike'=>'Rp 3.705.882', 'total'=>'Rp 2.520.000', 'discount'=>'Diskon 32%'],
        ['label'=>'2 Tahun', 'price'=>'Rp 190.000', 'strike'=>'Rp 7.000.000', 'total'=>'Rp 4.560.000', 'discount'=>'Diskon 34%'],
        ['label'=>'Kustom', 'price'=>'Rp 200.000', 'strike'=>'', 'total'=>'', 'discount'=>'']
    ];
}
if (empty($vipPackages)) {
    $vipPackages = [
        ['label'=>'1 Minggu', 'price'=>'Rp 150.000', 'strike'=>'Rp 200.000', 'total'=>'Rp 150.000', 'discount'=>'Diskon 25%'],
        ['label'=>'2 Minggu', 'price'=>'Rp 250.000', 'strike'=>'Rp 350.000', 'total'=>'Rp 250.000', 'discount'=>'Diskon 28%'],
        ['label'=>'1 Bulan', 'price'=>'Rp 400.000', 'strike'=>'Rp 550.000', 'total'=>'Rp 400.000', 'discount'=>'Diskon 27%'],
        ['label'=>'3 Bulan', 'price'=>'Rp 350.000', 'strike'=>'Rp 1.650.000', 'total'=>'Rp 1.050.000', 'discount'=>'Diskon 36%'],
        ['label'=>'6 Bulan', 'price'=>'Rp 275.000', 'strike'=>'Rp 4.852.941', 'total'=>'Rp 3.300.000', 'discount'=>'Diskon 32%'],
        ['label'=>'1 Tahun', 'price'=>'Rp 260.000', 'strike'=>'Rp 4.588.235', 'total'=>'Rp 3.120.000', 'discount'=>'Diskon 32%'],
        ['label'=>'2 Tahun', 'price'=>'Rp 240.000', 'strike'=>'Rp 8.000.000', 'total'=>'Rp 5.760.000', 'discount'=>'Diskon 28%'],
        ['label'=>'Kustom', 'price'=>'Rp 200.000', 'strike'=>'', 'total'=>'', 'discount'=>'']
    ];
}

$dynamicPricing = [
    'batu-ampar' => ['label' => 'Batu Ampar', 'std' => $stdPackages, 'vip' => $vipPackages],
    'batu-besar' => ['label' => 'Batu Besar', 'std' => $stdPackages, 'vip' => $vipPackages],
    'mtc' => ['label' => 'MTC Batam', 'std' => $stdPackages, 'vip' => $vipPackages],
    'canggu' => ['label' => 'Canggu', 'std' => $stdPackages, 'vip' => $vipPackages],
    'kuta' => ['label' => 'Kuta', 'std' => $stdPackages, 'vip' => $vipPackages],
];
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
                All You Can Fit with One Membership. Bergabunglah dengan Hercules Fitness dan nikmati fasilitasnya.
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

        <!-- ==================== PACKAGE SLIDER ==================== -->
        <?php $allPackages = array_merge(
            array_map(fn($p) => array_merge($p, ['type' => 'std']), $stdPackages),
            array_map(fn($p) => array_merge($p, ['type' => 'vip']), $vipPackages)
        );
        usort($allPackages, fn($a, $b) => ($a['rawPrice'] ?? 0) <=> ($b['rawPrice'] ?? 0));
        ?>

        <div class="max-w-6xl mx-auto mb-20 relative">
            <!-- Standard / VIP Toggle -->
            <div class="flex items-center gap-3 mb-6">
                <div class="relative inline-flex items-center gap-0 border-b border-white/10">
                    <button onclick="filterPackageType('std', 0)" id="pkg-tab-std" class="pkg-type-btn px-6 py-3 text-xs font-bold uppercase tracking-wider transition-all duration-300 text-white">Standard</button>
                    <button onclick="filterPackageType('vip', 1)" id="pkg-tab-vip" class="pkg-type-btn px-6 py-3 text-xs font-bold uppercase tracking-wider transition-all duration-300 text-slate-500 hover:text-slate-300">VIP</button>
                    <div id="pkg-tab-underline" class="absolute bottom-0 h-[3px] bg-brand-gold rounded-full transition-all duration-300 ease-out" style="left: 0; width: 0;"></div>
                </div>
            </div>

            <!-- Slider Navigation Arrows -->
            <button id="slider-prev" onclick="slidePackages(-1)" class="absolute -left-4 md:-left-12 lg:-left-16 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-[#1a1c23] border border-white/10 text-white/60 hover:text-white hover:border-brand-gold/50 hover:bg-[#1a1c23] transition-all duration-300 flex items-center justify-center shadow-[0_4px_20px_rgba(0,0,0,0.5)] backdrop-blur-sm hidden md:flex">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            <button id="slider-next" onclick="slidePackages(1)" class="absolute -right-4 md:-right-12 lg:-right-16 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-[#1a1c23] border border-white/10 text-white/60 hover:text-white hover:border-brand-gold/50 hover:bg-[#1a1c23] transition-all duration-300 flex items-center justify-center shadow-[0_4px_20px_rgba(0,0,0,0.5)] backdrop-blur-sm hidden md:flex">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>

            <!-- Slider Track -->
            <div class="overflow-hidden px-4 -mx-4 py-8 -my-8" id="pkg-slider-viewport">
                <div class="flex transition-transform duration-500 ease-out gap-5" id="pkg-slider-track" style="touch-action: pan-y;">
                    <?php foreach ($allPackages as $idx => $pkg):
                        $isVip = ($pkg['type'] ?? '') === 'vip';
                        $borderClass = $isVip ? 'border-brand-gold/60' : 'border-white/10';
                        $accentColor = $isVip ? 'text-brand-gold' : 'text-white';
                        $badgeBg = $isVip ? 'bg-brand-gold text-slate-900' : 'bg-white/10 text-slate-300';
                        $btnClass = $isVip
                            ? 'bg-brand-gold text-slate-900 hover:shadow-[0_0_24px_rgba(212,175,55,0.35)]'
                            : 'border-2 border-white/20 text-white hover:bg-white hover:text-slate-900';
                        $typeLabel = $isVip ? 'VIP' : 'Standard';
                        $isKustom = strtolower($pkg['label'] ?? '') === 'kustom';
                        $checkoutUrl = $isKustom 
                            ? 'https://wa.me/628111234567?text=' . urlencode("Halo Admin Hercules Fitness, saya tertarik dengan paket {$typeLabel} Kustom.")
                            : Url::to(['site/checkout', 'package' => $pkg['type'] ?? 'std', 'duration' => $pkg['label'] ?? '']);
                        $btnText = $isKustom ? 'DISKUSIKAN PAKET ANDA' : (Yii::$app->user->isGuest ? 'Daftar' : 'Pilih Paket');
                    ?>
                    <div class="flex-shrink-0 w-[280px] md:w-[300px] pkg-card" data-pkg-type="<?= $isVip ? 'vip' : 'std' ?>">
                        <div class="bg-[#15171c] border <?= $borderClass ?> rounded-2xl p-6 flex flex-col h-full relative group hover:border-brand-gold/40 transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_12px_40px_-12px_rgba(212,175,55,0.15)]">
                            
                            <!-- Package Type Badge -->
                            <div class="flex items-center justify-between mb-5">
                                <span class="<?= $badgeBg ?> text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full"><?= $isKustom ? 'CUSTOM PLAN' : $typeLabel ?></span>
                                <?php if ($isVip): ?>
                                    <i class="fas fa-star text-brand-gold/40 text-xs"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Duration Label -->
                            <h3 class="text-2xl font-black <?= $accentColor ?> uppercase tracking-tight mb-1"><?= $isKustom ? 'CUSTOM MEMBERSHIP' : htmlspecialchars($pkg['label']) ?></h3>
                            <?php if ($isKustom): ?>
                                <p class="text-[10px] text-slate-400 font-medium mb-3 leading-tight max-w-[200px]">Solusi Keanggotaan Sesuai Kebutuhan Anda</p>
                            <?php endif; ?>

                            <!-- Price Per Month -->
                            <div class="flex items-baseline gap-1.5 <?= $isKustom ? 'mb-2 mt-auto' : 'mb-5' ?>">
                                <?php if ($isKustom): ?>
                                    <span class="text-xs font-bold text-slate-400 tracking-wide">Mulai</span>
                                <?php endif; ?>
                                <span class="text-3xl font-extrabold text-white"><?= $pkg['price'] ?></span>
                                <span class="text-slate-500 text-sm font-medium"><?= $isKustom ? '/paket' : '/bulan' ?></span>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-white/5 mb-4"></div>

                            <?php if ($isKustom): ?>
                            <!-- Kustom Benefits -->
                            <div class="mb-6 flex-1">
                                <ul class="space-y-2.5">
                                    <li class="flex items-start gap-2"><i class="fas fa-check text-brand-gold text-[10px] mt-[3px]"></i><span class="text-slate-300 text-[11px] leading-tight">Penyesuaian jadwal & hari kedatangan</span></li>
                                    <li class="flex items-start gap-2"><i class="fas fa-check text-brand-gold text-[10px] mt-[3px]"></i><span class="text-slate-300 text-[11px] leading-tight">Pilihan personalisasi kuota sesi</span></li>
                                    <li class="flex items-start gap-2"><i class="fas fa-check text-brand-gold text-[10px] mt-[3px]"></i><span class="text-slate-300 text-[11px] leading-tight">Konsultasi bebas biaya tersembunyi</span></li>
                                </ul>
                            </div>
                            <?php else: ?>
                            <!-- Total Price -->
                            <div class="mb-6">
                                <p class="text-xs text-slate-600 line-through mb-1"><?= $pkg['strike'] ?></p>
                                <div class="flex items-center gap-2.5">
                                    <span class="text-lg font-bold text-slate-200"><?= $pkg['total'] ?></span>
                                    <span class="bg-red-500/15 text-red-400 text-[10px] font-bold px-2 py-0.5 rounded"><?= $pkg['discount'] ?></span>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- CTA Button -->
                            <div class="mt-auto">
                                <a href="<?= $checkoutUrl ?>" <?= $isKustom ? 'target="_blank"' : '' ?> class="block w-full py-3.5 text-center rounded-xl font-bold uppercase tracking-widest text-sm transition-all duration-300 <?= $btnClass ?>">
                                    <?= $btnText ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Dot Indicators (Mobile) -->
            <div class="flex items-center justify-center gap-2 mt-6 md:hidden" id="pkg-dots"></div>
        </div>

        <script>
        (function() {
            var track = document.getElementById('pkg-slider-track');
            var viewport = document.getElementById('pkg-slider-viewport');
            var prevBtn = document.getElementById('slider-prev');
            var nextBtn = document.getElementById('slider-next');
            var dotsContainer = document.getElementById('pkg-dots');
            var cards = track.children;
            var currentIndex = 0;
            var cardWidth = 0;
            var gap = 20;
            var visibleCards = 3;
            var activeCardCount = cards.length;

            function calcDimensions() {
                if (!cards.length) return;
                // find first visible card to get width
                for (var i = 0; i < cards.length; i++) {
                    if (cards[i].style.display !== 'none') {
                        cardWidth = cards[i].offsetWidth;
                        break;
                    }
                }
                var vw = viewport.offsetWidth;
                visibleCards = Math.max(1, Math.floor((vw + gap) / (cardWidth + gap)));
            }

            function maxIndex() {
                return Math.max(0, activeCardCount - visibleCards);
            }

            function updateSlider() {
                var offset = currentIndex * (cardWidth + gap);
                track.style.transform = 'translateX(-' + offset + 'px)';
                if (prevBtn) prevBtn.style.opacity = currentIndex <= 0 ? '0.3' : '1';
                if (prevBtn) prevBtn.style.pointerEvents = currentIndex <= 0 ? 'none' : 'auto';
                if (nextBtn) nextBtn.style.opacity = currentIndex >= maxIndex() ? '0.3' : '1';
                if (nextBtn) nextBtn.style.pointerEvents = currentIndex >= maxIndex() ? 'none' : 'auto';
                renderDots();
            }

            function renderDots() {
                if (!dotsContainer) return;
                dotsContainer.innerHTML = '';
                var total = maxIndex() + 1;
                for (var i = 0; i < total; i++) {
                    var dot = document.createElement('button');
                    dot.className = 'w-2 h-2 rounded-full transition-all duration-300 ' + (i === currentIndex ? 'bg-brand-gold w-6' : 'bg-white/20');
                    dot.setAttribute('data-idx', i);
                    dot.onclick = function() { currentIndex = parseInt(this.getAttribute('data-idx')); updateSlider(); };
                    dotsContainer.appendChild(dot);
                }
            }

            window.slidePackages = function(dir) {
                currentIndex = Math.max(0, Math.min(maxIndex(), currentIndex + dir));
                updateSlider();
            };

            // Touch/swipe support
            var startX = 0, startY = 0, isDragging = false;
            track.addEventListener('touchstart', function(e) { startX = e.touches[0].clientX; startY = e.touches[0].clientY; isDragging = true; }, {passive: true});
            track.addEventListener('touchend', function(e) {
                if (!isDragging) return;
                isDragging = false;
                var dx = e.changedTouches[0].clientX - startX;
                var dy = e.changedTouches[0].clientY - startY;
                if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 40) {
                    slidePackages(dx < 0 ? 1 : -1);
                }
            }, {passive: true});

            window.addEventListener('resize', function() { calcDimensions(); updateSlider(); });
            document.addEventListener('DOMContentLoaded', function() { calcDimensions(); updateSlider(); });

            // Package type filter
            window.filterPackageType = function(type, tabIndex) {
                var underline = document.getElementById('pkg-tab-underline');
                document.querySelectorAll('.pkg-type-btn').forEach(function(b) {
                    b.classList.remove('text-white');
                    b.classList.add('text-slate-500');
                });
                var activeBtn = document.getElementById('pkg-tab-' + type);
                if (activeBtn) {
                    activeBtn.classList.add('text-white');
                    activeBtn.classList.remove('text-slate-500');
                    underline.style.left = activeBtn.offsetLeft + 'px';
                    underline.style.width = activeBtn.offsetWidth + 'px';
                }
                var count = 0;
                document.querySelectorAll('.pkg-card').forEach(function(card) {
                    if (type === 'all' || card.getAttribute('data-pkg-type') === type) {
                        card.style.display = '';
                        count++;
                    } else {
                        card.style.display = 'none';
                    }
                });
                activeCardCount = count;
                currentIndex = 0;
                calcDimensions();
                updateSlider();
            };

            // Initialize underline on first tab and filter
            document.addEventListener('DOMContentLoaded', function() {
                var firstTab = document.getElementById('pkg-tab-std');
                if (firstTab) {
                    var underline = document.getElementById('pkg-tab-underline');
                    underline.style.left = firstTab.offsetLeft + 'px';
                    underline.style.width = firstTab.offsetWidth + 'px';
                }
                filterPackageType('std', 0);
            });

            // Branch selector logic
            var BRANCHES = {
                'batu-ampar': 'Batu Ampar',
                'batu-besar': 'Batu Besar',
                'mtc': 'MTC Batam',
                'canggu': 'Canggu',
                'kuta': 'Kuta'
            };
            var CITIES = { batam: ['batu-ampar', 'batu-besar', 'mtc'], bali: ['canggu', 'kuta'] };
            var currentBranch = 'batu-ampar';

            function renderBranches(city) {
                var c = document.getElementById('branch-container');
                c.innerHTML = '';
                CITIES[city].forEach(function(key) {
                    var b = document.createElement('button');
                    var isActive = key === currentBranch;
                    b.className = 'branch-btn px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-300 border ' +
                        (isActive ? 'bg-brand-gold text-slate-900 border-brand-gold shadow-[0_4px_15px_rgba(212,175,55,0.3)]' : 'bg-transparent text-slate-400 border-slate-700 hover:text-white hover:border-slate-500');
                    b.textContent = BRANCHES[key];
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
                    if (b.textContent === BRANCHES[key]) {
                        b.className = 'branch-btn px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-300 border bg-brand-gold text-slate-900 border-brand-gold shadow-[0_4px_15px_rgba(212,175,55,0.3)]';
                    } else {
                        b.className = 'branch-btn px-5 py-2 text-xs font-bold uppercase tracking-wider rounded-full transition-all duration-300 border bg-transparent text-slate-400 border-slate-700 hover:text-white hover:border-slate-500';
                    }
                });
                document.getElementById('active-branch-label').textContent = BRANCHES[key];
            };

            // Initialize branch selector on load
            document.addEventListener('DOMContentLoaded', function() {
                var urlParams = new URLSearchParams(window.location.search);
                var initialCity = urlParams.get('city') || 'batam';
                var initialBranch = urlParams.get('branch') || 'batu-ampar';
                if (!CITIES[initialCity] || !CITIES[initialCity].includes(initialBranch)) {
                    initialCity = 'batam';
                    initialBranch = 'batu-ampar';
                }
                selectCity(initialCity, initialBranch);
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

