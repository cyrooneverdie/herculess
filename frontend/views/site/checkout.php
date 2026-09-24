<?php
/** @var yii\web\View $this */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Checkout Membership - Hercules Fitness Centre';
$this->params['hideFooter'] = true;

$package = Yii::$app->request->get('package', 'std');
$branch = Yii::$app->request->get('branch', 'batu-ampar');
$duration = Yii::$app->request->get('duration', '12');
?>

<div class="bg-[#0d0f13] min-h-screen pt-28 pb-20 px-4 md:px-6 font-sans">
    <div class="max-w-5xl mx-auto">

        <!-- Back Link -->
        <a href="<?= Url::to(['site/membership']) ?>" class="inline-flex items-center gap-2 text-slate-400 hover:text-brand-gold transition-colors text-sm mb-8 group">
            <i class="fas fa-arrow-left text-xs group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Pilihan Paket
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            <!-- LEFT: Plan Summary (2 cols) -->
            <div class="lg:col-span-2">
                <div class="bg-[#121316] border border-white/10 rounded-2xl p-6 sticky top-28">
                    
                    <!-- Plan Badge -->
                    <div class="mb-5">
                        <span id="checkout-badge" class="inline-block text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full bg-white/10 text-slate-300">Standard</span>
                    </div>

                    <!-- Plan Name -->
                    <h2 id="checkout-plan-name" class="text-2xl font-black text-white uppercase tracking-tight font-condensed mb-1">Standard</h2>
                    <p id="checkout-plan-desc" class="text-slate-500 text-sm mb-6">Akses latihan 1 cabang fokus</p>
                    
                    <!-- Price -->
                    <div class="border-t border-white/10 pt-5 mb-6">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span id="checkout-price" class="text-3xl font-extrabold text-white">Rp 225.000</span>
                            <span class="text-slate-500 text-sm">/bulan</span>
                        </div>
                        <p id="checkout-duration-label" class="text-xs text-slate-500">Durasi 12 bulan</p>
                    </div>

                    <!-- Benefits -->
                    <div class="mb-6">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-3">Termasuk:</p>
                        <ul id="checkout-benefits" class="space-y-3">
                            <li class="flex items-start gap-2.5"><i class="fas fa-check text-brand-gold mt-0.5 text-xs"></i><span class="text-slate-300 text-sm">1 Cabang Tetap</span></li>
                            <li class="flex items-start gap-2.5"><i class="fas fa-check text-brand-gold mt-0.5 text-xs"></i><span class="text-slate-300 text-sm">Loker Reguler Harian</span></li>
                            <li class="flex items-start gap-2.5"><i class="fas fa-check text-brand-gold mt-0.5 text-xs"></i><span class="text-slate-300 text-sm">1x Sesi Orientasi Alat</span></li>
                            <li class="flex items-start gap-2.5"><i class="fas fa-check text-brand-gold mt-0.5 text-xs"></i><span class="text-slate-300 text-sm">Shower & Wi-Fi</span></li>
                        </ul>
                    </div>

                    <!-- Branch -->
                    <div class="bg-white/[0.03] rounded-xl p-4 border border-white/5">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cabang</p>
                        <p id="checkout-branch" class="text-white font-bold text-sm">Batu Ampar</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Payment & Summary (3 cols) -->
            <div class="lg:col-span-3 space-y-6">

                <!-- Duration Selector -->
                <div class="bg-[#121316] border border-white/10 rounded-2xl p-6">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Pilih Durasi</h3>
                    <div class="grid grid-cols-3 gap-3">
                        <button onclick="selectDuration(0)" id="dur-0" class="dur-opt py-3 rounded-xl text-center border-2 transition-all text-sm font-bold border-white/10 text-slate-400 hover:border-white/20">
                            <span class="block">6 Bulan</span>
                        </button>
                        <button onclick="selectDuration(1)" id="dur-1" class="dur-opt py-3 rounded-xl text-center border-2 transition-all text-sm font-bold border-brand-gold text-brand-gold bg-brand-gold/10">
                            <span class="block">12 Bulan</span>
                        </button>
                        <button onclick="selectDuration(2)" id="dur-2" class="dur-opt py-3 rounded-xl text-center border-2 transition-all text-sm font-bold border-white/10 text-slate-400 hover:border-white/20">
                            <span class="block">18 Bulan</span>
                        </button>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="bg-[#121316] border border-white/10 rounded-2xl p-6">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Metode Pembayaran</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <button onclick="selectPayment(this, 'qris')" class="pay-opt group flex flex-col items-center gap-2 py-4 rounded-xl border-2 transition-all border-brand-gold bg-brand-gold/10">
                            <i class="fas fa-qrcode text-xl text-brand-gold"></i>
                            <span class="text-xs font-bold text-brand-gold">QRIS</span>
                        </button>
                        <button onclick="selectPayment(this, 'transfer')" class="pay-opt group flex flex-col items-center gap-2 py-4 rounded-xl border-2 transition-all border-white/10 text-slate-400 hover:border-white/20">
                            <i class="fas fa-university text-xl"></i>
                            <span class="text-xs font-bold">Transfer</span>
                        </button>
                        <button onclick="selectPayment(this, 'ewallet')" class="pay-opt group flex flex-col items-center gap-2 py-4 rounded-xl border-2 transition-all border-white/10 text-slate-400 hover:border-white/20">
                            <i class="fas fa-wallet text-xl"></i>
                            <span class="text-xs font-bold">E-Wallet</span>
                        </button>
                        <button onclick="selectPayment(this, 'card')" class="pay-opt group flex flex-col items-center gap-2 py-4 rounded-xl border-2 transition-all border-white/10 text-slate-400 hover:border-white/20">
                            <i class="fas fa-credit-card text-xl"></i>
                            <span class="text-xs font-bold">Kartu</span>
                        </button>
                    </div>
                </div>

                <!-- Promo Code -->
                <div class="bg-[#121316] border border-white/10 rounded-2xl p-6">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Kode Promo</h3>
                    <div class="flex gap-3">
                        <input type="text" id="promo-input" placeholder="Masukkan kode promo" class="flex-1 bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:outline-none focus:border-brand-gold/50 transition-colors" />
                        <button onclick="applyPromo()" class="px-6 py-3 bg-white/[0.06] border border-white/10 rounded-xl text-sm font-bold text-slate-300 hover:bg-white/10 hover:text-white transition-all">
                            Terapkan
                        </button>
                    </div>
                    <p id="promo-msg" class="text-xs mt-2 hidden"></p>
                </div>

                <!-- Order Summary -->
                <div class="bg-[#121316] border border-white/10 rounded-2xl p-6">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider mb-5">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-3 mb-5">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 text-sm">Subtotal</span>
                            <span id="summary-subtotal" class="text-slate-300 text-sm font-medium">Rp 2.700.000</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 text-sm">Diskon</span>
                            <span id="summary-discount" class="text-sm font-medium text-red-400">- Rp 0</span>
                        </div>
                        <div class="border-t border-white/10 pt-3 flex justify-between items-center">
                            <span class="text-white font-bold">Total</span>
                            <span id="summary-total" class="text-white font-extrabold text-xl">Rp 2.700.000</span>
                        </div>
                    </div>

                    <!-- Pay Button -->
                    <button id="btn-pay" onclick="processPayment()" class="w-full py-4 bg-brand-gold text-slate-900 font-black uppercase tracking-widest rounded-xl hover:shadow-[0_4px_20px_rgba(212,175,55,0.35)] hover:-translate-y-0.5 transition-all text-sm">
                        Bayar Sekarang — <span id="btn-total">Rp 2.700.000</span>
                    </button>
                    
                    <p class="text-center text-slate-600 text-xs mt-4">
                        <i class="fas fa-shield-alt text-brand-gold/60 mr-1"></i>
                        Pembayaran diproses dengan aman oleh Payment Gateway
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QRIS Payment Modal -->
<div id="qris-modal" class="fixed inset-0 z-[100] hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeQrisModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-[#121316] border border-white/10 rounded-2xl w-full max-w-md relative shadow-2xl overflow-hidden">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                <div class="flex items-center gap-3">
                    <img src="/img/hercules.jpeg" alt="Hercules" class="w-8 h-8 rounded-full object-cover">
                    <div>
                        <p class="text-white font-bold text-sm">Hercules Fitness</p>
                        <p class="text-slate-500 text-[10px] uppercase tracking-wider">Checkout</p>
                    </div>
                </div>
                <button onclick="closeQrisModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Timer -->
            <div class="px-6 pb-3">
                <div class="flex items-center justify-between bg-red-500/10 border border-red-500/20 rounded-lg px-4 py-2">
                    <span class="text-red-400 text-xs font-bold flex items-center gap-1.5">
                        <i class="fas fa-exclamation-triangle text-[10px]"></i>
                        Selesaikan pembayaran dalam
                    </span>
                    <span id="qris-timer" class="text-red-400 font-mono font-bold text-sm">59:59</span>
                </div>
            </div>

            <!-- Total -->
            <div class="px-6 pb-4 text-center">
                <p class="text-slate-500 text-xs uppercase tracking-wider mb-1">Total Pembayaran</p>
                <p id="qris-total" class="text-3xl font-extrabold text-white">Rp 2.700.000</p>
            </div>

            <!-- QR Code -->
            <div class="px-6 pb-4 flex justify-center">
                <div class="bg-white rounded-xl p-4 relative">
                    <canvas id="qr-canvas" width="200" height="200"></canvas>
                    <!-- Hercules logo overlay -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-10 h-10 rounded-full bg-white border-2 border-white shadow-md overflow-hidden">
                            <img src="/img/hercules.jpeg" alt="H" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="px-6 pb-3 text-center">
                <p class="text-slate-400 text-xs flex items-center justify-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-brand-gold animate-pulse"></span>
                    Menunggu pembayaran kamu...
                </p>
            </div>

            <!-- Actions -->
            <div class="px-6 pb-6 flex flex-col items-center gap-3">
                <button onclick="simulateSuccess()" class="text-brand-gold text-xs font-bold hover:underline transition">
                    Simulasi: Bayar Berhasil
                </button>
                <button onclick="closeQrisModal()" class="text-slate-500 text-xs hover:text-slate-300 transition">
                    Ganti metode pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Success Modal -->
