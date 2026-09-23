  <?php
  use yii\helpers\Html;
  use yii\helpers\Url;

  /** @var $faqList app\models\Faq[] */
  /** @var $terpopuler app\models\Faq[] */
  /** @var $palingMembantu app\models\Faq[] */
  /** @var $search string|null */
  ?>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


  <div class="container my-4">
  <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>

  
  <!-- Form Search dan Tombol Tambah -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <?= Html::beginForm(['faq/index'], 'get', ['class' => 'd-flex w-100 me-3']) ?>
      <?= Html::textInput('search', $search ?? '', [
          'class' => 'form-control form-control-sm me-2',
          'placeholder' => 'Cari FAQ...',
          'id' => 'faq-search-input'
      ]) ?>
    <?= Html::endForm() ?>

    <?= Html::a('<i class="fas fa-plus"></i> Tambah FAQ', ['create'], [
        'class' => 'btn btn-sm btn-primary',
        'id' => 'btn-tambah-faq',
        'onclick' => "$('#faqModal').modal('show').find('#faqModalContent').load($(this).attr('href')); return false;"
    ]) ?>
  </div>

  <!-- Hasil Pencarian -->
  <?php if (!empty($search)): ?>
    <h5 class="text-muted">🔍 Hasil Pencarian untuk: <em><?= Html::encode($search) ?></em></h5>
    <?php foreach ($faqList as $faq): ?>
      <div class="card mb-2">
        <div class="card-header fw-bold"><?= Html::encode($faq->question) ?></div>
        <div class="card-body d-flex justify-content-between align-items-start">
          <div class="me-2"><?= nl2br(Html::encode($faq->answer)) ?></div>
          <div class="d-flex flex-column align-items-end">
            <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $faq->faqid], [
                'title' => 'Edit FAQ',
                'class' => 'btn btn-sm btn-outline-secondary mb-1',
                'onclick' => "$('#faqModal').modal('show').find('#faqModalContent').load($(this).attr('href')); return false;"
            ]) ?>
            <?= Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $faq->faqid], [
                'title' => 'Hapus FAQ',
                'class' => 'btn btn-sm btn-outline-danger',
                'data-confirm' => 'Apakah Anda yakin ingin menghapus FAQ ini?',
                'data-method' => 'post'
            ]) ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <div class="faq-intro mb-4">
    <h2>Frequently Asked Questions</h2>
    <p class="text-muted">
      First, a disclaimer – the entire process of writing a blog post often takes more than a couple of hours, even if you can type eighty words per minute and your writing skills are sharp.
    </p>
  </div>
  <hr>
              
  <!-- Accordion TERPOPULER dan PALING MEMBANTU -->
  <div class="row">
    <!-- Kolom Terpopuler -->
    <div class="col-md-6">
      <h4>🔥 Terpopuler</h4>
      <div class="accordion" id="accordionTerpopuler">
        <?php foreach ($terpopuler as $i => $faq): ?>
          <div class="accordion-item mb-2">
            <h2 class="accordion-header" id="headingTerpopuler<?= $i ?>">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTerpopuler<?= $i ?>">
                <?= \yii\helpers\Html::encode($faq->question) ?>
              </button>
            </h2>
            <div id="collapseTerpopuler<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#accordionTerpopuler">
              <div class="accordion-body">
                <?= \yii\helpers\Html::encode($faq->answer) ?>
                <div class="mt-3">
                  <a href="<?= \yii\helpers\Url::to(['faq/update', 'id' => $faq->faqid]) ?>" class="btn btn-sm btn-outline-warning edit-faq"><i class="fas fa-edit"></i></a>
                  <a href="<?= \yii\helpers\Url::to(['faq/delete', 'id' => $faq->faqid]) ?>" class="btn btn-sm btn-outline-danger" data-method="post" data-confirm="Yakin ingin menghapus?"><i class="fas fa-trash-alt"></i></a>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  <div class="modal fade" id="faq-edit-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" id="faq-edit-modal-content">
        <!-- AJAX content will be loaded here -->
         <script>
          $(document).on('click', '.edit-faq', function (e) {
          e.preventDefault();
          let url = $(this).attr('href');
          $('#faq-edit-modal').modal('show').find('#faq-edit-modal-content').load(url);
          });
         </script>
      </div>
    </div>
  </div>

    <!-- Kolom Paling Membantu -->
    <div class="col-md-6">
      <h4>👍 Paling Membantu</h4>
      <div class="accordion" id="accordionMembantu">
        <?php foreach ($palingMembantu as $i => $faq): ?>
          <div class="accordion-item mb-2">
            <h2 class="accordion-header" id="headingMembantu<?= $i ?>">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMembantu<?= $i ?>">
                <?= \yii\helpers\Html::encode($faq->question) ?>
              </button>
            </h2>
            <div id="collapseMembantu<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#accordionMembantu">
              <div class="accordion-body">
                <?= \yii\helpers\Html::encode($faq->answer) ?>
                <div class="mt-3">
                  <a href="<?= \yii\helpers\Url::to(['faq/update', 'id' => $faq->faqid]) ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                  <a href="<?= \yii\helpers\Url::to(['faq/delete', 'id' => $faq->faqid]) ?>" class="btn btn-sm btn-outline-danger" data-method="post" data-confirm="Yakin ingin menghapus?">Hapus</a>
                </div>
              </div>
            </div>
          </div>
         <?php endforeach; ?>
       </div>
      </div>
    </div>
  </div>

    <!-- Modal FAQ -->
  <?php
    \yii\bootstrap5\Modal::begin([
        'title' => '<h5>Tambah</h5>', 
        'id' => 'faqModal',
        'size' => \yii\bootstrap5\Modal::SIZE_LARGE,
    ]);
    echo '<div id="faqModalContent"></div>';
    \yii\bootstrap5\Modal::end();
    ?>
  </div>

  <?php
  $script = <<<JS
  // Tutup semua accordion saat klik di luar area accordion
  document.addEventListener('click', function(event) {
      const accordions = document.querySelectorAll('.accordion-collapse.show');
      const isInsideAccordion = event.target.closest('.accordion-item');

      if (!isInsideAccordion) {
          accordions.forEach(function(accordion) {
              let collapse = bootstrap.Collapse.getInstance(accordion);
              if (collapse) {
                  collapse.hide();
              } else {
                  new bootstrap.Collapse(accordion, { toggle: false }).hide();
              }
          });
      }
  });
  JS;

  $js = <<<JS
  $(function() {
    let url = new URL(window.location.href);
    if (url.searchParams.get("id")) {
        $('#faqModal').modal('show');
    }
  });
  JS;

  $this->registerJs($js);

  $this->registerJs($script);
  ?>