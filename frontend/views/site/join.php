<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Pendaftaran Membership - Hercules Fitness Centre';
$this->registerCssFile('/metronic/assets/plugins/global/plugins.bundle.css');
$this->registerJsFile('/metronic/assets/plugins/global/plugins.bundle.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCss("
/* Metronic Select2 Dark Theme Override */
.select2-container--bootstrap5 .select2-selection {
    background-color: #1c1c1c !important;
    border: 1px solid #334155 !important;
    border-radius: 0.5rem !important;
    color: #ffffff !important;
    height: 46px !important;
    padding: 0 1rem !important;
    box-shadow: none !important;
    display: flex !important;
    align-items: center !important;
}
.select2-container--bootstrap5 .select2-selection--single {
    background-image: url(\"data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e\") !important;
    background-repeat: no-repeat !important;
    background-position: right 1rem center !important;
    background-size: 16px 12px !important;
}
.select2-container--bootstrap5 .select2-selection__rendered {
    color: #ffffff !important;
    width: 100% !important;
    margin: 0 !important;
}
.select2-container--bootstrap5 .select2-selection__placeholder {
    color: #64748b !important;
}
.select2-container--bootstrap5 .select2-dropdown {
    background-color: #1c1c1c !important;
    border: 1px solid #334155 !important;
}
.select2-container--bootstrap5 .select2-results__option {
    color: #cbd5e1 !important;
}
.select2-container--bootstrap5 .select2-results__option--highlighted {
    background-color: #334155 !important;
    color: #ffffff !important;
}
");
?>

<div class="min-h-screen bg-white font-sans flex flex-col md:flex-row-reverse">

  <!-- Left Side: Branding Banner -->
  <div class="w-full md:w-[50%] lg:w-[75%] relative overflow-hidden hidden md:flex flex-col justify-center">
    <div class="absolute inset-0">
      <img src="/img/join_banner.png" 
           class="w-full h-full object-cover" alt="Gym Facilities">
    </div>
  </div>

  <!-- Right Side: Form -->
  <div class="w-full md:w-[50%] lg:w-[45%] bg-[#121212] flex items-center justify-center p-6 md:p-12">
    <div class="w-full max-w-xl md:p-8">
      
      <!-- Mobile Back Button -->
      <a href="/" class="md:hidden inline-flex items-center gap-2 text-slate-400 hover:text-white placeholder-slate-400 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span class="text-xs font-bold uppercase tracking-wider">Kembali</span>
      </a>

      <div class="mb-8">
        <div class="flex items-center gap-3 mb-6">
          <img src="/img/hercules.jpeg" alt="Hercules Logo" class="w-12 h-12 rounded-full object-cover shadow-lg shadow-brand-gold/20">
          <div class="flex flex-col -gap-1 leading-none">
              <span class="text-white text-3xl font-logo-main uppercase tracking-wide">HERCULES</span>
              <span class="text-brand-gold text-xs font-bold tracking-[0.25em] mt-0.5">FITNESS</span>
          </div>
        </div>
        <h2 class="text-2xl font-extrabold text-white">Daftar Membership</h2>
        <p class="text-slate-400 text-sm mt-1">Lengkapi data diri Anda di bawah ini.</p>
      </div>

      <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="mb-6 p-4 rounded-lg bg-red-900/50 border border-red-500/50 text-red-200 text-sm">
          <?= Html::encode(Yii::$app->session->getFlash('error')) ?>
        </div>
      <?php endif; ?>

      <form id="join-form" method="POST" action="<?= Url::to(['site/submit-join']) ?>" onsubmit="handleFormSubmit(event)" novalidate class="space-y-6">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        
        <div class="grid grid-cols-1 gap-6">
          <!-- Username -->
          <div>
            <label for="username" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Username <span class="text-brand-gold">*</span></label>
            <input type="text" id="username" name="username" placeholder="Masukkan Username" required
                   class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
          </div>

          <!-- Name -->
          <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nama Lengkap <span class="text-brand-gold">*</span></label>
            <input type="text" id="name" name="name" placeholder="Sesuai KTP" required
                   class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
          </div>

          <!-- WhatsApp -->
          <div>
            <label for="whatsapp_no" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nomor HP / WA <span class="text-brand-gold">*</span></label>
            <div class="relative flex">
              <span class="inline-flex items-center px-3 bg-[#2a2a2a] border border-r-0 border-slate-700 rounded-l-lg text-white text-sm font-medium">+62</span>
              <input type="tel" id="whatsapp_no" name="whatsapp_no" placeholder="812345678" required
                     oninput="formatWhatsapp(this)"
                     class="flex-1 bg-[#1c1c1c] border border-slate-700 rounded-r-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            </div>
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Email <span class="text-brand-gold">*</span></label>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required
                   class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Password <span class="text-brand-gold">*</span></label>
            <div class="relative flex items-center">
              <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required minlength="6"
                     class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 pr-10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
              <button type="button" onclick="togglePassword()" class="absolute right-4 text-slate-400 hover:text-white transition-colors focus:outline-none">
                <i class="fas fa-eye text-sm" id="togglePasswordIcon"></i>
              </button>
            </div>
          </div>


          <!-- Gender -->
          <div class="relative">
            <label for="gender" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Gender <span class="text-brand-gold">*</span></label>
            <div class="relative">
              <select id="gender" name="gender" required data-control="select2" data-hide-search="true" data-placeholder="Pilih Gender"
                      class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
                <option></option>
                <option value="L">Laki-Laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>
          </div>

          <div class="relative">
            <label for="fitness_goal" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Target Fitness <span class="text-brand-gold">*</span></label>
            <div class="relative">
              <select id="fitness_goal" name="fitness_goal" required data-control="select2" data-hide-search="true" data-placeholder="Pilih Target Anda"
                      class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
                <option></option>
                <option value="Menurunkan Berat Badan / Fat Loss">Menurunkan Berat Badan / Fat Loss</option>
                <option value="Membentuk Otot & Body Building">Membentuk Otot & Body Building</option>
                <option value="Meningkatkan Stamina & Kebugaran">Meningkatkan Stamina & Kebugaran</option>
                <option value="Gaya Hidup Sehat & Kebugaran Harian">Gaya Hidup Sehat & Kebugaran Harian</option>
                <option value="Masih Pemula / Ingin Konsultasi Dulu">Masih Pemula / Ingin Konsultasi Dulu</option>
              </select>
              
            </div>
          </div>
        </div>

        <hr class="border-slate-800 my-6">

        <!-- Agreement -->
        <div class="mt-6">
          <label class="flex items-start gap-3 text-sm text-slate-400 cursor-pointer">
            <input type="checkbox" required name="agreement" class="mt-1 w-4 h-4 rounded border-slate-700 bg-[#1c1c1c] text-brand-gold focus:ring-brand-gold transition-colors accent-brand-gold">
            <span>Saya menyetujui <a href="#" class="text-brand-gold font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-brand-gold font-semibold hover:underline">Kebijakan Privasi</a> dari Hercules Fitness.</span>
          </label>
        </div>

        <!-- Submit -->
        <button type="submit" id="submit-btn" class="w-full py-4 mt-4 rounded-xl bg-brand-gold hover:bg-brand-gold-hover text-white font-extrabold text-sm uppercase tracking-widest transition-all shadow-lg shadow-brand-gold/30 hover:-translate-y-0.5">
          Daftar Sekarang
        </button>
        
        <!-- Back Link -->
        <div class="mt-6 text-center hidden md:block">
          <a href="/" class="inline-flex items-center gap-2 text-slate-400 hover:text-white placeholder-slate-400 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="text-xs font-bold uppercase tracking-wider">Kembali ke Beranda</span>
          </a>
        </div>
        
        <div class="mt-8 text-center pt-6 border-t border-slate-800">
            <p class="text-sm text-slate-400">Sudah punya akun? <a href="<?= Url::to(['site/login']) ?>" class="text-brand-gold font-bold hover:underline">Masuk</a></p>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- ========== SUCCESS MODAL ========== -->
<div id="success-modal" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
  <div id="success-modal-box" class="bg-white rounded-2xl max-w-md w-full p-8 text-center shadow-2xl transform scale-95 transition-transform duration-300">
    <div class="w-16 h-16 rounded-full bg-green-100 text-green-500 flex items-center justify-center mx-auto mb-5">
      <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h3 class="text-2xl font-extrabold text-black placeholder-slate-400">Pendaftaran Berhasil!</h3>
    <p class="text-sm text-slate-600 mt-2 leading-relaxed">
      Terima kasih, <strong id="success-name" class="text-slate-900"></strong>.<br>
      Pendaftaran Anda di <strong id="success-branch" class="text-slate-900"></strong> telah diterima. Tim kami akan segera menghubungi Anda.
    </p>

    <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-100 text-left text-xs space-y-3 text-slate-600">
      <div class="flex justify-between items-center"><span class="uppercase tracking-wider font-semibold text-[10px]">Kode Tiket</span><span class="font-mono font-bold text-brand-gold text-sm" id="success-ticket-code"></span></div>
      <hr class="border-slate-300">
      <div class="flex justify-between items-center"><span class="uppercase tracking-wider font-semibold text-[10px]">WhatsApp</span><span class="font-bold text-black placeholder-slate-400" id="success-phone"></span></div>
    </div>

    <p class="text-xs text-slate-500 mt-5">Tunjukkan layar ini atau sebutkan nama Anda kepada resepsionis.</p>

    <div class="mt-8 flex gap-3">
      <a href="/" class="flex-1 py-3.5 rounded-xl border-2 border-slate-300 text-slate-600 text-xs font-bold uppercase tracking-wider hover:bg-slate-50 transition-colors text-center block">
        Tutup & Kembali
      </a>
    </div>
  </div>
</div>



<?php
$js = <<<JS
// Inisialisasi manual Select2 karena kita tidak meload scripts.bundle.js dari Metronic
$('#fitness_goal, #gender').select2({
    minimumResultsForSearch: Infinity
});
JS;
$this->registerJs($js);
?>

<script>
// === WHATSAPP FORMAT ===
function formatWhatsapp(input) {
  let val = input.value.replace(/[^0-9]/g, '');
  if (val.startsWith('0')) val = val.substring(1);
  input.value = val;
}

// === PASSWORD TOGGLE ===
function togglePassword() {
  const input = document.getElementById('password');
  const icon = document.getElementById('togglePasswordIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

// === FORM SUBMIT ===
function handleFormSubmit(e) {
  e.preventDefault();
  const username = document.getElementById('username').value.trim();
  const name = document.getElementById('name').value.trim();
  const phone = document.getElementById('whatsapp_no').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  const gender = document.getElementById('gender').value;
  

  if (!username || !name || !phone || !email || !password || !gender) { alert('Mohon lengkapi semua field yang diwajibkan.'); return; }
  if (phone.length < 8 || phone.length > 14) { alert('Nomor WhatsApp tidak valid.'); return; }
  if (password.length < 6) { alert('Password minimal 6 karakter.'); return; }

  // Submit the form to backend if validation passes
  e.target.submit();
}

<?php if (Yii::$app->session->hasFlash('success_join')): 
    $flashData = Yii::$app->session->getFlash('success_join');
?>
// Tampilkan modal dari Flash session jika redirect berhasil
document.addEventListener("DOMContentLoaded", function() {
  document.getElementById('success-name').innerText = "<?= Html::encode($flashData['name']) ?>";
  document.getElementById('success-branch').innerText = "<?= Html::encode($flashData['branch']) ?>";
  document.getElementById('success-phone').innerText = "<?= Html::encode($flashData['phone']) ?>";
  document.getElementById('success-ticket-code').innerText = "<?= Html::encode($flashData['ticket_code']) ?>";

  const modal = document.getElementById('success-modal');
  const box = document.getElementById('success-modal-box');
  modal.classList.remove('opacity-0', 'pointer-events-none');
  modal.classList.add('opacity-100');
  box.classList.remove('scale-95');
  box.classList.add('scale-100');
});
<?php endif; ?>


</script>

