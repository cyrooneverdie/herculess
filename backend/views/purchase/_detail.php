<?php
$title = Yii::$app->lang->t('extra', 'extra53');
$this->title = $title;

use yii\helpers\Html;
use yii\helpers\Url;

// var_dump($details);die;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* Force Light mode as default */
    .invoice-container {
        background-color: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        max-width: 990px;
        margin: 20px auto;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px var(--shadow);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        transition: background-color 0.3s ease, color 0.3s ease;
        min-height: calc(100vh - 100px);
    }

    /* CSS Variables for light mode (default) */
    :root,
    .invoice-container {
        --bg-primary: #fff;
        --bg-secondary: #f8f9fa;
        --text-primary: #343a40;
        --text-secondary: #6c757d;
        --text-muted: #6c757d;
        --border-color: #e9ecef;
        --shadow: rgba(0, 0, 0, 0.1);
        --hover-bg: #f8f9fa;
        --active-glow: #0d6efd;
        --input-focus: #4dabf7;
    }

    /* Dark mode only when explicitly activated */
    body.dark .invoice-container,
    .dark-mode .invoice-container,
    .invoice-container.dark-mode {
        --bg-primary: #1a1a1a;
        --bg-secondary: #2d2d2d;
        --text-primary: #ffffff;
        --text-secondary: #b0b0b0;
        --text-muted: #888888;
        --border-color: #404040;
        --shadow: rgba(0, 0, 0, 0.3);
        --hover-bg: #333333;
        --active-glow: #4dabf7;
        --input-focus: #6cb2eb;
        background-color: var(--bg-primary) !important;
        color: var(--text-primary) !important;
    }

    /* Override system dark mode preferences */
    @media (prefers-color-scheme: dark) {
        .invoice-container {
            background-color: #fff !important;
            color: #343a40 !important;
        }
    }

    /* Badge theme switch styling */
    .badge-theme-switch {
        background-color: var(--active-glow);
        color: #fff;
        border-radius: 0.5rem;
        font-size: 0.9rem;
        text-decoration: none;
        padding: 0.4rem 0.8rem;
        transition: all 0.3s ease;
    }

    .badge-theme-switch:hover {
        background-color: var(--hover-bg);
        color: var(--text-primary);
    }

    .badge-theme-switch:active {
        box-shadow: 0 0 8px var(--active-glow);
        opacity: 0.9;
    }

    /* Invoice header */
    .invoice-header {
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .invoice-header h1 {
        color: var(--text-primary);
        font-size: 1.5rem;
        margin: 0;
    }

    /* Badge status */
    .badge-status {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
        border-radius: 50rem;
        background-color: #dc3545;
        color: #fff;
    }

    /* Customer and invoice info */
    .customer-info,
    .invoice-info {
        font-size: 1.1rem;
    }

    .customer-info h5 {
        color: var(--text-primary);
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .customer-info i {
        color: var(--text-secondary);
        width: 20px;
        margin-right: 0.5rem;
    }

    /* Text styling */
    .text-muted {
        color: var(--text-muted) !important;
    }

    .text-primary {
        color: var(--active-glow) !important;
    }

    .fw-bold {
        color: var(--text-primary);
        font-weight: 700;
    }

    /* Table styling */
    .table {
        width: 100%;
        border-collapse: collapse;
        background-color: var(--bg-primary);
    }

    .table-bordered {
        border: 1px solid var(--border-color);
    }

    .table-striped tbody tr:nth-child(odd) {
        background-color: var(--bg-secondary);
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
        border: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .table th {
        background-color: #363434;
        color: #fff !important;
        font-weight: 700;
        text-align: left;
    }

    .table td.text-center {
        text-align: center;
    }

    .table a.text-primary {
        color: var(--active-glow);
        text-decoration: none;
    }

    .table a.text-primary:hover {
        text-decoration: underline;
    }

    /* Totals section */
    .totals-section {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1.5rem;
        font-size: 1.1rem;
    }

    /* Modernized Payment Form - Horizontal Layout */
    .payment-form {
        margin-top: 2rem;
        padding: 1.5rem;
        background-color: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 0 2px 8px var(--shadow);
        transition: all 0.3s ease;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .payment-form h2 {
        font-size: 1.5rem;
        color: var(--text-primary);
        margin-bottom: 1.5rem;
        font-weight: 600;
        flex: 0 0 100%;
    }

    .form-group {
        margin-bottom: 0;
        flex: 1 1 calc(20% - 1rem);
        min-width: 150px;
        position: relative;
    }

    .form-group label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        display: block;
        font-weight: 500;
    }

    .form-group input,
    .form-group select,
    .withholding-btn {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .withholding-btn:focus {
        outline: none;
        border-color: var(--input-focus);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }

    .form-group input::placeholder {
        color: var(--text-muted);
        opacity: 0.7;
    }

    .attachment-upload {
        padding: 1rem;
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        background-color: var(--bg-secondary);
        transition: border-color 0.3s ease, background-color 0.3s ease;
        position: relative;
        flex: 1 1 calc(20% - 1rem);
        min-width: 150px;
    }

    .attachment-upload.drag-active {
        border-color: var(--active-glow);
        background-color: rgba(13, 110, 253, 0.05);
    }

    .attachment-upload:hover {
        border-color: var(--active-glow);
    }

    .attachment-upload input[type="file"] {
        display: none;
    }

    .attachment-upload label {
        color: var(--text-primary);
        margin: 0;
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .attachment-upload .attachment-preview {
        margin-top: 1rem;
        max-width: 100%;
        max-height: 150px;
        object-fit: contain;
        border-radius: 8px;
        display: none;
    }

    .attachment-upload.active .attachment-preview {
        display: block;
    }

    .attachment-upload .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
        transition: background-color 0.3s ease;
    }

    .attachment-upload.active .close-btn {
        display: flex;
    }

    .attachment-upload .close-btn:hover {
        background-color: #c82333;
    }

    .summary {
        display: flex;
        justify-content: space-between;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
        font-size: 1.1rem;
        flex: 0 0 100%;
    }

    .summary .label {
        font-weight: 600;
        color: var(--text-primary);
    }

    .summary .value {
        color: var(--active-glow);
        font-weight: 600;
    }

    .button {
        background-color: var(--active-glow);
        color: #fff;
        border: none;
        padding: 0.75rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        width: auto;
        text-align: center;
        transition: background-color 0.3s ease, transform 0.2s ease;
        flex: 0 0 auto;
    }

    .button:hover {
        background-color: #0b5ed7;
        transform: translateY(-1px);
    }

    .button:active {
        transform: translateY(0);
    }

    .watch-log {
        margin-top: 1.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
        text-align: center;
    }

    .watch-log a {
        color: var(--active-glow);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .watch-log a:hover {
        text-decoration: underline;
    }

    /* Dropdown for Withholding */
    .withholding-dropdown {
        position: relative;
    }

    .withholding-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 1rem;
    }

    .withholding-btn::after {
        content: '\f078';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: var(--text-secondary);
    }

    .withholding-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        box-shadow: 0 4px 12px var(--shadow);
        z-index: 10;
        margin-top: 0.25rem;
    }

    .withholding-menu.active {
        display: block;
    }

    .withholding-menu-item {
        padding: 0.75rem;
        color: var(--text-primary);
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s ease;
    }

    .withholding-menu-item:hover {
        background-color: var(--hover-bg);
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .invoice-container {
            max-width: 700px;
            margin: 15px auto;
            padding: 1.25rem;
        }

        .invoice-header h1 {
            font-size: 1.25rem;
        }

        .customer-info,
        .invoice-info {
            font-size: 1rem;
        }
    }

    @media (max-width: 768px) {
        .invoice-container {
            max-width: 100%;
            margin: 10px 15px;
            padding: 1rem;
        }

        .invoice-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .payment-form {
            flex-direction: column;
        }

        .form-group,
        .attachment-upload {
            flex: 0 0 100%;
        }
    }

    @media (max-width: 576px) {
        .invoice-container {
            margin: 10px 10px;
            padding: 0.75rem;
        }

        .btn-outline-secondary,
        .badge-theme-switch {
            font-size: 0.8rem;
            padding: 0.3rem 0.5rem;
        }

        .payment-form h2 {
            font-size: 1.25rem;
        }

    }
</style>

<div class="invoice-container" id="invoice-content">
    <!-- Header with Invoice Number -->
    <div class="invoice-header d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold mb-0"><?= Yii::$app->lang->t('extra', 'extra53') . " " . Html::encode($model->tranno) ?></h1>
        <div class="d-flex gap-2 align-items-center">
            <!-- Share Button -->
            <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-share-alt me-1"></i>Share
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="sendEmail()"><i class="fa-solid fa-envelope me-2"></i>Send Email</a></li>
                    <li><a class="dropdown-item" href="#" onclick="sendWhatsapp()"><i class="fa-brands fa-whatsapp me-2"></i>Send Whatsapp
                    <li><a class="dropdown-item" href="#" onclick="sendSMS()"><i class="fa-solid fa-sms me-2"></i>Send SMS</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="copyLink()"><i class="fa-solid fa-link me-2"></i>Copy Link</a></li>
                    <li><a class="dropdown-item" href="#" onclick="emailPaymentRecap()"><i class="fa-solid fa-receipt me-2"></i>Email Payment Recap</a></li>
                    <li><a class="dropdown-item" href="#" onclick="whatsappPaymentRecap()"><i class="fa-brands fa-whatsapp me-2"></i>Whatsapp Payment Recap</a></li>
                    <li><a class="dropdown-item" href="#" onclick="smsPaymentRecap()"><i class="fa-solid fa-sms me-2"></i>SMS Payment Recap</a></li>
                </ul>
            </div>

            <!-- Back Button -->
            <a href="/purchase/request" class="badge badge-theme-switch fs-6 px-3 py-2">
                <i class="fa-solid fa-arrow-left me-1"></i><?= Yii::$app->lang->t('extra', 'extra17') ?>
            </a>
        </div>
    </div>

    <!-- Payment Status -->
    <div class="mb-4">
        <?php
        $statusLabel = match ($model->statuspaid) {
            1 => Yii::$app->lang->t('dashboard', 'paid'),
            10 => Yii::$app->lang->t('cashbackend', 'cashbackend12'),
            default => Yii::$app->lang->t('cashbackend', 'cashbackend12'),
        };
        ?>
        <span class="badge badge-status bg-danger text-white"><?= Html::encode($statusLabel) ?></span>
    </div>

    <!-- Invoice Info Section -->
    <div class="row mb-4">
        <div class="col-md-7 customer-info">
            <p class="text-muted mb-1"><?= Yii::$app->lang->t('extra', 'extra54') ?></p>
            <h5 class="mb-3"><?= Html::encode($details[0]['contact_name'] ?? '-') ?></h5>
            <div class="d-flex align-items-start mb-2">
                <i class="fa-solid fa-map-marker-alt me-2 mt-1"></i>
                <p class="mb-0 text-muted small"><?= Html::encode($details[0]['address'] ?? '-') ?></p>
            </div>
            <div class="d-flex align-items-start mb-2">
                <i class="fa-solid fa-building me-2 mt-1"></i>
                <p class="mb-0 text-muted small">-</p>
            </div>
            <div class="d-flex align-items-start">
                <i class="fa-solid fa-phone me-2 mt-1"></i>
                <p class="mb-0 text-muted small"><?= Html::encode($details[0]['contact_phone1'] ?? '-') ?></p>
            </div>
        </div>

        <div class="col-md-5 invoice-info">
            <div class="mb-3">
                <p class="text-muted mb-1"><?= Yii::$app->lang->t('extra', 'extra55') ?></p>
                <p class="fw-bold mb-0"><?= Html::encode($model->tranno) ?></p>
            </div>
            <div class="mb-3 d-flex">
                <div class="me-4">
                    <p class="text-muted mb-1"><?= Yii::$app->lang->t('tran', 'tran_date') ?></p>
                    <p class="fw-bold mb-0"><?= Yii::$app->formatter->asDate($model->trandate, 'php:d/m/Y') ?></p>
                </div>
                <div>
                    <p class="text-muted mb-1"><?= Yii::$app->lang->t('cashbackend', 'cashbackend5') ?></p>
                    <p class="fw-bold mb-0"><?= $model->tranduedate ? Yii::$app->formatter->asDate($model->tranduedate, 'php:d/m/Y') : '-' ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th><?= Yii::$app->lang->t('varian', 'varian_sku') ?></th>
                    <th><?= Yii::$app->lang->t('purchase_table', 'purchase_deskripsi') ?></th>
                    <th class="text-center"><?= Yii::$app->lang->t('produk', 'detail_qty') ?></th>
                    <th class="text-center"><?= Yii::$app->lang->t('front_home', 'price') ?></th>
                    <th class="text-center"><?= Yii::$app->lang->t('extra', 'extra5') ?></th>
                    <th class="text-center"><?= Yii::$app->lang->t('extra', 'extra70') ?></th>
                    <th class="text-center"><?= Yii::$app->lang->t('extra', 'extra106') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($details as $detail) : ?>
                    <tr>
                        <td class="py-3">
                            <a href="produk/detail" class="text-primary"><?= Html::encode($detail['sku'] ?? '-') ?></a>
                        </td>
                        <td class="py-3">
                            <p class="mb-0"><?= Html::encode($detail['itemtype'] ?? '-') ?></p>
                        </td>
                        <td class="py-3 text-center"><?= Html::encode($detail['amount'] ?? 0) ?></td>
                        <td class="py-3 text-center"><?= Yii::$app->formatter->asDecimal($detail['price'] ?? 0, 0) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Totals Section -->
    <div class="totals-section">
        <div class="d-flex justify-content-between mb-2">
            <span><?= Yii::$app->lang->t('produk', 'detail_qty') ?></span>
            <span class="fw-bold">
                <?php
                $totalQuantity = array_sum(array_column($details, 'amount'));
                echo Html::encode($totalQuantity);
                ?>
            </span>
        </div>
        <div class="d-flex justify-content-between mb-2">
            <span>Sub Total</span>
            <span class="fw-bold">Rp <?= Yii::$app->formatter->asDecimal($total, 0) ?></span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold"><?= Yii::$app->lang->t('cashbackend', 'cashbackend7') ?></span>
            <span class="fw-bold fs-5">Rp <?= Yii::$app->formatter->asDecimal($total, 0) ?></span>
        </div>
    </div>

    <!-- Modernized Payment Form - Horizontal Layout -->
    <div class="payment-form">
        <h2>Receive a payment</h2>
        <div class="form-group">
            <label for="amount-paid">Amount Paid <span style="color: #dc3545;">*</span></label>
            <input type="text" id="amount-paid" placeholder="e.g., 1,901,000" required>
        </div>
        <div class="form-group">
            <label for="transaction-date">Transaction Date <span style="color: #dc3545;">*</span></label>
            <input type="date" id="transaction-date" value="2025-08-06" required>
        </div>
        <div class="form-group">
            <label for="paid-to">Paid To</label>
            <input type="text" id="paid-to" placeholder="Enter code (e.g., INV-123)">
        </div>
        <div class="form-group">
            <label for="tag">Tag</label>
            <input type="text" id="tag" placeholder="Enter tag">
        </div>
        <div class="form-group attachment-upload" id="attachment-upload">
            <input type="file" id="attachment-input" accept="image/*" onchange="previewAttachment(this)">
            <label for="attachment-input"><i class="fa-solid fa-upload"></i> Upload Image</label>
            <img class="attachment-preview" id="attachment-preview" src="" alt="Attachment Preview">
            <button class="close-btn" onclick="clearAttachment()"><i class="fa-solid fa-times"></i></button>
        </div>
        <div class="form-group withholding-dropdown">
            <label for="withholding">Withholding</label>
            <button type="button" class="withholding-btn" id="withholding" onclick="toggleWithholdingMenu()">Select Withholding</button>
            <div class="withholding-menu" id="withholding-menu">
                <div class="withholding-menu-item" onclick="selectWithholding('Option 1')">Option 1</div>
                <div class="withholding-menu-item" onclick="selectWithholding('Option 2')">Option 2</div>
                <div class="withholding-menu-item" onclick="selectWithholding('Option 3')">Option 3</div>
            </div>
        </div>
        <div class="summary text-center">
            <div class="label"><?= Yii::$app->lang->t('cashbackend', 'cashbackend7') ?></div>
            <div class="value">Rp <?= Yii::$app->formatter->asDecimal($total, 0) ?></div>
        </div>
        <button class="button" onclick="sendPayment()">+ Add Payment</button>
    </div>

    <!-- Watch Data Log Section -->
    <div class="watch-log">
        Watch data log
        <br>
        <a href="#">Last modified by on <?= date('d M Y H:i') ?></a>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    function previewAttachment(input) {
    const uploadArea = document.getElementById('attachment-upload');
    const preview = document.getElementById('attachment-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            uploadArea.classList.add('active');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function clearAttachment() {
    const uploadArea = document.getElementById('attachment-upload');
    const preview = document.getElementById('attachment-preview');
    const fileInput = document.getElementById('attachment-input');
    preview.src = '';
    fileInput.value = '';
    uploadArea.classList.remove('active');
}

// Drag and Drop functionality
document.addEventListener('DOMContentLoaded', () => {
    const uploadArea = document.getElementById('attachment-upload');
    const fileInput = document.getElementById('attachment-input');

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-active');
    });

    uploadArea.addEventListener('dragenter', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-active');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-active');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-active');
        const files = e.dataTransfer.files;
        if (files.length > 0 && files[0].type.startsWith('image/')) {
            fileInput.files = files;
            previewAttachment(fileInput);
        } else {
            alert('Please drop an image file.');
        }
    });
});

function toggleWithholdingMenu() {
    const menu = document.getElementById('withholding-menu');
    menu.classList.toggle('active');
}

function selectWithholding(option) {
    const btn = document.querySelector('.withholding-btn');
    btn.textContent = option;
    toggleWithholdingMenu();
}

function sendPayment() {
    alert("Payment and attachment sending functionality not implemented yet. Please integrate with backend.");
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href)
        .then(() => {
            alert("Link copied to clipboard!");
        })
        .catch(err => {
            console.error("Failed to copy link: ", err);
        });
}

function sendEmail() {
    alert("Send Email functionality not implemented yet.");
}

function sendWhatsapp() {
    alert("Send WhatsApp functionality not implemented yet.");
}

function sendSMS() {
    alert("Send SMS functionality not implemented yet.");
}

function emailPaymentRecap() {
    alert("Email Payment Recap functionality not implemented yet.");
}

function whatsappPaymentRecap() {
    alert("WhatsApp Payment Recap functionality not implemented yet.");
}

function smsPaymentRecap() {
    alert("SMS Payment Recap functionality not implemented yet.");
}
JS, \yii\web\View::POS_END);
?>