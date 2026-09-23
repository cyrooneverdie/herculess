<?php

use yii\helpers\Html;
use yii\helpers\Url;

$formatnumber = ["ORD", "QU"];
$this->title = 'Calendar Event';
?>
<style>
    @media (max-width: 576px) {
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title fw-bold"><?= Yii::$app->lang->t('calendar', 'calendar1') ?></h2>
                <div class="card-toolbar d-flex gap-3 align-items-center flex-wrap">
                    <!-- <button type="button" class="btn btn-light-primary" data-bs-toggle="modal"
                        data-bs-target="#filter_modal">
                        <i class="fa fa-filter me-2"></i>
                        Filter
                    </button> -->
                </div>
            </div>
            <div class="card-body">
                <div id="kt_calendar_app"></div>
            </div>
        </div>

        <div class="modal fade" id="kt_modal_view_event" tabindex="-1" data-bs-focus="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content rounded-4 shadow-lg">

                    <div class="modal-header border-0 pb-0 justify-content-between align-items-center">
                        <div class="d-flex align-items-center">

                            <div>
                                <h4 class="modal-title fw-bolder text-gray-900 mb-0" data-kt-calendar="event_title">
                                </h4>
                                <span class="fs-7 text-muted" data-kt-calendar="event_tranno">-</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </button>
                    </div>
                    <div class="modal-body pt-5 pb-8 px-lg-10">

                        <div class="card bg-light-subtle border border-dashed border-gray-300 rounded-3 p-4 mb-6">
                            <div class="row g-3">
                                <div class="col-sm-6 d-flex align-items-center">
                                    <i class="fa fa-user-circle fs-3 text-gray-500 me-3"></i>
                                    <div class="d-flex flex-column">
                                        <span
                                            class="fs-8 text-gray-500 fw-semibold text-uppercase tracking-wide"><?= Yii::$app->lang->t('extra', 'extra54') ?></span>
                                        <span class="fs-6 fw-bold text-gray-800"
                                            data-kt-calendar="event_customer">-</span>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex align-items-center">
                                    <i class="fa fa-location-dot fs-3 text-gray-500 me-3"></i>
                                    <div class="d-flex flex-column">
                                        <span
                                            class="fs-8 text-gray-500 fw-semibold text-uppercase tracking-wide">Lokasi</span>
                                        <span class="fs-6 fw-bold text-gray-800"
                                            data-kt-calendar="event_location">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div class="d-flex justify-content-end">
                                <div id="link-detail" data-kt-calendar="event_project"></div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label
                                class="fs-7 text-gray-500 fw-bolder text-uppercase tracking-wide mb-3 d-block">Jadwal</label>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div
                                        class="bg-light-warning rounded-3 p-3 text-center border border-warning border-opacity-25 h-100">
                                        <span class="fs-8 fw-bold text-warning d-block text-uppercase mb-1">Setup</span>
                                        <span class="fs-7 fw-bolder text-gray-800"
                                            data-kt-calendar="event_setup_date">-</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div
                                        class="bg-light-info rounded-3 p-3 text-center border border-info border-opacity-25 h-100">
                                        <span class="fs-8 fw-bold text-info d-block text-uppercase mb-1">Start</span>
                                        <span class="fs-7 fw-bolder text-gray-800"
                                            data-kt-calendar="event_start_date">-</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div
                                        class="bg-light-success rounded-3 p-3 text-center border border-success border-opacity-25 h-100">
                                        <span
                                            class="fs-8 fw-bold text-success d-block text-uppercase mb-1">Complete</span>
                                        <span class="fs-7 fw-bolder text-gray-800"
                                            data-kt-calendar="event_complete_date">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <span
                                        class="fs-7 text-gray-500 fw-bolder text-uppercase tracking-wide"><?= Yii::$app->lang->t('extrasidebar', 'extrasidebar2') ?></span>
                                </div>
                            </div>
                            <div data-kt-calendar="event_products"></div>
                        </div>

                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-sm btn-light fw-bold"
                            data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="filter_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title"><?= Yii::$app->lang->t('extra', 'extra9') ?></h3>
                        <div class="btn btn-icon btn-sm btn-active-light-primary" data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-5">
                            <label
                                class="form-label fw-semibold"><?= Yii::$app->lang->t('calendar', 'eventtype1') ?></label>
                            <select id="eventtype-filter" class="form-select">
                                <option value=""><?= Yii::$app->lang->t('front_home', 'select') ?></option>
                                <option value="0">Setup</option>
                                <option value="1">Event</option>
                                <option value="2">Bongkar</option>
                                <option value="3">Antar</option>
                                <option value="4">Tarik</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal"><?= Yii::$app->lang->t('calendar', 'close') ?></button>
                        <button type="button" class="btn btn-primary" id="apply-filter">
                            <i class="ki-duotone ki-filter fs-3 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <?= Yii::$app->lang->t('calendar', 'applyfilter') ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_form_tran" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen-lg-down modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= Yii::$app->lang->t('cta_add', 'cta_add') ?> <?= $title ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-content" class="nopadding"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        const viewEventModalEl = document.getElementById('kt_modal_view_event');
        const viewEventModal = new bootstrap.Modal(viewEventModalEl);
        const calendarEl = document.getElementById('kt_calendar_app');
        const eventtypeFilter = document.getElementById('eventtype-filter');

        const safeSetContent = (modalEl, selector, value, isHtml = false) => {
            const el = modalEl.querySelector(selector);
            if (el) {
                isHtml ? (el.innerHTML = value) : (el.textContent = value);
            }
        };

        function loadEventTypeFilter() {
            $('#eventtype-filter').select2({
                placeholder: '<?= Yii::$app->lang->t('front_home', 'select') ?>',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#filter_modal')
            });
        }

        function populateViewModal(event) {
            const modal = viewEventModalEl;
            const props = event.extendedProps;

            safeSetContent(
                modal,
                '[data-kt-calendar="event_title"]',
                `${event.title}`
            );
            safeSetContent(modal, '[data-kt-calendar="event_tranno"]', '<?= Yii::$app->lang->t('tran', 'tran_no') ?> : ' + (props.tranno || '-'));
            safeSetContent(modal, '[data-kt-calendar="event_customer"]', (props.customer || 'Tidak ada customer'));
            safeSetContent(modal, '[data-kt-calendar="event_location"]', props.location || 'Tidak ada lokasi');

            const projectLinkHtml = `<a href="<?= Url::to(['tran/createtrack']) ?>?id=${props.tranid}" class="text-primary text-hover-primary"><?= Yii::$app->lang->t('contact', 'detail') ?></a>`;
            safeSetContent(modal, '[data-kt-calendar="event_project"]', projectLinkHtml, true);

            safeSetContent(modal, '[data-kt-calendar="event_setup_date"]', props.setupdate || '-');
            safeSetContent(modal, '[data-kt-calendar="event_start_date"]', props.trandate || '-');
            safeSetContent(modal, '[data-kt-calendar="event_complete_date"]', props.tranduedate || '-');

            let productsHtml = '';
            if (props.products && props.products.length > 0) {
                productsHtml = `
        <div class="border rounded-3 overflow-hidden">
            <div class="table-responsive style-scrollbar" style="max-height: 220px;">
                <table class="table table-row-dashed align-middle gs-4 gy-3 mb-0">
                    <thead class="bg-light">
                        <tr class="fw-bold fs-8 text-gray-500 text-uppercase">
                            <th><?= Yii::$app->lang->t('produk', 'label2') ?></th>
                            <th class="text-end min-w-80px"><?= Yii::$app->lang->t('produk', 'detail_qty') ?></th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-800 fs-7">
    `;

                props.products.forEach(product => {
                    let imgSrc = product.productpict ? product.productpict : `/uploads/produk/default.png`;

                    productsHtml += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-35px me-3">
                            <img src="${imgSrc}" class="rounded-2 object-fit-cover" alt="${product.productname}">
                        </div>
                        <span class="fw-bold text-gray-800">${product.productname}</span>
                    </div>
                </td>
                <td class="text-end">
                    <span class="badge badge-light-primary fw-bolder px-3 py-2 fs-8">${product.amount} ${product.unit || ''}</span>
                </td>
            </tr>
        `;
                });

                productsHtml += `
                    </tbody>
                </table>
            </div>
        </div>
    `;
            } else {
                productsHtml = `
        <div class="text-center p-5 bg-light-subtle rounded-3 border border-dashed">
            <i class="fa fa-box-open fs-2 text-muted mb-2 d-block"></i>
            <span class="text-gray-500 fs-7 italic">Belum ada item produk terdaftar</span>
        </div>
    `;
            }

            safeSetContent(modal, '[data-kt-calendar="event_products"]', productsHtml, true);

            viewEventModal.show();
            modal.setAttribute('data-event-id', event.id);
        }

        function getResponsiveOptions() {
            const isMobile = window.innerWidth < 768;
            return {
                initialView: isMobile ? 'listWeek' : 'dayGridMonth',
                headerToolbar: isMobile
                    ? { left: 'prev,next', center: 'title', right: 'today' }
                    : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' }
            };
        }

        let calendar;
        if (calendarEl) {
            const responsiveOptions = getResponsiveOptions();

            calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'Asia/Jakarta',
                initialView: responsiveOptions.initialView,
                headerToolbar: responsiveOptions.headerToolbar,
                editable: true,
                selectable: true,
                dayMaxEvents: true,

                events: function (info, successCallback, failureCallback) {
                    const selectedEventType = eventtypeFilter.value;

                    $.ajax({
                        url: '<?= Url::to(['site/getproject']) ?>',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            start: info.startStr,
                            end: info.endStr,
                            eventtypeid: selectedEventType
                        },
                        success: function (response) {
                            successCallback(response);
                        },
                        error: function (xhr, status, error) {
                            failureCallback(error);
                        }
                    });
                },

                eventClick: function (info) {
                    populateViewModal(info.event);
                },

                select: function (info) { }
            });

            calendar.render();
            loadEventTypeFilter();

            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    const opts = getResponsiveOptions();
                    if (calendar.view.type !== opts.initialView) {
                        calendar.changeView(opts.initialView);
                        calendar.setOption('headerToolbar', opts.headerToolbar);
                    }
                }, 200);
            });

            $('#apply-filter').on('click', function () {
                calendar.refetchEvents();
                $('#filter_modal').modal('hide');
            });

            $('#eventtype-filter').on('change', function () {
                calendar.refetchEvents();
            });
        }

        $(document).on('click', 'a[data-bs-toggle="modal"]', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const target = $(this).attr('data-bs-target');

            if (target === '#modal_form_tran') {
                $('#modal-content').html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Loading...</p></div>');

                const isEdit = url.includes('update') || url.includes('edit');
                const add = <?= json_encode(Yii::$app->lang->t('cta_add', 'cta_add')) ?>;
                const title = <?= json_encode($title) ?>;

                if (isEdit) {
                    $('.modal-title').text(`Edit ${title}`);
                } else {
                    $('.modal-title').text(`${add} ${title}`);
                }

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        penomoran: <?= json_encode($formatnumber) ?>
                    },
                    success: function (data) {
                        let parser = new DOMParser();
                        let doc = parser.parseFromString(data, 'text/html');
                        $('#modal-content').html(doc.body.innerHTML);
                        $(target).find('.modal-dialog').css('max-width', '95%');
                    },
                    error: function () {
                        $('#modal-content').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle me-2"></i>Error loading form.</div>');
                    }
                });
            }

            $(target).modal('show');
        });

    });
</script>