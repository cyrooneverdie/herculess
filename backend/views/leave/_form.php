<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\Response;
use yii\widgets\ActiveForm;

$leavetype = $leavetype ?? Yii::$app->request->get('leavetype', '');
$tab = $tab ?? Yii::$app->request->get('tab', '');
$currentUrl = Yii::$app->request->url;

$isAbsentForm = ($leavetype === 'absent');
$isParttimeForm = ($leavetype === 'parttime' && !empty($tab));

if ($isParttimeForm && empty($tab)) {
    $tab = 'profiledata';
}

$labelText = 'Jenis Cuti';
if ($isAbsentForm) {
    $labelText = 'Jenis Absensi';
} elseif ($isParttimeForm) {
    $labelText = 'Jenis Parttime';
}

$this->title = 'Leave Request Form';
if ($isAbsentForm) {
    $this->title = 'Absent Request Form';
} elseif ($isParttimeForm) {
    if ($tab === 'profiledata') {
        $this->title = 'Data Diri Parttime';
    } elseif ($tab === 'workattendance') {
        $this->title = 'Absensi Kerja Parttime';
    } elseif ($tab === 'parttimepayment') {
        $this->title = 'Pembayaran Parttime';
    } else {
        $this->title = 'Part Time Request Form';
    }
}

$createUrl = ['leave/create'];
$updateUrl = ['leave/update', 'id' => $model->leaveid];

if (!empty($leavetype)) {
    $createUrl['leavetype'] = $leavetype;
    $updateUrl['leavetype'] = $leavetype;
}

if ($isParttimeForm && !empty($tab)) {
    $createUrl['tab'] = $tab;
    $updateUrl['tab'] = $tab;
}

$form = ActiveForm::begin([
    'id' => 'form-leave',
    'method' => 'post',
    'action' => $model->isNewRecord ? Url::to($createUrl) : Url::to($updateUrl),
    'options' => [
        'enctype' => 'multipart/form-data',
        'data-pjax' => false,
    ],
    'validateOnSubmit' => false,
    'enableAjaxValidation' => false,
    'enableClientScript' => false,
]);
?>

