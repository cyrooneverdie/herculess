<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Pendaftaran Membership - Hercules Fitness Centre';
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
  <div class="w-full md:w-[50%] lg:w-[45%] bg-slate-50 flex items-center justify-center p-6 md:p-12">
    <div class="w-full max-w-xl md:p-8">
      
      <!-- Mobile Back Button -->
      <a href="/" class="md:hidden inline-flex items-center gap-2 text-slate-500 hover:text-black placeholder-slate-400 transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span class="text-xs font-bold uppercase tracking-wider">Kembali</span>
      </a>

      <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-black placeholder-slate-400">Daftar Membership HERCULES FITNESS</h2>
        <p class="text-slate-500 text-sm mt-1">Lengkapi data diri Anda di bawah ini.</p>
      </div>

      <form id="join-form" method="POST" action="<?= Url::to(['site/submit-join']) ?>" onsubmit="handleFormSubmit(event)" novalidate class="space-y-6">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        
        <div class="grid grid-cols-1 gap-6">
          <!-- Full Name -->
          <div>
            <label for="full_name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" id="full_name" name="full_name" placeholder="Sesuai KTP" required
                   class="w-full !bg-none bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-black placeholder-slate-400 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
          </div>

          <!-- WhatsApp -->
          <div>
            <label for="whatsapp_no" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Nomor HP / WA <span class="text-red-500">*</span></label>
            <div class="relative flex">
              <span class="inline-flex items-center px-3 bg-slate-100 border border-r-0 border-slate-300 rounded-l-lg text-black text-sm font-medium">+62</span>
              <input type="tel" id="whatsapp_no" name="whatsapp_no" placeholder="812345678" required
                     oninput="formatWhatsapp(this)"
                     class="flex-1 bg-slate-50 border border-slate-300 rounded-r-lg px-4 py-3 text-black placeholder-slate-400 text-grey focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            </div>
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required
                   class="w-full !bg-none bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-black placeholder-slate-400 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
          </div>

          <!-- Kota -->
          <div class="relative">
            <label for="selected_city" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Kota <span class="text-red-500">*</span></label>
            <div class="relative">
              <select id="selected_city" name="selected_city" required onchange="updateBranches()"
                      class="w-full !bg-none bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-black placeholder-slate-400 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors appearance-none">
                <option value="" disabled selected hidden>Pilih Kota</option>
                <option value="Batam">Batam</option>
                <option value="Bali">Bali</option>
              </select>
              
            </div>
          </div>

          <!-- Cabang -->
          <div class="relative">
            <label for="selected_branch" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Pilih Cabang<span class="text-red-500">*</span></label>
            <div class="relative">
              <select id="selected_branch" name="selected_branch" required disabled
                      class="w-full !bg-none bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-black placeholder-slate-400 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                <option value=""></option>
              </select>
            </div>
            <p class="text-xs text-slate-500 mt-2">Kamu tetap bisa akses semua lokasi klub</p>
          </div>


          <!-- Target Fitness -->
          <div class="relative">
            <label for="fitness_goal" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Target Fitness <span class="text-red-500">*</span></label>
            <div class="relative">
              <select id="fitness_goal" name="fitness_goal" required
                      class="w-full !bg-none bg-slate-50 border border-slate-300 rounded-lg px-4 py-3 text-black placeholder-slate-400 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors appearance-none">
                <option value="">Pilih Target Anda</option>
                <option value="Menurunkan Berat Badan / Fat Loss">Menurunkan Berat Badan / Fat Loss</option>
                <option value="Membentuk Otot & Body Building">Membentuk Otot & Body Building</option>
                <option value="Meningkatkan Stamina & Kebugaran">Meningkatkan Stamina & Kebugaran</option>
                <option value="Gaya Hidup Sehat & Kebugaran Harian">Gaya Hidup Sehat & Kebugaran Harian</option>
                <option value="Masih Pemula / Ingin Konsultasi Dulu">Masih Pemula / Ingin Konsultasi Dulu</option>
              </select>
              
            </div>
          </div>
        </div>

        <hr class="border-slate-100 my-6">

        <!-- Agreement -->
        <div class="mt-6">
          <label class="flex items-start gap-3 text-sm text-slate-500 cursor-pointer">
            <input type="checkbox" required name="agreement" class="mt-1 w-4 h-4 rounded border-slate-300 text-brand-gold focus:ring-brand-gold transition-colors accent-brand-gold">
            <span>Saya menyetujui <a href="#" class="text-brand-gold font-semibold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-brand-gold font-semibold hover:underline">Kebijakan Privasi</a> dari Hercules Fitness.</span>
          </label>
        </div>

        <!-- Submit -->
        <button type="submit" id="submit-btn" class="w-full py-4 mt-4 rounded-xl bg-brand-gold hover:bg-brand-gold-hover text-white font-extrabold text-sm uppercase tracking-widest transition-all shadow-lg shadow-brand-gold/30 hover:-translate-y-0.5">
          Daftar Sekarang
        </button>
        
        <!-- Back Link -->
        <div class="mt-6 text-center hidden md:block">
          <a href="/" class="inline-flex items-center gap-2 text-slate-500 hover:text-black placeholder-slate-400 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="text-xs font-bold uppercase tracking-wider">Kembali ke Beranda</span>
          </a>
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



<script>
// === DYNAMIC BRANCHES ===
const branchesByCity = {
  'Batam': ['Batu Ampar', 'Batu Besar'],
  'Bali': ['Canggu', 'Kuta']
};

function updateBranches() {
  const city = document.getElementById('selected_city').value;
  
  // Clear choices
  window.branchChoice.clearChoices();
  window.branchChoice.clearStore();
  
  if (city && branchesByCity[city]) {
    const options = branchesByCity[city].map(branch => {
      return { value: branch, label: branch };
    });
    
    // Add placeholder
    options.unshift({ value: '', label: '', disabled: true, selected: true });
    
    window.branchChoice.setChoices(options, 'value', 'label', true);
    window.branchChoice.enable();
  } else {
    window.branchChoice.setChoices([{ value: '', label: '', disabled: true, selected: true }], 'value', 'label', true);
    window.branchChoice.disable();
  }
}

// === WHATSAPP FORMAT ===
function formatWhatsapp(input) {
  let val = input.value.replace(/[^0-9]/g, '');
  if (val.startsWith('0')) val = val.substring(1);
  input.value = val;
}

// === FORM SUBMIT ===
function handleFormSubmit(e) {
  e.preventDefault();
  const name = document.getElementById('full_name').value.trim();
  const phone = document.getElementById('whatsapp_no').value.trim();
  const email = document.getElementById('email').value.trim();
  const branch = document.getElementById('selected_branch').value;
  

  if (!name || !phone || !email || !branch) { alert('Mohon lengkapi semua field yang diwajibkan.'); return; }
  if (phone.length < 8 || phone.length > 14) { alert('Nomor WhatsApp tidak valid.'); return; }

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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Choices for all selects except city & branch
    const selects = document.querySelectorAll('select:not(#selected_city):not(#selected_branch)');
    selects.forEach(select => {
        new Choices(select, {
            searchEnabled: false,
            itemSelectText: '',
            shouldSort: false
        });
    });

    // Initialize City
    window.cityChoice = new Choices('#selected_city', {
        searchEnabled: false,
        itemSelectText: '',
        shouldSort: false
    });

    // Initialize Branch (empty at first)
    window.branchChoice = new Choices('#selected_branch', {
        searchEnabled: false,
        itemSelectText: '',
        shouldSort: false,
        placeholder: true,
        placeholderValue: 'Pilih kota terlebih dahulu'
    });
});
</script>
