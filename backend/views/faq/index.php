<?php
$title = Yii::$app->lang->t('faq', 'faq');
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\helpers\Json;
use yii\web\YiiAsset;
use backend\assets\AppAsset;

/** @var $faqList common\models\Faq[] */
/** @var $terpopuler common\models\Faq[] */
/** @var $palingMembantu common\models\Faq[] */
/** @var $search string|null */

AppAsset::register($this);
YiiAsset::register($this);
?>

<!-- Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<meta name="csrf-token" content="<?= Yii::$app->request->csrfToken ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .faq-container [data-bs-theme="dark"] .card {
        background-color: #2d2d2d;
        border-color: #404040;
        color: #ffffff;
    }

    .faq-container [data-bs-theme="dark"] .card-header {
        background-color: #404040;
        border-color: #404040;
    }

    .faq-container [data-bs-theme="dark"] .btn-link {
        color: #ffffff;
    }

    .faq-container [data-bs-theme="dark"] .btn-link:hover {
        color: #4dabf7;
    }

    .faq-container [data-bs-theme="dark"] .text-muted {
        color: #868e96 !important;
    }

    .faq-container [data-bs-theme="dark"] .form-control {
        background-color: #2d2d2d;
        border-color: #404040;
        color: #ffffff;
    }

    .faq-container [data-bs-theme="dark"] .form-control:focus {
        background-color: #2d2d2d;
        border-color: #4dabf7;
        color: #ffffff;
        box-shadow: 0 0 0 0.25rem rgba(77, 171, 247, 0.25);
    }

    .faq-container [data-bs-theme="dark"] .btn-outline-secondary {
        color: #ffffff;
        border-color: #404040;
    }

    .faq-container [data-bs-theme="dark"] .btn-outline-secondary:hover {
        background-color: #404040;
        border-color: #404040;
        color: #ffffff;
    }

    .faq-container [data-bs-theme="dark"] .btn-outline-warning {
        color: #ffd43b;
        border-color: #ffd43b;
    }

    .faq-container [data-bs-theme="dark"] .btn-outline-warning:hover {
        background-color: #ffd43b;
        border-color: #ffd43b;
        color: #1a1a1a;
    }

    .faq-container [data-bs-theme="dark"] .btn-outline-danger {
        color: #ff6b6b;
        border-color: #ff6b6b;
    }

    .faq-container [data-bs-theme="dark"] .btn-outline-danger:hover {
        background-color: #ff6b6b;
        border-color: #ff6b6b;
        color: #ffffff;
    }

    .faq-container [data-bs-theme="dark"] .modal-content {
        background-color: #2d2d2d;
        color: #ffffff;
        border: 1px solid #404040;
    }

    .faq-container [data-bs-theme="dark"] .modal-header {
        border-bottom: 1px solid #404040;
    }

    .faq-container [data-bs-theme="dark"] .modal-footer {
        border-top: 1px solid #404040;
    }

    .faq-container [data-bs-theme="dark"] .btn-close {
        filter: invert(1);
    }

    .faq-container [data-bs-theme="dark"] .collapse {
        color: #ffffff !important;
        background-color: #2d2d2d !important;
    }

    .faq-container [data-bs-theme="light"] .collapse {
        color: #808080ff;
        background-color: #ffffff;
    }

    .faq-container .toggle-icon {
        transition: transform 0.3s ease;
    }

    .faq-container .toggle-icon.rotated {
        transform: rotate(180deg);
    }

    .faq-container .collapse {
        transition: height 0.3s ease;
    }

    .faq-container .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .faq-container .span-class {
        transform: uppercase;
    }
</style>

