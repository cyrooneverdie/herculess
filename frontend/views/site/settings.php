<?php
use yii\helpers\Html;

$this->title = 'Pengaturan Akun - Hercules Fitness';
$this->params['hideFooter'] = true;

$currentUser = Yii::$app->user->identity;
$displayName = $currentUser ? Html::encode($currentUser->name) : 'Guest';
$displayUsername = $currentUser ? Html::encode($currentUser->username) : 'guest';
$displayEmail = $currentUser ? Html::encode($currentUser->email) : 'guest@email.com';
$contactId = $currentUser && $currentUser->contact_id ? Html::encode($currentUser->contact_id) : '';
$isActive = $currentUser && $currentUser->status == \common\models\User::STATUS_ACTIVE;
$avatar = $currentUser && $currentUser->avatar ? $currentUser->avatar : '';

// Mock untuk tipe paket (bisa 'VIP' atau 'STANDARD'), nanti dihubungkan ke field tabel
$membershipType = 'VIP'; 
?>

<div class="min-h-screen bg-[#121212] pt-24 pb-12 relative overflow-hidden">
    <!-- Grid Pattern Background -->
    <div class="absolute inset-0 z-0 opacity-100" 
         style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M0 0h40v40H0z\' fill=\'none\' stroke=\'rgba(255,255,255,0.06)\' stroke-width=\'1\'/%3E%3C/svg%3E');">
    </div>
    
    <div class="max-w-5xl mx-auto px-6 sm:px-10 lg:px-16 relative z-10">
        
        <!-- Back Button -->
        <a href="/" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="text-xs font-bold uppercase tracking-wider">Kembali ke Beranda</span>
        </a>

        <!-- Header Section -->
        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Pengaturan Akun</h1>
            <p class="text-sm text-slate-400 mt-2">Kelola profil, preferensi, dan detail membership Anda.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Navigation -->
            <div class="w-full lg:w-1/4">
                <div class="flex flex-col items-center mb-10">
                    <div class="relative mb-4 group cursor-pointer" onclick="document.getElementById('avatar-upload').click()">
                        <input type="file" id="avatar-upload" accept="image/png, image/jpeg, image/webp" class="hidden">
                        <div class="w-24 h-24 rounded-full bg-[#1c1c1c] border border-slate-800 flex items-center justify-center text-slate-400 text-4xl overflow-hidden relative">
                            <img id="avatar-preview" src="<?= $avatar ?>" alt="Profile" class="w-full h-full object-cover <?= $avatar ? '' : 'hidden' ?>">
                            <i id="avatar-placeholder" class="fa-solid fa-user <?= $avatar ? 'hidden' : '' ?>"></i>
                        </div>
                        <button type="button" class="absolute bottom-0 right-1 w-7 h-7 bg-[#121212] border border-slate-700 rounded-full flex items-center justify-center text-white text-[10px] group-hover:text-brand-gold transition-colors pointer-events-none">
                            <i class="fa-solid fa-camera"></i>
                        </button>
                    </div>
                    <h3 class="text-white font-extrabold text-sm tracking-wide"><?= $displayName ?></h3>
                    <p class="text-slate-400 text-xs"><?= $displayUsername ?></p>
                    <p class="text-brand-gold text-xs mt-1"><?= $isActive ? $membershipType : 'Non-Member' ?></p>
                </div>

                <nav class="flex flex-col gap-1 text-sm font-semibold pl-4">
                    <a href="#" data-tab="tab-profil" class="tab-link px-4 py-3 text-brand-gold bg-transparent transition-colors flex items-center gap-4">
                        <i class="fa-solid fa-user w-5 text-center"></i> Profil
                    </a>
                    <a href="#" data-tab="tab-membership" class="tab-link px-4 py-3 text-slate-500 hover:text-white transition-colors flex items-center gap-4">
                        <i class="fa-solid fa-id-card w-5 text-center"></i> Membership
                    </a>
                    <a href="#" class="px-4 py-3 text-slate-500 hover:text-white transition-colors flex items-center gap-4">
                        <i class="fa-solid fa-file-invoice-dollar w-5 text-center"></i> Tagihan
                    </a>
                    <a href="#" class="px-4 py-3 text-slate-500 hover:text-white transition-colors flex items-center gap-4">
                        <i class="fa-solid fa-bell w-5 text-center"></i> Notifikasi
                    </a>
                    <a href="#" class="px-4 py-3 text-slate-500 hover:text-white transition-colors flex items-center gap-4 mt-6">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Keluar
                    </a>
                </nav>
            </div>

            <!-- Content Area -->
            <div class="w-full lg:w-3/4">
                
                <!-- Profile Card -->
                <div id="tab-profil" class="tab-content bg-[#1c1c1c] border border-slate-800 rounded-3xl p-8 lg:p-10 shadow-sm mb-8">
                    <h2 class="text-xl font-bold text-white mb-10 border-b border-slate-800 pb-6">Edit Information</h2>
                    
                    <form class="space-y-8" method="POST" action="">
                        <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-10">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Username</label>
                                <input type="text" name="username" value="<?= $displayUsername ?>" class="w-full bg-transparent border-0 border-b border-slate-700 px-0 py-2 text-slate-300 placeholder-slate-600 text-sm focus:outline-none focus:ring-0 focus:border-brand-gold transition-colors">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="<?= $displayName ?>" class="w-full bg-transparent border-0 border-b border-slate-700 px-0 py-2 text-slate-300 placeholder-slate-600 text-sm focus:outline-none focus:ring-0 focus:border-brand-gold transition-colors">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email</label>
                                <input type="email" name="email" value="<?= $displayEmail ?>" class="w-full bg-transparent border-0 border-b border-slate-700 px-0 py-2 text-slate-300 placeholder-slate-600 text-sm focus:outline-none focus:ring-0 focus:border-brand-gold transition-colors">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Gender</label>
                                <div class="relative group">
                                    <input type="text" value="Laki-Laki" class="w-full bg-transparent border-0 border-b border-slate-700/50 px-0 py-2 text-slate-500 text-sm focus:outline-none focus:ring-0 cursor-not-allowed" readonly disabled>
                                    <i class="fa-solid fa-lock absolute right-0 top-3 text-slate-600 pointer-events-none text-xs" title="Hubungi admin untuk mengubah gender"></i>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nomor HP / WA</label>
                                <input type="text" name="contact_id" value="<?= $contactId ?>" placeholder="+62 812 3456 7890" class="w-full bg-transparent border-0 border-b border-slate-700 px-0 py-2 text-slate-300 placeholder-slate-600 text-sm focus:outline-none focus:ring-0 focus:border-brand-gold transition-colors">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Target Fitness</label>
                                <input type="text" value="Menurunkan Berat Badan / Fat Loss" disabled class="w-full bg-transparent border-0 border-b border-slate-700 px-0 py-2 text-slate-500 text-sm cursor-not-allowed">
                                <p class="text-[10px] text-slate-500 mt-1">Target fitness tidak dapat diubah melalui pengaturan.</p>
                            </div>
                        </div>
                        
                        <div id="action-buttons" class="pt-8 flex gap-4 hidden transition-all duration-300">
                            <button type="submit" class="px-8 py-3 rounded-lg bg-white text-black font-extrabold text-xs uppercase tracking-widest hover:bg-slate-200 transition-colors">
                                Save
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Membership Status Card -->
                <div id="tab-membership" class="tab-content hidden bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl relative overflow-hidden">
                    <?php if ($isActive): ?>
                        <div class="absolute top-0 right-0 p-8 opacity-10">
                            <i class="fa-solid fa-crown text-8xl text-brand-gold"></i>
                        </div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-2">
                                <h2 class="text-brand-gold font-bold uppercase tracking-widest text-xs">Status Membership</h2>
                                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-green-500/20 text-green-400 text-[10px] font-bold tracking-widest border border-green-500/30">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></div>
                                    AKTIF
                                </span>
                            </div>
                            <h3 class="text-2xl font-extrabold text-white"><?= $membershipType ?> All-Access</h3>
                            <p class="text-slate-400 text-sm mt-1">Berlaku di seluruh cabang Hercules Fitness</p>
                            
                            <div class="mt-8 pt-6 border-t border-white/10 flex flex-wrap gap-x-12 gap-y-4">
                                <div>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Masa Berlaku s/d</p>
                                    <p class="text-white font-semibold">21 Sep 2027</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-1">Cabang Pendaftaran</p>
                                    <p class="text-white font-semibold">Batam (MTC)</p>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex gap-3">
                                <button class="px-5 py-2.5 rounded-xl bg-white text-slate-900 font-extrabold text-xs uppercase tracking-widest hover:bg-slate-100 transition-colors">
                                    Perpanjang
                                </button>
                                <button class="px-5 py-2.5 rounded-xl border border-slate-700 text-white font-extrabold text-xs uppercase tracking-widest hover:bg-slate-800 transition-colors">
                                    Lihat Tagihan
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="absolute top-0 right-0 p-8 opacity-5">
                            <i class="fa-solid fa-ban text-8xl text-slate-500"></i>
                        </div>
                        <div class="relative z-10 flex flex-col items-center justify-center text-center py-10">
                            <div class="w-16 h-16 rounded-full bg-[#1c1c1c] border border-slate-800 flex items-center justify-center mb-5">
                                <i class="fa-solid fa-id-card text-2xl text-slate-500"></i>
                            </div>
                            <h3 class="text-2xl font-extrabold text-white mb-2">Belum Berlangganan</h3>
                            <p class="text-slate-400 text-sm max-w-sm mx-auto mb-8">Anda belum memiliki paket membership aktif. Segera hubungi admin untuk melakukan aktivasi dan nikmati fasilitas lengkap kami.</p>
                            <a href="https://wa.me/6281234567890" target="_blank" class="px-8 py-3.5 rounded-xl bg-brand-gold hover:bg-brand-gold-hover text-white font-bold text-xs uppercase tracking-widest transition-all duration-300 shadow-lg shadow-brand-gold/30 hover:shadow-xl hover:-translate-y-0.5 inline-flex items-center gap-2">
                                <i class="fa-brands fa-whatsapp text-lg"></i> Hubungi Admin
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- Cropper Modal -->
<div id="cropper-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/90 backdrop-blur-sm p-4">
    <div class="bg-[#12141a] border border-white/10 rounded-2xl p-6 w-full max-w-md shadow-2xl flex flex-col">
        <h3 class="text-white font-bold text-lg mb-4 uppercase tracking-wide">Sesuaikan Foto Profil</h3>
        <div class="w-full aspect-square bg-[#0b0c10] rounded-xl overflow-hidden mb-6 relative">
            <img id="cropper-image" src="" alt="Crop preview" class="max-w-full hidden">
        </div>
        <div class="flex items-center justify-end gap-4">
            <button type="button" id="btn-cancel-crop" class="px-5 py-2.5 rounded text-sm font-bold text-slate-400 hover:text-white transition-colors">Batal</button>
            <button type="button" id="btn-save-crop" class="px-5 py-2.5 rounded bg-brand-gold hover:bg-amber-400 text-slate-950 text-sm font-bold uppercase tracking-wide transition-colors">Simpan Profil</button>
        </div>
    </div>
