<?php
$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar100');
$this->title = $title;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/*----------------------------------------------------
|  Persiapan data & ikon paket
---------------------------------------------------*/
$session = Yii::$app->session;

switch ($modelsubs->package) {
  case 'Basic':
    $paketicon = '<i class="fa-solid fa-certificate fs-2 text-primary"></i>';
    break;
  case 'Advanced':
    $paketicon = '<i class="fa-solid fa-gem fs-2 text-primary"></i>';
    break;
  case 'Enterprise':
    $paketicon = '<i class="fa-solid fa-crown fs-2 text-primary"></i>';
    break;
  default:
    $paketicon = '<span class="text-danger">NULL</span>';
}

/*----------------------------------------------------
|  Form
---------------------------------------------------*/
$form = ActiveForm::begin([
  'id'    => 'FormValid',
  'method'=> 'post',
  'options' => ['enctype' => 'multipart/form-data', 'multiple' => true],
  'validateOnSubmit' => true,
]);
?>

<?php
/*====================================================
|  JIKA SUDAH BERLANGGANAN
===================================================*/
if ($modelsubs && $modelsubs->subs_id && $modelsubs->status == 1 && $modelsubs->subs_status == 1):
  $now = new DateTime();
  $end = new DateTime($modelsubs->tglendsubs);
  if (Yii::$app->session->hasFlash('billing_expired') && $now > $end): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= Yii::$app->session->getFlash('billing_expired') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>
	<div class="card mt-10">
			<div class="card-header">
				<div class="card-title d-flex justify-content-between w-100">
					<?= Yii::$app->lang->t('extra', 'extra73') ?>
					<a href="<?= Url::to(['users/printhistory']) ?>" class="btn btn-primary">
						<i class="fa-solid fa-print"></i> Print
					</a>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table align-middle table-row-dashed fs-6 gy-5">
						<thead>
							<tr>
								<th class="fw-bold"><?= Yii::$app->lang->t('extra', 'extra3') ?></th>
								<th class="fw-bold"><?= Yii::$app->lang->t('front_home', 'price') ?></th>
								<th class="fw-bold"><?= Yii::$app->lang->t('tran', 'tran_date') ?></th>
								<th class="fw-bold">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($histories as $history): ?>
								<tr>
									<td>
										<?php
										if ($history['itemtype'] == null) {
											echo "<span class='badge badge-secondary'> Error</span>";
										} elseif ($history['itemtype'] == 'Basic') {
											echo "<span class='badge badge-primary'>" . $history['itemtype'] . "</span>";
										} elseif ($history['itemtype'] == 'Advanced') {
											echo "<span class='badge badge-info'>" . $history['itemtype'] . "</span>";
										} elseif ($history['itemtype'] == 'Enterprise') {
											echo "<span class='badge badge-warning'>" . $history['itemtype'] . "</span>";
										}
										?>
									</td>

									<td>
										Rp.<?= is_numeric($history['subtotal']) ? number_format($history['subtotal'], 2, ',', '.') : '-' ?>
									</td>

									<td>
										<?= Html::encode($history['createdat'])	?>
									</td>

									<td>
										<?php
										if ($history['statuspaid'] == null || $history['statuspaid'] == 0) {
											echo "<span class='badge badge-danger'>" . Yii::$app->lang->t('cashbackend', 'cashbackend12') . "</span>";
										} else {
											echo "<span class='badge badge-success'>" . Yii::$app->lang->t('dashboard', 'paid') . "</span>";
										}
										?>
									</td>

								</tr>
							<?php endforeach; ?>
						</tbody>
  <!-- Kartu info paket aktif -->
  
   

          <!-- Detail langganan -->
         
         

         
          </div>
        </div>
      </div>
    </div>
  </div>

