<?php
/** @var yii\web\View $this */
/** @var string $id */
/** @var array $branch */
/** @var array $facilities */
/** @var array $equipments */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = $branch['name'] . ' - Hercules Fitness Centre';
?>
<?php
$isBali = in_array($id, ['canggu', 'kuta']);
$backLink = $isBali ? Url::to(['site/location-bali']) : Url::to(['site/location-batam']);
$backText = $isBali ? 'Kembali ke Lokasi Bali' : 'Kembali ke Lokasi Batam';
?>

<div class="bg-[#0b0c10] min-h-screen font-sans text-white overflow-hidden pb-20">

    <!-- HERO SECTION -->
    <div class="relative h-[60vh] min-h-[500px] w-full flex items-end justify-center pb-16">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="<?= Html::encode($branch['bg']) ?>" alt="<?= Html::encode($branch['name']) ?>" class="w-full h-full object-cover">
            <!-- Overlays -->
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0b0c10] via-[#0b0c10]/80 to-transparent"></div>
        </div>

        <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
            <a href="<?= $backLink ?>" class="inline-flex items-center gap-2 text-slate-300 hover:text-brand-gold transition-colors text-xs font-bold uppercase tracking-wider mb-8 bg-white/5 hover:bg-white/10 px-4 py-2 rounded-full border border-white/10 backdrop-blur-sm">
                <i class="fas fa-arrow-left"></i> <?= $backText ?>
            </a>
            <h1 class="text-5xl md:text-7xl font-black uppercase font-condensed tracking-tight leading-none mb-6">
                Cabang <span class="text-brand-gold drop-shadow-[0_0_15px_rgba(212,175,55,0.3)]"><?= Html::encode($branch['name']) ?></span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-8 font-light">
                <?= Html::encode($branch['desc']) ?>
            </p>

        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 relative z-10 -mt-8">
        <!-- QUICK INFO BAR -->
        <div class="bg-[#121316] border border-white/10 rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl mb-24 backdrop-blur-xl">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-brand-gold/10 flex items-center justify-center text-brand-gold text-xl">
                    <i class="fas fa-map-signs"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Alamat Lengkap</p>
                    <p class="text-sm text-slate-200 font-medium"><?= Html::encode($branch['address']) ?></p>
                    <a href="#map-section" onclick="document.getElementById('map-section').scrollIntoView({ behavior: 'smooth' }); return false;" class="inline-flex items-center gap-2 mt-3 text-brand-gold text-xs font-bold uppercase tracking-wider hover:text-amber-400 transition-colors">
                        Lihat Lokasi Peta <i class="fas fa-arrow-down"></i>
                    </a>
                </div>
            </div>
            <div class="h-12 w-px bg-white/10 hidden md:block"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-brand-gold/10 flex items-center justify-center text-brand-gold text-xl">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mb-1">Jam Operasional</p>
                    <p class="text-sm text-slate-200 font-medium">Setiap Hari: 06:00 - 22:00 WIB</p>
                </div>
            </div>
        </div>

        <!-- PHOTO GALLERY (Asymmetric Masonry Concept) -->
        <div class="mb-24">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-black uppercase font-condensed tracking-wide">Galeri <span class="text-brand-gold">Klub</span></h2>
                    <p class="text-slate-400 mt-2 text-sm">Intip suasana dan fasilitas di Cabang <?= Html::encode($branch['name']) ?></p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 grid-rows-2 gap-4 h-[600px]">
                <div class="md:col-span-2 md:row-span-2 rounded-2xl overflow-hidden relative group">
                    <img src="<?= Html::encode($branch['bg']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Gallery">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <div class="md:col-span-2 rounded-2xl overflow-hidden relative group">
                    <img src="/img/clip6.jpeg" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Gallery">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <div class="rounded-2xl overflow-hidden relative group">
                    <img src="/img/clip4.jpeg" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Gallery">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <div class="rounded-2xl overflow-hidden relative group cursor-pointer" onclick="openGalleryModal()">
                    <img src="/img/clip5.jpeg" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Gallery">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-black/50 transition-colors flex items-center justify-center">
                        <span class="text-white font-bold tracking-wider text-sm flex items-center gap-2 px-5 py-2.5 bg-white/10 border border-white/20 rounded-full backdrop-blur-md">
                            <i class="fas fa-images"></i> Lihat Semua Gambar
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- BENTO GRID: FASILITAS -->
        <div class="mb-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black uppercase font-condensed tracking-wide mb-4">
                    Fasilitas <span class="text-brand-gold">Premium</span>
                </h2>
                <p class="text-slate-400 text-sm max-w-xl mx-auto">Dirancang untuk memberikan kenyamanan maksimal sebelum, selama, dan setelah sesi latihan Anda.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($facilities as $idx => $fac): ?>
                    <div class="bg-[#121316] border border-white/5 rounded-3xl p-8 hover:bg-white/[0.03] transition-all duration-300 hover:border-brand-gold/30 hover:-translate-y-1 hover:shadow-[0_15px_30px_rgba(0,0,0,0.5)] group relative overflow-hidden">
                        <!-- Glow effect on hover -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-brand-gold/0 rounded-full blur-3xl group-hover:bg-brand-gold/10 transition-colors duration-500"></div>
                        
                        <div class="w-14 h-14 rounded-2xl bg-white/5 flex items-center justify-center mb-6 group-hover:bg-brand-gold/20 transition-colors">
                            <i class="<?= $fac['icon'] ?> text-2xl text-brand-gold"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2"><?= $fac['title'] ?></h3>
                        <p class="text-slate-400 text-sm leading-relaxed"><?= $fac['desc'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- ALAT GYM CATEGORIES -->
        <div class="mb-24">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-black uppercase font-condensed tracking-wide mb-4">
                    Ketersediaan <span class="text-brand-gold">Alat Gym</span>
                </h2>
                <p class="text-slate-400 text-sm">Peralatan standar internasional untuk segala kebutuhan latihan beban dan kardio Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-16">
                <?php foreach ($equipments as $category => $items): ?>
                    <div>
                        <h4 class="text-lg font-black uppercase tracking-widest text-white border-b border-white/10 pb-3 mb-5 flex items-center gap-3">
                            <i class="fas fa-dumbbell text-brand-gold text-sm"></i> <?= $category ?>
                        </h4>
                        <ul class="space-y-3">
                            <?php foreach ($items as $item): ?>
                                <li class="flex items-start gap-3">
                                    <i class="fas fa-check text-brand-gold/70 mt-1 text-xs"></i>
                                    <span class="text-slate-300 text-sm"><?= $item ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- MAP SECTION -->
        <div class="mb-20" id="map-section">
            <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-white mb-8 border-b border-white/10 pb-4">
                Lokasi <span class="text-brand-gold">Cabang</span>
            </h2>
            <div class="w-full h-[400px] rounded-3xl overflow-hidden border border-white/10 relative z-10 shadow-2xl" id="branch-map"></div>
        </div>

        <!-- CTA BANNER -->
        <div class="bg-gradient-to-r from-brand-gold to-yellow-500 rounded-3xl p-10 md:p-14 text-center md:text-left flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden shadow-[0_20px_50px_rgba(212,175,55,0.2)]">
            <!-- Decorative watermark -->
            <i class="fas fa-dumbbell absolute -right-10 -top-10 text-[200px] text-black/5 -rotate-12 pointer-events-none"></i>
            
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-black uppercase font-condensed text-slate-900 mb-3 tracking-tight">
                    Latihan di <?= Html::encode($branch['name']) ?>
                </h2>
                <p class="text-slate-800 font-medium">Dapatkan penawaran spesial membership bulan ini.</p>
            </div>
            <?php $city = in_array($id, ['canggu', 'kuta']) ? 'bali' : 'batam'; ?>
            <a href="<?= Url::to(['site/membership', 'city' => $city, 'branch' => $id]) ?>" class="relative z-10 shrink-0 px-8 py-4 bg-slate-900 text-brand-gold font-black uppercase tracking-widest rounded-xl hover:bg-black hover:scale-105 transition-all shadow-xl text-sm">
                Lihat Harga Membership
            </a>
        </div>

        <!-- CABANG LAINNYA -->
        <?php if (!empty($otherBranches)): ?>
        <div class="mt-20 border-t border-white/10 pt-16">
            <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-white mb-8">
                Cabang Lainnya di <span class="text-brand-gold">Wilayah Ini</span>
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($otherBranches as $branchId => $branchData): ?>
                <a href="<?= Url::to(['site/location-detail', 'id' => $branchId]) ?>" class="group relative bg-[#121316] rounded-3xl p-6 border border-white/5 hover:border-brand-gold/40 hover:-translate-y-2 transition-all duration-300 shadow-xl overflow-hidden flex items-center gap-6">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-gold/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    <div class="w-20 h-20 rounded-2xl overflow-hidden shrink-0 border border-white/10 group-hover:border-brand-gold/30 transition-colors">
                        <img src="<?= Html::encode($branchData['bg']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="<?= Html::encode($branchData['name']) ?>">
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-white font-bold text-lg mb-1 group-hover:text-brand-gold transition-colors font-condensed tracking-wide uppercase"><?= Html::encode($branchData['name']) ?></h3>
                        <p class="text-slate-400 text-xs line-clamp-2 leading-relaxed"><?= Html::encode($branchData['address']) ?></p>
                    </div>
                    <div class="ml-auto w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-slate-400 group-hover:bg-brand-gold group-hover:text-slate-900 transition-colors shrink-0 relative z-10">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Gallery Modal -->
<div id="gallery-modal" class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300 flex flex-col">
    <div class="flex justify-between items-center p-6 border-b border-white/10 bg-[#0b0c10]/50 sticky top-0 z-10">
        <h3 class="text-white font-bold tracking-wider uppercase">Galeri Cabang <?= Html::encode($branch['name']) ?></h3>
        <button onclick="closeGalleryModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="flex-1 overflow-y-auto p-6 md:p-10 scrollbar-hide">
        <div class="max-w-6xl mx-auto columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6 pb-20">
            <!-- Mock Gallery Images -->
            <img src="<?= Html::encode($branch['bg']) ?>" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/clip6.jpeg" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/clip4.jpeg" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/clip5.jpeg" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/hero.jpg" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/hero2.jpg" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/lingkungan_gym.png" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/clip1.jpeg" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
            <img src="/img/clip2.jpeg" class="w-full rounded-2xl border border-white/10 shadow-2xl" alt="Gallery">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof L !== 'undefined') {
        const map = L.map('branch-map', {
            zoomControl: false,
            scrollWheelZoom: false
        }).setView([<?= $branch['lat'] ?>, <?= $branch['lng'] ?>], 15);
        
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const customIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div style="background-color: #D4A017; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(212,175,55,0.5); border: 2px solid white; overflow: hidden;"><img src="/img/hercules.jpeg" style="width:100%;height:100%;object-fit:cover;" /></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        L.marker([<?= $branch['lat'] ?>, <?= $branch['lng'] ?>], {icon: customIcon}).addTo(map)
            .bindPopup('<div class="p-2"><h4 class="font-bold text-slate-900 mb-1"><?= Html::encode($branch['name']) ?></h4><p class="text-xs text-slate-600"><?= Html::encode($branch['address']) ?></p></div>')
            .openPopup();
    }
});

function openGalleryModal() {
    const modal = document.getElementById('gallery-modal');
    modal.classList.remove('opacity-0', 'pointer-events-none');
    document.body.style.overflow = 'hidden';
}
function closeGalleryModal() {
    const modal = document.getElementById('gallery-modal');
    modal.classList.add('opacity-0', 'pointer-events-none');
    document.body.style.overflow = '';
}
// Close modal when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeGalleryModal();
});
</script>
<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
