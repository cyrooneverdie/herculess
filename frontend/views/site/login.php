<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login - Hercules Fitness Centre';
?>

<div class="min-h-screen bg-white font-sans flex flex-col md:flex-row-reverse">

  <!-- Left Side: Branding Banner -->
  <div class="w-full md:w-[50%] lg:w-[75%] relative overflow-hidden hidden md:flex flex-col justify-center">
    <div class="absolute inset-0 bg-black/40 z-10"></div>
    <div class="absolute inset-0">
      <img src="/img/join_banner.png" 
           class="w-full h-full object-cover" alt="Gym Facilities">
    </div>
  </div>

  <!-- Right Side: Form -->
  <div class="w-full md:w-[50%] lg:w-[45%] bg-[#121212] flex items-center justify-center p-6 md:p-12 relative z-20">
    <div class="w-full max-w-xl md:p-8">
      
      <!-- Mobile Back Button -->
      <a href="/" class="md:hidden inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors mb-6">
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
        <h2 class="text-2xl font-extrabold text-white">Masuk ke Akun Anda</h2>
        <p class="text-slate-400 text-sm mt-1">Silakan masuk dengan kredensial akun Anda.</p>
      </div>

      <form id="login-form" method="POST" action="<?= Url::to(['site/login']) ?>" class="space-y-6">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        
        <div class="grid grid-cols-1 gap-6">
          <!-- Email -->
          <div>
            <label for="loginform-email" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Email <span class="text-brand-gold">*</span></label>
            <input type="email" id="loginform-email" name="LoginForm[username]" placeholder="nama@email.com" required
                   class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            <?php if(isset($model) && $model->hasErrors('username')): ?>
                <p class="text-red-500 text-xs mt-1"><?= Html::encode($model->getFirstError('username')) ?></p>
            <?php endif; ?>
          </div>

          <!-- Password -->
          <div>
            <label for="loginform-password" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Password <span class="text-brand-gold">*</span></label>
            <div class="relative flex items-center">
              <input type="password" id="loginform-password" name="LoginForm[password]" placeholder="Masukkan password Anda" required
                     class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 pr-10 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
              <button type="button" onclick="togglePassword()" class="absolute right-4 text-slate-400 hover:text-white transition-colors focus:outline-none">
                <i class="fas fa-eye text-sm" id="togglePasswordIcon"></i>
              </button>
            </div>
            <?php if(isset($model) && $model->hasErrors('password')): ?>
                <p class="text-red-500 text-xs mt-1"><?= Html::encode($model->getFirstError('password')) ?></p>
            <?php endif; ?>
          </div>
        </div>

        <!-- Remember Me -->
        <div class="mt-4 flex items-center justify-between">
          <label class="flex items-center gap-3 text-sm text-slate-400 cursor-pointer">
            <input type="hidden" name="LoginForm[rememberMe]" value="0">
            <input type="checkbox" name="LoginForm[rememberMe]" value="1" checked class="w-4 h-4 rounded border-slate-700 bg-[#1c1c1c] text-brand-gold focus:ring-brand-gold transition-colors accent-brand-gold">
            <span>Ingat Saya</span>
          </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full py-4 mt-8 rounded-xl bg-brand-gold hover:bg-brand-gold-hover text-white font-extrabold text-sm uppercase tracking-widest transition-all shadow-lg shadow-brand-gold/30 hover:-translate-y-0.5">
          Masuk Sekarang
        </button>
        
        <!-- Back Link -->
        <div class="mt-6 text-center hidden md:block">
          <a href="/" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="text-xs font-bold uppercase tracking-wider">Kembali ke Beranda</span>
          </a>
        </div>
        
        <div class="mt-8 text-center pt-6 border-t border-slate-800">
            <p class="text-sm text-slate-400">Belum punya akun? <a href="<?= Url::to(['site/join']) ?>" class="text-brand-gold font-bold hover:underline">Daftar Membership</a></p>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
function togglePassword() {
  const input = document.getElementById('loginform-password');
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
</script>