<?php
/*====================================================
|  JIKA BELUM BERLANGGANAN
===================================================*/
else: ?>

  <!-- Tab paket bulanan / tahunan -->
  <?php /* ..... blok paket (bulanan & tahunan) tetap, sudah dirapikan di atas ..... */ ?>

  <!-- Tabel ringkasan + tombol submit -->
  <div class="card mt-5">
    <div class="card-header">
      <h1 class="card-title mb-0"><?= Yii::$app->lang->t('billing', 'billing4') ?></h1>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5">
          <thead>
            <th class="min-w-100px"><?= Yii::$app->lang->t('produk', 'produk_nama') ?></th>
            <th class="min-w-150px"><?= Yii::$app->lang->t('billing', 'billing2') ?></th>
            <th class="min-w-150px"><?= Yii::$app->lang->t('front_home', 'price') ?></th>
            <th class="min-w-100px">PPN 11%</th>
            <th class="min-w-150px"><?= Yii::$app->lang->t('kasbackend', 'total') ?></th>
          </thead>
          <tbody></tbody>
        </table>
      </div>

      <!-- Total -->
      <div class="d-flex justify-content-between mt-4">
        <h1><?= Yii::$app->lang->t('kasbackend', 'total') ?> :</h1>
        <h1 id="totalpaid"></h1>
      </div>

      <!-- Hidden inputs -->
      <?= Html::hiddenInput('total_price', '', ['id' => 'total_price']) ?>
      <?= Html::hiddenInput('contract',     '', ['id' => 'contract']) ?>
      <?= Html::hiddenInput('ppn',          '', ['id' => 'ppn']) ?>
      <?= Html::hiddenInput('itemdisc',     '', ['id' => 'itemdisc']) ?>
      <?= Html::hiddenInput('persendisc',   '', ['id' => 'persendisc']) ?>
      <?= Html::hiddenInput('harga',        '', ['id' => 'harga']) ?>

      <!-- Tombol submit -->
      <div class="float-end">
        <?= Html::submitButton(
              Yii::$app->lang->t('extra', 'extra15'),
              ['id' => 'btnsubmit', 'class' => 'btn btn-primary mt-5']
            ) ?>
      </div>
    </div>
  </div>

<?php endif; ActiveForm::end(); ?>

