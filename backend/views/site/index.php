<?php


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = ' Dashboard';

$dataPerMonth = array_fill(1, 12, 0); // Inisialisasi 12 bulan

$dataPerMonth = [50, 80, 120, 90, 60, 30, 20, 40, 70, 100, 130, 150];
$data = array_values($dataPerMonth);
?>
<!--begin::Dashboard-->
<div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-2">
            <h5 class="text-dark font-weight-bold mt-2 mb-2 mr-5">Dashboard</h5>
            <div class="subheader-separator subheader-separator-ver mt-2 mb-2 mr-4 bg-gray-200"></div>
            <span class="text-muted font-weight-bold mr-4"></span>
        </div>
        <!--end::Info-->

        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <a href="#" class="btn btn-sm btn-light font-weight-bold mr-2" id="kt_dashboard_daterangepicker"
                data-toggle="tooltip" title="Select dashboard daterange" data-placement="left">
                <span class="text-muted font-size-base font-weight-bold mr-2"
                    id="kt_dashboard_daterangepicker_title">Today:</span>
                <span class="text-primary font-size-base font-weight-bolder"
                    id="kt_dashboard_daterangepicker_date"><?= date('M d, Y') ?></span>
            </a>
        </div>
        <!--end::Toolbar-->
    </div>
</div>

<div>
    <div class="row">
        <!-- CHART: Sales Stat -->
        <div class="col-xxl-4">
            <div class="card card-custom bg-gray-100 card-stretch gutter-b">
                <div class="card-header border-0 bg-danger py-5">
                    <h3 class="card-title font-weight-bolder text-white">Sales Stat</h3>
                    <div class="card-toolbar">
                        <div class="dropdown"></div>
                    </div>
                </div>
                <div class="card-body p-0 position-relative overflow-hidden">
                    <div class="card-rounded-bottom bg-danger" style="height: 200px; position: relative;">
                        <canvas id="salesStatChart" style="width: 110%; height: 110%; z-index: 1;"></canvas>
                    </div>

                    <!-- Chart.js Script -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <!-- <div class="card-spacer mt-10"> -->
                    <div class="row m-0">
                        <div class="col bg-light-warning px-6 py-8 rounded-xl mr-7 mb-7">
                            <a href="#" class="text-warning font-weight-bold font-size-h6">Penjualan</a>
                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2" id="totalPenjualanValue">
                                Rp <?= number_format($totalPenjualan ?? 0, 0, ',', '.') ?>
                            </span>
                            <span class="text-warning font-size-sm" id="totalTransValue"><?= $totalTrans ?? 0 ?> Transaksi</span>
                        </div>
                        <div class="col bg-light-primary px-6 py-8 rounded-xl mb-7">
                            <a href="#" class="text-primary font-weight-bold font-size-h6 mt-2">Pengguna</a>
                            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2"><?= $totalUsers ?? 0 ?></span>
                        </div>
                    </div>
                    <div class="row m-0">
                        <div class="col bg-light-danger px-6 py-8 rounded-xl mr-7">
                            <a href="#" class="text-danger font-weight-bold font-size-h6 mt-2">Kas</a>
                            <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                Rp <?= number_format($totalKas ?? 0, 0, ',', '.') ?>
                            </span>
                            <span class="text-danger font-size-sm"></span>
                        </div>
                        <div class="col bg-light-success px-6 py-8 rounded-xl">
                            <a href="#" class="text-success font-weight-bold font-size-h6 mt-2">Kontak</a>
                            <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2"><?= $totalContacts ?? 0 ?></span>
                        </div>
                    </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
        <!-- END CHART -->

        <!-- Top 5 Customer -->
        <div class="col-xxl-4">
            <div class="card card-custom card-stretch gutter-b">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Top 5 Customer</span>
                        <span class="text-muted mt-3 font-weight-bold font-size-sm"></span>
                    </h3>
                    <div class="card-toolbar">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <i class="fas fa-trophy text-warning"></i>
                        </span>
                    </div>
                </div>
                <div class="card-body pt-0 pb-3">
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table id="top5CustomerTable"
                                class="table table-head-custom table-vertical-center table-borderless">
                                <thead>
                                    <tr class="text-left text-uppercase">
                                        <th style="min-width: 50px" class="pl-7">
                                            <span class="text-dark-75">Rank</span>
                                        </th>
                                        <th style="min-width: 200px">Customer</th>
                                    </tr>
                                </thead>
                                <tbody id="top5CustomerBody">
                                    <!-- Data akan diisi melalui JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Top 5 Customer -->

        <!-- Summary -->
        <!-- <div class="col-xxl-4">
            <div class="card card-custom gutter-b">
                <div class="card-header border-0">
                    <h3 class="card-title font-weight-bolder text-dark">Summary</h3>
                    <div class="card-toolbar">
                        <div class="dropdown b-dropdown btn-group" id="__BVID__68"></div>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 mr-5 symbol-light-primary">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-lg svg-icon-primary">
                                    <svg version="1.1" viewBox="0 0 24 24" height="24px" width="24px"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title>Library</title>
                                        <rect id="bound" x="0" y="0" width="24" height="24"></rect>
                                        <path
                                            d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z"
                                            fill="#000000"></path>
                                        <rect fill="#000000" opacity="0.3"
                                            transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519)"
                                            x="16.3255682" y="2.94551858" width="3" height="18" rx="1"></rect>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a class="text-dark text-hover-primary mb-1 font-size-lg">Total Sale</a>
                            <span id="totalpayment" class="text-muted"> </span>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 mr-5 symbol-light-success">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-lg svg-icon-success">
                                    <svg version="1.1" viewBox="0 0 24 24" height="24px" width="24px"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title>Write</title>
                                        <rect id="bound" x="0" y="0" width="24" height="24"></rect>
                                        <path
                                            d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z"
                                            fill="#000000" fill-rule="nonzero"
                                            transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953)">
                                        </path>
                                        <path
                                            d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z"
                                            fill="#000000" fill-rule="nonzero" opacity="0.3"></path>
                                    </svg>
                                </span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a class="text-dark text-hover-primary mb-1 font-size-lg">Total Rent</a>
                            <span id="totalshipment" class="text-muted"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-custom gutter-b"></div>
        </div> -->

        <div class="col-xxl-12 order-1 order-xxl-2"></div>
    </div>
