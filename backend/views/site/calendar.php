<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Calendar Employee';
?>
<style>
    @media (max-width: 576px) {
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 8px;
        }
    }

    .fc .fc-list-table td {
        white-space: normal;
        word-break: break-word;
        vertical-align: top;
    }

    .fc .fc-list-event-title {
        white-space: normal;
    }

    .fc-view-harness {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .fc .fc-list-day-cushion {
        white-space: normal;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 4px;
    }
</style>

<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1"><?= Html::encode($this->title) ?></span>
                    <!-- <span class="text-muted mt-1 fw-semibold fs-7">Jadwal penugasan crew dan pengajuan cuti karyawan</span> -->
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-light-primary fw-bold" data-bs-toggle="modal"
                        data-bs-target="#filter_modal">
                        <i class="ki-duotone ki-filter fs-3 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        Filter Karyawan
                    </button>
                </div>
            </div>
            <div class="card-body pt-2">
                <div id="kt_calendar_app"></div>
            </div>
        </div>

        <div class="modal fade" id="kt_modal_view_event" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content rounded-4 shadow-lg">

                    <div class="modal-header border-0 pb-0 justify-content-between align-items-center">
                        <div class="d-flex align-items-center me-3">
                            <div class="symbol symbol-45px symbol-circle bg-light-primary me-3">
                                <!-- <i class="fa fa-calendar-check text-primary fs-3"></i> -->
                            </div>
                            <div>
                                <h4 class="modal-title fw-bolder text-gray-900 mb-0" data-kt-calendar="eventtype_title">
                                    -</h4>
                                <span class="fs-7 text-muted" data-kt-calendar="event_tranno">-</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </button>
                    </div>

                    <div class="modal-body pt-5 pb-7 px-lg-10">
                        <div class="card bg-light-subtle border border-dashed border-gray-300 rounded-3 p-4 mb-5">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-user-tie fs-3 text-gray-500 me-3"></i>
                                <div class="d-flex flex-column">
                                    <span
                                        class="fs-8 text-gray-500 fw-semibold text-uppercase tracking-wide">Customer</span>
                                    <span class="fs-6 fw-bold text-gray-800" data-kt-calendar="event_contact">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light-primary border border-primary border-opacity-25 rounded-3 p-4 mb-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px symbol-circle bg-white me-4 shadow-sm">
                                    <i class="fa fa-user fs-3 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="fs-5 fw-bolder text-gray-900" data-kt-calendar="crew_name">-</span>
                                        <span class="badge badge-primary fw-bold"
                                            data-kt-calendar="crewtype_badge">-</span>
                                    </div>
                                    <div class="fs-7 text-gray-600" data-kt-calendar="crew_phone"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="fs-8 text-gray-500 fw-bolder text-uppercase tracking-wide mb-2 d-block">Waktu
                                Penugasan</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div
                                        class="bg-light-success rounded-3 p-3 text-center border border-success border-opacity-25">
                                        <span class="fs-8 fw-bold text-success d-block text-uppercase mb-1">Mulai</span>
                                        <span class="fs-7 fw-bolder text-gray-800"
                                            data-kt-calendar="crew_start_date">-</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div
                                        class="bg-light-danger rounded-3 p-3 text-center border border-danger border-opacity-25">
                                        <span
                                            class="fs-8 fw-bold text-danger d-block text-uppercase mb-1">Selesai</span>
                                        <span class="fs-7 fw-bolder text-gray-800"
                                            data-kt-calendar="crew_end_date">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start bg-light-subtle rounded-3 p-3 border border-gray-200">
                            <i class="fa fa-location-dot fs-3 text-danger me-3 mt-1"></i>
                            <div class="d-flex flex-column">
                                <span class="fs-8 text-gray-500 fw-semibold text-uppercase">Lokasi Penugasan</span>
                                <span class="fs-7 fw-bold text-gray-800" data-kt-calendar="event_location">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-sm btn-light fw-bold px-6"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="kt_modal_view_leave" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-600px">
                <div class="modal-content rounded-4 shadow-lg">

                    <div class="modal-header border-0 pb-0 justify-content-between align-items-center">
                        <div class="d-flex align-items-center me-3">
                            <div class="symbol symbol-45px symbol-circle bg-light-warning me-3">
                                <i class="fa fa-plane-departure text-warning fs-3"></i>
                            </div>
                            <div>
                                <h4 class="modal-title fw-bolder text-gray-900 mb-0" data-kt-calendar="leavetype_title">
                                    Pengajuan Cuti</h4>
                                <span class="fs-7 text-muted" data-kt-calendar="leave_no">-</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </button>
                    </div>

                    <div class="modal-body pt-5 pb-7 px-lg-10">
                        <div class="card bg-light-warning border border-warning border-opacity-25 rounded-3 p-4 mb-5">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-45px symbol-circle bg-white me-4 shadow-sm">
                                    <i class="fa fa-user-clock fs-3 text-warning"></i>
                                </div>
                                <div>
                                    <span class="fs-8 text-gray-500 fw-semibold text-uppercase d-block mb-1">Nama
                                        Karyawan</span>
                                    <span class="fs-5 fw-bolder text-gray-900" data-kt-calendar="full_name">-</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="fs-8 text-gray-500 fw-bolder text-uppercase tracking-wide mb-2 d-block">Periode
                                Cuti</label>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div
                                        class="bg-light-success rounded-3 p-3 text-center border border-success border-opacity-25">
                                        <span class="fs-8 fw-bold text-success d-block text-uppercase mb-1">Tanggal
                                            Mulai</span>
                                        <span class="fs-7 fw-bolder text-gray-800"
                                            data-kt-calendar="start_date">-</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div
                                        class="bg-light-danger rounded-3 p-3 text-center border border-danger border-opacity-25">
                                        <span class="fs-8 fw-bold text-danger d-block text-uppercase mb-1">Tanggal
                                            Selesai</span>
                                        <span class="fs-7 fw-bolder text-gray-800" data-kt-calendar="end_date">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-sm btn-light fw-bold px-6"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="filter_modal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-450px">
                <div class="modal-content rounded-4 shadow-lg">
                    <div class="modal-header border-0 pb-0 justify-content-between align-items-center">
                        <h4 class="modal-title fw-bolder text-gray-900"><?= Yii::$app->lang->t('extra', 'extra9') ?>
                        </h4>
                        <button type="button" class="btn btn-icon btn-sm btn-active-light-primary rounded-circle"
                            data-bs-dismiss="modal">
                            <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span
                                    class="path2"></span></i>
                        </button>
                    </div>
                    <div class="modal-body pt-4 pb-6 px-lg-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold fs-7 text-gray-700">Pilih Karyawan</label>
                            <select id="crew-filter" class="form-select form-select-solid">
                                <option value="">Semua Karyawan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-sm btn-light fw-bold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-primary fw-bold px-6" id="apply-filter">
                            <i class="ki-duotone ki-filter fs-4 me-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const viewEventModal = new bootstrap.Modal('#kt_modal_view_event');
        const viewLeaveModal = new bootstrap.Modal('#kt_modal_view_leave');
        const calendarEl = document.getElementById('kt_calendar_app');
        const crewFilter = $('#crew-filter');

        const eventTypeMap = {
            0: "Setup",
            1: "Event",
            2: "Bongkar",
            3: "Antar",
            4: "Tarik"
        };

        const crewTypeMap = {
            'pi': 'PIC',
            'op': 'Operator',
            'sb': 'Standby',
            'cr': 'Crew',
            'dr': 'Driver',
            'fe': 'Freelance'
        };

        const setContent = (selector, value, isHtml = false) => {
            const el = document.querySelector(selector);
            if (el) {
                isHtml ? (el.innerHTML = value) : (el.textContent = value);
            }
        };

        function loadCrewFilter() {
            $.ajax({
                url: '<?= Url::to(['site/getcrewlist']) ?>',
                type: 'GET',
                success: function (crews) {
                    crewFilter.html('<option value="">Semua Karyawan</option>');

                    if (Array.isArray(crews)) {
                        crews.forEach(crew => {
                            crewFilter.append(`<option value="${crew.crewid}">${crew.crew_name}</option>`);
                        });
                    }

                    initSelect2();
                },
                error: (xhr, status, error) => console.error('Error loading crew list:', error)
            });
        }

        function initSelect2() {
            if (crewFilter.hasClass("select2-hidden-accessible")) {
                crewFilter.select2('destroy');
            }

            crewFilter.select2({
                placeholder: 'Pilih Karyawan',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#filter_modal')
            }).trigger('change');
        }

        $('#filter_modal').on('shown.bs.modal', function () {
            initSelect2();
        });

        function populateViewModal(event) {
            const props = event.extendedProps;
            const startDate = moment(event.start);
            const endDate = event.end ? moment(event.end) : startDate;
            const eventTypeId = parseInt(props.eventtypeid);
            const eventTypeName = eventTypeMap[eventTypeId] || '-';

            setContent('[data-kt-calendar="eventtype_title"]', `${eventTypeName} - ${props.eventname || '-'}`);
            setContent('[data-kt-calendar="event_tranno"]', `No. Transaksi: ${props.tranno || '-'}`);
            setContent('[data-kt-calendar="event_contact"]', props.client_name || '-');

            setContent('[data-kt-calendar="crew_name"]', props.crew_name || 'Unknown Crew');
            setContent('[data-kt-calendar="crewtype_badge"]', props.crewtype_name || crewTypeMap[props.crewtype] || 'Crew');
            setContent('[data-kt-calendar="crew_phone"]', props.crew_phone ? `No. Telp: ${props.crew_phone}` : 'No. Telp: -');

            setContent('[data-kt-calendar="crew_start_date"]', startDate.format('DD MMM YYYY, HH:mm'));
            setContent('[data-kt-calendar="crew_end_date"]', endDate.format('DD MMM YYYY, HH:mm'));

            setContent('[data-kt-calendar="event_location"]', props.locations || 'Tidak Ada Lokasi');

            viewEventModal.show();
        }

        function populateViewModalLeave(event) {
            const props = event.extendedProps;
            const startDate = moment(event.start);
            const endDate = event.end ? moment(event.end) : startDate;

            setContent('[data-kt-calendar="leavetype_title"]', `Cuti: ${props.leavetype || '-'}`);
            setContent('[data-kt-calendar="leave_no"]', `ID Ref: ${props.leaveno || '-'}`);
            setContent('[data-kt-calendar="full_name"]', props.fullname || '-');
            setContent('[data-kt-calendar="start_date"]', startDate.format('DD MMMM YYYY'));
            setContent('[data-kt-calendar="end_date"]', endDate.format('DD MMMM YYYY'));

            viewLeaveModal.show();
        }

        function getResponsiveOptions() {
            const isMobile = window.innerWidth < 768;
            return {
                initialView: isMobile ? 'listMonth' : 'dayGridMonth',
                headerToolbar: isMobile
                    ? { left: 'prev,next', center: 'title', right: 'today' }
                    : { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' }
            };
        }

        const responsiveOptions = getResponsiveOptions();

        const calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'Asia/Jakarta',
            initialView: responsiveOptions.initialView,
            headerToolbar: responsiveOptions.headerToolbar,
            editable: false,
            selectable: true,
            dayMaxEvents: true,
            events: function (info, successCallback, failureCallback) {
                $.ajax({
                    url: '<?= Url::to(['site/getcrew']) ?>',
                    type: 'GET',
                    data: {
                        start: info.startStr,
                        end: info.endStr,
                        crewid: crewFilter.val()
                    },
                    success: response => {
                        console.log('Crew events loaded:', response);
                        successCallback(response);
                    },
                    error: (xhr, status, error) => {
                        console.error('Error loading crew events:', error);
                        failureCallback(error);
                    }
                });
            },
            eventClick: info => {
                const props = info.event.extendedProps;

                if (props.type === 'leave') {
                    populateViewModalLeave(info.event);
                } else {
                    populateViewModal(info.event);
                }
            }
        });

        calendar.render();
        loadCrewFilter();

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

        crewFilter.on('change', () => calendar.refetchEvents());
    });
</script>