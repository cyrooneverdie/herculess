<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;


$title = Yii::$app->lang->t('extrasidebar', 'extrasidebar22');
$this->title = $title;

$form = ActiveForm::begin([
    'id' => 'user-form',
    'enableAjaxValidation' => false,
]);
?>

<div class="mb-5 hover-scroll-x">
    <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap"
        id="customTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link text-active-primary ms-0 me-10 py-5 active" id="tab-user-management" data-bs-toggle="tab"
                href="#kt_tab_pane_1" role="tab">
                <?= Yii::$app->lang->t('back_home', 'notif22') ?>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link text-active-primary me-10 py-5" id="tab-user-menu" data-bs-toggle="tab"
                href="#kt_tab_pane_2" role="tab">
                <?= Yii::$app->lang->t('back_home', 'notif23') ?>
            </a>
        </li>
    </ul>
</div>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
        <div class="row g-5">

            <div class="col-md-6">
                <?= $form->field($model, "name")->textInput([
                    "maxlength" => true,
                    "placeholder" => Yii::$app->lang->t('back_home', 'notif24'),
                    "id" => "user-name",
                    "required" => true,
                    "class" => "form-control form-control-solid"
                ]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, "password", [
                    "template" => "{label}\n<div class='input-group input-group-solid'>{input}<span class='input-group-text toggle-password cursor-pointer'><i class='bi bi-eye fs-4'></i></span></div>\n{error}",
                ])->passwordInput([
                            "maxlength" => true,
                            "placeholder" => $model->isNewRecord
                                ? Yii::$app->lang->t('back_home', 'notif25')
                                : Yii::$app->lang->t('back_home', 'notif26'),
                            "disabled" => !$model->isNewRecord,
                            "id" => "password-input",
                            "required" => true,
                            "class" => "form-control form-control-solid"
                        ]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'contact_id', [
                    'errorOptions' => ['class' => 'text-danger mt-2'],
                ])->dropDownList(
                        $model->contact ? [$model->contact['contact_id'] => $model->contact['contact_name']] : [],
                        [
                            'class' => 'form-select form-select-solid contacts',
                            'data-control' => 'select2',
                            'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
                        ]
                    ); ?>
            </div>

            <div class="col-md-6 d-flex align-items-center mt-8">
                <div class="form-check form-check-custom form-check-solid form-check-sm">
                    <?= $form->field($model, "status", [
                        "template" => "{input}\n{label}\n{error}",
                        "options" => ["class" => false],
                        "labelOptions" => ["class" => "form-check-label fw-semibold text-gray-700 fs-6 ms-2"],
                    ])->checkbox([
                                "class" => "form-check-input",
                                "label" => Yii::$app->lang->t('back_home', 'notif30'),
                            ], false) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
        <div class="col-md-6 mb-5">
            <?= $form->field($model, 'positionid', [
                'errorOptions' => ['class' => 'text-danger mt-2'],
            ])->dropDownList(
                    $model->positionid
                    ? [
                        $model->positionid =>
                            ($model->enum->enumtext_id ?? '') . ' [' . ($model->enum->enum_code_id ?? '') . ']'
                    ]
                    : [],
                    [
                        'class' => 'form-select form-select-solid position',
                        'data-control' => 'select2',
                        'placeholder' => Yii::$app->lang->t('tran', 'tran_no'),
                    ]
                ); ?>
        </div>

        <div class="card-body p-0" id="menu-checkbox-container">
            <?php Pjax::begin(['id' => 'grid-tran', 'enablePushState' => false]); ?>
            <?= $this->render('_menucheckbox', [
                'form' => $form,
                'model' => $model,
                'position' => $position ?? null,
            ]) ?>
            <?php Pjax::end(); ?>
        </div>

    </div>
</div>

<div class="form-group mt-8 text-end">
    <?= Html::button(Yii::$app->lang->t('back_home', 'notif18'), ['class' => 'btn btn-light me-3', 'id' => 'btn-cancel']) ?>
    <?= Html::submitButton(
        $model->isNewRecord ? Yii::$app->lang->t('back_home', 'notif31') : Yii::$app->lang->t('back_home', 'notif32'),
        ['class' => 'btn btn-primary', 'id' => 'btn-submit']
    ) ?>
</div>

<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function () {
        $('input').attr('autocomplete', 'off');

        if (typeof $.fn.select2 === 'function') {
            $('select[data-control="select2"]').select2({
                width: '100%',
                placeholder: function () {
                    return $(this).data('placeholder');
                }
            });

            var moduletype = <?= json_encode($module ?? '') ?>;
            var placeholderContact = (moduletype == 'purchase') ? 'Vendor' : "<?= Yii::$app->lang->t('extradouble', 'double1') ?>";

            $("select[name='User[contact_id]']").select2({
                ajax: {
                    url: "<?= Url::to(['contact/select']) ?>",
                    type: "POST",
                    dataType: "json",
                    data: function (params) {
                        return {
                            contacttype: "employee",
                            positionid: "",
                            search: params.term || '',
                            q: params.term,
                            page: params.page,
                            module: "user"
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 5) < data.totalcount
                            }
                        };
                    },
                    cache: false
                },
                escapeMarkup: function (markup) { return markup; },
                templateSelection: function (param) {
                    return param.id ? param.text : "Choose Employee";
                },
                templateResult: function (param) {
                    if (!param.id || param.loading) return param.text;
                    var span = document.createElement('span');
                    span.innerHTML = param.text;
                    return span;
                },
                placeholder: "Choose Employee",
                allowClear: true,
                dropdownParent: $('#modal_form_user')
            });

            $("select[name='User[positionid]']").select2({
                ajax: {
                    url: "<?= Url::to(['enum/select']) ?>",
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
                                return { id: item.id, text: item.text };
                            }),
                            pagination: {
                                more: (params.page * 5) < data.totalcount
                            }
                        };
                    },
                    cache: true
                },
                placeholder: "Choose Position",
                allowClear: true,
                dropdownParent: $('#modal_form_user')
            }).on('change', function () {
                let pos = $(this).val();
                if (!pos) return;

                $.ajax({
                    url: "<?= Url::to(['users/loadusergroup']) ?>",
                    method: "POST",
                    data: { position: pos },
                    success: function (res) {
                        $("#menu-checkbox-container").html(res);
                    },
                    error: function () {
                        $("#menu-checkbox-container").html("<div class='text-danger text-center p-3'>Gagal memuat menu...</div>");
                    }
                });
            });
        }

        $('#btn-cancel').on('click', function () {
            $.ajax({
                url: "<?= Url::to(['userslist']) ?>",
                type: "GET",
                success: function (response) {
                    $('#userslist-container').html(response);
                },
                error: function () {
                    alert('Gagal memuat daftar user.');
                }
            });
        });

        $('.toggle-password').on('click', function () {
            var input = $('#password-input');
            var icon = $(this).find('i');
            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                input.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });

        $('#user-name').on('input', function () {
            var val = $(this).val();
            val = val.replace(/\s+/g, '').toLowerCase();
            $(this).val(val);
        });

        $('#user-form').on('beforeSubmit', function (e) {
            var $form = $(this);
            var btn = $('#btn-submit');

            if (btn.data('submitted') === true) {
                return false;
            }

            btn.data('submitted', true);
            btn.addClass('disabled').css('pointer-events', 'none');
            btn.append(' <span class="spinner-border spinner-border-sm ms-2" role="status" aria-hidden="true"></span>');

            return true;
        });
    });
</script>