<div id="success-modal" class="fixed inset-0 z-[110] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-[#121316] border border-white/10 rounded-2xl w-full max-w-sm relative shadow-2xl text-center px-8 py-10">
            <div class="w-20 h-20 rounded-full bg-green-500/20 border-2 border-green-500/40 flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-check text-green-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-black text-white uppercase tracking-tight mb-2">Pembayaran Berhasil</h3>
            <p class="text-slate-400 text-sm mb-6">Terima kasih! Membership Anda sedang diproses. Tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi.</p>
            <div class="bg-white/[0.03] border border-white/10 rounded-xl p-4 mb-6 text-left">
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-slate-500">No. Transaksi</span>
                    <span id="trx-id" class="text-white font-mono font-bold">HRC-240924-XXXX</span>
                </div>
                <div class="flex justify-between text-xs mb-2">
                    <span class="text-slate-500">Status</span>
                    <span class="text-green-400 font-bold">Lunas</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-slate-500">Metode</span>
                    <span class="text-white font-bold">QRIS</span>
                </div>
            </div>
            <a href="<?= Url::to(['site/index']) ?>" class="block w-full py-3.5 bg-brand-gold text-slate-900 font-black uppercase tracking-widest rounded-xl hover:shadow-[0_4px_20px_rgba(212,175,55,0.35)] transition-all text-sm">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
