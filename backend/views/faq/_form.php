<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\YiiAsset;

YiiAsset::register($this);

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="faq-form">
    <div id="form-alert" class="alert alert-danger d-none" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <span id="form-alert-message"></span>
    </div>

    <?php $form = ActiveForm::begin([
        'id' => 'faq-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
        'options' => ['class' => 'needs-validation', 'novalidate' => true],
        'validationUrl' => $model->isNewRecord ? 
            Url::to(['faq/validate']) : 
            Url::to(['faq/validate', 'id' => $model->faqid])
    ]); ?>

    <div class="row g-3">
        <div class="col-md-12">
            <?= $form->field($model, 'question')->textInput([
                'maxlength' => true,
                'placeholder' => 'Enter your question',
                'class' => 'form-control',
                'required' => true,
                'id' => 'faq-question'
            ])->label(Yii::$app->lang->t('faq', 'question1')) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'answer')->textarea([
                'rows' => 4,
                'placeholder' => 'Enter the answer...',
                'class' => 'form-control',
                'required' => true,
                'id' => 'faq-answer'
            ])->label(Yii::$app->lang->t('faq', 'answer1')) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'faqtype')->dropDownList([
                // 'user' => 'User',
                // 'account' => Yii::$app->lang->t('faq', 'Account'), 
                'vendor' => 'Vendor',
                // 'partner' => Yii::$app->lang->t('faq', 'Partner'),
                // 'payment' => Yii::$app->lang->t('faq', 'Payment'),
            ], [
                'prompt' => Yii::$app->lang->t('extra', 'extra109'),
                'class' => 'form-select',
                'required' => true,
                'id' => 'faq-type'
            ])->label(Yii::$app->lang->t('faq', 'category1')) ?>
        </div>

        <?php if (!$model->isNewRecord): ?>
        <div class="col-md-3">
            <?= $form->field($model, 'view_count')->input('number', [
                'placeholder' => Yii::$app->lang->t('faq', 'viewcount1'),
                'class' => 'form-control',
                'min' => 0,
                'readonly' => true,
                'id' => 'faq-view-count'
            ])->label(Yii::$app->lang->t('faq', 'viewcount1')) ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'helpful')->input('number', [
                'placeholder' => Yii::$app->lang->t('faq', 'Helpful count'),
                'class' => 'form-control',
                'min' => 0,
                'id' => 'faq-helpful'
            ])->label(Yii::$app->lang->t('faq', 'Helpful Count')) ?>
        </div>
        <?php endif; ?>

        <div class="col-md-12 text-end mt-4">
            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal" id="btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </button>
            <?= Html::submitButton(
                $model->isNewRecord ? 
                    '<i class="fas fa-save"></i> ' . Yii::$app->lang->t('faq', 'save1') : 
                    '<i class="fas fa-edit"></i> ' . Yii::$app->lang->t('faq', 'update1'),
                [
                    'class' => 'btn btn-primary', 
                    'id' => 'btn-submit-faq',
                    'data-loading-text' => '<i class="fas fa-spinner fa-spin"></i> ' . 'Saving'
                ]
            ) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJsFile('https://cdn.tinymce.com/4/tinymce.min.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<script>
$(document).ready(function() {
    console.log('FAQ Form initialized');
    initializeFormValidation();
    
    // Handle form submission - this will be overridden by parent page
    $('#faq-form').on('submit', function(e) {
        console.log('Form submitted via form handler');
        // This will be handled by initializeFormHandler() in the parent page
    });
    
    // Handle cancel button with Bootstrap 5
    $('#btn-cancel').on('click', function() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('faqModal'));
        if (modal) {
            modal.hide();
        }
    });
});

