<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'FormValid',
    'method' => 'post',
    'options' => [
        'enctype' => 'multipart/form-data',
    ],
    'validateOnSubmit' => true,
]);
?>
<!--begin::Scroll-->
<div class="d-flex flex-column scroll-y px-5 px-lg-10" id="modal_form_kasbank_scroll">
    <!--begin::Input group-->
    <div class="row">
        <div class="mb-7 col-lg-2 col-md-12 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">Kode</label>
            <?= $form->field($model, 'coa_no')->textInput([
                'placeholder' => 'Nomor COA',
                'type' => 'number',
                'name' => 'coa_no',
                'required' => true,
            ])->label(false); ?>
        </div>
    </div>
    
    <div class="form-group">
        <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">Nama COA (ID)</label>
            <?= $form->field($model, 'coa_name_id')->textInput([
                'placeholder' => 'Nama COA (ID)',
                'id' => 'coa_name_id',
                'required' => true
            ])->label(false); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="mb-7 col-lg-12 col-md-12 col-sm-12">
            <label class="fw-semibold fs-6 mb-2">Nama COA (EN)</label>
            <?= $form->field($model, 'coa_name_en')->textInput([
                'placeholder' => 'Nama COA (EN)',
                'id' => 'coa_name_en',
                'required' => true
            ])->label(false); ?>
        </div>
    </div>

    <div class="form-group row">
        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <label class="fw-semibold fs-6 mb-2">Level COA</label>
            <?= $form->field($model, 'coa_level')->textInput([
                'placeholder' => 'Level COA',
                'type' => 'number',
                'id' => 'coa_level',
                'required' => true
            ])->label(false); ?>
        </div>

        <div class="mb-7 col-lg-6 col-md-6 col-sm-6">
            <label class="fw-semibold fs-6 mb-2">Tipe COA</label>
            <?= $form->field($model, 'coa_type')->dropDownList([
                'category' => 'Category',
                'account' => 'Account'
            ], [
                'class' => 'form-select',
                'id' => 'coa_type',
                'required' => true
            ])->label(false); ?>
        </div>
    </div>

    <!-- Other sections, such as for Penerimaan, can be kept as it was before -->
    <!-- If needed to add or remove more fields, let me know -->

</div>
<!--end::Scroll-->

<!--begin::Actions-->
<div class="text-end pt-10">
    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel" data-bs-dismiss="modal">Discard</button>
    <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Submit', [
        'id' => 'btnsubmit', 
        'class' => 'btn btn-success'
    ]) ?>
</div>
<!--end::Actions-->

<?php ActiveForm::end(); ?>

<script>
    function setNo() {
        var ob1 = document.querySelector("input[name='no']");
        if (ob1 && ob1.value.trim() === "") {
            fetch("<?= Url::to(['kas/getno'], true); ?>", {
                method: "POST",
                headers: {
                    "X-CSRF-Token": "<?= Yii::$app->request->getCsrfToken() ?>"
                }
            })
            .then(response => response.json())
            .then(data => {
                ob1.value = data.no; // Set nomor yang didapat dari backend
            })
            .catch(error => console.error("Error fetching number:", error));
        }
    }

    function repeater() {
        var repeater = $('#kt_repeater_1').repeater({
            initEmpty: false,
            show: function() {
                $(this).slideDown();
                updateItemNumbers();
                hitungTotalHarga();
            },
            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
                setTimeout(updateItemNumbers, 300);
            }
        });

        function updateItemNumbers() {
            $('#kt_repeater_1 [data-repeater-item]').each(function(index) {
                $(this).find('.item-number').val(index + 1); // Nomor urut mulai dari 1
            });
        }

        function hitungTotalHarga() {
            let total = 0;
            $('.format-harga').each(function() {
                let angka = $(this).val().replace(/\D/g, ''); 
                total += angka ? parseInt(angka) : 0;
            });
            $('#total-penerimaan').val(total.toLocaleString('id-ID'));
        }
    }

    function form() {
        flatpickr("#tgladd", {
            allowClear: true,
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            defaultDate: new Date(),
            time_24hr: true
        });

        flatpickr("#transferdate", {
            allowClear: true,
            dateFormat: "d-m-Y"
        });
    }

    function setupSubmission() {
		$('#btnsubmit').off('click').on('click', function(e) {
			e.preventDefault();

			if (!window.isSubmitting) {
				$('#FormValid').submit();
                console.log("TEST");
			}

			return false;
		});

		$('#FormValid').off('submit').on('submit', function(e) {
			e.preventDefault();

			if (window.isSubmitting) {
				console.log('Form is already being submitted, ignoring additional submit');
				return false;
			}

			// Validate form
			let isValid = true;

			// Check required fields

			if (isValid) {
				window.isSubmitting = true;
				submitProductForm();
			}

			return false;
		});
    }

    function submitProductForm() {
        $('#btnsubmit').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');

        const formData = new FormData($('#FormValid')[0]);
        $.ajax({
            url: $('#FormValid').attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                window.isSubmitting = false;
                $('#btnsubmit').prop('disabled', false).html('Submit');
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil',
                        text: response.pesan,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: true,
                        confirmButtonText: "OK"
                    }).then(() => {
                        if ($('#datatable').length > 0 && $.fn.DataTable) {
                            $('#datatable').DataTable().ajax.reload();
                        } else {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                    });
                } else {
                    Swal.fire('Error', response.pesan, 'error');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                window.isSubmitting = false;
                $('#btnsubmit').prop('disabled', false).html('Submit');
                Swal.fire('Gagal', 'Terjadi kesalahan pada server. Coba lagi!', 'error');
            }
        });
    }
</script>
