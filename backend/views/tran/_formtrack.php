<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php
$form = ActiveForm::begin([
    'id' => 'tracking-form',
    'method' => 'post',
    'action' => Url::to(['tran/trackcreate', 'id' => $model->tranid]),
    'validateOnSubmit' => false,
    'options' => [
        'enctype' => 'multipart/form-data',
        'novalidate' => true,
    ]
]);
?>
<div class="d-flex flex-column scroll-y px-5 px-lg-10">

    <h2><?= Yii::$app->function->findByField("tranno", "trans", " and tranid ='" . $model->tranid . "' ") ?></h2>

    <div class="row mb-4">
        <div class="col-lg-3 col-md-4 col-sm-12 me-0">
            <?= $form->field($model, 'time')->textInput(['class' => 'form-control picktime']) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <?= $form->field($model, 'status')->textInput(['class' => 'form-control', 'placeholder' => 'Status']) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <?= $form->field($model, 'location')->textInput(['class' => 'form-control', 'placeholder' => 'Location']) ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <?= $form->field($model, 'remarks')->textarea([
                'rows' => 3,
                'class' => 'form-control',
                'placeholder' => 'Remarks'
            ]); ?>
        </div>
    </div>

    <!-- Upload Gambar -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <label for="tracking-image">Upload Image</label>
                <div class="mb-3">
                    <label for="tracking-image"
                        class="w-100 d-flex align-items-center justify-content-center cursor-pointer rounded overflow-hidden border border-secondary-subtle"
                        style="height: 200px; background-color: #f8f9fa;">
                        <img id="preview-img" src="<?= !empty($model->image)
                            ? Yii::getAlias('@web') . '/' . $model->image
                            : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>" alt="Preview"
                            class="w-100 h-100 object-fit-contain"
                            style="display: <?= !empty($model->image) ? 'block' : 'none' ?>;">
                        <span id="upload-placeholder" style="display: <?= !empty($model->image) ? 'none' : 'block' ?>;">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                            <p class="text-muted mt-2">Click to upload image</p>
                        </span>
                    </label>
                    <input type="file" class="d-none" id="tracking-image" name="tracking-image" accept="image/*">
                </div>
                <small class="form-text text-muted">Max file size: 2MB. Allowed: JPG, PNG, GIF</small>

                <!-- Remove Button -->
                <div id="image-actions" style="display: <?= !empty($model->image) ? 'block' : 'none' ?>;">
                    <button type="button" class="btn btn-sm btn-danger mt-2" id="remove-image">
                        <i class="fas fa-times"></i> Remove Image
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="text-end pt-10">
        <button type="reset" class="btn btn-light me-3 btn-back">Back</button>
        <?= Html::submitButton($model->isNewRecord ? Yii::$app->lang->t('extra', 'extra16') : Yii::$app->lang->t('extra', 'extra16'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function () {
        setForm();

        // Image Preview
        $('#tracking-image').on('change', function (e) {
            const file = e.target.files[0];

            if (file) {
                // Validasi ukuran file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Maximum file size is 2MB'
                    });
                    $(this).val('');
                    return;
                }

                // Validasi tipe file
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Only JPG, PNG, and GIF files are allowed'
                    });
                    $(this).val('');
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview-img').attr('src', e.target.result).show();
                    $('#upload-placeholder').hide();
                    $('#image-actions').show();
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove image
        $('#remove-image').on('click', function () {
            $('#tracking-image').val('');
            $('#preview-img').attr('src', '<?= Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>').hide();
            $('#upload-placeholder').show();
            $('#image-actions').hide();
        });

        $(document).on('click', '.btn-back', function () {
            Swal.fire({
                title: 'Loading...',
                text: 'Memuat data',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            back();
            Swal.close();
        });
    });

    function back() {
        $.ajax({
            url: '<?= Url::to(['/tran/track', 'id' => $model->tranid]) ?>',
            type: 'GET',
            success: function (data) {
                let parser = new DOMParser();
                let doc = parser.parseFromString(data, 'text/html');
                $('#modal-content-track').html(doc.body.innerHTML);
            },
            error: function () {
                $('#modal-content-track').html('<div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i>Error loading form.</div>');
            }
        });
    }

    function setForm() {
        if (<?= $isajax ?>) {
            var form = $('#tracking-form');
            form.on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            Swal.fire({
                                icon: "success",
                                title: "Successful",
                                html: data['pesan']
                            }).then(() => {
                                back();
                            });
                        } else {
                            Swal.fire({
                                icon: "warning",
                                title: "Warning",
                                html: data.pesan
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Ajax error:', xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            html: "Something went wrong: " + error,
                        });
                    }
                });
            });
        }

        initMasking();
    }
</script>