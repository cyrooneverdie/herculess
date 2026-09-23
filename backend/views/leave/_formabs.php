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
}

$this->title = 'Leave Request Form';
if ($isAbsentForm) {
    $this->title = 'Absent Request Form';
}

$createUrl = ['leave/create'];
$updateUrl = ['leave/updateabs', 'id' => $model->leaveid];

if (!empty($leavetype)) {
    $createUrl['leavetype'] = $leavetype;
    $updateUrl['leavetype'] = $leavetype;
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
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingBasicInfo">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasicInfo"
                    aria-expanded="true" aria-controls="collapseBasicInfo">
                    <i class="fas fa-user-circle me-2"></i>
                    <strong><?= Yii::$app->lang->t('leave', 'leave73') ?></strong>
                </button>
            </h2>
            <div id="collapseBasicInfo" class="accordion-collapse collapse show" aria-labelledby="headingBasicInfo"
                data-bs-parent="#accordionParttimeProfile">

            </div>
        </div>
    </div>

<?php else: ?>
    <div class="row mb-4">
        <div class="col-lg-6 col-md-6 col-sm-12 px-1">
            <label class="fs-6">Jadwal</label>
            <?php
            $refidOptions = [
                'class' => 'form-select refid-select',
                'data-control' => 'select2',
                'data-module' => 'leave',
                'data-type' => 'tranid',
                'disabled' => !$model->isNewRecord,
            ];
            $refidData = [];
            if (!$model->isNewRecord && $model->refid) {
                $selectedRef = Yii::$app->db->createCommand("
                    SELECT CONCAT(tranno, ' - ', eventname) AS text 
                    FROM trans 
                    WHERE tranid = '{$model->refid}'
                ")->queryScalar();
                if ($selectedRef) {
                    $refidData = [$model->refid => $selectedRef];
                }
            }
            ?>
            <?= $form->field($model, 'refid')->dropDownList(
                $refidData,
                $refidOptions
            )->label(false); ?>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <label class="fs-6">
                Tahap
            </label>
            <?php
            $tahapOptions = [
                'class' => 'form-select tahap-select',
                'data-control' => 'select2',
                'data-module' => 'leave',
                'data-type' => 'tahap',
                'disabled' => !$model->isNewRecord,
            ];
            $tahapData = [];
            if (!$model->isNewRecord && $model->traneventid) {
                $selectedTahap = Yii::$app->db->createCommand("
                SELECT CONCAT(
                    CASE
                        WHEN eventtypeid = '0' THEN 'Setup'
                        WHEN eventtypeid = '1' THEN 'Event'
                        WHEN eventtypeid = '2' THEN 'Bongkar'
                        WHEN eventtypeid = '3' THEN 'Antar'
                        WHEN eventtypeid = '4' THEN 'Tarik'
                        ELSE 'Unknown'
                    END,
                    ' | ', startdate, ' - ', enddate
                ) AS text
                FROM tranevents 
                WHERE traneventid = '{$model->traneventid}'
            ")->queryScalar();

                if ($selectedTahap) {
                    $tahapData = [$model->traneventid => $selectedTahap];
                }
            }
            ?>
            <?= $form->field($model, 'traneventid')->dropDownList(
                $tahapData,
                $tahapOptions
            )->label(false); ?>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <label><?= Yii::$app->lang->t('leave', 'leave11') ?><span class="text-danger">*</span></label>
            <select id="contactid" name="Leave[contactid]" class="form-select" required <?= !$model->isNewRecord ? 'disabled' : '' ?>>
                <option value="" readonly><?= Yii::$app->lang->t('leave', 'leave12') ?></option>
            </select>
        </div>
        <div class="col-md-4">
            <label><?= Yii::$app->lang->t('leave', 'leave15') ?></label>
            <input type="text" id="employee_code" class="form-control bg-light" placeholder="Auto-filled" <?= !$model->isNewRecord ? 'disabled' : '' ?>>
        </div>
        <div class="col-md-4">
            <label><?= $labelText ?><span class="text-danger">*</span></label>
            <select id="leavetype" name="Leave[leavetype]" class="form-select" required <?= !$model->isNewRecord ? 'disabled' : '' ?>>
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
                value="<?= !$model->isNewRecord ? ($model->leavedate ?? '') : date('d/m/Y') ?>" required <?= !$model->isNewRecord ? 'disabled' : '' ?>>
        </div>
        <div class="col-md-6">
            <label><?= Yii::$app->lang->t('leave', 'leave5') ?><span class="text-danger">*</span></label>
            <input type="text" id="leaveduedate" name="Leave[leaveduedate]" class="form-control pickdate"
                value="<?= !$model->isNewRecord ? ($model->leaveduedate ?? '') : date('d/m/Y') ?>" required <?= !$model->isNewRecord ? 'disabled' : '' ?>>
        </div>
    </div>

    <?php if ($isAbsentForm): ?>
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
        <div class="col-md-6 text-center border-end border-secondary-subtle">
            <label class="fw-semibold fs-6 mb-2">
                Foto ClockIn <span class="text-muted"></span>
            </label>

            <div class="image-upload-container border border-secondary-subtle rounded overflow-hidden"
                style="min-height: 200px; max-width: 250px; margin: 0 auto; position: relative;">

                <img id="pict_employee_preview" src="<?= $model->pict_employee
                    ? Yii::getAlias('@web') . '/uploads/leave/clockin/' . $model->pict_employee
                    : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>" alt="Preview Foto Profil 1"
                    class="w-100 h-auto max-height-150 object-fit-contain mb-2 rounded" style="display: block;">

                <video id="webcam_stream" autoplay playsinline muted
                    style="display: none; width: 100%; height: 100%; object-fit: cover;">
                </video>

                <canvas id="snapshot_canvas" style="display: none;"></canvas>
            </div>

            <div class="mt-3" style="min-height: 140px;">
                <select id="camera_select" class="form-select form-select-sm w-20 mb-2" style="display: none;"></select>

                <label for="pict_employee_input" class="btn btn-sm btn-light-primary w-50 mb-2">
                    <i class="fas fa-upload me-1"></i> Upload dari File
                </label>

                <button type="button" class="btn btn-sm btn-primary w-50 mb-2" id="btn_open_camera">
                    <i class="fas fa-camera me-1"></i> Buka Kamera
                </button>

                <div id="camera_actions" style="display: none;">
                    <button type="button" class="btn btn-sm btn-success w-20 mb-2" id="btn_take_photo">
                        <i class="fas fa-camera me-1"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-sm btn-warning w-20 mb-2" id="btn_retake_photo"
                        style="display: none;">
                        <i class="fas fa-redo me-1"></i> Ulangi
                    </button>
                    <button type="button" class="btn btn-sm btn-danger w-20 mb-2" id="btn_close_camera">
                        <i class="fas fa-times me-1"></i> Tutup Kamera
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
                        <i class="fas fa-check-circle me-1"></i> File: <?= $model->pict_employee ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6 text-center">
            <label class="fw-semibold fs-6 mb-2">
                Foto ClockOut<span class="text-muted"></span>
            </label>

            <div class="image-upload-container border border-secondary-subtle rounded overflow-hidden"
                style="min-height: 200px; max-width: 250px; margin: 0 auto; position: relative;">

                <img id="pict_employee2_preview" src="<?= $model->pict_employee2
                    ? Yii::getAlias('@web') . '/uploads/leave/clockout/' . $model->pict_employee2
                    : Yii::getAlias('@web') . '/assets/media/logos/empty.png' ?>" alt="Preview Foto Profil 2"
                    class="w-100 h-auto max-height-150 object-fit-contain mb-2 rounded" style="display: block;">

                <video id="webcam_stream2" autoplay playsinline muted
                    style="display: none; width: 100%; height: 100%; object-fit: cover;">
                </video>

                <canvas id="snapshot_canvas2" style="display: none;"></canvas>
            </div>

            <div class="mt-3" style="min-height: 140px;">
                <select id="camera_select2" class="form-select form-select-sm w-20 mb-2" style="display: none;"></select>

                <label for="pict_employee2_input" class="btn btn-sm btn-light-primary w-50 mb-2">
                    <i class="fas fa-upload me-1"></i> Upload dari File
                </label>

                <button type="button" class="btn btn-sm btn-primary w-50 mb-2" id="btn_open_camera2">
                    <i class="fas fa-camera me-1"></i> Buka Kamera
                </button>

                <div id="camera_actions2" style="display: none;">
                    <button type="button" class="btn btn-sm btn-success w-20 mb-2" id="btn_take_photo2">
                        <i class="fas fa-camera me-1"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-sm btn-warning w-20 mb-2" id="btn_retake_photo2"
                        style="display: none;">
                        <i class="fas fa-redo me-1"></i> Ulangi
                    </button>
                    <button type="button" class="btn btn-sm btn-danger w-20 mb-2" id="btn_close_camera2">
                        <i class="fas fa-times me-1"></i> Tutup Kamera
                    </button>
                </div>
            </div>

            <?= $form->field($model, 'pict_employee2')->fileInput([
                'id' => 'pict_employee2_input',
                'accept' => 'image/*',
                'class' => 'd-none',
            ])->label(false) ?>

            <input type="hidden" name="webcam_photo2" id="webcam_photo2">
            <input type="hidden" name="old_pict_employee2" value="<?= $model->pict_employee2 ?>">

            <?php if ($model->pict_employee2): ?>
                <div class="mt-2">
                    <small class="text-success">
                        <i class="fas fa-check-circle me-1"></i> File: <?= $model->pict_employee2 ?>
                    </small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <label><?= Yii::$app->lang->t('leave', 'leave6') ?><span class="text-danger"></span></label>
            <textarea id="note" name="Leave[note]" class="form-control" rows="4"><?= $model->note ?? '' ?></textarea>
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
    </div>

<?php endif; ?>

<!-- Form Buttons -->
<div class="text-end pt-10">
    <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
    <?= Html::submitButton(
        $model->isNewRecord ? 'Submit Request' : 'Update Request',
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'btnsubmit']
    ) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    (function () {
        const cameraState = {
            1: { stream: null, currentFacingMode: 'user' },
            2: { stream: null, currentFacingMode: 'user' }
        };

        const cam1 = {
            video: document.getElementById('webcam_stream'),
            canvas: document.getElementById('snapshot_canvas'),
            preview: document.getElementById('pict_employee_preview'),
            cameraSelect: document.getElementById('camera_select'),
            hiddenInput: document.getElementById('webcam_photo'),
            btnOpen: document.getElementById('btn_open_camera'),
            btnTake: document.getElementById('btn_take_photo'),
            btnRetake: document.getElementById('btn_retake_photo'),
            btnClose: document.getElementById('btn_close_camera'),
            cameraActions: document.getElementById('camera_actions'),
            fileInput: document.getElementById('pict_employee_input')
        };

        const cam2 = {
            video: document.getElementById('webcam_stream2'),
            canvas: document.getElementById('snapshot_canvas2'),
            preview: document.getElementById('pict_employee2_preview'),
            cameraSelect: document.getElementById('camera_select2'),
            hiddenInput: document.getElementById('webcam_photo2'),
            btnOpen: document.getElementById('btn_open_camera2'),
            btnTake: document.getElementById('btn_take_photo2'),
            btnRetake: document.getElementById('btn_retake_photo2'),
            btnClose: document.getElementById('btn_close_camera2'),
            cameraActions: document.getElementById('camera_actions2'),
            fileInput: document.getElementById('pict_employee2_input')
        };

        if (!cam1.video || !cam2.video || !cam1.preview || !cam2.preview) {
            console.error('Beberapa elemen Webcam/Preview tidak ditemukan di DOM');
            return;
        }


        async function getCameras(camObj) {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                camObj.cameraSelect.innerHTML = '';
                videoDevices.forEach((device, index) => {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Kamera ${index + 1}`;
                    camObj.cameraSelect.appendChild(option);
                });

                if (videoDevices.length > 1) {
                    camObj.cameraSelect.style.display = 'block';
                }
                return videoDevices;
            } catch (error) {
                console.error('Gagal memuat daftar kamera:', error);
                return [];
            }
        }

        async function startCamera(id, camObj, deviceId = null) {
            try {
                if (cameraState[id].stream) {
                    cameraState[id].stream.getTracks().forEach(track => track.stop());
                }

                const constraints = {
                    video: deviceId ? { exact: deviceId } : {
                        facingMode: cameraState[id].currentFacingMode,
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    },
                    audio: false
                };

                const activeStream = await navigator.mediaDevices.getUserMedia(constraints);
                cameraState[id].stream = activeStream;
                camObj.video.srcObject = activeStream;

                camObj.video.style.display = 'block';
                camObj.preview.style.display = 'none';
                camObj.cameraActions.style.display = 'block';
                camObj.btnOpen.style.display = 'none';

            } catch (error) {
                console.error('Error saat membuka kamera:', error);
                let errorMsg = 'Tidak dapat mengakses kamera.';
                if (error.name === 'NotAllowedError') {
                    errorMsg = 'Akses kamera ditolak. Silakan izinkan akses kamera di pengaturan browser.';
                }
                Swal.fire({ icon: 'error', title: 'Error Kamera', text: errorMsg });
            }
        }

        function stopCamera(id, camObj) {
            if (cameraState[id].stream) {
                cameraState[id].stream.getTracks().forEach(track => track.stop());
                cameraState[id].stream = null;
            }

            camObj.video.style.display = 'none';
            camObj.preview.style.display = 'block';
            camObj.cameraActions.style.display = 'none';
            camObj.cameraSelect.style.display = 'none';
            camObj.btnOpen.style.display = 'block';
            camObj.btnTake.style.display = 'block';
            camObj.btnRetake.style.display = 'none';
        }

        function takePhoto(camObj) {
            const context = camObj.canvas.getContext('2d');
            camObj.canvas.width = camObj.video.videoWidth;
            camObj.canvas.height = camObj.video.videoHeight;

            context.drawImage(camObj.video, 0, 0, camObj.canvas.width, camObj.canvas.height);
            const imageData = camObj.canvas.toDataURL('image/jpeg', 0.9);

            camObj.preview.src = imageData;
            camObj.preview.style.display = 'block';
            camObj.video.style.display = 'none';
            camObj.hiddenInput.value = imageData;

            camObj.btnTake.style.display = 'none';
            camObj.btnRetake.style.display = 'block';
        }

        function retakePhoto(camObj) {
            camObj.video.style.display = 'block';
            camObj.preview.style.display = 'none';
            camObj.btnTake.style.display = 'block';
            camObj.btnRetake.style.display = 'none';
            camObj.hiddenInput.value = '';
        }

        function setupFilePreview(camObj) {
            if (camObj.fileInput) {
                camObj.fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (event) {
                            camObj.preview.src = event.target.result;
                            camObj.preview.style.display = 'block';
                            camObj.hiddenInput.value = ''; // Hapus base64 dari kamera jika user ganti ke file upload
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        if (cam1.btnOpen) cam1.btnOpen.addEventListener('click', async () => { await getCameras(cam1); await startCamera(1, cam1); });
        if (cam1.btnTake) cam1.btnTake.addEventListener('click', () => takePhoto(cam1));
        if (cam1.btnRetake) cam1.btnRetake.addEventListener('click', () => retakePhoto(cam1));
        if (cam1.btnClose) cam1.btnClose.addEventListener('click', () => stopCamera(1, cam1));
        if (cam1.cameraSelect) cam1.cameraSelect.addEventListener('change', (e) => startCamera(1, cam1, e.target.value));
        setupFilePreview(cam1);

        if (cam2.btnOpen) cam2.btnOpen.addEventListener('click', async () => { await getCameras(cam2); await startCamera(2, cam2); });
        if (cam2.btnTake) cam2.btnTake.addEventListener('click', () => takePhoto(cam2));
        if (cam2.btnRetake) cam2.btnRetake.addEventListener('click', () => retakePhoto(cam2));
        if (cam2.btnClose) cam2.btnClose.addEventListener('click', () => stopCamera(2, cam2));
        if (cam2.cameraSelect) cam2.cameraSelect.addEventListener('change', (e) => startCamera(2, cam2, e.target.value));
        setupFilePreview(cam2);

        window.addEventListener('beforeunload', () => {
            stopCamera(1, cam1);
            stopCamera(2, cam2);
        });

        console.log('Fungsi kembar webcam & file preview berhasil diinisialisasi.');
    })();

    $(document).ready(function () {

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
            console.log(` Restoring ${fieldId} with enumId: ${enumId}, enumType: ${enumType}`);

            $.get('<?= Url::to(["leave/leavetypelist"]) ?>', {
                id: enumId,
                enumtype: enumType
            }, res => {
                if (res.results?.[0]) {
                    $(`#${fieldId}`).append(
                        new Option(res.results[0].text, res.results[0].id, true, true)
                    ).trigger('change');

                    console.log(`Restored ${fieldId}:`, res.results[0].text);

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
            console.log(' Initializing Absent Form with Temperature...');

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
                // console.log(' Attempting to auto-select "Hadir"...');
                setTimeout(function () {
                    $.ajax({
                        url: '<?= Url::to(["leave/leavetypelist"]) ?>',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            enumtype: 'absenttype'
                        },
                        success: function (response) {
                            console.log(' Leavetype list response:', response);
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
                                    console.log('Auto-selected "Hadir"');
                                }
                            }
                        }
                    });
                }, 500);
            }

            console.log('Absent Form initialized with Temperature');
        }

        if (!isParttimeForm) {
            console.log('📝 Initializing Leave/Absent Employee Select...');

            // Employee Select
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
                console.log('Selected employee:', e.params.data.text);
            });

            // Attachment Toggle
            $('#toggle-attachment').on('click', function () {
                $('#attachment-section').slideToggle();
                $(this).html($('#attachment-section').is(':visible') ?
                    '<i class="fas fa-minus"></i> Hide Attachment' :
                    '<i class="fas fa-plus"></i> Show Attachment');
            });

            console.log('Leave/Absent Employee Select initialized');
        }


        <?php if (!$model->isNewRecord): ?>
            console.log(' Edit mode detected, restoring data...');

            <?php if ($isParttimeForm && $tab === 'workattendance'): ?>
                console.log(' Restoring work attendance data...');

                <?php if (!empty($model->leavetype)): ?>
                    $.get('<?= Url::to(["leave/leavetypelist"]) ?>', {
                        id: '<?= $model->leavetype ?>',
                        enumtype: 'parttimetype'
                    }, function (res) {
                        if (res.results && res.results[0]) {
                            const option = new Option(res.results[0].text, res.results[0].id, true, true);
                            $('#leavetype_attendance').append(option).trigger('change');
                            console.log('Restored leavetype:', res.results[0].text);
                        }
                    });
                <?php endif; ?>

                console.log('Work attendance data restored');
            <?php endif; ?>

            <?php if (!$isParttimeForm): ?>
                // RESTORE EMPLOYEE/CONTACT untuk Absent/Leave
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
                                console.log('Restored employee:', emp.text);
                            }
                        },
                        error: function (err) {
                            console.error(' Failed to restore employee:', err);
                        }
                    });
                <?php endif; ?>

                // RESTORE LEAVETYPE untuk Absent/Leave
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
                                console.log('Restored leavetype (Absent):', res.results[0].text);

                                // Trigger time section check
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

            console.log('Data restoration initiated');
        <?php endif; ?>

    }); // Penutup doc.ready


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