</div>

<!-- JavaScript (Tetap Utuh + Tambahan Top 5 Customer) -->
<script type="text/javascript">
    $(document).ready(function() {
        let currentStartDate = moment().format('YYYY-MM-DD');
        let currentEndDate = moment().format('YYYY-MM-DD');
        let salesChart = null;

        // Initialize daterangepicker
        $('#kt_dashboard_daterangepicker').daterangepicker({
            opens: 'left',
            startDate: moment(),
            endDate: moment(),
            ranges: {
                'Hari Ini': [moment(), moment()],
                'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
                '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'MMM D, YYYY',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                customRangeLabel: 'Rentang Kustom'
            }
        }, function(start, end, label) {
            // Update title berdasarkan label
            let titleText = '';
            if (label === 'Hari Ini') titleText = 'Hari Ini:';
            else if (label === 'Kemarin') titleText = 'Kemarin:';
            else if (label === '7 Hari Terakhir') titleText = '7 Hari Terakhir:';
            else if (label === '30 Hari Terakhir') titleText = '30 Hari Terakhir:';
            else if (label === 'Bulan Ini') titleText = 'Bulan Ini:';
            else if (label === 'Bulan Lalu') titleText = 'Bulan Lalu:';
            else titleText = 'Periode:';

            $('#kt_dashboard_daterangepicker_title').html(titleText);
            $('#kt_dashboard_daterangepicker_date').html(start.format('MMM D') + ' - ' + end.format('MMM D'));

            currentStartDate = start.format('YYYY-MM-DD');
            currentEndDate = end.format('YYYY-MM-DD');

            // Reload data
            loadSalesData(currentStartDate, currentEndDate);
            loadTop5Customer(currentStartDate, currentEndDate);
        });

        // Load Sales Chart dengan filter tanggal
        function loadSalesChart(startDate, endDate) {
            $.ajax({
                url: '<?= Url::to(['site/saleschartdata']) ?>',
                type: 'GET',
                data: {
                    start: startDate,
                    end: endDate
                },
                dataType: 'json',
                beforeSend: function() {
                    // Tampilkan loading
                    if (salesChart) {
                        salesChart.destroy();
                    }
                },
                success: function(response) {
                    console.log('Chart Data Response:', response); // DEBUG

                    const ctx = document.getElementById('salesStatChart').getContext('2d');

                    if (salesChart) {
                        salesChart.destroy();
                    }

                    // VALIDASI: Pastikan ada data
                    if (!response.labels || !response.data || response.labels.length === 0) {
                        console.warn('No data for chart');
                        response = {
                            labels: ['No Data'],
                            data: [0]
                        };
                    }

                    salesChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: response.labels,
                            datasets: [{
                                label: 'Total Penjualan',
                                data: response.data,
                                borderColor: '#fff',
                                backgroundColor: 'rgba(255, 77, 79, 0.3)',
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#ff4d4f',
                                pointBorderColor: '#fff',
                                pointRadius: 5,
                                pointHoverRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    top: 10,
                                    bottom: 20,
                                    left: 10,
                                    right: 10
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        },
                                        color: '#ffffff'
                                    },
                                    grid: {
                                        color: 'rgba(255,255,255,0.1)'
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: '#ffffff'
                                    },
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        color: '#ffffff'
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            label += 'Rp ' + Number(context.parsed.y).toLocaleString('id-ID');
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Chart AJAX Error:', status, error);
                    console.error('Response:', xhr.responseText);
                }
            });
        }

        // Load Sales Data (total penjualan dan transaksi)
        function loadSalesData(startDate, endDate) {
            $.ajax({
                url: '<?= Url::to(['site/salesdata']) ?>',
                type: 'GET',
                data: {
                    start: startDate,
                    end: endDate
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#totalPenjualanValue').html('Rp ' + Number(response.data.total_penjualan).toLocaleString('id-ID'));
                        $('#totalTransValue').html(response.data.total_transaksi + ' Transaksi');
                    }
                },
                error: function() {
                    console.error('Gagal memuat data penjualan');
                }
            });

            loadSalesChart(startDate, endDate);
        }

        // ============================================
        // KODE LAMA (DICOMMENT KARENA DIGANTI YANG BARU)
        // ============================================
        // function loadTopcustomer(startDate, endDate) {
        //     $.ajax({
        //         url: '/prealert/vloadtopcustomer',
        //         method: 'GET',
        //         data: {
        //             start_date: startDate,
        //             end_date: endDate
        //         },
        //         success: function(data) {
        //             renderTable(data);
        //         },
        //         error: function(error) {
        //             console.error("Error fetching top customer:", error);
        //         }
        //     });
        // }

        // function renderTable(data) {
        //     if ($.fn.DataTable.isDataTable('#myTable')) {
        //         $('#myTable').DataTable().clear().destroy();
        //     }

        //     data.forEach(function(row, index) {
        //         row['no'] = index + 1;
        //     });

        //     $('#myTable').DataTable({
        //         data: data,
        //         columns: [{
        //                 title: "No",
        //                 data: "no"
        //             },
        //             {
        //                 title: "Name",
        //                 data: "name"
        //             },
        //             {
        //                 title: "Total Shipment",
        //                 data: "total_shipment",
        //                 render: d => d.toLocaleString()
        //             },
        //             {
        //                 title: "Total",
        //                 data: "total",
        //                 render: d => parseFloat(d).toLocaleString('en-US', {
        //                     minimumFractionDigits: 2,
        //                     maximumFractionDigits: 2
        //                 })
        //             }
        //         ],
        //         searching: false,
        //         paging: false,
        //         info: false,
        //         order: [
        //             [3, 'desc']
        //         ]
        //     });
        // }

        // ============================================
        // KODE BARU: Load Top 5 Customer by Point
        // ============================================
        function loadTop5Customer(startDate, endDate) {
            $.ajax({
                url: '<?= Url::to(['/site/topcustomer']) ?>',
                type: 'GET',
                data: {
                    start: startDate,
                    end: endDate
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#top5CustomerBody').html(`
                        <tr>
                            <td colspan="2" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </td>
                        </tr>
                    `);
                },
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        let html = '';

                        response.data.forEach(function(customer, index) {
                            let rankIcon = '';
                            if (index === 0) {
                                rankIcon = '<i class="fas fa-trophy text-warning fa-2x"></i>';
                            } else if (index === 1) {
                                rankIcon = '<i class="fas fa-medal text-secondary fa-2x"></i>';
                            } else if (index === 2) {
                                rankIcon = '<i class="fas fa-medal text-danger fa-2x"></i>';
                            } else {
                                rankIcon = `<span class="font-weight-bolder text-dark font-size-h3">${index + 1}</span>`;
                            }

                            let totalBelanja = new Intl.NumberFormat('id-ID', {
                                style: 'currency',
                                currency: 'IDR',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(customer.total_belanja || 0);

                            html += `
                                <tr>
                                    <td class="pl-7">
                                        <span class="d-flex align-items-center justify-content-center">
                                            ${rankIcon}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark-75 font-weight-bolder d-block font-size-h6">
                                            ${customer.contact_name || '-'}
                                        </span>
                                        <span class="text-muted font-weight-bold font-size-sm">
                                            ${customer.total_transaksi || 0} Transaksi
                                        </span>
                                        <span class="text-primary font-weight-bold font-size-sm d-block">
                                            ${totalBelanja}
                                        </span>
                                    </td>
                                </tr>
                            `;
                        });

                        $('#top5CustomerBody').html(html);
                    } else {
                        $('#top5CustomerBody').html(`
                            <tr>
                                <td colspan="2" class="text-center text-muted py-10">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <p class="font-weight-bold">Belum ada data customer</p>
                                </td>
                            </tr>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading top 5 customer:', error);
                    $('#top5CustomerBody').html(`
                        <tr>
                            <td colspan="2" class="text-center text-danger py-10">
                                <i class="fas fa-exclamation-triangle fa-3x mb-3 d-block"></i>
                                <p class="font-weight-bold">Gagal memuat data customer</p>
                            </td>
                        </tr>
                    `);
                }
            });
        }

        // Refresh data setiap 5 menit (opsional)
        // setInterval(function() {
        //     loadTop5Customer();
        // }, 300000);

        function numberFormat(num, decimals = 2, decimalSeparator = '.', thousandsSeparator = ',') {
            let [integerPart, decimalPart] = num.toFixed(decimals).split('.');
            integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSeparator);
            return decimalPart ? `${integerPart}${decimalSeparator}${decimalPart}` : integerPart;
        }

        // Load data pertama kali
        loadTop5Customer(); // LOAD TOP 5 CUSTOMER BY POINT
        // loadTopcustomer(); // COMMENTED KARENA DIGANTI YANG BARU
        // loadTotal();
    });
</script>