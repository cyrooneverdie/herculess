<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$typeParam = Yii::$app->request->get('leavetype') ?? $model->leavetype;
$this->title = 'Leave Request Form';

$form = ActiveForm::begin([
    'id' => 'form-leave',
    'method' => 'post',
    'action' => $model->isNewRecord
        ? Url::to(['leave/create'])
        : Url::to(['leave/update', 'id' => $model->leaveid]),
    'options' => [
        'enctype' => 'multipart/form-data',
        'data-pjax' => false,
    ],
    'validateOnSubmit' => false,
    'enableAjaxValidation' => false,
    'enableClientScript' => false,
]);
?>

<!-- Employee -->
<div class="row mb-4">
    <div class="col-md-6">
        <label>Nama Employee <span class="text-danger">*</span></label>
        <select id="contactid" name="Leave[contactid]" class="form-select contactid" required>
            <option value="">-- Pilih Employee --</option>
            <?php 
            if (!$model->isNewRecord && $model->contactid): 
                $employeeName = Yii::$app->function->findByField("contact_name", "contacts", " and contact_id='" . $model->contactid . "' ");
            ?>
                <option value="<?= $model->contactid ?>" selected>
                    <?= $employeeName ?>
                </option>
            <?php endif; ?>
        </select>
    </div>
    
    <div class="col-md-6">
        <label>Jenis Cuti / Leave Type <span class="text-danger">*</span></label>
        <select id="leavetype" name="Leave[leavetype]" class="form-select leavetype" required>
            <option value="">-- Pilih Jenis Cuti --</option>
            <?php if (!$model->isNewRecord && $model->leavetype): ?>
                <option value="<?= $model->leavetype ?>" selected>
                    <?= Yii::$app->function->findByField("enumtext_id", "enum", " and enumid='" . $model->leavetype . "' ") ?>
                </option>
            <?php endif; ?>
        </select>
    </div>
</div>

<!-- Additional Employee Information -->
<div class="row mb-4" id="employee-details" style="<?= (!$model->isNewRecord && $model->contactid) ? '' : 'display: none;' ?>">
    <div class="col-md-6">
        <label>Nomor Telepon</label>
        <input type="text" id="contact_phone" class="form-control" readonly>
    </div>
    <div class="col-md-6">
        <label>ID Number</label>
        <input type="text" id="idnumber" class="form-control" readonly>
    </div>
    <div class="col-md-12 mt-2">
        <label>Alamat</label>
        <textarea id="address" class="form-control" rows="2" readonly></textarea>
    </div>
    <div class="col-md-6 mt-2">
        <label>Posisi</label>
        <input type="text" id="position" class="form-control" readonly>
        <input type="hidden" id="positionid" name="Leave[positionid]">
    </div>
    <div class="col-md-6 mt-2">
        <label>Divisi</label>
        <input type="text" id="division" class="form-control" readonly>
        <input type="hidden" id="divisionid" name="Leave[divisionid]">
    </div>
</div>

<hr>

<!-- Leave Dates -->
<div class="row mb-4">
    <div class="col-md-6">
        <label>Tanggal Mulai / Start Date <span class="text-danger">*</span></label>
        <input type="text" id="leavedate" name="Leave[leavedate]" class="form-control pickdate"
            value="<?= !$model->isNewRecord ? ($model->leavedate ?? '') : '' ?>"
            placeholder="Select Start Date" required>
    </div>
    <div class="col-md-6">
        <label>Tanggal Selesai / End Date <span class="text-danger">*</span></label>
        <input type="text" id="leaveduedate" name="Leave[leaveduedate]" class="form-control pickdate"
            value="<?= !$model->isNewRecord ? ($model->leaveduedate ?? '') : '' ?>"
            placeholder="Select End Date" required>
    </div>
</div>

<!-- Reason -->
<div class="row mb-4">
    <div class="col-md-12">
        <label>Alasan / Reason / Note <span class="text-danger">*</span></label>
        <textarea id="note" name="Leave[note]" class="form-control" rows="4" 
            placeholder="Enter reason for leave..." required><?= !$model->isNewRecord ? ($model->note ?? '') : '' ?></textarea>
    </div>
</div>

<hr>

<!-- Attachment Section -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="mb-0">Lampiran / Attachment</h5>
    <button type="button" id="toggle-attachment" class="btn btn-outline-primary btn-sm">
        <i class="fas fa-plus"></i> Show Attachment
    </button>
</div>

<div id="attachment-section" style="display: none;">
    <div class="row mb-4">
        <div class="col-md-12">
            <label>Upload Supporting Document</label>
            <?= $form->field($model, 'fileUpload')->fileInput([
                'id' => 'fileUpload',
                'accept' => '.pdf,.doc,.docx,.jpg,.jpeg,.png',
                'class' => 'form-control',
            ])->label(false) ?>
            <small class="text-muted">Max file size: 5MB</small>
            
            <?php if (!$model->isNewRecord && $model->attachment): ?>
                <div class="mt-2">
                    <span class="badge bg-success">
                        <i class="fas fa-file me-1"></i> 
                        <?= $model->attachment ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Buttons -->
