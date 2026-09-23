<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Invoice Calendar';
?>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card">
            <div class="card-header border-0 pt-6 sticky-top bg-white shadow-sm"
                style="top: var(--kt-app-header-height, 70px); z-index: 1010;">
                <div class="flex-shrink-0">
                    <h2 class="card-title fw-bold mb-1">Invoice Calendar</h2>
                    <!-- <div class="text-muted fs-7">Pantau status invoice dan pembayaran secara real-time</div> -->
                </div>
                <div
                    class="card-toolbar d-flex flex-wrap flex-md-nowrap gap-3 align-items-center ms-auto w-100 w-md-auto">
                    <?= Html::dropDownList('contact_id', null, [], [
                        'prompt' => 'Semua Client',
                        'class' => 'form-select form-select-sm client-filter w-100',
                        'style' => 'min-width:0; max-width:100%;',
                    ]) ?>

                    <div class="d-flex align-items-center border rounded px-2 flex-shrink-0">
                        <button type="button" class="btn btn-icon btn-sm" id="cal-prev">
                            <i class="fa fa-chevron-left fs-6"></i>
                        </button>
                        <span class="fw-semibold mx-2 text-nowrap" id="cal-current-label"
                            style="min-width:110px; text-align:center;">-</span>
                        <button type="button" class="btn btn-icon btn-sm" id="cal-next">
                            <i class="fa fa-chevron-right fs-6"></i>
                        </button>
                    </div>

                    <button type="button" class="btn btn-light-primary btn-sm flex-shrink-0" id="cal-today">Hari
                        ini</button>

                    <button type="button" class="btn btn-primary btn-sm flex-shrink-0" data-bs-toggle="modal"
                        data-bs-target="#invoice_filter_modal">
                        <i class="fa fa-filter me-2"></i>Filter
                    </button>
                </div>
            </div>

            <div class="card-body">

                <!-- Stat Cards -->
                <div class="row g-4 mb-6">
                    <div class="col-6 col-md">
                        <div class="card border h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="symbol symbol-45px bg-light-primary rounded me-4">
                                    <i class="fa fa-paper-plane fs-2 text-primary m-auto"></i>
                                </div>
                                <div>
                                    <div class="text-muted fs-7">Invoice Terkirim</div>
                                    <div class="fs-2 fw-bold" data-stat="terkirim_count">0</div>
                                    <div class="text-muted fs-8" data-stat="terkirim_amount">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md">
                        <div class="card border h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="symbol symbol-45px bg-light-success rounded me-4">
                                    <i class="fa fa-circle-check fs-2 text-success m-auto"></i>
                                </div>
                                <div>
                                    <div class="text-muted fs-7">Sudah DP</div>
                                    <div class="fs-2 fw-bold" data-stat="dp_count">0</div>
                                    <div class="text-muted fs-8" data-stat="dp_amount">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md">
                        <div class="card border h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="symbol symbol-45px bg-light-warning rounded me-4">
                                    <i class="fa fa-clock fs-2 text-warning m-auto"></i>
                                </div>
                                <div>
                                    <div class="text-muted fs-7">Jatuh Tempo</div>
                                    <div class="fs-2 fw-bold" data-stat="jatuhtempo_terlambat1_count">0</div>
                                    <div class="text-muted fs-8" data-stat="jatuhtempo_terlambat1_amount">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md">
                        <div class="card border h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="symbol symbol-45px bg-light-danger rounded me-4">
                                    <i class="fa fa-triangle-exclamation fs-2 text-danger m-auto"></i>
                                </div>
                                <div>
                                    <div class="text-muted fs-7">Bad Debt (&gt; 60 hari)</div>
                                    <div class="fs-2 fw-bold" data-stat="baddebt_count">0</div>
                                    <div class="text-muted fs-8" data-stat="baddebt_amount">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md">
                        <div class="card border h-100">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="symbol symbol-45px bg-light rounded me-4">
                                    <i class="fa fa-file-invoice fs-2 text-gray-700 m-auto"></i>
                                </div>
                                <div>
                                    <div class="text-muted fs-7">Total Invoice</div>
                                    <div class="fs-2 fw-bold" data-stat="total_count">0</div>
                                    <div class="text-muted fs-8" data-stat="total_amount">Rp 0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div class="d-flex flex-wrap gap-5 mb-6">
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-dot h-8px w-8px bg-primary me-2"></span>
                        <span class="fs-7">Terkirim</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-dot h-8px w-8px bg-success me-2"></span>
                        <span class="fs-7">Sudah DP</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-dot h-8px w-8px bg-warning me-2"></span>
                        <span class="fs-7">Jatuh Tempo (1-30 hari)</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-dot h-8px w-8px me-2" style="background-color:#f2833a"></span>
                        <span class="fs-7">Terlambat (31-60 hari)</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-dot h-8px w-8px bg-danger me-2"></span>
                        <span class="fs-7">Terlambat &gt; 60 hari (Bad Debt)</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="bullet bullet-dot h-8px w-8px bg-secondary me-2"></span>
                        <span class="fs-7">Lunas</span>
                    </div>
                </div>

                <div class="row g-6">
                    <!-- Calendar Grid -->
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead>
                                    <tr class="fw-bold text-muted bg-light text-center">
                                        <th>Sen</th>
                                        <th>Sel</th>
                                        <th>Rab</th>
                                        <th>Kam</th>
                                        <th>Jum</th>
                                        <th>Sab</th>
                                        <th>Min</th>
                                    </tr>
                                </thead>
                                <tbody id="calGrid">
                                    <!-- rendered by JS -->
                                </tbody>
                            </table>
                        </div>

                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mt-4 text-muted fs-7">
                            <div>
                                * Klik pada tanggal untuk melihat detail invoice
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div>
                                    Total Invoice Bulan Ini: <span class="fw-bold text-gray-800"
                                        id="footerMonthCount">0</span>
                                </div>

                                <span class="d-none d-md-inline text-gray-400">|</span>

                                <div>
                                    Total Nilai: <span class="fw-bold text-gray-800" id="footerMonthTotal">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Detail -->
                    <div class="col-lg-4">
                        <div class="card border h-100">
                            <div class="card-header">
                                <h3 class="card-title fw-bold" id="sideDateLabel">Pilih tanggal</h3>
                                <div class="card-toolbar">
                                    <span class="badge badge-light-primary" id="sideCount">0</span>
                                </div>
                            </div>
                            <div class="card-body" style="max-height:480px; overflow-y:auto;">
                                <div id="sideList">
                                    <div class="alert alert-light d-flex align-items-center p-4">
                                        <i class="fa fa-info-circle me-2 text-muted"></i>
                                        <span class="text-muted fst-italic">Klik salah satu tanggal pada kalender untuk
                                            melihat daftar invoice.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center" id="btnLihatSemuaWrapper" style="display:none;">
                                <a href="javascript:void(0)" id="btnLihatSemua" class="btn btn-light-primary w-100">
                                    Lihat Semua Invoice di Tanggal Ini
                                    <i class="fa fa-chevron-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="separator separator-dashed my-8"></div>

                <h6 class="fw-bold mb-5">Keterangan Status</h6>
                <div class="row g-4">
                    <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center mb-1">
                            <span class="bullet bullet-dot h-8px w-8px bg-primary me-2"></span>
                            <span class="fw-semibold fs-8">Terkirim</span>
                        </div>
                        <div class="text-muted fs-9 ms-5">Invoice sudah dikirim ke client</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center mb-1">
                            <span class="bullet bullet-dot h-8px w-8px bg-success me-2"></span>
                            <span class="fw-semibold fs-8">Sudah DP</span>
                        </div>
                        <div class="text-muted fs-9 ms-5">Client sudah melakukan pembayaran uang muka</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center mb-1">
                            <span class="bullet bullet-dot h-8px w-8px bg-warning me-2"></span>
                            <span class="fw-semibold fs-8">Jatuh Tempo (1-30 hari)</span>
                        </div>
                        <div class="text-muted fs-9 ms-5">Invoice telah jatuh tempo 1-30 hari</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center mb-1">
                            <span class="bullet bullet-dot h-8px w-8px me-2" style="background-color:#f2833a"></span>
                            <span class="fw-semibold fs-8">Terlambat (31-60 hari)</span>
                        </div>
                        <div class="text-muted fs-9 ms-5">Invoice terlambat 31-60 hari</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center mb-1">
                            <span class="bullet bullet-dot h-8px w-8px bg-danger me-2"></span>
                            <span class="fw-semibold fs-8">Terlambat &gt; 60 hari</span>
                        </div>
                        <div class="text-muted fs-9 ms-5">Invoice terlambat lebih dari 60 hari (Bad Debt)</div>
                    </div>
                    <div class="col-6 col-md-2">
                        <div class="d-flex align-items-center mb-1">
                            <span class="bullet bullet-dot h-8px w-8px bg-secondary me-2"></span>
                            <span class="fw-semibold fs-8">Lunas</span>
                        </div>
                        <div class="text-muted fs-9 ms-5">Invoice sudah lunas</div>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="invoice_filter_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Filter Invoice</h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label fw-semibold">Status</label>
                            <select id="status-filter" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="terkirim">Terkirim</option>
                                <option value="dp">Sudah DP</option>
                                <option value="jatuhtempo">Jatuh Tempo (1-30 hari)</option>
                                <option value="terlambat1">Terlambat (31-60 hari)</option>
                                <option value="baddebt">Bad Debt (&gt; 60 hari)</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="apply-filter">
                            <i class="ki-duotone ki-filter fs-3 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="invoice_detail_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Detail Invoice <span id="detNo"></span></h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span><span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body" id="invoiceDetailBody">
                        <div class="text-center py-10">
                            <span class="spinner-border spinner-border-sm text-primary"></span> Memuat detail...
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {

        const monthNames = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const statusMeta = {
            terkirim: { label: 'Terkirim', badge: 'badge-light-primary' },
            dp: { label: 'Sudah DP', badge: 'badge-light-success' },
            jatuhtempo: { label: 'Jatuh Tempo', badge: 'badge-light-warning' },
            terlambat1: { label: 'Terlambat', badge: 'badge-light-terlambat1' },
            baddebt: { label: 'Bad Debt', badge: 'badge-light-danger' },
            lunas: { label: 'Lunas', badge: 'badge-light-secondary' }
        };

        let calYear = new Date().getFullYear();
        let calMonth = new Date().getMonth() + 1;
        let invoicesByDate = {};

        function loadStatusFilter() {
            $('#status-filter').select2({
                width: '100%',
                dropdownParent: $('#invoice_filter_modal')
            });
        }

        function formatRupiah(num) {
            return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
        }

        function badgeClass(status) {
            const meta = statusMeta[status] || statusMeta.terkirim;
            if (status === 'terlambat1') {
                return 'badge';
            }
            return 'badge ' + meta.badge;
        }

        function renderBadge(status, text) {
            if (status === 'terlambat1') {
                return `<span class="badge" style="background-color:#fff1e6;color:#f2833a;">${text}</span>`;
            }
            const meta = statusMeta[status] || statusMeta.terkirim;
            return `<span class="badge ${meta.badge}">${text}</span>`;
        }

        function fetchInvoiceData() {
            $.ajax({
                url: '<?= Url::to(['site/getinvoicecalendar']) ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    year: calYear,
                    month: calMonth,
                    contact_id: $('.client-filter').val(),
                    status: $('#status-filter').val()
                },
                success: function (response) {
                    console.log('Raw response:', response);
                    invoicesByDate = response.invoicesByDate || {};
                    renderStats(response.summary || {});
                    renderCalendar(response);
                    resetSidebar();
                },
                error: function (xhr, status, error) {
                    console.error('Gagal memuat data invoice calendar:', error);
                }
            });
        }

        function renderStats(summary) {
            ['terkirim', 'dp', 'jatuhtempo_terlambat1', 'baddebt', 'total'].forEach(function (key) {
                const s = summary[key] || { count: 0, amount: 0 };
                $('[data-stat="' + key + '_count"]').text(s.count || 0);
                $('[data-stat="' + key + '_amount"]').text(formatRupiah(s.amount));
            });
            $('#footerMonthCount').text((summary.total || {}).count || 0);
            $('#footerMonthTotal').text(formatRupiah((summary.total || {}).amount));
        }

        function renderCalendar(response) {
            const daysInMonth = response.daysInMonth;
            const leadingCount = response.leadingCount;
            const daysInPrevMonth = response.daysInPrevMonth;
            const todayDay = response.todayDay;

            $('#cal-current-label').text(monthNames[calMonth] + ' ' + calYear);

            let html = '';
            let dow = 1; // 1 = Senin

            html += '<tr>';
            for (let i = leadingCount; i > 0; i--) {
                const d = daysInPrevMonth - i + 1;
                html += '<td class="text-start p-2 bg-light text-muted" style="height:100px;">' + d + '</td>';
                dow++;
            }

            for (let d = 1; d <= daysInMonth; d++) {
                if (dow > 7) { html += '</tr><tr>'; dow = 1; }

                const items = invoicesByDate[String(d)] || [];
                const isToday = todayDay === d;
                const cellClass = isToday ? 'bg-light-primary' : '';

                html += '<td class="text-start p-2 ' + cellClass + '" style="height:100px; cursor:pointer;" data-date="' + d + '">';
                html += '<div class="fw-bold mb-1">' + d + '</div>';

                if (items.length > 0) {
                    const first = items[0];
                    html += '<div class="mb-1">' + renderBadge(first.status, first.code) + '</div>';
                    if (items.length > 1) {
                        html += '<div class="text-muted fs-9">+' + (items.length - 1) + ' lainnya</div>';
                    }
                }
                html += '</td>';
                dow++;
            }

            while (dow <= 7) {
                html += '<td class="bg-light" style="height:100px;"></td>';
                dow++;
            }
            html += '</tr>';

            $('#calGrid').html(html);
        }

        function resetSidebar() {
            $('#sideDateLabel').text('Pilih tanggal');
            $('#sideCount').text('0');
            $('#sideList').html('<div class="alert alert-light d-flex align-items-center p-4"><i class="fa fa-info-circle me-2 text-muted"></i><span class="text-muted fst-italic">Klik salah satu tanggal pada kalender untuk melihat daftar invoice.</span></div>');
            $('#btnLihatSemuaWrapper').hide();
        }

        $(document).on('click', '#calGrid td[data-date]', function () {
            const day = $(this).data('date');
            const items = invoicesByDate[String(day)] || [];

            $('#sideDateLabel').text('Invoice - ' + day + ' ' + monthNames[calMonth] + ' ' + calYear);
            $('#sideCount').text(items.length);

            if (items.length === 0) {
                $('#sideList').html('<div class="alert alert-light d-flex align-items-center p-4"><i class="fa fa-info-circle me-2 text-muted"></i><span class="text-muted fst-italic">Tidak ada invoice pada tanggal ini.</span></div>');
                $('#btnLihatSemuaWrapper').hide();
                return;
            }

            let html = '';
            items.forEach(function (inv) {
                html += `
                <div class="border rounded p-4 mb-3 invoice-item" data-tranid="${inv.tranid}" style="cursor:pointer;">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <span class="fw-bold text-primary">${inv.code}</span>
                        ${renderBadge(inv.status, inv.badge || (statusMeta[inv.status] || statusMeta.terkirim).label)}
                    </div>
                    <div class="fs-7 text-muted mb-1">${inv.client || '-'}</div>
                    <div class="fw-semibold mb-1">Total: ${inv.amount || ''}</div>
                    <div class="fw-semibold mb-1">Bayar : ${inv.paid || ''}</div>
                    <div class="fs-8 text-muted">${inv.meta || ''}</div>
                    ${inv.extra ? '<div class="fs-8 text-danger">' + inv.extra + '</div>' : ''}
                </div>
            `;
            });

            $('#sideList').html(html);
            $('#btnLihatSemuaWrapper').show();
            $('#btnLihatSemua').attr('data-date', day);
        });


        $('#btnLihatSemua').on('click', function (e) {
            e.preventDefault();

            const day = $(this).attr('data-date');
            const dd = String(day).padStart(2, '0');
            const mm = String(calMonth).padStart(2, '0');
            const dateStr = dd + '-' + mm + '-' + calYear;
            const dateRange = dateStr + ' - ' + dateStr;

            window.location.assign('/sales/invoice?duedatefilter=' + encodeURIComponent(dateRange));
        });

        $('#cal-prev').on('click', function () {
            calMonth--;
            if (calMonth < 1) { calMonth = 12; calYear--; }
            fetchInvoiceData();
        });

        $('#cal-next').on('click', function () {
            calMonth++;
            if (calMonth > 12) { calMonth = 1; calYear++; }
            fetchInvoiceData();
        });

        $('#cal-today').on('click', function () {
            const now = new Date();
            calYear = now.getFullYear();
            calMonth = now.getMonth() + 1;
            fetchInvoiceData();
        });

        $('.client-filter').on('change', function () {
            fetchInvoiceData();
        });

        $('#apply-filter').on('click', function () {
            fetchInvoiceData();
            $('#invoice_filter_modal').modal('hide');
        });

        function selectContact(target, selection, type, positionid, label) {
            let $select = $(target).find(selection);

            $select.off('change.contact').off('select2:open.contact').select2({
                ajax: {
                    url: "<?= Url::to(['contact/select']) ?>",
                    type: "POST",
                    dataType: "json",
                    data: function (params) {
                        return {
                            contacttype: type,
                            positionid: positionid,
                            q: params.term,
                            page: params.page,
                            module: "<?= $module ?? 'purchase' ?>"
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
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateSelection: function (param) {
                    if (!param.id) {
                        return `Choose ${label}`;
                    }
                    return param.jobcompany;
                },
                templateResult: function (contact) {
                    if (!contact.id) {
                        return `Choose ${label}`;
                    }
                    if (contact.loading) {
                        return type === 'customer' ? contact.jobcompany : contact.text;
                    }
                    let html = `
                    <div class="d-flex align-items-start gap-2 py-1">
                        <div class="d-flex flex-column flex-grow-1 overflow-hidden">
                            <span class="fw-bold text-dark text-truncate" style="font-size:13px;">
                                ${type === 'customer' ? contact.jobcompany : contact.text}
                            </span>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                     `;

                    html += `
                            </div>
                        </div>
                    </div>
                `;
                    return $(html);

                },
                placeholder: `Choose ${label}`,
                allowClear: true,
                width: '100%',
                dropdownParent: $(target)
            });
        }

        selectContact($('#kt_app_content_container'), ".client-filter", 'customer', '', 'Customer');

        $(document).on('click', '.invoice-item', function () {
            const tranid = $(this).data('tranid');
            openInvoiceDetail(tranid);
        });

        function openInvoiceDetail(tranid) {
            $('#invoiceDetailBody').html('<div class="text-center py-10"><span class="spinner-border spinner-border-sm text-primary"></span> Memuat detail...</div>');
            $('#invoice_detail_modal').modal('show');

            $.ajax({
                url: '<?= Url::to(['site/getinvoicedetail']) ?>',
                type: 'GET',
                dataType: 'json',
                data: { id: tranid },
                success: function (res) {
                    if (!res.success) {
                        $('#invoiceDetailBody').html('<div class="alert alert-danger">' + res.message + '</div>');
                        return;
                    }
                    renderInvoiceDetail(res.header, res.items);
                },
                error: function () {
                    $('#invoiceDetailBody').html('<div class="alert alert-danger">Gagal memuat detail invoice.</div>');
                }
            });
        }

        function renderInvoiceDetail(h, items) {
            $('#detNo').text(h.tranno);
            const sisa = parseFloat(h.grandtotal) - parseFloat(h.totalpaid);

            let rows = '';
            (items || []).forEach(function (it) {
                rows += `
                <tr>
                    <td>${it.productname}</td>
                    <td class="text-center">${(it.qty)}</td>
                    <td class="text-center">${(it.freqvalue)}</td>
                    <td class="text-end">${formatRupiah(it.price)}</td>
                    <td class="text-end">${formatRupiah(it.itemsubtotaltax)}</td>
                </tr>`;
            });

            const html = `
            <div class="row mb-4">
                <div class="col-6">
                    <div class="text-muted fs-7">Client</div>
                    <div class="fw-bold">${h.contact_name || '-'}</div>
                    <div class="fw-bold">${h.jobcompany || ''}</div>
                    <div class="fw-bold">${h.contact_phone1 || ''}</div>
                </div>
                <div class="col-6 text-end">
                    <div class="text-muted fs-7">Invoice Date</div>
                    <div class="fw-semibold">${h.trandate || '-'}</div>
                    <div class="text-muted fs-7 mt-2">Invoice Duedate</div>
                    <div class="fw-semibold">${h.tranduedate || '-'}</div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr class="text-muted fs-7">
                            <th>Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Periode</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
            <div class="separator my-4"></div>
            <div class="d-flex justify-content-end">
                <div style="min-width:260px;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Total</span>
                        <span class="fw-bold">${formatRupiah(h.grandtotal)}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Sudah Dibayar</span>
                        <span class="fw-bold text-success">${formatRupiah(h.totalpaid)}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Sisa</span>
                        <span class="fw-bold text-danger">${formatRupiah(sisa)}</span>
                    </div>
                </div>
            </div>
            ${h.note ? '<div class="mt-4"><div class="text-muted fs-7">Catatan</div><div>' + h.note + '</div></div>' : ''}
        `;

            $('#invoiceDetailBody').html(html);
        }


        loadStatusFilter();
        fetchInvoiceData();
    });
</script>