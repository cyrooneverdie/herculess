<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap5\Modal;
use yii\helpers\Json;
use yii\web\YiiAsset;
use backend\assets\AppAsset;

$this->title = 'Kledo | Asset Tetap';
$this->params['breadcrumbs'][] = ['label' => 'Asset Tetap', 'url' => ['index']];
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="asset-tetap-index">
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'beranda' ? 'active' : '' ?>" href="<?= Url::to(['beranda']) ?>">
                    <i class="fas fa-home"></i> Beranda
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'penjualan' ? 'active' : '' ?>" href="<?= Url::to(['penjualan']) ?>">
                    <i class="fas fa-shopping-cart"></i> Penjualan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'pembelian' ? 'active' : '' ?>" href="<?= Url::to(['pembelian']) ?>">
                    <i class="fas fa-truck"></i> Pembelian
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'biaya' ? 'active' : '' ?>" href="<?= Url::to(['biaya']) ?>">
                    <i class="fas fa-money-bill"></i> Biaya
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'produk' ? 'active' : '' ?>" href="<?= Url::to(['produk']) ?>">
                    <i class="fas fa-box"></i> Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'inventori' ? 'active' : '' ?>" href="<?= Url::to(['inventori']) ?>">
                    <i class="fas fa-warehouse"></i> Inventori
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'laporan' ? 'active' : '' ?>" href="<?= Url::to(['laporan']) ?>">
                    <i class="fas fa-chart-bar"></i> Laporan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'kas-bank' ? 'active' : '' ?>" href="<?= Url::to(['kas-bank']) ?>">
                    <i class="fas fa-piggy-bank"></i> Kas & Bank
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= Yii::$app->controller->action->id == 'akun' ? 'active' : '' ?>" href="<?= Url::to(['akun']) ?>">
                    <i class="fas fa-user"></i> Akun
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?= Url::to(['asset-tetap/index']) ?>">
                    <i class="fas fa-building"></i> Asset Tetap
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1><?= Html::encode($this->title) ?></h1>

        <!-- Warning Message -->
        <div class="alert alert-warning" role="alert">
            Data yang tampil saat ini adalah data dummy. Setelah Anda selesai, klik disini untuk mengosongkan data.
        </div>

        <!-- Tabs -->
        <ul class="nav nav-tabs" id="assetTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="nilai-asset-tab" data-bs-toggle="tab" data-bs-target="#nilai-asset" type="button" role="tab" aria-controls="nilai-asset" aria-selected="true">Nilai Asset</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="depresiasi-tab" data-bs-toggle="tab" data-bs-target="#depresiasi" type="button" role="tab" aria-controls="depresiasi" aria-selected="false">Depresiasi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="labarugi-tab" data-bs-toggle="tab" data-bs-target="#labarugi" type="button" role="tab" aria-controls="labarugi" aria-selected="false">Laba/Rugi Pelepasan Asset</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="asset-baru-tab" data-bs-toggle="tab" data-bs-target="#asset-baru" type="button" role="tab" aria-controls="asset-baru" aria-selected="false">Asset Baru</button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="assetTabContent">
            <!-- Nilai Asset Tab -->
            <div class="tab-pane fade show active" id="nilai-asset" role="tabpanel" aria-labelledby="nilai-asset-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>Nilai Asset</h5>
                                <p>34.000.000 Hari ini vs 365 hari lalu</p>
                                <!-- Placeholder for chart -->
                                <div id="nilai-asset-chart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5>10 Nilai Asset Tertinggi</h5>
                                <!-- Placeholder for chart -->
                                <div id="top-10-asset-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Depresiasi Tab -->
            <div class="tab-pane fade" id="depresiasi" role="tabpanel" aria-labelledby="depresiasi-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Depresiasi</h5>
                                <p>0 Tahun ini vs tanggal sama tahun lalu</p>
                                <!-- Placeholder for chart -->
                                <div id="depresiasi-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laba/Rugi Pelepasan Asset Tab -->
            <div class="tab-pane fade" id="labarugi" role="tabpanel" aria-labelledby="labarugi-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Untung Rugi Pelepasan Asset</h5>
                                <p>0 Tahun ini vs tanggal sama tahun lalu</p>
                                <!-- Placeholder for chart -->
                                <div id="labarugi-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Asset Baru Tab -->
            <div class="tab-pane fade" id="asset-baru" role="tabpanel" aria-labelledby="asset-baru-tab">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Asset Baru</h5>
                                <p>28.000.000 Tahun ini vs tanggal sama tahun lalu</p>
                                <!-- Placeholder for chart -->
                                <div id="asset-baru-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Table -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button class="btn btn-secondary btn-sm" id="filter-btn">Filter</button>
                        <button class="btn btn-primary btn-sm">Tambah</button>
                        <button class="btn btn-outline-secondary btn-sm">Import</button>
                    </div>
                    <div>
                        <input type="text" class="form-control" placeholder="Cari">
                    </div>
                </div>
                <ul class="nav nav-pills mb-3" id="assetStatus" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="draft-tab" data-bs-toggle="pill" data-bs-target="#draft" type="button" role="tab" aria-controls="draft" aria-selected="false">Draft</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="terdaftar-tab" data-bs-toggle="pill" data-bs-target="#terdaftar" type="button" role="tab" aria-controls="terdaftar" aria-selected="true">Terdaftar</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="terjual-dilepas-tab" data-bs-toggle="pill" data-bs-target="#terjual-dilepas" type="button" role="tab" aria-controls="terjual-dilepas" aria-selected="false">Terjual/Dilepas</button>
                    </li>
                </ul>

                <div class="tab-content" id="assetStatusContent">
                    <div class="tab-pane fade" id="draft" role="tabpanel" aria-labelledby="draft-tab">
                        <!-- Draft content -->
                    </div>
                    <div class="tab-pane fade show active" id="terdaftar" role="tabpanel" aria-labelledby="terdaftar-tab">
                        <table class="table table-dark">
                            <thead>
                                <tr>
                                    <th>Nama Asset</th>
                                    <th>Nomor</th>
                                    <th>Referensi</th>
                                    <th>Tanggal Pembelian</th>
                                    <th>Harga Beli</th>
                                    <th>Nilai Buku</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Wisma Kantor BDG</td>
                                    <td>FA-00001</td>
                                    <td></td>
                                    <td>02/10/2024</td>
                                    <td>6.000.000</td>
                                    <td>6.000.000</td>
                                </tr>
                                <tr>
                                    <td>Mobil Dinas B1299BK</td>
                                    <td>FA-00002</td>
                                    <td></td>
                                    <td>27/01/2025</td>
                                    <td>28.000.000</td>
                                    <td>28.000.000</td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td colspan="3"></td>
                                    <td>34.000.000</td>
                                    <td>34.000.000</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between">
                            <span>Total 2 data</span>
                            <span>15/halaman</span>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="terjual-dilepas" role="tabpanel" aria-labelledby="terjual-dilepas-tab">
                        <!-- Terjual/Dilepas content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS for Charts (Placeholder) -->