<div class="text-end pt-10">
    <button type="button" class="btn btn-light me-3 text-dark" data-bs-dismiss="modal">Cancel</button>
    <?= Html::submitButton(
        $model->isNewRecord ? 'Submit Leave Request' : 'Update Leave Request',
        ['id' => 'btnsubmit', 'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
    ) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
console.log('=== LEAVE FORM SCRIPT LOADED ===');
$('#toggle-attachment').on('click', function() {
    const section = $('#attachment-section');
    
    if (section.is(':visible')) {
        section.slideUp();
        $(this).html('<i class="fas fa-plus"></i> Show Attachment');
    } else {
        section.slideDown();
        $(this).html('<i class="fas fa-minus"></i> Hide Attachment');
    }
});
function getEnumText(enumId, callback) {
    $.ajax({
        url: '<?= Url::to(["leave/get-enum-text"]) ?>',
        type: 'POST',
        data: { enumid: enumId, _csrf: '<?= Yii::$app->request->csrfToken ?>' },
        success: res => callback(res.text || '')
    });
}
function showEmployeeDetails() { $('#employee-details').slideDown(); }
function hideEmployeeDetails() { $('#employee-details').slideUp(); }
function clearEmployeeFields() {
    $('#contact_phone, #address, #idnumber, #position, #division').val('');
    $('#positionid, #divisionid').val('');
}
function processEmployeeData(data) {
    $('#contact_phone').val(data.contact_phone1 || '');
    $('#address').val(data.address || '');
    $('#idnumber').val(data.idnumber || '');
    $('#positionid').val(data.positionid || '');
    $('#divisionid').val(data.divisionid || '');

    if (data.position_name) {
        $('#position').val(data.position_name);
    } else if (data.positionid) {
        getEnumText(data.positionid, txt => $('#position').val(txt));
    }

    if (data.division_name) {
        $('#division').val(data.division_name);
    } else if (data.divisionid) {
        getEnumText(data.divisionid, txt => $('#division').val(txt));
    }

    showEmployeeDetails();
}
$(document).ready(function() {
    if (typeof flatpickr !== 'undefined') {
        flatpickr('.pickdate', {
            dateFormat: "d/m/Y",
            allowInput: true,
            altInput: true,
            altFormat: "d/m/Y",
            defaultDate: null,
            onChange: function(selectedDates, dateStr, instance) {
                instance.input.value = dateStr;
            }
        });
        console.log("✓ Flatpickr aktif");
    }
    $('#contactid').select2({
        placeholder: 'Pilih Employee...',
        allowClear: true,
        dropdownParent: $('#modal_form_leave'),
        ajax: {
            url: '<?= Url::to(["leave/select"]) ?>',
            type: 'POST',
            dataType: 'json',
            data: params => ({
                q: params.term || '',
                page: params.page || 1,
                _csrf: '<?= Yii::$app->request->csrfToken ?>'
            }),
            processResults: data => ({
                results: $.map(data.items, item => ({
                    id: item.id || item.contact_id,
                    text: item.contact_name,
                    contact_phone1: item.contact_phone1,
                    address: item.address,
                    idnumber: item.idnumber,
                    position_name: item.position_name,
                    positionid: item.positionid,
                    division_name: item.division_name,
                    divisionid: item.divisionid
                }))
            })
        }
    });

    $('#contactid').on('select2:select', function(e) {
        processEmployeeData(e.params.data);
    });

    $('#contactid').on('select2:clear', function() {
        clearEmployeeFields();
        hideEmployeeDetails();
    });

    // SELECT2 LEAVE TYPE
    $('#leavetype').select2({
        placeholder: 'Pilih Jenis Cuti...',
        allowClear: true,
        dropdownParent: $('#modal_form_leave'),
        ajax: {
            url: '<?= Url::to(["leave/leavetypelist"]) ?>',
            dataType: 'json',
            processResults: data => ({ results: data.results })
        }
    });

    // PRELOAD MODE EDIT
    <?php if(!$model->isNewRecord && $model->contactid): ?>
    $.ajax({
        url: '<?= Url::to(["leave/select"]) ?>',
        type: 'POST',
        dataType: 'json',
        data: { id: '<?= $model->contactid ?>', _csrf: '<?= Yii::$app->request->csrfToken ?>' },
        success: function(res) {
            if (res.items && res.items.length > 0) {
                var emp = res.items[0];
                processEmployeeData(emp);
            }
        }
    });
    <?php endif; ?>
});
</script>