</div>

<?php
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/sweetalert2@11', ['depends' => [\yii\web\JqueryAsset::class]]);

$flashSuccess = Yii::$app->session->getFlash('success');

$css = <<<CSS
/* Select2 Dark Mode - Bottom Border Style */
.select2-container--default .select2-selection--single {
    background-color: transparent !important;
    border: none !important;
    border-bottom: 1px solid #334155 !important;
    border-radius: 0 !important;
    height: auto !important;
    padding: 0.5rem 0 !important;
    outline: none !important;
}
.select2-container--default.select2-container--open .select2-selection--single {
    border-bottom: 1px solid #D4AF37 !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #cbd5e1 !important;
    padding-left: 0 !important;
    line-height: 1.5 !important;
    font-size: 0.875rem !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow {
    display: none !important;
}
/* Dropdown Menu */
.select2-dropdown {
    background-color: #1c1c1c !important;
    border: 1px solid #334155 !important;
    border-radius: 0.5rem !important;
    overflow: hidden;
    z-index: 9999;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
}
.select2-search--dropdown .select2-search__field {
    background-color: #121212 !important;
    border: 1px solid #334155 !important;
    color: white !important;
    border-radius: 0.25rem !important;
}
.select2-results__option {
    color: #94a3b8 !important;
    padding: 0.75rem 1rem !important;
    font-size: 0.875rem !important;
    transition: all 0.2s;
}
.select2-container--default .select2-results__option--selected {
    background-color: #121212 !important;
    color: #D4AF37 !important;
    font-weight: bold;
}
.select2-container--default .select2-results__option--highlighted.select2-results__option--selectable {
    background-color: #D4AF37 !important;
    color: #121212 !important;
}
CSS;
$this->registerCss($css);

$sweetAlertScript = '';
if ($flashSuccess) {
    $sweetAlertScript = "
        Swal.fire({
            title: 'Berhasil!',
            text: '{$flashSuccess}',
            icon: 'success',
            background: '#12141a',
            color: '#fff',
            confirmButtonColor: '#D4AF37',
            iconColor: '#D4AF37'
        });
    ";
}

$js = <<<JS
$(document).ready(function() {
    {$sweetAlertScript}
    $('.select2-gender').select2({
        minimumResultsForSearch: Infinity,
        width: '100%'
    });

    // Tab Switching Logic
    $('.tab-link').on('click', function(e) {
        e.preventDefault();
        var target = $(this).data('tab');
        if(!target) return; // Ignore if no data-tab
        
        // Hide all contents and remove active state from all links
        $('.tab-content').addClass('hidden');
        $('.tab-link').removeClass('text-brand-gold').addClass('text-slate-500 hover:text-white');
        
        // Show target content and add active state to clicked link
        $('#' + target).removeClass('hidden');
        $(this).removeClass('text-slate-500 hover:text-white').addClass('text-brand-gold');
    });

    // Show Save button on input change
    $('#tab-profil form input, #tab-profil form select').on('input change', function() {
        $('#action-buttons').removeClass('hidden');
    });

    // Cropper Logic
    let cropper;
    const image = document.getElementById('cropper-image');
    const input = document.getElementById('avatar-upload');
    const modal = $('#cropper-modal');

    $(input).on('change', function(e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(event) {
                image.src = event.target.result;
                $(image).removeClass('hidden');
                modal.removeClass('hidden').addClass('flex');
                
                if (cropper) cropper.destroy();
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 1,
                    background: false,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $('#btn-cancel-crop').on('click', function() {
        modal.removeClass('flex').addClass('hidden');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        input.value = '';
    });

    $('#btn-save-crop').on('click', function() {
        if (!cropper) return;
        
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
        });

        const base64Avatar = canvas.toDataURL('image/jpeg');
        
        // Update UI
        $('#avatar-preview').attr('src', base64Avatar).removeClass('hidden');
        $('#avatar-placeholder').addClass('hidden');
        
        // Hide Modal
        modal.removeClass('flex').addClass('hidden');
        cropper.destroy();
        cropper = null;
        
        // Show Save Button
        $('#action-buttons').removeClass('hidden');
        
        // Create a hidden input to submit the base64 string
        if ($('#avatar_base64').length === 0) {
            $('#tab-profil form').append('<input type="hidden" name="avatar_base64" id="avatar_base64">');
        }
        $('#avatar_base64').val(base64Avatar);
    });
});
JS;
$this->registerJs($js);
?>