<div class="faq-container">
    <div class="container-fluid py-4" style="max-width: 1400px;">
        <div class="card p-10 mb-4">
            <!-- Search Form and Add FAQ Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <?= Html::beginForm(['faq/index'], 'get', ['class' => 'd-flex w-65', 'id' => 'faq-search-form']) ?>
                    <?= Html::textInput('search', $search ?? '', [
                        'class' => 'form-control form-control-sm me-2',
                        'placeholder' => Yii::$app->lang->t('faq', 'search1'),
                        'id' => 'faq-search-input'
                    ]) ?>
                    <?= Html::submitButton('<i class="fas fa-search"></i>', ['class' => 'btn btn-outline-secondary']) ?>
                    <?= Html::endForm() ?>
                </div>

            <div class="d-flex align-items-center">
                <div>
                    <button type="button" class="btn btn-light-primary me-3" id="filterButton" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="ki-duotone ki-filter fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i> Filter
                    </button>
                </div>

                <div class="ms-3">
                    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->role_id === 0): ?>
                        <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::$app->lang->t('faq', 'add_faq1'), ['create'], [
                            'class' => 'btn btn-primary me-2',
                            'id' => 'btn-tambah-faq',
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#faqModal',
                            'onclick' => "loadModalContent('/faq/create'); return false;"
                        ]) ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- FAQ Introduction -->
        <div class="faq-intro mt-5 mb-4">
            <p class="text-muted">
                <?= Yii::$app->lang->t('faq', 'write1') ?>
            </p>
        </div>

        <!-- Search Results Indicator -->
        <?php if (!empty($search)): ?>
            <div id="faq-search-results">
                <h5 class="text-muted mb-3">🔍 <?= Yii::$app->lang->t('faq', 'search1') ?> <em><?= Html::encode($search) ?></em></h5>
                <div id="search-results-list"></div>
            </div>
        <?php else: ?>
            <!-- Terpopuler and Paling Membantu -->
            <div class="row">
                <div class="col-md-6">
                    <h4>🔥 <?= Yii::$app->lang->t('faq', 'popular1') ?></h4>
                    <div id="terpopuler-list"></div>
                </div>
                <div class="col-md-6">
                    <h4>👍 <?= Yii::$app->lang->t('faq', 'helpful1') ?></h4>
                    <div id="palingMembantu-list"></div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- FAQ Modal -->
<?php
Modal::begin([
    'title' => '<h5 id="modal-title">' . Yii::$app->lang->t('faq', 'add_faq1') . '</h5>',
    'id' => 'faqModal',
    'size' => Modal::SIZE_LARGE,
    'options' => ['tabindex' => false],
]);
echo '<div id="faqModalContent"></div>';
Modal::end();
?>

<!-- Filter Modal -->
<?php
Modal::begin([
    'title' => '<h5 id="filter-modal-title">' . Yii::$app->lang->t('faq', 'filter1') . '</h5>',
    'id' => 'filterModal',
    'size' => Modal::SIZE_DEFAULT,
    'options' => ['tabindex' => false],
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => false
    ]
]);
?>
<div id="filterModalContent" class="p-4">
    <form id="filterForm" method="post">
        <div class="mb-3">
            <label class="fw-semibold fs-6 mb-2" for="faqFilter"><?= Yii::$app->lang->t('faq', 'faqtype') ?></label>
            <select id="faqFilter" class="form-select faq-category" data-control="select2" name="type" data-placeholder="<?= Yii::$app->lang->t('extra', 'extra105') ?>">
                <option value="" selected disabled><?= Yii::$app->lang->t('extra', 'extra105') ?></option>
            </select>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-primary" id="applyFilterButton">
                <i class="fas fa-filter"></i> <?= Yii::$app->lang->t('faq', 'Apply') ?>
            </button>
        </div>
    </form>
