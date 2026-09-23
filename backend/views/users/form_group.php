<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
$module = $module ?? 'user';

$this->title = Yii::$app->lang->t('extrasidebar', 'usergroup1');

?>

<div class="mb-4">
    <h3 class="fw-bold text-dark">Usergroup Template</h3>
</div>

<div class="card card-flush p-4 shadow-sm mb-5">

    <?php
    $form = ActiveForm::begin([
        'id' => 'group-form',
        // 'action' => ['updateusergroup'],
    ]);
    ?>

    <input type="hidden" id="hidden-position-id" name="id" value="<?= $model->positionid ?? '' ?>">

    <div class="card-body">
        <?= Html::label(Yii::$app->lang->t('contact', 'position'), 'position', ['class' => 'form-label']) ?>
        <?= Html::dropDownList(
            'position',
            $model->positionid ?? null,
            [],
            [
                'class' => 'form-select position',
                'data-control' => 'select2',
                'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
            ]
        ) ?>

    </div>

    <div class="card-body">
        <?php Pjax::begin(['id' => 'grid-tran', 'enablePushState' => false]); ?>
        <?= $this->render('_menucheckboxgroup', [
            'form' => $form,
            'model' => $model
        ]) ?>
        <?php Pjax::end(); ?>
    </div>

    <div class="form-group mt-4 text-end">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-primary', 'id' => 'btnsubmit']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    $(document).ready(function () {

        $("select[name='position']").select2({
            ajax: {
                url: "<?= \yii\helpers\Url::to(['enum/select']) ?>",
                type: "POST",
                dataType: "json",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term || '',
                        page: params.page,
                        module: "user",
                        enumttype: 'position'
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: $.map(data.items, function (item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        }),
                        pagination: {
                            more: (params.page * 5) < data.totalcount
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateSelection: function (param) {
                if (!param.id) {
                    return "Choose Position";
                }
                return param.text;
            },
            templateResult: function (param) {
                if (!param.id) {
                    return "Choose Position";
                }
                if (param.loading) {
                    return param.text;
                }
                var span = document.createElement('span');
                span.textContent = param.text;
                return span;
            },
            placeholder: "Choose Position",
            allowClear: true,
            // dropdownParent: $('#modal_form_user')
        }).on('select2:open', function () {
        }).on('change', function (e) {
            console.log("Selected position:", $(this).val());
        });

        $("select[name='position']").on("change", function () {
            // $("#hidden-position-id").val($(this).val());
            // alert( $("select[name='position']").val());


            $.pjax.reload({
                container: '#grid-tran',
                timeout: 6000,
                push: false,
                data: "position=" + $("select[name='position']").val(),
                type: 'GET'
            });
        });

        var formIsSubmitting = false;

        $('#group-form').on('submit', function (e) {
            e.preventDefault();
            if (formIsSubmitting) return false;
            formIsSubmitting = true;


            var btn = $('#btnsubmit');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function (res) {
                    if (res && res.success) {
                        Swal.fire("Success!", "Data berhasil disimpan.", "success");
                        // setTimeout(() => location.reload(), 1000);
                    } else {
                        Swal.fire("Error!", res.pesan || "Gagal menyimpan", "error");
                    }
                    btn.prop('disabled', false).html('Simpan');
                },
                error: function () {
                    Swal.fire("Error!", "Server error", "error");
                    btn.prop('disabled', false).html('Simpan');
                },
                complete: function () { formIsSubmitting = false; }
            });
        });

    });
</script>