<style>
    .sidebar {
        width: 250px;
        position: fixed;
        height: 100%;
        background-color: #1a1a1a;
        padding-top: 20px;
    }
    .sidebar .nav-item .nav-link {
        color: #ffffff;
        padding: 10px 15px;
    }
    .sidebar .nav-item .nav-link.active {
        background-color: #2d2d2d;
        color: #4dabf7;
    }
    .main-content {
        margin-left: 260px;
        padding: 20px;
    }
    .card {
        background-color: #2d2d2d;
        border: 1px solid #404040;
        color: #ffffff;
    }
    .table-dark {
        background-color: #2d2d2d;
        color: #ffffff;
    }
    .nav-pills .nav-link.active {
        background-color: #4dabf7;
        color: #ffffff;
    }
</style>

<!-- JavaScript for Charts (Placeholder) -->

<script>
    // Placeholder for chart initialization (e.g., using Chart.js)
    // You would replace this with actual chart data and configuration
    $(document).ready(function() {
        // Example chart initialization
        new Chart(document.getElementById('nilai-asset-chart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt'],
                datasets: [{
                    label: 'Nilai Asset',
                    data: [70, 60, 50, 40, 30, 20],
                    backgroundColor: '#4dabf7'
                }, {
                    label: 'Depresiasi',
                    data: [0, 0, 0, 0, 0, 0],
                    backgroundColor: '#ff6b6b'
                }]
            }
        });
    });
</script>