function initializeFormValidation() {
    // Real-time validation
    $('#faq-form input, #faq-form textarea, #faq-form select').on('blur change', function() {
        validateField($(this));
    });

    // Clear validation on input
    $('#faq-form input, #faq-form textarea, #faq-form select').on('input', function() {
        $(this).removeClass('is-invalid is-valid');
        $(this).siblings('.invalid-feedback').remove();
        $('#form-alert').addClass('d-none');
    });
    
    // Form validation before submit
    $('#faq-form').on('submit', function(e) {
        let isFormValid = true;
        
        // Validate all required fields
        $('#faq-form [required]').each(function() {
            if (!validateField($(this))) {
                isFormValid = false;
            }
        });
        
        if (!isFormValid) {
            e.preventDefault();
            $('#form-alert-message').text('<?= Yii::$app->lang->t('faq', 'Please check your inputs.') ?>');
            $('#form-alert').removeClass('d-none');
            return false;
        }
        
        $('#form-alert').addClass('d-none');
        return true;
    });
}

function validateField($field) {
    let isValid = true;
    const value = $field.val() ? $field.val().trim() : '';
    const fieldName = $field.attr('name');
    
    // Remove existing feedback
    $field.removeClass('is-invalid is-valid');
    $field.siblings('.invalid-feedback').remove();
    
    // Check if required field is empty
    if ($field.prop('required') && !value) {
        isValid = false;
        $field.addClass('is-invalid');
        $field.after('<div class="invalid-feedback">' + 
            <?= json_encode(Yii::$app->lang->t('faq', 'This field is required.')) ?> + 
            '</div>');
    }
    
    // Specific validations
    if (value && isValid) {
        switch(fieldName) {
            case 'Faq[question]':
                if (value.length < 10) {
                    isValid = false;
                    $field.addClass('is-invalid');
                    $field.after('<div class="invalid-feedback">' + 
                        <?= json_encode(Yii::$app->lang->t('faq', 'Question must be at least 10 characters.')) ?> + 
                        '</div>');
                } else if (value.length > 255) {
                    isValid = false;
                    $field.addClass('is-invalid');
                    $field.after('<div class="invalid-feedback">' + 
                        <?= json_encode(Yii::$app->lang->t('faq', 'Question is too long.')) ?> + 
                        '</div>');
                }
                break;
                
            case 'Faq[answer]':
                if (value.length < 20) {
                    isValid = false;
                    $field.addClass('is-invalid');
                    $field.after('<div class="invalid-feedback">' + 
                        <?= json_encode(Yii::$app->lang->t('faq', 'Answer must be at least 20 characters.')) ?> + 
                        '</div>');
                } else if (value.length > 5000) {
                    isValid = false;
                    $field.addClass('is-invalid');
                    $field.after('<div class="invalid-feedback">' + 
                        <?= json_encode(Yii::$app->lang->t('faq', 'Answer is too long.')) ?> + 
                        '</div>');
                }
                break;
                
            case 'Faq[view_count]':
            case 'Faq[helpful]':
                const numValue = parseInt(value);
                if (isNaN(numValue) || numValue < 0) {
                    isValid = false;
                    $field.addClass('is-invalid');
                    $field.after('<div class="invalid-feedback">' + 
                        <?= json_encode(Yii::$app->lang->t('faq', 'Please enter a valid number.')) ?> + 
                        '</div>');
                }
                break;
        }
        
        if (isValid) {
            $field.addClass('is-valid');
        }
    }
    
    return isValid;
}

// Function to show server-side validation errors
function showValidationErrors(errors) {
    for (const field in errors) {
        const fieldElement = $('[name="' + field + '"]');
        if (fieldElement.length) {
            fieldElement.addClass('is-invalid');
            fieldElement.after('<div class="invalid-feedback">' + errors[field].join('<br>') + '</div>');
        }
    }
}

// Function to clear all validation states
function clearValidationStates() {
    $('#faq-form .is-invalid, #faq-form .is-valid').removeClass('is-invalid is-valid');
    $('#faq-form .invalid-feedback').remove();
    $('#form-alert').addClass('d-none');
}
</script>