<!-- ===========================  CSS rangkuman  =========================== -->
<style>
  .custom{transition:.3s} .custom:hover{box-shadow:0 6px 15px rgba(0,0,0,.25);transform:translateY(-2px);background:rgba(0,0,0,.03);cursor:pointer}
  .package-radio:checked+label{border:3px solid #0b5ed7;background:rgba(11,94,215,.15);box-shadow:0 8px 20px rgba(11,94,215,.4);transform:scale(1.03)}
  .contract-radio+label{transition:.3s;border:2px solid #dee2e6;border-radius:8px;cursor:pointer}
  .contract-radio+label:hover{box-shadow:0 6px 15px rgba(0,0,0,.2);transform:translateY(-3px);background:rgba(0,0,0,.02)}
  .contract-radio:checked+label{border:3px solid #0d6efd;background:rgba(13,110,253,.1);box-shadow:0 8px 20px rgba(13,110,253,.4);transform:scale(1.03)}
  .contract-radio+label .position-absolute{transition:.3s;font-size:14px;border-radius:0 0 5px 5px}
  .contract-radio:checked+label .position-absolute{background:#0d6efd!important;font-size:15px}
  span.select-info{display:none!important}
  .dropdown-item{transition:.2s}.dropdown-item:hover{border-radius:4px;transform:translateX(5px)}
  .dropdown-item.text-hover-success:hover{background:rgba(80,205,137,.1)}
  .dropdown-item.text-hover-danger:hover{background:rgba(241,65,108,.1)}
  .dropdown-item.text-hover-warning:hover{background:rgba(255,172,27,.1)}
  .dropdown-item.text-hover-primary:hover{background:rgba(0,158,247,.1)}
</style>
<?php
/* ---------- map terjemahan ---------- */
$langMap = json_encode([
    'extra5'   => Yii::$app->lang->t('extra', 'extra5'),
    'extra69'  => Yii::$app->lang->t('extra', 'extra69'),
    'billing6' => Yii::$app->lang->t('billing', 'billing6'),
    'mon'      => Yii::$app->lang->t('front_home', 'mon'),
    'year'     => Yii::$app->lang->t('front_home', 'year'),
], JSON_UNESCAPED_UNICODE);

$this->registerJs(<<<JS
$(function () {
  const radioPackage   = $('.package-radio');
  const pricingDisplay = $('#contactPricing');
  const tableBody      = $('.table tbody');
  const totalPaid      = $('#totalpaid');

  /* helpers */
  function rupiah(n){return n.toLocaleString('id-ID');}
  function lang(k){const m = $langMap; return m[k]||k;}

  /* opsi kontrak */
  function getOptions(t){
    if(t==='mon')  return [{m:3,d:0},{m:6,d:5},{m:12,d:20}];
    if(t==='year') return [{m:12,d:10},{m:24,d:25}];
    return [{m:6,d:2},{m:12,d:15}];
  }

  /* gambar kartu kontrak */
  function drawContracts(base,t){
    let html='<div class="row g-4">';
    getOptions(t).forEach(o=>{
      const disc = Math.round(base*(1-o.d/100));
      const save = base-disc;
      html += `
        <div class="col-md-6 col-lg-4">
          <input type="radio" id="c-\\\${o.m}" name="contract" class="contract-radio d-none"
                 value="\\\${o.m}" data-normalprice="\\\${base}" data-price="\\\${disc}" data-discount="\\\${o.d}">
          <label for="c-\\\${o.m}" class="card border shadow-md p-3 position-relative">
            <div class="position-absolute top-0 start-0 w-100 text-center text-white fw-bold py-1
                        \\\${o.d>0?'bg-danger':'bg-success'}">
              \\\${o.d>0?lang('extra5')+' '+o.d+'%':lang('extra69')}
            </div>
            <div class="card-body">
              <h5 class="fw-bold">\\\${o.m} \\\${lang(t)}</h5>
              <small class="text-gray-500"><s>Rp \\\${rupiah(base)} / \\\${lang(t)}</s></small>
              <h4 class="text-primary">Rp \\\${rupiah(disc)} / \\\${lang(t)}</h4>
              \\\${save>0?`<small class="text-success">\\\${lang('billing6')} Rp \\\${rupiah(save)}</small>`:''}
            </div>
          </label>
        </div>`;
    });
    pricingDisplay.html(html+'</div>');
    attachContractEvents();
  }

  /* hitung total */
  function updateTotal(){
    const p=$('.package-radio:checked');
    const c=$('.contract-radio:checked');
    if(!p.length) return;

    const pName = p.closest('label').find('h4').text();
    const len   = c.length ? c.val() : 1;
    const price = c.length ? +c.data('price') : +p.data('price');
    const ppn   = Math.round(price*0.11);
    const total = (price+ppn)*len;
    const tType = lang(p.data('tipe'));

    tableBody.html(`
      <tr>
        <td>\\\${pName}</td>
        <td>\\\${len} \\\${tType}</td>
        <td>Rp \\\${rupiah(price)} / \\\${tType}</td>
        <td>Rp \\\${rupiah(ppn)}</td>
        <td class="fw-bold text-primary">Rp \\\${rupiah(total)}</td>
      </tr>`);
    totalPaid.text('Rp '+rupiah(total));
    $('#total_price').val(total);
    $('#contract').val(len);
    $('#ppn').val(ppn);
  }

  /* event contract */
  function attachContractEvents(){
    $('.contract-radio').off('click').on('click',function(){
      const discP=$(this).data('price'),
            discPct=$(this).data('discount'),
            base=$(this).data('normalprice');
      $('#itemdisc').val(discP);
      $('#persendisc').val(discPct);
      $('#harga').val(base);

      $('.contract-radio').closest('label').removeClass('border-primary');
      if($(this).data('wasChecked')){
        $(this).prop('checked',false).data('wasChecked',false);
        updateTotal(); return;
      }
      $('.contract-radio').data('wasChecked',false);
      $(this).data('wasChecked',true).closest('label').addClass('border-primary');
      updateTotal();
    });
  }

  /* pilih paket */
  radioPackage.on('change',function(){
    $('.checkmark').addClass('d-none');
    $(this).siblings('.checkmark').removeClass('d-none');
    drawContracts(+$(this).data('price'),$(this).data('tipe'));
  });

  /* trigger awal */
  $('.package-radio:checked').trigger('change');
});
JS);
?>
