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
            <input type="email" id="loginform-email" name="LoginForm[email]" placeholder="nama@email.com" required
                   class="w-full !bg-none bg-[#1c1c1c] border border-slate-700 rounded-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            <?php if(isset($model) && $model->hasErrors('email')): ?>
                <p class="text-red-500 text-xs mt-1"><?= Html::encode($model->getFirstError('email')) ?></p>
            <?php endif; ?>
          </div>

          <!-- WhatsApp -->
          <div>
            <label for="loginform-whatsapp" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Nomor HP / WA <span class="text-brand-gold">*</span></label>
            <div class="relative flex">
              <span class="inline-flex items-center px-3 bg-[#2a2a2a] border border-r-0 border-slate-700 rounded-l-lg text-white text-sm font-medium">+62</span>
              <input type="tel" id="loginform-whatsapp" name="LoginForm[whatsapp]" placeholder="812345678" required
                     class="flex-1 bg-[#1c1c1c] border border-slate-700 rounded-r-lg px-4 py-3 text-white placeholder-slate-500 text-sm focus:outline-none focus:border-brand-gold focus:ring-1 focus:ring-brand-gold transition-colors">
            </div>
            <?php if(isset($model) && $model->hasErrors('whatsapp')): ?>
                <p class="text-red-500 text-xs mt-1"><?= Html::encode($model->getFirstError('whatsapp')) ?></p>
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