(function() {
    var PRICING = {
        'batu-ampar': {
            label: 'Batu Ampar',
            std: [
                { price: 325000, perMonth: 'Rp 325.000', total: 1950000, months: 6 },
                { price: 225000, perMonth: 'Rp 225.000', total: 2700000, months: 12 },
                { price: 199000, perMonth: 'Rp 199.000', total: 3582000, months: 18 }
            ],
            vip: [
                { price: 399000, perMonth: 'Rp 399.000', total: 2394000, months: 6 },
                { price: 275000, perMonth: 'Rp 275.000', total: 3300000, months: 12 },
                { price: 249000, perMonth: 'Rp 249.000', total: 4482000, months: 18 }
            ]
        },
        'batu-besar': {
            label: 'Batu Besar',
            std: [
                { price: 300000, perMonth: 'Rp 300.000', total: 1800000, months: 6 },
                { price: 210000, perMonth: 'Rp 210.000', total: 2520000, months: 12 },
                { price: 185000, perMonth: 'Rp 185.000', total: 3330000, months: 18 }
            ],
            vip: [
                { price: 375000, perMonth: 'Rp 375.000', total: 2250000, months: 6 },
                { price: 260000, perMonth: 'Rp 260.000', total: 3120000, months: 12 },
                { price: 235000, perMonth: 'Rp 235.000', total: 4230000, months: 18 }
            ]
        },
        'mtc': {
            label: 'MTC Batam',
            std: [
                { price: 350000, perMonth: 'Rp 350.000', total: 2100000, months: 6 },
                { price: 240000, perMonth: 'Rp 240.000', total: 2880000, months: 12 },
                { price: 215000, perMonth: 'Rp 215.000', total: 3870000, months: 18 }
            ],
            vip: [
                { price: 425000, perMonth: 'Rp 425.000', total: 2550000, months: 6 },
                { price: 295000, perMonth: 'Rp 295.000', total: 3540000, months: 12 },
                { price: 265000, perMonth: 'Rp 265.000', total: 4770000, months: 18 }
            ]
        },
        'canggu': {
            label: 'Canggu',
            std: [
                { price: 375000, perMonth: 'Rp 375.000', total: 2250000, months: 6 },
                { price: 260000, perMonth: 'Rp 260.000', total: 3120000, months: 12 },
                { price: 230000, perMonth: 'Rp 230.000', total: 4140000, months: 18 }
            ],
            vip: [
                { price: 450000, perMonth: 'Rp 450.000', total: 2700000, months: 6 },
                { price: 315000, perMonth: 'Rp 315.000', total: 3780000, months: 12 },
                { price: 280000, perMonth: 'Rp 280.000', total: 5040000, months: 18 }
            ]
        },
        'kuta': {
            label: 'Kuta',
            std: [
                { price: 350000, perMonth: 'Rp 350.000', total: 2100000, months: 6 },
                { price: 245000, perMonth: 'Rp 245.000', total: 2940000, months: 12 },
                { price: 220000, perMonth: 'Rp 220.000', total: 3960000, months: 18 }
            ],
            vip: [
                { price: 430000, perMonth: 'Rp 430.000', total: 2580000, months: 6 },
                { price: 300000, perMonth: 'Rp 300.000', total: 3600000, months: 12 },
                { price: 270000, perMonth: 'Rp 270.000', total: 4860000, months: 18 }
            ]
        }
    };

    var BENEFITS = {
        std: [
            '1 Cabang Tetap',
            'Loker Reguler Harian',
            '1x Sesi Orientasi Alat',
            'Shower & Wi-Fi'
        ],
        vip: [
            'Akses 2 Cabang',
            'Free Handuk Setiap Latihan',
            '4x Sesi PT Pribadi + InBody',
            '1 Free Pass Teman / Bulan',
            'Prioritas Reservasi Kelas'
        ]
    };

    var PLAN_NAMES = {
        std: { name: 'Standard', desc: 'Akses latihan 1 cabang fokus' },
        vip: { name: 'VIP All-Access', desc: 'Bebas tanpa batas 2 cabang' }
    };

    var params = new URLSearchParams(window.location.search);
    var currentPkg = params.get('package') || 'std';
    var currentBranch = params.get('branch') || 'batu-ampar';
    var durMap = { '6': 0, '12': 1, '18': 2 };
    var currentDurIndex = durMap[params.get('duration')] !== undefined ? durMap[params.get('duration')] : 1;
    window.initialDurIndex = currentDurIndex;
    var promoDiscount = 0;

    function fmt(n) {
        return 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateUI() {
        var data = PRICING[currentBranch];
        if (!data) { currentBranch = 'batu-ampar'; data = PRICING[currentBranch]; }
        var plan = data[currentPkg];
        if (!plan) { currentPkg = 'std'; plan = data[currentPkg]; }
        var tier = plan[currentDurIndex];
        var info = PLAN_NAMES[currentPkg];

        // Left panel
        var badge = document.getElementById('checkout-badge');
        badge.textContent = info.name;
        if (currentPkg === 'vip') {
            badge.className = 'inline-block text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full bg-brand-gold/20 text-brand-gold';
        } else {
            badge.className = 'inline-block text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full bg-white/10 text-slate-300';
        }
        document.getElementById('checkout-plan-name').textContent = info.name;
        document.getElementById('checkout-plan-desc').textContent = info.desc;
        document.getElementById('checkout-price').textContent = tier.perMonth;
        document.getElementById('checkout-duration-label').textContent = 'Durasi ' + tier.months + ' bulan';
        document.getElementById('checkout-branch').textContent = data.label;

        // Benefits
        var benefitsList = document.getElementById('checkout-benefits');
        benefitsList.innerHTML = '';
        BENEFITS[currentPkg].forEach(function(b) {
            var li = document.createElement('li');
            li.className = 'flex items-start gap-2.5';
            li.innerHTML = '<i class="fas fa-check text-brand-gold mt-0.5 text-xs"></i><span class="text-slate-300 text-sm">' + b + '</span>';
            benefitsList.appendChild(li);
        });

        // Duration buttons
        var durLabels = ['6 Bulan', '12 Bulan', '18 Bulan'];
        for (var i = 0; i < 3; i++) {
            var btn = document.getElementById('dur-' + i);
            var isInitial = (i === window.initialDurIndex);
            
            if (i === currentDurIndex) {
                btn.className = 'dur-opt py-3 rounded-xl flex flex-col items-center justify-center border-2 transition-all border-brand-gold text-brand-gold bg-brand-gold/10';
                if (isInitial) {
                    btn.innerHTML = '<span class="text-[9px] uppercase tracking-wider font-extrabold mb-0.5 opacity-80">Pilihan Awal</span><span class="block text-sm font-bold">' + durLabels[i] + '</span>';
                } else {
                    btn.innerHTML = '<span class="block text-sm font-bold">' + durLabels[i] + '</span>';
                }
            } else {
                btn.className = 'dur-opt py-3 rounded-xl flex flex-col items-center justify-center border-2 transition-all border-white/10 text-slate-400 hover:border-white/20';
                if (isInitial) {
                    // if user asked to keep it visible, but they said "teks penanda nya hilang". We'll hide it.
                    btn.innerHTML = '<span class="block text-sm font-bold">' + durLabels[i] + '</span>';
                } else {
                    btn.innerHTML = '<span class="block text-sm font-bold">' + durLabels[i] + '</span>';
                }
            }
        }

        // Summary
        var subtotal = tier.total;
        var discountAmt = Math.round(subtotal * promoDiscount);
        var total = subtotal - discountAmt;
        document.getElementById('summary-subtotal').textContent = fmt(subtotal);
        document.getElementById('summary-discount').textContent = '- ' + fmt(discountAmt);
        document.getElementById('summary-total').textContent = fmt(total);
        document.getElementById('btn-total').textContent = fmt(total);
    }

    window.selectDuration = function(idx) {
        currentDurIndex = idx;
        updateUI();
    };

    window.selectPayment = function(el, method) {
        document.querySelectorAll('.pay-opt').forEach(function(b) {
            b.className = 'pay-opt group flex flex-col items-center gap-2 py-4 rounded-xl border-2 transition-all border-white/10 text-slate-400 hover:border-white/20';
            b.querySelectorAll('i, span').forEach(function(c) {
                c.classList.remove('text-brand-gold');
            });
        });
        el.className = 'pay-opt group flex flex-col items-center gap-2 py-4 rounded-xl border-2 transition-all border-brand-gold bg-brand-gold/10';
        el.querySelectorAll('i, span').forEach(function(c) {
            c.classList.add('text-brand-gold');
        });
    };

    window.applyPromo = function() {
        var code = document.getElementById('promo-input').value.trim().toUpperCase();
        var msg = document.getElementById('promo-msg');
        msg.classList.remove('hidden');
        if (code === 'HERCULES10') {
            promoDiscount = 0.10;
            msg.textContent = 'Kode promo berhasil diterapkan! Diskon 10%';
            msg.className = 'text-xs mt-2 text-green-400';
        } else if (code === '') {
            msg.classList.add('hidden');
            promoDiscount = 0;
        } else {
            promoDiscount = 0;
            msg.textContent = 'Kode promo tidak valid.';
            msg.className = 'text-xs mt-2 text-red-400';
        }
        updateUI();
    };

    window.processPayment = function() {
        var totalEl = document.getElementById('summary-total');
        document.getElementById('qris-total').textContent = totalEl.textContent;
        document.getElementById('qris-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        drawFakeQR();
        startTimer();
    };

    window.closeQrisModal = function() {
        document.getElementById('qris-modal').classList.add('hidden');
        document.body.style.overflow = '';
        if (window._qrisTimer) clearInterval(window._qrisTimer);
    };

    window.simulateSuccess = function() {
        closeQrisModal();
        var now = new Date();
        var trxId = 'HRC-' + 
            String(now.getFullYear()).slice(2) +
            String(now.getMonth()+1).padStart(2,'0') +
            String(now.getDate()).padStart(2,'0') + '-' +
            String(Math.floor(Math.random()*9000)+1000);
        document.getElementById('trx-id').textContent = trxId;
        document.getElementById('success-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    function startTimer() {
        var remaining = 3599;
        var el = document.getElementById('qris-timer');
        if (window._qrisTimer) clearInterval(window._qrisTimer);
        window._qrisTimer = setInterval(function() {
            remaining--;
            if (remaining <= 0) {
                clearInterval(window._qrisTimer);
                closeQrisModal();
                return;
            }
            var m = Math.floor(remaining / 60);
            var s = remaining % 60;
            el.textContent = String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        }, 1000);
    }

    function drawFakeQR() {
        var canvas = document.getElementById('qr-canvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var size = 200;
        var cellSize = 5;
        var modules = size / cellSize;
        ctx.clearRect(0, 0, size, size);
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, size, size);
        ctx.fillStyle = '#000000';

        // Draw finder patterns (the 3 corner squares)
        function drawFinder(x, y) {
            for (var r = 0; r < 7; r++) {
                for (var c = 0; c < 7; c++) {
                    var fill = false;
                    if (r === 0 || r === 6 || c === 0 || c === 6) fill = true;
                    if (r >= 2 && r <= 4 && c >= 2 && c <= 4) fill = true;
                    if (fill) {
                        ctx.fillRect((x+c)*cellSize, (y+r)*cellSize, cellSize, cellSize);
                    }
                }
            }
        }
        drawFinder(0, 0);
        drawFinder(modules - 7, 0);
        drawFinder(0, modules - 7);

        // Draw random data modules (avoiding finder areas and center logo area)
        var seed = Date.now();
        function pseudoRand() { seed = (seed * 16807 + 7) % 2147483647; return seed / 2147483647; }
        var centerStart = Math.floor(modules/2) - 5;
        var centerEnd = Math.floor(modules/2) + 5;
        for (var r = 0; r < modules; r++) {
            for (var c = 0; c < modules; c++) {
                if (r < 9 && c < 9) continue;
                if (r < 9 && c > modules - 9) continue;
                if (r > modules - 9 && c < 9) continue;
                if (r >= centerStart && r <= centerEnd && c >= centerStart && c <= centerEnd) continue;
                if (pseudoRand() > 0.55) {
                    ctx.fillRect(c*cellSize, r*cellSize, cellSize, cellSize);
                }
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateUI();
    });
})();
</script>