<?php if ($isParttimeForm && $tab === 'profiledata'): ?>

    <div class="accordion" id="accordionParttimeProfile">

    <?php else: ?>
        <div class="row mb-4">
            <div class="col-md-6">
                <label><?= Yii::$app->lang->t('leave', 'leave11') ?><span class="text-danger">*</span></label>
                <select id="contactid" name="Leave[contactid]" class="form-select" required>
                    <option value=""><?= Yii::$app->lang->t('leave', 'leave12') ?></option>
                </select>
            </div>
            <div class="col-md-6">
                <label><?= Yii::$app->lang->t('leave', 'leave15') ?></label>
                <input type="text" id="employee_code" class="form-control bg-light" readonly placeholder="Auto-filled">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <label><?= $labelText ?><span class="text-danger">*</span></label>
                <select id="leavetype" name="Leave[leavetype]" class="form-select" required>
                    <option value="">Pilih <?= $labelText ?>...</option>
                    <?php if (!$model->isNewRecord && $model->leavetype): ?>
                        <option value="<?= $model->leavetype ?>" selected>
                            <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->leavetype . "' ") ?>
                        </option>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label><?= Yii::$app->lang->t('leave', 'leave4') ?><span class="text-danger">*</span></label>
                <input type="text" id="leavedate" name="Leave[leavedate]" class="form-control pickdate"
                    value="<?= !$model->isNewRecord ? ($model->leavedate ?? '') : date('d/m/Y') ?>" required>
            </div>
            <div class="col-md-6">
                <label><?= Yii::$app->lang->t('leave', 'leave5') ?><span class="text-danger">*</span></label>
                <input type="text" id="leaveduedate" name="Leave[leaveduedate]" class="form-control pickdate"
                    value="<?= !$model->isNewRecord ? ($model->leaveduedate ?? '') : date('d/m/Y') ?>" required>
            </div>
        </div>

        <?php if ($isAbsentForm): ?>
            <div class="col-md-6 mt-2">
                <label>Temperature (°C)</label>
                <input type="text" name="Leave[temperature]" class="form-control" value="<?= $model->temperature ?? '0.00' ?>"
                    placeholder="36.5" pattern="[0-9]+(\.[0-9]+)?">
                <small class="text-muted">Format: 36.5 atau 0.00</small>
            </div>

            <div class="col-md-6 mt-2">
                <label>Evaluation Status</label>
                <select name="Leave[evaluation]" class="form-select">
                    <option value="OK" <?= ($model->evaluation ?? 'OK') === 'OK' ? 'selected' : '' ?>>OK</option>
                    <option value="No Jadwal" <?= ($model->evaluation ?? '') === 'No Jadwal' ? 'selected' : '' ?>>No Jadwal
                    </option>
                    <option value="Warning" <?= ($model->evaluation ?? '') === 'Warning' ? 'selected' : '' ?>>Warning</option>
                    <option value="Fever" <?= ($model->evaluation ?? '') === 'Fever' ? 'selected' : '' ?>>Fever</option>
                </select>
            </div>

            <div class="row mb-4" id="time-section" style="display: none;">
                <div class="col-md-6">
                    <label><?= Yii::$app->lang->t('leave', 'leave8') ?> <span class="text-danger time-required"
                            style="display:none;">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        <input type="text" id="check_in" name="Leave[check_in]" class="form-control time-input"
                            value="<?= $model->check_in ? substr($model->check_in, 0, 5) : '' ?>" placeholder="08:00">
                    </div>
                </div>
                <div class="col-md-6">
                    <label><?= Yii::$app->lang->t('leave', 'leave9') ?> <span class="text-danger time-required"
                            style="display:none;"></span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        <input type="text" id="check_out" name="Leave[check_out]" class="form-control time-input"
                            value="<?= $model->check_out ? substr($model->check_out, 0, 5) : '' ?>" placeholder="18:00">
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6 text-center">
                <label class="fw-semibold fs-6 mb-2">
                    Foto Profil <span class="text-muted"></span>
                </label>

                <div class="image-upload-container border border-secondary-subtle rounded overflow-hidden"
                    style="min-height: 200px; max-width: 250px; margin: 0 auto; position: relative;">

                    <img id="pict_employee_preview" src="<?= $model->pict_employee
                        ? Yii::getAlias('@web') . '/uploads/leave/' . $model->pict_employee
                        : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>" alt="Preview Foto Profil"
                        class="w-100 h-auto max-height-150 object-fit-contain mb-2 rounded" style="display: block;">

                    <video id="webcam_stream" autoplay playsinline muted
                        style="display: none; width: 100%; height: 100%; object-fit: cover;">
                    </video>

                    <canvas id="snapshot_canvas" style="display: none;"></canvas>
                </div>

                <div class="mt-3">
                    <select id="camera_select" class="form-select form-select-sm mb-3 w-100"
                        style="display: none;"></select>

                    <div id="default_actions" class="d-flex flex-column flex-sm-row gap-2 mb-2">
                        <label for="pict_employee_input"
                            class="btn btn-sm btn-light-primary w-100 justify-content-center m-0">
                            <i class="fas fa-upload me-1"></i>
                            Upload dari File
                        </label>

                        <button type="button" class="btn btn-sm btn-primary w-100 justify-content-center m-0"
                            id="btn_open_camera">
                            <i class="fas fa-camera me-1"></i>
                            Buka Kamera
                        </button>
                    </div>

                    <div id="camera_actions" class="d-flex flex-column flex-sm-row gap-2 mb-2" style="display: none;">
                        <button type="button" class="btn btn-sm btn-success w-100 justify-content-center m-0"
                            id="btn_take_photo">
                            <i class="fas fa-camera me-1"></i>
                            Ambil Foto
                        </button>

                        <button type="button" class="btn btn-sm btn-warning w-100 justify-content-center m-0"
                            id="btn_retake_photo" style="display: none;">
                            <i class="fas fa-redo me-1"></i>
                            Ulangi
                        </button>

                        <button type="button" class="btn btn-sm btn-danger w-100 justify-content-center m-0"
                            id="btn_close_camera">
                            <i class="fas fa-times me-1"></i>
                            Tutup Kamera
                        </button>
                    </div>
                </div>

                <?= $form->field($model, 'pict_employee')->fileInput([
                    'id' => 'pict_employee_input',
                    'accept' => 'image/*',
                    'class' => 'd-none',
                ])->label(false) ?>

                <input type="hidden" name="webcam_photo" id="webcam_photo">
                <input type="hidden" name="old_pict_employee" value="<?= $model->pict_employee ?>">

                <?php if ($model->pict_employee): ?>
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            File: <?= $model->pict_employee ?>
                        </small>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <label><?= Yii::$app->lang->t('leave', 'leave6') ?><span class="text-danger"></span></label>
                    <textarea id="note" name="Leave[note]" class="form-control"
                        rows="4"><?= $model->note ?? '' ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0"><?= Yii::$app->lang->t('leave', 'leave14') ?></h5>
                <button type="button" id="toggle-attachment" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-plus"></i> <?= Yii::$app->lang->t('leave', 'leave19') ?>
                </button>
            </div>
            <div id="attachment-section" style="display: none;">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <label><?= Yii::$app->lang->t('leave', 'leave20') ?></label>
                        <?= $form->field($model, 'fileUpload')->fileInput([
                            'id' => 'fileUpload',
                            'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png'
                        ])->label(false) ?>
                        <small class="text-muted"><?= Yii::$app->lang->t('leave', 'leave21') ?>: 5MB</small>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <div class="text-end pt-10">
            <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
            <?= Html::submitButton(
                $model->isNewRecord ? 'Submit Request' : 'Update Request',
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'btnsubmit']
            ) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<script>
    (function () {
        let stream = null;
        let currentFacingMode = 'user'; // 'user' = front, 'environment' = back

        const video = document.getElementById('webcam_stream');
        const canvas = document.getElementById('snapshot_canvas');
        const preview = document.getElementById('pict_employee_preview');
        const cameraSelect = document.getElementById('camera_select');
        const hiddenInput = document.getElementById('webcam_photo');

        const btnOpen = document.getElementById('btn_open_camera');
        const btnTake = document.getElementById('btn_take_photo');
        const btnRetake = document.getElementById('btn_retake_photo');
        const btnClose = document.getElementById('btn_close_camera');
        const cameraActions = document.getElementById('camera_actions');

        if (!video || !canvas || !preview || !hiddenInput) {
            console.error(' Webcam elements not found');
            return;
        }

        async function getCameras() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                cameraSelect.innerHTML = '';
                videoDevices.forEach((device, index) => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Camera ${index + 1}`;
                    cameraSelect.appendChild(option);
                });

                if (videoDevices.length > 1) {
                    cameraSelect.style.display = 'block';
                }

                return videoDevices;
            } catch (error) {
                console.error(' Error enumerating devices:', error);
                return [];
            }
        }

        async function startCamera(deviceId = null) {
            try {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                const constraints = {
                    video: deviceId ? {
                        deviceId: { exact: deviceId }
                    } : {
                        facingMode: currentFacingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                };

                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;

                await getCameras();

                video.style.display = 'block';
                preview.style.display = 'none';

                document.getElementById('default_actions').style.display = 'none';

                cameraActions.style.setProperty('display', 'flex', 'important');

                btnTake.style.display = 'block';
                btnRetake.style.display = 'none';

            } catch (error) {
                console.error('Error starting camera:', error);

                let errorMsg = 'Tidak dapat mengakses kamera.';
                if (error.name === 'NotAllowedError') {
                    errorMsg = 'Akses kamera ditolak. Silakan izinkan akses kamera di browser Anda.';
                } else if (error.name === 'NotFoundError') {
                    errorMsg = 'Kamera tidak ditemukan. Pastikan perangkat Anda memiliki kamera.';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error Kamera',
                    text: errorMsg
                });
            }
        }

        function stopCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }

            video.style.display = 'none';
            cameraSelect.style.display = 'none';

            cameraActions.style.setProperty('display', 'none', 'important');

            document.getElementById('default_actions').style.setProperty('display', 'flex', 'important');

            btnTake.style.display = 'block';
            btnRetake.style.display = 'none';

            preview.style.display = 'block';
        }

        function takePhoto() {
            const context = canvas.getContext('2d');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            const imageData = canvas.toDataURL('image/jpeg', 0.9);

            preview.src = imageData;
            preview.style.display = 'block';
            video.style.display = 'none';

            hiddenInput.value = imageData;

            btnTake.style.display = 'none';
            btnRetake.style.display = 'block';

        }

        function retakePhoto() {
            video.style.display = 'block';
            preview.style.display = 'none';
            btnTake.style.display = 'block';
            btnRetake.style.display = 'none';
            hiddenInput.value = '';
        }

        if (btnOpen) {
            btnOpen.addEventListener('click', async () => {
                await getCameras();
                await startCamera();
            });
        }

        if (btnTake) {
            btnTake.addEventListener('click', takePhoto);
        }

        if (btnRetake) {
            btnRetake.addEventListener('click', retakePhoto);
        }

        if (btnClose) {
            btnClose.addEventListener('click', stopCamera);
        }

        if (cameraSelect) {
            cameraSelect.addEventListener('change', (e) => {
                startCamera(e.target.value);
            });
        }

        const fileInput = document.getElementById('pict_employee_input');
        if (fileInput) {
            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        hiddenInput.value = ''; // Clear webcam data
                        console.log(' File preview loaded');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        window.addEventListener('beforeunload', stopCamera);

    })();

    $(document).ready(function () {

        function previewIdentityCardPicture(event) {
            const input = event.target;
            const preview = document.getElementById('identitycardfile_preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
                console.log(' KTP preview loaded');
            }
        }

        function previewStudentCardPicture(event) {
            const input = event.target;
            const preview = document.getElementById('studentcardfile_preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
                console.log(' Student Card preview loaded');
            }
        }

        $('#identitycardfile_input').on('change', previewIdentityCardPicture);
        $('#studentcardfile_input').on('change', previewStudentCardPicture);

        const isParttimeForm = <?= $isParttimeForm ? 'true' : 'false' ?>;
        const isAbsentForm = <?= $isAbsentForm ? 'true' : 'false' ?>;
        const currentTab = '<?= $tab ?>';
        const isNewRecord = <?= $model->isNewRecord ? 'true' : 'false' ?>;

        flatpickr('.pickdate', {
            dateFormat: "d/m/Y",
            allowInput: true,
            // altInput: true,
            clickOpens: true,
            // altFormat: "d/m/Y"
        });

        flatpickr('#check_in', {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            allowInput: true,
            clickOpens: true,
            time_24hr: true,
            defaultHour: 8
        });

        flatpickr('#check_out', {
            enableTime: true,
            noCalendar: true,
            allowInput: true,
            clickOpens: true,
            dateFormat: "H:i",
            time_24hr: true,
            defaultHour: 18
        });

        function updateMemberBadge(text) {
            const badge = $('#member_badge_display');
            const lower = text.toLowerCase();
            let className = 'member-badge ';

            if (lower.includes('training')) className += 'member-training';
            else if (lower.includes('silver')) className += 'member-silver';
            else if (lower.includes('gold')) className += 'member-gold';
            else if (lower.includes('platinum')) className += 'member-platinum';

            badge.html(`<span class="${className}">${text}</span>`).show();
        }

        function convertToTerbilang(angka) {
            const bilangan = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

            if (angka < 12) return bilangan[angka];
            if (angka < 20) return bilangan[angka - 10] + ' Belas';
            if (angka < 100) return bilangan[Math.floor(angka / 10)] + ' Puluh ' + bilangan[angka % 10];
            if (angka < 200) return 'Seratus ' + convertToTerbilang(angka - 100);
            if (angka < 1000) return bilangan[Math.floor(angka / 100)] + ' Ratus ' + convertToTerbilang(angka % 100);
            if (angka < 2000) return 'Seribu ' + convertToTerbilang(angka - 1000);
            if (angka < 1000000) return convertToTerbilang(Math.floor(angka / 1000)) + ' Ribu ' + convertToTerbilang(angka % 1000);
            if (angka < 1000000000) return convertToTerbilang(Math.floor(angka / 1000000)) + ' Juta ' + convertToTerbilang(angka % 1000000);

            return angka.toString();
        }

        function restoreEnum(fieldId, enumId) {
            if (!enumId) {
                console.log(` No enumId for ${fieldId}`);
                return;
            }

            const enumTypeMap = {
                'grade': 'grade',
                'member_level': 'member_level',
                'jenis_parttime': 'jenis_parttime',
                'current_work_status': 'current_work_status',
                'hubungan_rekening': 'hubungan_rekening',
                'payment': 'payment',
                'leavetype': '<?= $isAbsentForm ? "absenttype" : ($isParttimeForm && $tab === "workattendance" ? "absenttype" : "leavetype") ?>'
            };

            const enumType = enumTypeMap[fieldId];

            $.get('<?= Url::to(["leave/leavetypelist"]) ?>', {
                id: enumId,
                enumtype: enumType
            }, res => {
                if (res.results?.[0]) {
                    $(`#${fieldId}`).append(
                        new Option(res.results[0].text, res.results[0].id, true, true)
                    ).trigger('change');


                    if (fieldId === 'member_level') {
                        updateMemberBadge(res.results[0].text);
                    }
                } else {
                    console.warn(` No results for ${fieldId}`);
                }
            }).fail(err => {
                console.error(` Failed to restore ${fieldId}:`, err);
            });
        }

        if (isAbsentForm && !isParttimeForm) {

            $('#leavetype').select2({
                placeholder: 'Pilih <?= $labelText ?>...',
                allowClear: true,
                dropdownParent: $('#modal_form_leave'),
                width: '100%',
                ajax: {
                    url: '<?= Url::to(["leave/leavetypelist"]) ?>',
                    data: params => ({
                        q: params.term,
                        enumtype: 'absenttype'
                    }),
                    processResults: data => ({
                        results: data.results
                    })
                }
            }).on('select2:select', function () {
                const text = $(this).select2('data')[0].text.toLowerCase();
                const isHadir = text.includes('hadir') || text.includes('present');

                if (isHadir) {
                    $('#time-section').slideDown();
                    $('#check_in').prop('required', true);
                    $('.time-required').show();
                } else {
                    $('#time-section').slideUp();
                    $('#check_in').prop('required', false).val('');
                    $('.time-required').hide();
                }
            });

            if (isNewRecord) {
                setTimeout(function () {
                    $.ajax({
                        url: '<?= Url::to(["leave/leavetypelist"]) ?>',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            enumtype: 'absenttype'
                        },
                        success: function (response) {
                            console.log('📥 Leavetype list response:', response);
                            if (response.results && response.results.length > 0) {
                                const hadirOption = response.results.find(item =>
                                    item.text.toLowerCase().includes('hadir') ||
                                    item.text.toLowerCase().includes('present')
                                );
                                if (hadirOption) {
                                    const option = new Option(hadirOption.text, hadirOption.id, true, true);
                                    $('#leavetype').append(option).trigger('change');
                                    $('#leavetype').trigger({
                                        type: 'select2:select',
                                        params: {
                                            data: hadirOption
                                        }
                                    });
                                    console.log(' Auto-selected "Hadir"');
                                }
                            }
                        }
                    });
                }, 500);
            }

        }

        if (!isParttimeForm && !isAbsentForm) {
            $('#leavetype').select2({
                placeholder: 'Pilih Jenis Cuti...',
                allowClear: true,
                dropdownParent: $('#modal_form_leave'),
                width: '100%',
                ajax: {
                    url: '<?= Url::to(["leave/leavetypelist"]) ?>',
                    data: params => ({
                        q: params.term,
                        enumtype: 'leavetype'
                    }),
                    processResults: data => ({
                        results: data.results
                    })
                }
            });

        }

        if (!isParttimeForm) {
            $('#contactid').select2({
                placeholder: 'Pilih Employee...',
                allowClear: true,
                dropdownParent: $('#modal_form_leave'),
                width: '100%',
                ajax: {
                    url: '<?= Url::to(["leave/select"]) ?>',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        q: params.term || '',
                        page: params.page || 1,
                        _csrf: '<?= Yii::$app->request->csrfToken ?>'
                    }),
                    processResults: (data, params) => ({
                        results: data.items.map(i => ({
                            id: i.id,
                            text: i.text,
                            contact_no: i.contact_no
                        })),
                        pagination: {
                            more: data.pagination?.more
                        }
                    }),
                    cache: true
                }
            }).on('select2:select', function (e) {
                $('#employee_code').val(e.params.data.contact_no || 'N/A');
                console.log(' Selected employee:', e.params.data.text);
            });

            $('#toggle-attachment').on('click', function () {
                $('#attachment-section').slideToggle();
                $(this).html($('#attachment-section').is(':visible') ?
                    '<i class="fas fa-minus"></i> Hide Attachment' :
                    '<i class="fas fa-plus"></i> Show Attachment');
            });

        }

        <?php if (!$model->isNewRecord): ?>

            <?php if (!empty($model->contactid)): ?>
                $.ajax({
                    url: '<?= Url::to(["leave/select"]) ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: '<?= $model->contactid ?>',
                        _csrf: '<?= Yii::$app->request->csrfToken ?>'
                    },
                    success: function (res) {
                        if (res.items && res.items.length > 0) {
                            const emp = res.items[0];
                            const option = new Option(emp.text, emp.id, true, true);
                            $('#contactid').append(option).trigger('change');
                            $('#employee_code').val(emp.contact_no || 'N/A');
                        }
                    },
                    error: function (err) {
                        console.error(' Failed to restore employee:', err);
                    }
                });
            <?php endif; ?>

            <?php if ($isAbsentForm): ?>
                <?php if (!empty($model->leavetype)): ?>
                    $.get('<?= Url::to(["leave/leavetypelist"]) ?>', {
                        id: '<?= $model->leavetype ?>',
                        enumtype: 'absenttype'
                    }, function (res) {
                        if (res.results?.[0]) {
                            $('#leavetype').append(
                                new Option(res.results[0].text, res.results[0].id, true, true)
                            ).trigger('change');

                            const text = res.results[0].text.toLowerCase();
                            if (text.includes('hadir') || text.includes('present')) {
                                $('#time-section').slideDown();
                                $('#check_in').prop('required', true);
                                $('.time-required').show();
                            }
                        }
                    });
                <?php endif; ?>
            <?php else: ?>
                <?php if (!empty($model->leavetype)): ?>
                    restoreEnum('leavetype', '<?= $model->leavetype ?>');
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>


    });

    $(document).on('submit', '#form-leave', function (e) {
        if (isParttimeForm && currentTab === 'profiledata') {
            let hasError = false;
            let firstErrorSection = null;

            $('.required-field').each(function () {
                if (!$(this).val() || $(this).val().trim() === '') {
                    hasError = true;

                    const accordionBody = $(this).closest('.accordion-collapse');
                    if (accordionBody.length && !firstErrorSection) {
                        firstErrorSection = accordionBody;
                    }

                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (hasError) {
                e.preventDefault();
                e.stopPropagation();

                if (firstErrorSection) {
                    firstErrorSection.collapse('show');

                    setTimeout(() => {
                        $('.is-invalid').first().focus();
                    }, 400);
                }

                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Form',
                    text: 'Mohon lengkapi semua field yang wajib diisi (bertanda *)',
                    confirmButtonText: 'OK'
                });

                return false;
            }
        }
    });

    $(document).on('input change', '.required-field', function () {
        if ($(this).val() && $(this).val().trim() !== '') {
            $(this).removeClass('is-invalid');
        }
    });

</script>