<?php use yii\helpers\Url; ?>
<div class="container">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h3 class="card-title mb-0">
                        <a href="<?= \yii\helpers\Url::to(['/site/trackview', 'id' => $tranid], true) ?>"
                            target="_blank" class="text-gray-900 text-hover-primary text-decoration-none">
                            <?= \yii\helpers\Html::encode($tranno) ?>
                            <i class="fas fa-external-link-alt ms-1 fs-8 opacity-50"></i>
                        </a>
                    </h3>
                    <button class="btn btn-primary btn-add-track" data-id="<?= $tranid ?>">
                        <i class="ki-duotone ki-plus fs-2"></i> <?= Yii::$app->lang->t('cta_add', 'cta_add') ?>
                        Tracking
                    </button>
                </div>

                <div id="content" class="overflow-auto mh-350px p-2">
                    <?php if (!empty($model)) { ?>
                        <div class="timeline timeline-border-dashed">
                            <?php foreach ($model as $tracking) { ?>
                                <div class="timeline-item event" data-id="<?= $tracking->trantrackingid ?>">
                                    <!-- garis vertikal -->
                                    <div class="timeline-line w-40px"></div>

                                    <!-- bullet point -->
                                    <div class="timeline-icon symbol symbol-circle symbol-40px">
                                        <div class="symbol-label bg-light-primary">
                                            <i class="fas fa-map-marker-alt text-primary fs-4"></i>
                                        </div>
                                    </div>

                                    <!-- konten -->
                                    <div class="timeline-content mb-6 mt-n1">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                            <div>
                                                <strong class="text-gray-900 fs-6"><?= \yii\helpers\Html::encode($tracking->location) ?></strong>
                                                <span class="text-muted fs-8 d-block">
                                                    <?= \yii\helpers\Html::encode($tracking->time) ?>
                                                </span>
                                            </div>
                                            <button class="btn btn-sm btn-icon btn-light-danger btn-delete"
                                                data-id="<?= $tracking->trantrackingid ?>" title="Hapus Tracking">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>

                                        <?php if (!empty($tracking->status)): ?>
                                            <div class="fw-bold text-primary fs-6 mb-1">
                                                <?= \yii\helpers\Html::encode($tracking->status) ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($tracking->remarks)): ?>
                                            <p class="text-gray-700 fs-7 mb-2">
                                                <?= \yii\helpers\Html::encode($tracking->remarks) ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if ($tracking->image): ?>
                                            <a href="<?= Yii::getAlias('@web') . '/' . $tracking->image ?>" target="_blank"
                                                data-lightbox="tracking-<?= $tracking->trantrackingid ?>">
                                                <img src="<?= Yii::getAlias('@web') . '/' . $tracking->image ?>"
                                                    alt="Tracking Image"
                                                    class="rounded shadow-sm cursor-pointer mw-100"
                                                    style="max-height:200px;object-fit:cover;">
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-circle me-2"></i>Tidak ada data tracking.
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="text-end pt-10">
    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel"
        data-bs-dismiss="modal"><?= Yii::$app->lang->t('back_home', 'chat34') ?></button>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    $(document).ready(function () {
        $(document).on('click', '.btn-delete', function () {
            var id = $(this).data('id');
            let event = $(this).closest('.timeline-item');

            if (!id) {
                Swal.fire({
                    title: "Error!",
                    text: "ID tidak ditemukan! Periksa tombol yang diklik.",
                    icon: "error",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "OK"
                });
                return;
            }

            Swal.fire({
                title: 'Delete Tracking',
                text: 'Are you sure?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= Url::to(['/tran/trackdelete']) ?>' + '?id=' + id,
                        type: 'post',
                        data: {
                            _csrf: '<?= Yii::$app->request->getCsrfToken() ?>'
                        },
                        headers: {
                            "X-CSRF-Token": "<?= Yii::$app->request->csrfToken ?>"
                        },
                        success: function (response) {
                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('extra', 'extra62') ?>",
                                text: "<?= Yii::$app->lang->t('extra', 'extra59') ?>",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            event.remove();
                            if ($('.timeline-item').length < 1) {
                                $('.timeline').remove();
                                $('#content').html('<div class="alert alert-warning mt-3"><i class="fas fa-exclamation-circle me-2"></i>Tidak ada data tracking.</div>');
                            }
                        },
                        error: function (xhr) {
                            console.error('Error:', xhr.responseText);
                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('extra', 'extra60') ?>",
                                text: "<?= Yii::$app->lang->t('extra', 'extra61') ?>",
                                icon: "error",
                                confirmButtonColor: "#d33",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-add-track', function () {
            var tranid = $(this).data('id');

            Swal.fire({
                title: 'Loading...',
                text: 'Memuat data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: '/tran/trackcreate?id=' + tranid,
                type: 'GET',
                dataType: 'html',
                success: function (html) {
                    Swal.close();
                    try {
                        const jsonResponse = JSON.parse(html);
                        if (jsonResponse.success === false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: jsonResponse.pesan,
                                html: '<pre>' + (jsonResponse.trace || '') + '</pre>'
                            });
                            return;
                        }
                    } catch (e) {
                        // Not JSON, it's HTML - good!
                    }
                    $('#modal-content-track').html(html);
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    let errorMessage = 'Gagal memuat form';
                    let errorDetail = '';
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.pesan || errorMessage;
                        errorDetail = response.trace || '';
                    } catch (e) {
                        errorDetail = xhr.responseText;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMessage,
                        html: '<div style="max-height: 300px; overflow-y: auto;"><pre>' + errorDetail + '</pre></div>',
                        width: '600px'
                    });
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        });
    });
</script>