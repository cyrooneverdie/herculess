<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<style>
/* Namespace untuk menghindari konflik dengan tema Clloo */
.balance-sheet {
    font-family: Arial, sans-serif; /* Ganti dengan font dari tema Clloo jika ada */
}

.balance-sheet .card {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.balance-sheet .card-header {
    background-color: #f5f7fa;
    padding: 15px;
    border-bottom: 1px solid #e0e0e0;
}

.balance-sheet .card-title {
    font-size: 24px;
    font-weight: bold;
    color: #1a1a1a;
    margin: 0;
}

.balance-sheet .card-body {
    padding: 20px;
}

.balance-sheet .ratio-section {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}

.balance-sheet .ratio-card {
    background-color: #f9f9f9;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    flex: 1 1 22%;
    min-width: 200px;
}

.balance-sheet .ratio-card .value {
    font-size: 20px;
    font-weight: bold;
    color: #1a1a1a;
}

.balance-sheet .ratio-card .label {
    font-size: 14px;
    color: #666;
}

.balance-sheet .ratio-card .trend {
    color: #28a745;
    font-size: 16px;
}

.balance-sheet .see-more {
    text-align: center;
    margin-bottom: 20px;
}

.balance-sheet .see-more a {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}

.balance-sheet .see-more a:hover {
    text-decoration: underline;
}

.balance-sheet .table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.balance-sheet .table tr {
    border-bottom: 1px solid #e0e0e0;
}

.balance-sheet .table td {
    padding: 10px;
    text-align: left;
}

.balance-sheet .table td.amount {
    text-align: right;
    color: #1a1a1a;
}

.balance-sheet .table .category {
    background-color: #f0f2f5;
    font-weight: bold;
}

.balance-sheet .btn-group .btn {
    background-color: #fff;
    color: #007bff;
    border: 1px solid #e0e0e0;
    padding: 5px 10px;
    font-size: 14px;
}

.balance-sheet .btn-warning {
    background-color: #ff9800;
    color: #fff;
    border: none;
    padding: 5px 10px;
    font-size: 14px;
}

.balance-sheet .float-end {
    float: right;
}

.balance-sheet .mb-18 {
    margin-bottom: 18px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
}

.balance-sheet .filter-btn, .balance-sheet .period-btn {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    padding: 5px 10px;
    font-size: 14px;
    cursor: pointer;
}

.balance-sheet select, .balance-sheet input[type="date"] {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    padding: 5px;
    font-size: 14px;
    border-radius: 4px;
}

/* Media Queries untuk Responsivitas */
@media (max-width: 768px) {
    .balance-sheet .ratio-section {
        flex-direction: column;
    }
    .balance-sheet .ratio-card {
        width: 100%;
        min-width: unset;
    }
    .balance-sheet .mb-18 {
        flex-direction: column;
        align-items: stretch;
    }
    .balance-sheet .float-end {
        float: none;
    }
}
</style>

<div class="balance-sheet">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Balance Sheet</div>
        </div>

        <div class="card-body">
            <div class="mb-18">
                <div class="btn-group float-end ms-3">
                    <button type="button" class="btn btn-default dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Download options">
                        Download
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item text-danger" href="<?= Url::to(['report/print']) ?>" data-type="pdf">
                            <i class="fa-solid fa-file-pdf"></i> PDF</a>
                        </li>
                        <li><a class="dropdown-item text-success" href="#"><i class="fa-solid fa-file-excel"></i> Excel</a></li>
                    </ul>
                </div>
                <a class="btn btn-warning float-end balek" href="<?= Url::to(['report/index']) ?>" aria-label="Back to report index">Back</a>
                <div class="float-end me-3">
                    <select class="form-select" aria-label="Select period">
                        <option>Month</option>
                        <option>Year</option>
                    </select>
                </div>
                <div class="float-end me-3">
                    <input type="date" class="form-control" value="2025-08-29" aria-label="Select date">
                </div>
                <div class="float-end me-3">
                    <button class="btn period-btn" aria-label="Compare periods">Compare</button>
                </div>
                <div class="float-end me-3">
                    <button class="btn period-btn" aria-label="Select period">Period</button>
                </div>
                <div class="float-end me-3">
                    <button class="btn period-btn" aria-label="Select year">Year</button>
                </div>
            </div>

            <div class="ratio-section">
                <div class="ratio-card">
                    <div class="label">QUICK RATIO</div>
                    <div class="value">2.2</div>
                    <div class="label">Target 0.2</div>
                </div>
                <div class="ratio-card">
                    <div class="label">CURRENT RATIO</div>
                    <div class="value">1.25</div>
                    <div class="label">+33.0% vs previous month</div>
                    <div class="trend">↑</div>
                </div>
                <div class="ratio-card">
                    <div class="label">DEBT EQUITY RATIO</div>
                    <div class="value">0</div>
                    <div class="label">0% vs previous month</div>
                    <div class="trend">↑</div>
                </div>
                <div class="ratio-card">
                    <div class="label">EQUITY RATIO</div>
                    <div class="value">0.76</div>
                    <div class="label">+13% vs previous month</div>
                    <div class="trend">↑</div>
                </div>
            </div>

            <div class="see-more">
                <a href="#" aria-label="See more details">See More</a>
            </div>

            <button class="filter-btn" aria-label="Apply filter">Filter</button>
            <button class="filter-btn" aria-label="More options">⋮</button>

            <table class="table">
                <tr><td colspan="2" class="category">Aset</td><td class="amount">29/08/2025</td></tr>
                <tr><td>Kas & Bank</td><td></td><td></td></tr>
                <tr><td>1-10001 Cash</td><td></td><td class="amount">13,440,277</td></tr>
                <tr><td>1-10002 Bank Account</td><td></td><td class="amount">32,394,397</td></tr>
                <tr><td>1-10003 Giro</td><td></td><td class="amount">26,994,230</td></tr>
                <tr><td><strong>Total Kas & Bank</strong></td><td></td><td class="amount">72,828,904</td></tr>
                <tr><td>Aset Lancar</td><td></td><td></td></tr>
                <tr><td>1-10100 Account Receivable</td><td></td><td class="amount">37,692,364</td></tr>
                <tr><td>1-10102 Doubtful Receivable</td><td></td><td class="amount">6,469,289</td></tr>
                <tr><td>1-10200 Inventory</td><td></td><td class="amount">21,950,102</td></tr>
                <tr><td>1-10401 Other Current Assets</td><td></td><td class="amount">10,811</td></tr>
                <tr><td>1-10402 Prepaid Expenses</td><td></td><td class="amount">(34,234)</td></tr>
                <tr><td>1-10500 VAT In</td><td></td><td class="amount">4,011,000</td></tr>
                <tr><td>1-10502 Prepaid Income Tax - PPh 23</td><td></td><td class="amount">23,423</td></tr>
                <tr><td><strong>Total Aset Lancar</strong></td><td></td><td class="amount">70,122,756</td></tr>
                <tr><td>Aset Tetap</td><td></td><td></td></tr>
                <tr><td>1-10700 Fixed Assets - Land</td><td></td><td class="amount">87,400,000</td></tr>
                <tr><td>1-10701 Fixed Assets - Building</td><td></td><td class="amount">(72,973)</td></tr>
                <tr><td>1-10703 Fixed Assets - Vehicles</td><td></td><td class="amount">81,982</td></tr>
                <tr><td>1-10705 Fixed Assets - Office Equipment</td><td></td><td class="amount">(46,847)</td></tr>
                <tr><td><strong>Total Aset Tetap</strong></td><td></td><td class="amount">87,362,162</td></tr>
                <tr><td>Depresiasi & Amortiasi</td><td></td><td></td></tr>
                <tr><td><strong>Total Depresiasi & Amortiasi</strong></td><td></td><td class="amount">-</td></tr>
                <tr><td>Lainnya</td><td></td><td></td></tr>
                <tr><td><strong>Total Lainnya</strong></td><td></td><td class="amount">-</td></tr>
                <tr><td><strong>Total Aset</strong></td><td></td><td class="amount">230,313,822</td></tr>
                <tr><td colspan="2" class="category">Liabilitas and Modal</td><td class="amount">29/08/2025</td></tr>
                <tr><td>Liabilitas Jangka Pendek</td><td></td><td></td></tr>
                <tr><td>2-20100 Trade Payable</td><td></td><td class="amount">31,271,099</td></tr>
                <tr><td>2-20101 Unbilled Accounts Payable</td><td></td><td class="amount">10,486,170</td></tr>
                <tr><td>2-20201 Salaries Payable</td><td></td><td class="amount">58,559</td></tr>
                <tr><td>2-20500 VAT Out</td><td></td><td class="amount">14,396,757</td></tr>
                <tr><td>2-20502 Tax Payable - PPh 22</td><td></td><td class="amount">76,577</td></tr>
                <tr><td>2-20601 Other Current Liabilities</td><td></td><td class="amount">(23,423)</td></tr>
                <tr><td><strong>Total Liabilitas Jangka Pendek</strong></td><td></td><td class="amount">56,265,738</td></tr>
                <tr><td>Liabilitas Jangka Panjang</td><td></td><td></td></tr>
                <tr><td><strong>Total Liabilitas Jangka Panjang</strong></td><td></td><td class="amount">-</td></tr>
                <tr><td>Perubahan Modal</td><td></td><td></td></tr>
                <tr><td>3-30000 Paid In Capital</td><td></td><td class="amount">154,967,568</td></tr>
                <tr><td>3-30001 Additional Paid In Capital</td><td></td><td class="amount">48,649</td></tr>
                <tr><td>3-30999 Opening Balance</td><td></td><td class="amount">(75,676)</td></tr>
                <tr><td>Pendapatan periode ini</td><td></td><td class="amount">19,107,544</td></tr>
                <tr><td><strong>Total Perubahan Modal</strong></td><td></td><td class="amount">174,048,084</td></tr>
                <tr><td><strong>Total Liabilitas and Modal</strong></td><td></td><td class="amount">230,313,822</td></tr>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.balek').on('click', function(e) {
        e.preventDefault(); // Mencegah reload
        var url = $(this).attr('href');
        history.pushState(null, '', url);
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('.app-container.container-xxl').html(data);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan error yang lebih ramah pengguna
                console.error('Gagal memuat halaman:', error);
                alert('Gagal memuat halaman. Silakan coba lagi.');
            }
        });
    });
});
</script>