</div>
<?php Modal::end(); ?>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        console.log('jQuery version:', $.fn.jquery);
        console.log('Bootstrap version:', typeof bootstrap !== 'undefined' ? 'Bootstrap 5' : 'Bootstrap not loaded');
        console.log('Bootstrap modal available:', typeof bootstrap !== 'undefined' && bootstrap.Modal);

        if (typeof bootstrap === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js';
            script.onload = function() {
                console.log('Bootstrap loaded dynamically');
                initializePage();
            };
            document.head.appendChild(script);
        } else {
            initializePage();
        }
    });

    function initializePage() {
        initializeFilterModal();
        initializeCollapseEvents();
        initializeSearchForm();
        loadFaqData();
        checkActiveFilters();
    }

    function checkActiveFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        const hasCategory = urlParams.get('faqtype');
        if (hasCategory) {
            $('.faq-container #filterButton').addClass('filter-active');
        }
    }

    function escapeHtml(unsafe) {
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function loadFaqData(page = 1, faqtype = $('#faqFilter').val() || '', search = $('#faq-search-input').val() || '') {
    console.log('Loading FAQ data with:', { faqtype, search, page });

    $.ajax({
        url: '<?= Url::to(['faq/data']) ?>',
        type: 'GET',
        data: {
            faqtype: faqtype,
            search: search,
            page: page
        },
        dataType: 'json',
        beforeSend: function() {
            $('#terpopuler-list, #palingMembantu-list, #search-results-list').html(
                '<div class="text-center p-3"><i class="fas fa-spinner fa-spin"></i> <?= Yii::$app->lang->t('faq', 'Loading...') ?></div>'
            );
        },
        success: function(response) {
            console.log('FAQ data response:', response);
            if (response.success !== false) {
                renderFaqData(response);
            } else {
                showErrorMessage(response.message || '<?= Yii::$app->lang->t('faq', 'Failed to load FAQs.') ?>');
            }
        },
        error: function(xhr) {
            console.error('AJAX Error:', xhr.status, xhr.responseText);
            let message = '<?= Yii::$app->lang->t('faq', 'Server error while loading FAQs.') ?>';
            if (xhr.status === 403) message = '<?= Yii::$app->lang->t('faq', 'Access denied. Please log in again.') ?>';
            else if (xhr.status === 404) message = '<?= Yii::$app->lang->t('faq', 'Data not found.') ?>';
            showErrorMessage(message);
            $('#terpopuler-list, #palingMembantu-list, #search-results-list').html(
                '<div class="alert alert-danger"><?= Yii::$app->lang->t('faq', 'Failed to load data.') ?></div>'
            );
        }
    });
}

    function renderFaqData(response) {
        const search = $('#faq-search-input').val();
        console.log('Search value:', search, 'Length:', search.length, 'Trimmed:', search.trim());
        if (search) {
            renderSearchResults(response.data);
            $('#terpopuler-list').html('');
            $('#palingMembantu-list').html('');
        } else {
            renderTerpopuler(response.terpopuler);
            renderPalingMembantu(response.palingMembantu);
            $('#search-results-list').html('');
        }
    }

    function renderSearchResults(data) {
        let searchHtml = '';
        if (data && data.length > 0) {
            data.forEach((faq, i) => {
                searchHtml += createFaqCard(faq, 'Search' + i);
            });
            searchHtml += `<div class="mt-3">
            <a href="<?= Url::to(['index']) ?>" class="btn btn-sm btn-outline-danger">
                <i class="fas fa-times"></i> <?= Yii::$app->lang->t('faq', 'Clear search') ?>
            </a>
        </div>`;
        } else {
            searchHtml = `
            <div class="card mb-2">
                <div class="card-body text-center text-muted">
                    <i class="fas fa-search fa-2x mb-2"></i>
                    <p><?= Yii::$app->lang->t('faq', 'No results found.') ?></p>
                </div>
            </div>`;
        }
        $('#search-results-list').html(searchHtml);
    }

    function renderTerpopuler(data) {
        let html = '';
        if (data && data.length > 0) {
            data.forEach((faq, i) => {
                html += createFaqCard(faq, 'Terpopuler' + i);
            });
        } else {
            html = '<p class="text-muted"><?= Yii::$app->lang->t('faq', 'No popular FAQs available.') ?></p>';
        }
        $('#terpopuler-list').html(html);
    }

    function renderPalingMembantu(data) {
        let html = '';
        if (data && data.length > 0) {
            data.forEach((faq, i) => {
                html += createFaqCard(faq, 'Membantu' + i);
            });
        } else {
            html = '<p class="text-muted"><?= Yii::$app->lang->t('faq', 'No helpful FAQs available.') ?></p>';
        }
        $('#palingMembantu-list').html(html);
    }

    function createFaqCard(faq, prefix) {
        const canEdit = <?= json_encode(Yii::$app->user->identity && Yii::$app->user->identity->role_id === 0) ?>;
        const safeFaqId = Number(faq.faqid) || 0;
        const safeQuestion = escapeHtml(faq.question || '');
        const safeAnswer = escapeHtml(faq.answer || '');
        const safeFaqType = escapeHtml(faq.faqtype || '');
        const editButtons = canEdit ? `
        <div class="mt-3">
            <a href="<?= Url::to(['faq/update']) ?>?id=${safeFaqId}" 
               class="btn btn-sm btn-outline-warning edit-faq" 
               data-bs-toggle="modal" 
               data-bs-target="#faqModal" 
               onclick="loadModalContent($(this).attr('href'), '<?= Yii::$app->lang->t('faq', 'Edit FAQ') ?>'); return false;">
                <i class="fas fa-edit"></i> <?= Yii::$app->lang->t('faq', 'Edit') ?>
            </a>
            <button class="btn btn-sm btn-danger" onclick="deleteFaq(${safeFaqId})">
                <i class="fas fa-trash"></i> <?= Yii::$app->lang->t('faq', 'Delete') ?>
            </button>
        </div>` : '';

        return `
        <div class="card mb-2">
            <div class="card-header" id="heading${prefix}${safeFaqId}">
                <button class="btn btn-link w-100 text-start d-flex justify-content-between align-items-center collapsed"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapse${prefix}${safeFaqId}"
                        aria-expanded="false"
                        aria-controls="collapse${prefix}${safeFaqId}">
                    <span>${safeQuestion}</span>
                    <i class="fas fa-chevron-down toggle-icon" id="toggleIcon${prefix}${safeFaqId}"></i>
                </button>
            </div>
            <div id="collapse${prefix}${safeFaqId}" class="collapse" aria-labelledby="heading${prefix}${safeFaqId}">
                <div class="card-body">
                    <div class="faq-answer mb-2">${safeAnswer}</div>
                    <small class="text-muted">
                        <i class="fas fa-eye"></i> ${faq.view_count} <?= Yii::$app->lang->t('faq', 'views') ?>
                        <i class="fas fa-thumbs-up ms-2"></i> ${faq.helpful} <?= Yii::$app->lang->t('faq', 'helpful') ?>
                        ${safeFaqType ? `<span class="badge bg-secondary ms-2">${safeFaqType}</span>` : ''}
                    </small>
                    ${editButtons}
                </div>
            </div>
        </div>`;
    }

    function showErrorMessage(message) {
        Swal.fire({
            title: '<?= Yii::$app->lang->t('faq', 'Error') ?>',
            text: message,
            icon: 'error',
            confirmButtonText: '<?= Yii::$app->lang->t('faq', 'OK') ?>',
            background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#2d2d2d' : '#ffffff',
            color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#333333'
        });
    }

    function initializeFilterModal() {
        let select2Initialized = false;

        // Inisialisasi Select2 saat dokumen siap
        $(document).ready(function() {
            if (!select2Initialized) {
                $('#faqFilter').select2({
                    placeholder: "<?= Yii::$app->lang->t('faq', 'Select a FAQ Type') ?>",
                    allowClear: true,
                    theme: 'bootstrap-5',
                    minimumInputLength: 0,
                    dropdownParent: $('#filterModal'),
                    ajax: {
                        url: '<?= Url::to(['faq/get-type']) ?>',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.items || [],
                                pagination: {
                                    more: data.more
                                }
                            };
                        },
                        error: function(xhr) {
                            console.error('Select2 Error:', xhr.status, xhr.responseText);
                            return {
                                results: [],
                                pagination: {
                                    more: false
                                }
                            }; // Kembalikan array kosong jika error
                        }
                    }
                });
                select2Initialized = true;
            }
        });

        // Muat data awal berdasarkan parameter URL saat modal ditampilkan
        $('#filterModal').on('shown.bs.modal', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const faqtype = urlParams.get('faqtype');
            if (faqtype && $('#faqFilter').find(`option[value="${faqtype}"]`).length === 0) {
                $.ajax({
                    url: '<?= Url::to(['faq/get-type']) ?>',
                    data: {
                        q: faqtype,
                        page: 1
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.items && data.items.length > 0) {
                            const option = new Option(data.items[0].text, data.items[0].id, true, true);
                            $('#faqFilter').append(option).trigger('change');
                        } else {
                            $('#faqFilter').val(faqtype).trigger('change');
                        }
                    },
                    error: function() {
                        $('#faqFilter').val(faqtype).trigger('change'); // Fallback jika AJAX gagal
                    }
                });
            } else if (faqtype) {
                $('#faqFilter').val(faqtype).trigger('change');
            }
        });

        $('#filterModal').on('hidden.bs.modal', function() {
            // Tidak perlu destroy Select2, cukup reset nilai
            $('#faqFilter').val(null).trigger('change');
        });

        $('#filterForm').on('submit', function(e) {
            e.preventDefault(); // Hentikan pengiriman formulir default
            const faqtype = $('#faqFilter').val() || '';
            const search = $('#faq-search-input').val() || '';
            console.log('Applying filter with:', {
                faqtype,
                search
            }); // Debug log
            loadFaqData(1, faqtype, search); // Panggil loadFaqData dengan parameter filter
        });
    }

    function initializeSearchForm() {
        $('.faq-container #faq-search-input').on('input', debounce(function() {
            loadFaqData();
        }, 300));

        $('.faq-container #faq-search-form').on('submit', function(e) {
            e.preventDefault();
            loadFaqData();
        });
    }

    function initializeCollapseEvents() {
        $('.faq-container').on('show.bs.collapse', '.collapse', function() {
            const collapseId = this.id;
            const iconId = collapseId.replace('collapse', 'toggleIcon');
            const icon = document.getElementById(iconId);
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up', 'rotated');
            }
        });

        $('.faq-container').on('hide.bs.collapse', '.collapse', function() {
            const collapseId = this.id;
            const iconId = collapseId.replace('collapse', 'toggleIcon');
            const icon = document.getElementById(iconId);
            if (icon) {
                icon.classList.remove('fa-chevron-up', 'rotated');
                icon.classList.add('fa-chevron-down');
            }
        });
    }

    function deleteFaq(id) {
        Swal.fire({
            title: "<?= Yii::$app->lang->t('faq', 'Are you sure?') ?>",
            text: "<?= Yii::$app->lang->t('extra', 'extra45') ?>",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6e7d88",
            confirmButtonText: "<?= Yii::$app->lang->t('faq', 'Yes, delete it!') ?>",
            cancelButtonText: "<?= Yii::$app->lang->t('faq', 'Cancel') ?>",
            customClass: {
                confirmButton: 'btn btn-danger'
            },
            background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#2d2d2d' : '#ffffff',
            color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#333333'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= Url::to(['faq/delete']) ?>',
                    type: 'POST',
                    data: {
                        faqid: id,
                        [<?= Json::encode(Yii::$app->request->csrfParam) ?>]: <?= Json::encode(Yii::$app->request->csrfToken) ?>
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: "<?= Yii::$app->lang->t('faq', 'Deleted!') ?>",
                                text: "<?= Yii::$app->lang->t('faq', 'FAQ deleted successfully.') ?>",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false,
                                background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#2d2d2d' : '#ffffff',
                                color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#333333'
                            }).then(() => {
                                loadFaqData();
                            });
                        } else {
                            showErrorMessage(response.message || '<?= Yii::$app->lang->t('faq', 'Failed to delete FAQ.') ?>');
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.status, xhr.responseText);
                        showErrorMessage('<?= Yii::$app->lang->t('faq', 'Server error while deleting FAQ.') ?>');
                    }
                });
            }
        });
    }

    function loadModalContent(url, title) {
        $('#modal-title').text(title || '<?= Yii::$app->lang->t('faq', 'Add FAQ') ?>');
        $('#faqModalContent').html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> <?= Yii::$app->lang->t('faq', 'Loading...') ?></div>');

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#faqModalContent').html(response);
                initializeFormHandler();

                // Initialize Yii2 client validation if available
                if (typeof yii !== 'undefined' && yii.initModule) {
                    yii.initModule(document.getElementById('faqModalContent'));
                }

                // Re-initialize any form elements that need it
                setTimeout(function() {
                    const formAlertDiv = $('#faqModalContent').find('#form-alert');
                    if (formAlertDiv.length === 0) {
                        $('#faqModalContent').prepend('<div id="form-alert" class="alert alert-danger d-none" role="alert"></div>');
                    }
                }, 100);
            },
            error: function(xhr) {
                console.error('AJAX Error:', xhr.status, xhr.responseText);
                let errorMessage = '<?= Yii::$app->lang->t('faq', 'Error Loading Content') ?>';

                if (xhr.status === 403) {
                    errorMessage = '<?= Yii::$app->lang->t('faq', 'Access denied. Please log in again.') ?>';
                } else if (xhr.status === 404) {
                    errorMessage = '<?= Yii::$app->lang->t('faq', 'Content not found.') ?>';
                }

                $('#faqModalContent').html(
                    '<div class="alert alert-danger">' +
                    '<h6>' + errorMessage + '</h6>' +
                    '<p>Status: ' + xhr.status + '</p>' +
                    '<p><?= Yii::$app->lang->t('faq', 'Please try again or contact administrator.') ?></p>' +
                    '</div>'
                );
            }
        });
    }

    function initializeFormHandler() {
        $(document).off('submit', '#faq-form').on('submit', '#faq-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const submitBtn = form.find('[type="submit"]');
            const originalText = submitBtn.html();

            if (form.find('.has-error, .is-invalid').length) {
                $('#form-alert').removeClass('d-none').text('<?= Yii::$app->lang->t('faq', 'Please check your inputs.') ?>');
                return false;
            }

            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> <?= Yii::$app->lang->t('faq', 'Saving...') ?>');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        // Use Bootstrap 5 modal method
                        const modal = bootstrap.Modal.getInstance(document.getElementById('faqModal'));
                        if (modal) {
                            modal.hide();
                        } else {
                            $('#faqModal').removeClass('show').css('display', 'none');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open').css('padding-right', '');
                        }

                        Swal.fire({
                            title: '<?= Yii::$app->lang->t('faq', 'Success!') ?>',
                            text: '<?= Yii::$app->lang->t('faq', 'FAQ saved successfully.') ?>',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false,
                            background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#2d2d2d' : '#ffffff',
                            color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#333333'
                        }).then(() => {
                            loadFaqData();
                        });
                    } else {
                        const errorMessage = response.message || response.pesan || (response.errors ? Object.values(response.errors).join(', ') : '<?= Yii::$app->lang->t('faq', 'Failed to save FAQ.') ?>');
                        $('#form-alert').removeClass('d-none').text(errorMessage);
                    }
                },
                error: function(xhr) {
                    console.error('Form submission error:', xhr.status, xhr.responseText);
                    $('#form-alert').removeClass('d-none').text('<?= Yii::$app->lang->t('faq', 'Server error. Please try again.') ?>');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.card').length) {
            $('.collapse.show').each(function() {
                const collapseInstance = bootstrap.Collapse.getInstance(this);
                if (collapseInstance) {
                    collapseInstance.hide();
                }
            });
        }
    });

    $(document).on('click', '.btn-outline-warning, .btn-outline-danger', function(e) {
        e.stopPropagation();
    });
</script>