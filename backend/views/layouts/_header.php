<?php

use yii\helpers\Url;

$userId = Yii::$app->user->id;
$data = Yii::$app->db->createCommand("SELECT * FROM company WHERE userid = '$userId'")->queryAll();

$companyData = Yii::$app->session->get('company_data');

$activeCompanyExists = false;
if ($companyData) {
    foreach ($data as $c) {
        if ($c['companyid'] == $companyData['companyid']) {
            $activeCompanyExists = true;
            break;
        }
    }
}

$model = $activeCompanyExists ? $companyData : ($data[0] ?? null);

$roleId = Yii::$app->user->identity->role_id;
$roleName = Yii::$app->function->findByField("enum_name", "enums", " and enum_type='users_roles' and enum_no = '$roleId'");
?>
<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
    data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
    data-kt-sticky-animation="false" style="z-index: 1015;">
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-end"
        id="kt_app_header_container">
        <!--begin::Sidebar mobile toggle-->
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        </div>
        <!--begin::Mobile logo-->
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="<?= Url::to(['site/index']) ?>" class="d-lg-none">
                <img alt="Logo" src="<?= Yii::getAlias('@web') . "/assets/media/logos/rbg.png" ?>" class="h-30px" />
            </a>
        </div>

        <div class="d-flex align-items-center">
            <!-- Theme Toggle - Mobile version (icon only) -->
            <div class="d-lg-none">
                <div class="btn-group" role="group" aria-label="Theme toggle">
                    <input type="radio" class="btn-check" name="themeOptionsMobile" id="lightThemeMobile"
                        autocomplete="off">
                    <label class="btn btn-icon btn-outline-warning text-light" for="lightThemeMobile">
                        <i class="fa fa-sun text-light"></i>
                    </label>
                    <input type="radio" class="btn-check" name="themeOptionsMobile" id="darkThemeMobile"
                        autocomplete="off">
                    <label class="btn btn-icon btn-outline-dark" for="darkThemeMobile">
                        <i class="fa fa-moon"></i>
                    </label>
                </div>
            </div>

            <!-- Theme Toggle - Desktop version -->
            <div class="d-none d-lg-block">
                <div class="btn-group" role="group" aria-label="Theme toggle">
                    <input type="radio" class="btn-check" name="themeOptions" id="lightTheme" autocomplete="off">
                    <label class="btn btn-outline-warning text-light" for="lightTheme">
                        <i class="fa fa-sun text-light"></i> Light
                    </label>
                    <input type="radio" class="btn-check" name="themeOptions" id="darkTheme" autocomplete="off">
                    <label class="btn btn-outline-dark" for="darkTheme">
                        <i class="fa fa-moon"></i> Dark
                    </label>
                </div>
            </div>

            <!-- Company Dropdown -->
            <div class="company-dropdown-wrapper mt-n3">
                <?= $this->render('@backend/views/company/company', [
                    'model' => $model,
                    'data' => $data,
                    'roleName' => $roleName
                ]); ?>
            </div>

            <!-- Language Dropdown -->
            <div class="dropdown language-dropdown-wrapper">
                <button class="btn btn-flex btn-link dropdown-toggle" type="button" id="dropdown-language"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php
                    if (Yii::$app->language == "en") {
                        $icon = "united-states";
                        $languange = "EN";
                    } elseif (Yii::$app->language == "id") {
                        $icon = "indonesia";
                        $languange = "ID";
                    } ?>
                    <span class="me-2"><?= $languange ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end animate slideIn pe-3" aria-labelledby="dropdown-language">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['site/setlang', 'lang' => 'en']) ?>"
                            class="dropdown-item d-flex px-5">
                            <span class="symbol symbol-20px me-4">
                                <img class="rounded-1"
                                    src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/flags/united-states.svg"
                                    alt="US Flag" />
                            </span> <?= Yii::$app->lang->t('back_layout', 'chat9') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['site/setlang', 'lang' => 'id']) ?>"
                            class="dropdown-item d-flex px-5">
                            <span class="symbol symbol-20px me-4">
                                <img class="rounded-1"
                                    src="<?= Yii::$app->getUrlManager()->getBaseUrl() ?>/assets/media/flags/indonesia.svg"
                                    alt="ID Flag" />
                            </span> <?= Yii::$app->lang->t('back_layout', 'chat10') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <?php echo $this->render('__navbar.php'); ?>
    </div>
</div>

<style>
    /* Mobile specific styles */
    @media (max-width: 991.98px) {

        /* Adjust header items spacing */
        #kt_app_header_container>.d-flex {
            gap: 0.5rem;
        }

        /* Make theme toggle buttons smaller */
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Hide text in theme toggle on mobile */
        .btn-group .btn i {
            margin-right: 0 !important;
        }

        .btn-group .btn span {
            display: none;
        }

        /* Make dropdown buttons more compact */
        .dropdown-toggle {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Truncate long company names */
        .company-name {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
            display: inline-block;
            vertical-align: middle;
        }
    }

    /* Desktop specific styles */
    @media (min-width: 992px) {

        /* Show full theme toggle */
        .btn-group .btn span {
            display: inline;
        }

        /* More spacing between items */
        #kt_app_header_container>.d-flex {
            gap: 1rem;
        }
    }

    /* Animation for dropdowns */
    .animate {
        animation-duration: 0.3s;
        -webkit-animation-duration: 0.3s;
        animation-fill-mode: both;
        -webkit-animation-fill-mode: both;
    }

    @keyframes slideIn {
        0% {
            transform: translateY(3rem);
            opacity: 0;
        }

        100% {
            transform: translateY(4rem);
            opacity: 1;
        }
    }

    #kt_app_header {
        z-index: 1015 !important;
    }

    .slideIn {
        animation-name: slideIn;
    }

    .slideOut {
        -webkit-animation: slideOut 0.3s ease-out forwards;
        animation: slideOut 0.3s ease-out forwards;
    }

    /* Dark Mode Styles */
    body.dark-mode {
        background-color: #121212;
        color: #f1f1f1;
    }

    body.dark-mode a,
    body.dark-mode .btn {
        color: #f1f1f1;
    }

    body.dark-mode #kt_app_header {
        background-color: #1f1f1f !important;
        color: #f0f0f0;
        border-bottom: 1px solid #333;
    }

    body.dark-mode .app-navbar,
    body.dark-mode .app-toolbar {
        background-color: #1f1f1f !important;
        color: #f0f0f0;
    }

    body.dark-mode[data-kt-header-scroll="on"] #kt_app_header {
        background-color: #1f1f1f !important;
        transition: background-color 0.3s ease;
    }

    body.dark-mode .btn-outline-primary {
        color: #3699ff;
        border-color: #3699ff;
    }

    body.dark-mode .card {
        background-color: #1e1e1e;
        border-color: #2d2d2d;
    }

    body.dark-mode .card-header {
        background-color: #252525;
        border-bottom-color: #2d2d2d;
    }

    body.dark-mode .table {
        color: #f1f1f1;
    }

    body.dark-mode .table thead th {
        background-color: #252525;
        color: #f1f1f1;
    }

    body.dark-mode .table tbody tr {
        background-color: #1e1e1e;
    }

    body.dark-mode .table tbody tr:hover {
        background-color: #2d2d2d !important;
    }

    body.dark-mode .form-control,
    body.dark-mode .form-select {
        background-color: #252525;
        border-color: #3d3d3d;
        color: #f1f1f1;
    }

    body.dark-mode .dropdown-menu {
        background-color: #252525;
        border-color: #3d3d3d;
    }

    body.dark-mode .dropdown-item {
        color: #f1f1f1;
    }

    body.dark-mode .dropdown-item:hover {
        background-color: #3d3d3d;
    }

    body.dark-mode .modal-content {
        background-color: #1e1e1e;
        border-color: #2d2d2d;
    }

    body.dark-mode .modal-content {
        background-color: #1e1e1e;
        border-color: #2d2d2d;
        color: #f1f1f1;
    }

    body.dark-mode .modal-header {
        border-bottom-color: #2d2d2d;
    }

    body.dark-mode .modal-footer {
        border-top-color: #2d2d2d;
    }

    body.dark-mode .card-flush {
        background-color: #1e1e1e !important;
        border-color: #2d2d2d !important;
    }

    body.dark-mode .breadcrumb-item.text-muted {
        color: #a1a5b7 !important;
    }

    body.dark-mode .text-gray-900 {
        color: #f1f1f1 !important;
    }

    body.dark-mode .card-title.text-gray-800,
    body.dark-mode .text-gray-800,
    body.dark-mode .card-title {
        color: #f1f1f1 !important;
    }

    body.dark-mode .text-muted {
        color: #a1a5b7 !important;
    }

    body.dark-mode .alert {
        background-color: #252525;
        border-color: #3d3d3d;
    }

    body.dark-mode .alert-primary {
        background-color: rgba(54, 153, 255, 0.2);
        border-color: rgba(54, 153, 255, 0.3);
        color: #3699ff;
    }

    body.dark-mode .bg-light-primary {
        background-color: rgba(54, 153, 255, 0.2) !important;
    }

    body.dark-mode .page-title h1 {
        color: #f1f1f1 !important;
    }

    body.dark-mode .app-toolbar {
        background-color: #1e1e1e;
    }

    body.dark-mode .datatable table {
        --bs-table-bg: #1e1e1e;
        --bs-table-color: #f1f1f1;
    }

    body.dark-mode .select2-container--default .select2-selection--single {
        background-color: #252525;
        border-color: #3d3d3d;
        color: #f1f1f1;
    }

    body.dark-mode .select2-container--default .select2-results__option {
        background-color: #252525;
        color: #f1f1f1;
    }

    body.dark-mode .select2-container--default .select2-results__option--highlighted {
        background-color: #3699ff;
    }

    /* Dark Mode - Daterangepicker Fixes */
    body.dark-mode .daterangepicker {
        background-color: #1e1e1e !important;
        border-color: #3d3d3d !important;
        color: #f1f1f1 !important;
    }

    /* Calendar Header */
    body.dark-mode .daterangepicker .calendar-table thead {
        background-color: #252525 !important;
    }

    body.dark-mode .daterangepicker .calendar-table thead tr th {
        color: #f1f1f1 !important;
    }

    /* Calendar Body */
    body.dark-mode .daterangepicker .calendar-table {
        background-color: #1e1e1e !important;
        border-color: #3d3d3d !important;
    }

    body.dark-mode .daterangepicker .calendar-table tbody td {
        color: #f1f1f1 !important;
    }

    /* Disabled dates */
    body.dark-mode .daterangepicker td.off {
        color: #5e5e5e !important;
        background-color: transparent !important;
    }

    body.dark-mode .daterangepicker td.off.in-range {
        background-color: rgba(54, 153, 255, 0.1) !important;
    }

    /* Available dates */
    body.dark-mode .daterangepicker td.available {
        color: #f1f1f1 !important;
    }

    body.dark-mode .daterangepicker td.available:hover {
        background-color: #2d2d2d !important;
        border-color: #3699ff !important;
        color: #3699ff !important;
    }

    /* Active/Selected dates */
    body.dark-mode .daterangepicker td.active,
    body.dark-mode .daterangepicker td.active:hover {
        background-color: #3699ff !important;
        border-color: #3699ff !important;
        color: #ffffff !important;
    }

    /* In-range dates */
    body.dark-mode .daterangepicker td.in-range {
        background-color: rgba(54, 153, 255, 0.2) !important;
        color: #f1f1f1 !important;
    }

    /* Start and end date */
    body.dark-mode .daterangepicker td.start-date,
    body.dark-mode .daterangepicker td.end-date {
        background-color: #3699ff !important;
        color: #ffffff !important;
    }

    /* Today date */
    body.dark-mode .daterangepicker td.today {
        background-color: rgba(54, 153, 255, 0.15) !important;
    }

    body.dark-mode .daterangepicker td.today:before {
        border-bottom-color: #3699ff !important;
    }

    /* Month/Year selects */
    body.dark-mode .daterangepicker select.monthselect,
    body.dark-mode .daterangepicker select.yearselect {
        background-color: #252525 !important;
        color: #f1f1f1 !important;
        border-color: #3d3d3d !important;
    }

    /* Navigation arrows */
    body.dark-mode .daterangepicker .prev span,
    body.dark-mode .daterangepicker .next span {
        border-color: #f1f1f1 !important;
    }

    body.dark-mode .daterangepicker .prev:hover,
    body.dark-mode .daterangepicker .next:hover {
        background-color: #2d2d2d !important;
    }

    body.dark-mode .daterangepicker th.prev:hover span,
    body.dark-mode .daterangepicker th.next:hover span {
        border-color: #3699ff !important;
    }

    /* Ranges sidebar */
    body.dark-mode .daterangepicker .ranges {
        background-color: #252525 !important;
        border-right-color: #3d3d3d !important;
    }

    body.dark-mode .daterangepicker .ranges ul {
        background-color: #252525 !important;
    }

    body.dark-mode .daterangepicker .ranges li {
        color: #f1f1f1 !important;
        background-color: transparent !important;
    }

    body.dark-mode .daterangepicker .ranges li:hover {
        background-color: #2d2d2d !important;
        color: #3699ff !important;
    }

    body.dark-mode .daterangepicker .ranges li.active {
        background-color: #3699ff !important;
        color: #ffffff !important;
    }

    /* Buttons */
    body.dark-mode .daterangepicker .drp-buttons {
        border-top-color: #3d3d3d !important;
        background-color: #1e1e1e !important;
    }

    body.dark-mode .daterangepicker .drp-buttons .btn {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode .daterangepicker .drp-buttons .btn:hover {
        background-color: #2d2d2d !important;
        border-color: #3699ff !important;
    }

    body.dark-mode .daterangepicker .drp-buttons .btn-primary {
        background-color: #3699ff !important;
        border-color: #3699ff !important;
        color: #ffffff !important;
    }

    body.dark-mode .daterangepicker .drp-buttons .btn-primary:hover {
        background-color: #1e87f0 !important;
        border-color: #1e87f0 !important;
    }

    /* Input dalam daterangepicker */
    body.dark-mode .daterangepicker input[type="text"] {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        color: #f1f1f1 !important;
    }

    /* Week numbers jika ada */
    body.dark-mode .daterangepicker .calendar-table .week {
        color: #7e8299 !important;
    }

    /* Dropdown content */
    body.dark-mode .daterangepicker.dropdown-menu {
        background-color: #1e1e1e !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
    }

    /* Calendar labels */
    body.dark-mode .daterangepicker .drp-calendar {
        background-color: #1e1e1e !important;
    }

    body.dark-mode .daterangepicker .calendar-time {
        background-color: #252525 !important;
        border-top-color: #3d3d3d !important;
    }

    /* Time picker jika ada */
    body.dark-mode .daterangepicker .calendar-time select {
        background-color: #252525 !important;
        color: #f1f1f1 !important;
        border-color: #3d3d3d !important;
    }

    /* Custom input yang di header */
    body.dark-mode #kt_dashboard_daterangepicker {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode #kt_dashboard_daterangepicker:hover {
        background-color: #2d2d2d !important;
        border-color: #3699ff !important;
    }

    body.dark-mode #kt_dashboard_daterangepicker_title {
        color: #a1a5b7 !important;
    }

    body.dark-mode #kt_dashboard_daterangepicker_date {
        color: #3699ff !important;
    }

    /* Separator */
    body.dark-mode .daterangepicker .drp-calendar.left {
        border-right-color: #3d3d3d !important;
    }

    /* Light Mode - Pastikan tetap terlihat bagus */
    body:not(.dark-mode) .daterangepicker td.active,
    body:not(.dark-mode) .daterangepicker td.active:hover {
        background-color: #3699ff !important;
        color: #ffffff !important;
    }

    body:not(.dark-mode) .daterangepicker td.available:hover {
        background-color: #f1f3f9 !important;
        border-color: #3699ff !important;
    }

    body:not(.dark-mode) .daterangepicker .ranges li.active {
        background-color: #3699ff !important;
        color: #ffffff !important;
    }

    body:not(.dark-mode) .daterangepicker .ranges li:hover {
        background-color: #f1f3f9 !important;
        color: #3699ff !important;
    }

    /* Chart.js specific styles */
    body.dark-mode .chartjs-render-monitor {
        filter: brightness(0.9);
    }

    /* Chart tooltip in dark mode */
    body.dark-mode .chartjs-tooltip {
        background-color: #252525 !important;
        border: 1px solid #2d2d2d !important;
        color: #f1f1f1 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
    }

    body.dark-mode .chartjs-tooltip-key {
        background-color: #3699ff !important;
    }

    /* Chart legend in dark mode */
    body.dark-mode .chartjs-legend {
        color: #f1f1f1 !important;
    }

    /* Chart grid lines in dark mode */
    body.dark-mode .chartjs-grid-line {
        stroke: #2d2d2d !important;
    }

    /* Chart ticks in dark mode */
    body.dark-mode .chartjs-tick {
        color: #a1a5b7 !important;
    }

    /* ========== DASHBOARD DARK MODE STYLES ========== */

    /* Card styles for dark mode */
    body.dark-mode .card-custom,
    body.dark-mode .card {
        background-color: #1e1e1e !important;
        border-color: #2d2d2d !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode .card-header {
        background-color: #252525 !important;
        border-bottom-color: #2d2d2d !important;
    }

    body.dark-mode .card-title {
        color: #f1f1f1 !important;
    }

    /* Stat cards background colors */
    body.dark-mode .bg-light-warning {
        background-color: rgba(255, 199, 0, 0.1) !important;
        border: 1px solid rgba(255, 199, 0, 0.2) !important;
    }

    body.dark-mode .bg-light-primary {
        background-color: rgba(54, 153, 255, 0.1) !important;
        border: 1px solid rgba(54, 153, 255, 0.2) !important;
    }

    body.dark-mode .bg-light-danger {
        background-color: rgba(241, 65, 108, 0.1) !important;
        border: 1px solid rgba(241, 65, 108, 0.2) !important;
    }

    body.dark-mode .bg-light-success {
        background-color: rgba(80, 205, 137, 0.1) !important;
        border: 1px solid rgba(80, 205, 137, 0.2) !important;
    }

    /* Stat cards text colors */
    body.dark-mode .text-warning {
        color: #ffc700 !important;
    }

    body.dark-mode .text-primary {
        color: #3699ff !important;
    }

    body.dark-mode .text-danger {
        color: #f1416c !important;
    }

    body.dark-mode .text-success {
        color: #50cd89 !important;
    }

    /* Table styles for dark mode */
    body.dark-mode .table {
        --bs-table-bg: transparent;
        --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
        --bs-table-striped-color: #f1f1f1;
        --bs-table-active-bg: rgba(255, 255, 255, 0.1);
        --bs-table-hover-bg: rgba(255, 255, 255, 0.075);
        --bs-table-border-color: #2d2d2d;
        color: #f1f1f1;
        border-color: #2d2d2d;
    }

    body.dark-mode .table thead th {
        background-color: #252525;
        color: #f1f1f1;
        border-bottom-color: #2d2d2d !important;
    }

    body.dark-mode .table tbody td {
        border-bottom-color: #2d2d2d !important;
        color: #f1f1f1;
    }

    body.dark-mode .table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
    }

    /* Subheader in dark mode */
    body.dark-mode .subheader-solid {
        background-color: #1e1e1e !important;
        border-bottom: 1px solid #2d2d2d !important;
    }

    body.dark-mode .text-dark {
        color: #f1f1f1 !important;
    }

    body.dark-mode .text-muted {
        color: #a1a5b7 !important;
    }

    body.dark-mode .bg-gray-200 {
        background-color: #3d3d3d !important;
    }

    /* Button in header */
    body.dark-mode #kt_dashboard_daterangepicker {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode #kt_dashboard_daterangepicker:hover {
        background-color: #2d2d2d !important;
        border-color: #3699ff !important;
    }

    body.dark-mode #kt_dashboard_daterangepicker_title {
        color: #a1a5b7 !important;
    }

    body.dark-mode #kt_dashboard_daterangepicker_date {
        color: #3699ff !important;
    }

    /* Chart.js Dark Mode Support */
    body.dark-mode canvas#salesStatChart {
        filter: brightness(0.9);
    }

    /* Spinner in dark mode */
    body.dark-mode .spinner-border {
        border-color: #3699ff transparent #3699ff transparent !important;
    }

    /* Rounded cards in dark mode */
    body.dark-mode .rounded-xl {
        background-color: #252525 !important;
        border: 1px solid #2d2d2d !important;
    }

    /* Status indicator colors */
    body.dark-mode .text-dark-75 {
        color: #f1f1f1 !important;
    }

    /* SVG icons in dark mode */
    body.dark-mode .svg-icon-warning path {
        fill: #ffc700 !important;
    }

    body.dark-mode .svg-icon-primary path {
        fill: #3699ff !important;
    }

    body.dark-mode .svg-icon-danger path {
        fill: #f1416c !important;
    }

    body.dark-mode .svg-icon-success path {
        fill: #50cd89 !important;
    }

    /* Font weight adjustments for dark mode */
    body.dark-mode .font-weight-bolder,
    body.dark-mode .font-weight-bold {
        color: #f1f1f1 !important;
    }

    /* DARK MODE FIXES */
    body.dark-mode .form-control,
    body.dark-mode .form-control-solid,
    body.dark-mode .form-select {
        background-color: #2b2b2b !important;
        color: #e0e0e0 !important;
        border-color: #444 !important;
    }

    body.dark-mode .form-control::placeholder {
        color: #aaaaaa !important;
    }

    body.dark-mode .select2-container--bootstrap5 .select2-selection {
        background-color: #2b2b2b !important;
        color: #e0e0e0 !important;
        border-color: #444 !important;
    }

    body.dark-mode .menu-sub-dropdown {
        background-color: #1e1e1e !important;
        color: #e0e0e0 !important;
        border: 1px solid #333 !important;
    }

    body.dark-mode .menu-sub-dropdown .form-label,
    body.dark-mode .menu-sub-dropdown .form-select,
    body.dark-mode .menu-sub-dropdown .form-control {
        color: #e0e0e0 !important;
    }

    /* Dark Mode Styles for Specific Elements */
    body.dark-mode .btn-light-primary {
        background-color: rgba(63, 66, 84, 0.8) !important;
        border-color: rgba(63, 66, 84, 0.8) !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode .btn.btn-primary,
    body.dark-mode .btn.btn-danger,
    body.dark-mode .btn.btn-success {
        background-color: rgba(63, 66, 84, 0.8) !important;
        border-color: rgba(63, 66, 84, 0.8) !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode .btn-light-primary:hover {
        background-color: rgba(63, 66, 84, 1) !important;
        border-color: rgba(63, 66, 84, 1) !important;
    }

    body.dark-mode .modal-title,
    body.dark-mode h5.modal-title,
    body.dark-mode .modal-header h5 {
        color: #f1f1f1 !important;
    }

    /* Icon filter color in dark mode */
    body.dark-mode .ki-duotone.ki-filter path {
        fill: #f1f1f1 !important;
    }

    /* More specific heading selectors */
    body.dark-mode h1,
    body.dark-mode h2,
    body.dark-mode h3,
    body.dark-mode h4,
    body.dark-mode h5,
    body.dark-mode h6 {
        color: #f1f1f1 !important;
    }

    /* Button and link specific styles */
    body.dark-mode button,
    body.dark-mode .btn,
    body.dark-mode a {
        color: #f1f1f1 !important;
    }

    body.dark-mode .btn-primary {
        background-color: #3699ff !important;
        border-color: #3699ff !important;
    }

    body.dark-mode .btn-outline-primary {
        color: #3699ff !important;
        border-color: #3699ff !important;
    }

    body.dark-mode .btn-outline-primary:hover {
        background-color: #3699ff !important;
        color: #fff !important;
    }

    /* Dark Mode Styles for Sidebar */
    body.dark-mode #kt_app_sidebar {
        background-color: #1e1e1e !important;
        border-right: 1px solid #2d2d2d !important;
    }

    body.dark-mode .app-sidebar-logo {
        background-color: #1e1e1e !important;
        border-bottom: 1px solid #2d2d2d !important;
    }

    body.dark-mode .menu-item .menu-link {
        color: #f1f1f1 !important;
    }

    body.dark-mode .menu-item .menu-link:hover {
        background-color: #2d2d2d !important;
    }

    body.dark-mode .menu-item .menu-link.active {
        background-color: rgba(54, 153, 255, 0.2) !important;
        color: #3699ff !important;
    }

    body.dark-mode .menu-sub {
        background-color: #252525 !important;
    }

    body.dark-mode .menu-item .menu-link .menu-icon i {
        color: #f1f1f1 !important;
    }

    body.dark-mode .menu-item .menu-link.active .menu-icon i {
        color: #3699ff !important;
    }

    body.dark-mode .app-sidebar-toggle {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
    }

    body.dark-mode .ki-duotone path {
        fill: #f1f1f1 !important;
    }

    /* Icon colors in dark mode */
    body.dark-mode .fa {
        color: #f1f1f1 !important;
    }

    body.dark-mode .menu-title {
        color: #f1f1f1 !important;
    }

    /* Scrollbar styling for dark mode */
    body.dark-mode ::-webkit-scrollbar {
        width: 6px;
    }

    body.dark-mode ::-webkit-scrollbar-track {
        background: #252525;
    }

    body.dark-mode ::-webkit-scrollbar-thumb {
        background: #3d3d3d;
        border-radius: 3px;
    }

    body.dark-mode ::-webkit-scrollbar-thumb:hover {
        background: #4d4d4d;
    }

    /* Icons color in dark mode */
    body.dark-mode .fa-certificate,
    body.dark-mode .fa-gem,
    body.dark-mode .fa-crown,
    body.dark-mode .fa-check-circle,
    body.dark-mode .fa-circle-check,
    body.dark-mode .fw-medium.text-dark.fs-6 {
        color: #f1f1f1 !important;
    }

    body.dark-mode .text-black {
        color: #f1f1f1 !important;
    }

    body.dark-mode .text-gray-700,
    body.dark-mode .text-gray-500 {
        color: #a1a5b7 !important;
    }

    body.dark-mode s {
        color: #a1a5b7 !important;
    }

    body.dark-mode .bg-light {
        background-color: #252525 !important;
    }

    body.dark-mode .alert {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode .btn-outline-primary {
        color: #3699ff !important;
        border-color: #3699ff !important;
    }

    body.dark-mode .btn-outline-primary:hover {
        background-color: #3699ff !important;
        color: #fff !important;
    }

    /* Position absolute badges */
    body.dark-mode .position-absolute.bg-danger {
        background-color: #f1416c !important;
    }

    body.dark-mode .position-absolute.bg-success {
        background-color: #50cd89 !important;
    }

    body.dark-mode .nav-pills .nav-link {
        background-color: #252525 !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode .nav-pills .nav-link.active {
        background-color: #3699ff !important;
        color: #fff !important;
    }

    /* Perbaikan ikon di dark mode */
    body.dark-mode .custom:hover .fa-certificate,
    body.dark-mode .custom:hover .fa-gem,
    body.dark-mode .custom:hover .fa-crown {
        color: #3699ff !important;
    }

    body.dark-mode .list-group-item {
        background-color: #252525;
        border-color: rgba(255, 255, 255, 0.08);
        color: #f1f1f1;
    }

    body.dark-mode .list-group-item:hover {
        background-color: #2d2d2d;
    }

    body.dark-mode .list-group-item strong {
        color: #e9ecef;
    }

    body.dark-mode i.text-primary.subs-logo {
        color: #e9ecef !important;
        /* Warna terang untuk dark mode */
    }

    body.dark-mode .list-group-item .text-primary {
        color: #3699ff !important;
    }

    body.dark-mode .list-group-item .text-success {
        color: #50cd89 !important;
    }

    body.dark-mode .list-group-item .text-danger {
        color: #f1416c !important;
    }

    body.dark-mode .list-group-item .text-muted {
        color: #a1a5b7 !important;
    }

    /* Dark Mode Styles for Image Upload Area */
    body.dark-mode .image-upload-area {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        color: #e0e0e0 !important;
    }

    body.dark-mode .image-upload-area:hover {
        background-color: #2d2d2d !important;
        border-color: #3699ff !important;
    }

    body.dark-mode .upload-icon,
    body.dark-mode .upload-text,
    body.dark-mode .upload-hint {
        color: #a1a5b7 !important;
    }

    body.dark-mode .image-upload-area:hover .upload-icon,
    body.dark-mode .image-upload-area:hover .upload-text,
    body.dark-mode .image-upload-area:hover .upload-hint {
        color: #f1f1f1 !important;
    }

    body.dark-mode .remove-image {
        background-color: #3d3d3d !important;
        color: #f1f1f1 !important;
    }

    body.dark-mode .remove-image:hover {
        background-color: #f1416c !important;
        color: #fff !important;
    }

    body.dark-mode .btn.btn-light.me-3 {
        color: #1e1e1e !important;
    }

    /* Dark Mode Styles for Company Card */
    body.dark-mode .company-item .card.bg-secondary {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        transition: all 0.3s ease;
    }

    body.dark-mode .company-item:hover .card.bg-secondary {
        background-color: #2d2d2d !important;
        border-color: #3699ff !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(54, 153, 255, 0.2);
    }

    body.dark-mode .company-item h4,
    body.dark-mode .company-item h5 {
        color: #f1f1f1 !important;
    }

    body.dark-mode .company-item .text-success {
        color: #50cd89 !important;
    }

    body.dark-mode .company-item .text-danger {
        color: #f1416c !important;
    }

    body.dark-mode .company-item .text-secondary {
        color: #a1a5b7 !important;
    }

    /* Button Styles */
    body.dark-mode .company-item .btn {
        transition: all 0.2s ease;
    }

    body.dark-mode .company-item .btn-danger {
        background-color: #f1416c !important;
        border-color: #f1416c !important;
    }

    body.dark-mode .company-item .btn-success {
        background-color: #50cd89 !important;
        border-color: #50cd89 !important;
    }

    body.dark-mode .company-item .btn-primary {
        background-color: #3699ff !important;
        border-color: #3699ff !important;
    }

    body.dark-mode .company-item .btn-danger:hover {
        background-color: #d9214e !important;
        transform: scale(1.05);
    }

    body.dark-mode .company-item .btn-success:hover {
        background-color: #3fb876 !important;
        transform: scale(1.05);
    }

    body.dark-mode .company-item .btn-primary:hover {
        background-color: #1e87f0 !important;
        transform: scale(1.05);
    }

    /* Status Indicator */
    body.dark-mode .company-item .fa-circle.text-success {
        color: #50cd89 !important;
        text-shadow: 0 0 8px rgba(80, 205, 137, 0.5);
    }

    body.dark-mode .company-item .fa-circle.text-danger {
        color: #f1416c !important;
        text-shadow: 0 0 8px rgba(241, 65, 108, 0.5);
    }

    /* Logo Image */
    body.dark-mode .company-item img {
        filter: brightness(0.9);
        transition: all 0.3s ease;
    }

    body.dark-mode .company-item:hover img {
        filter: brightness(1.1);
        transform: scale(1.03);
    }

    body.dark-mode #kt_reset_password .modal-content {
        background-color: #1e1e1e;
        border: 1px solid #3d3d3d;
        color: #f1f1f1;
    }

    body.dark-mode #kt_reset_password .modal-header {
        background-color: #252525;
        border-bottom: 1px solid #3d3d3d;
    }

    body.dark-mode #kt_reset_password .modal-title {
        color: #f1f1f1 !important;
        font-weight: 600;
    }

    body.dark-mode #kt_reset_password .modal-body {
        background-color: #1e1e1e;
        padding: 1.5rem;
    }

    body.dark-mode #kt_reset_password .form-label {
        color: #e0e0e0 !important;
        margin-bottom: 0.5rem;
    }

    body.dark-mode #kt_reset_password .form-control {
        background-color: #252525 !important;
        border-color: #3d3d3d !important;
        color: #f1f1f1 !important;
        padding: 0.5rem 1rem;
    }

    body.dark-mode #kt_reset_password .btn-primary {
        background-color: rgba(63, 66, 84, 0.8) !important;
        border-color: rgba(63, 66, 84, 0.8) !important;
        color: white !important;
        transition: all 0.2s ease;
    }

    body.dark-mode #kt_reset_password .btn-secondary {
        background-color: rgba(63, 66, 84, 0.8) !important;
        border-color: rgba(63, 66, 84, 0.8) !important;
        color: white !important;
        transition: all 0.2s ease;
    }

    body.dark-mode button.btn.btn-light.btn-active-light-primary {
        background-color: rgba(63, 66, 84, 0.8) !important;
        border-color: rgba(63, 66, 84, 0.8) !important;
        color: white !important;
        transition: all 0.2s ease;
    }

    body.dark-mode label.col-lg-4.col-form-label.fw-semibold.fs-6,
    body.dark-mode div.fs-6.fw-bold.mb-1 {
        color: white !important;
    }

    /* Dark Mode Styles for Account Deactivation Card */
    /* Dark mode base background for card */

    /* Header title */
    body.dark-mode .card-title h3 {
        color: #f5f5f5;
    }

    /* Collapse area background */
    body.dark-mode .collapse {
        background-color: #1e1e1e;
        border-color: #2d2d2d;
    }

    /* Form labels, text */
    body.dark-mode .form-check-label,
    body.dark-mode .fw-semibold,
    body.dark-mode .fs-6,
    body.dark-mode .fs-6.text-gray-700 {
        color: #cfd2da !important;
    }

    /* Notice background and border */
    body.dark-mode .notice.bg-light-warning {
        background-color: #2c2a1f !important;
        border-color: #ffd700 !important;
        color: #f5e9a1;
    }

    /* Icon warna di dalam notice */
    body.dark-mode .notice .text-warning {
        color: #f1c40f !important;
    }

    /* Checkbox styling */
    body.dark-mode .form-check-input {
        background-color: #2a2a3c;
        border-color: #444;
    }

    body.dark-mode .form-check-input:checked {
        background-color: #e63946;
        border-color: #e63946;
    }

    /* Danger button tweak */
    body.dark-mode .btn.btn-danger {
        background-color: #e63946;
        border-color: #e63946;
        color: #fff;
    }

    body.dark-mode .btn.btn-danger:hover {
        background-color: #c92c3a;
    }

    body.dark-mode .swal2-title {
        color: #f5f5f5 !important;
    }

    body.dark-mode .swal2-html-container {
        color: #dcdcdc !important;
    }

    body.dark-mode .swal2-popup {
        background: #2a2a2a !important;
        border: 1px solid #444;
    }

    body.dark-mode .swal2-confirm,
    body.dark-mode .swal2-cancel {
        box-shadow: none !important;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const lightRadios = [document.getElementById("lightTheme"), document.getElementById("lightThemeMobile")];
        const darkRadios = [document.getElementById("darkTheme"), document.getElementById("darkThemeMobile")];

        const updateDashboardTheme = function () {
            if (typeof updateChartTheme === 'function') {
                setTimeout(() => {
                    updateChartTheme();
                }, 300);
            }

            if (typeof updateStatCardsTheme === 'function') {
                setTimeout(() => {
                    updateStatCardsTheme();
                }, 300);
            }

            if (typeof initDateRangePicker === 'function') {
                setTimeout(() => {
                    $('#kt_dashboard_daterangepicker').daterangepicker('destroy');
                    initDateRangePicker();
                }, 400);
            }
        };

        // Set initial state from localStorage
        const currentTheme = localStorage.getItem("theme");
        if (currentTheme === "dark") {
            document.body.classList.add("dark-mode");
            darkRadios.forEach(radio => {
                if (radio) radio.checked = true;
            });
        } else {
            lightRadios.forEach(radio => {
                if (radio) radio.checked = true;
            });
            localStorage.setItem("theme", "light");
        }

        // Event handlers for desktop toggles
        if (document.getElementById("lightTheme")) {
            document.getElementById("lightTheme").addEventListener("change", function () {
                document.body.classList.remove("dark-mode");
                localStorage.setItem("theme", "light");
                lightRadios.forEach(radio => {
                    if (radio) radio.checked = true;
                });
                darkRadios.forEach(radio => {
                    if (radio) radio.checked = false;
                });
                updateDashboardTheme();
            });

            document.getElementById("darkTheme").addEventListener("change", function () {
                document.body.classList.add("dark-mode");
                localStorage.setItem("theme", "dark");
                darkRadios.forEach(radio => {
                    if (radio) radio.checked = true;
                });
                lightRadios.forEach(radio => {
                    if (radio) radio.checked = false;
                });
                updateDashboardTheme();
            });
        }

        // Event handlers for mobile toggles
        if (document.getElementById("lightThemeMobile")) {
            document.getElementById("lightThemeMobile").addEventListener("change", function () {
                document.body.classList.remove("dark-mode");
                localStorage.setItem("theme", "light");
                lightRadios.forEach(radio => {
                    if (radio) radio.checked = true;
                });
                darkRadios.forEach(radio => {
                    if (radio) radio.checked = false;
                });
                updateDashboardTheme();
            });

            document.getElementById("darkThemeMobile").addEventListener("change", function () {
                document.body.classList.add("dark-mode");
                localStorage.setItem("theme", "dark");
                darkRadios.forEach(radio => {
                    if (radio) radio.checked = true;
                });
                lightRadios.forEach(radio => {
                    if (radio) radio.checked = false;
                });
                updateDashboardTheme();
            });
        }

        // Force dark mode if preference is dark
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches && !localStorage.getItem("theme")) {
            document.body.classList.add("dark-mode");
            darkRadios.forEach(radio => {
                if (radio) radio.checked = true;
            });
            localStorage.setItem("theme", "dark");
            setTimeout(() => {
                updateDashboardTheme();
            }, 500);
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (!localStorage.getItem("theme")) {
                if (event.matches) {
                    document.body.classList.add("dark-mode");
                    darkRadios.forEach(radio => {
                        if (radio) radio.checked = true;
                    });
                    updateDashboardTheme();
                } else {
                    document.body.classList.remove("dark-mode");
                    lightRadios.forEach(radio => {
                        if (radio) radio.checked = true;
                    });
                    updateDashboardTheme();
                }
            }
        });

        // Initial dashboard theme update
        setTimeout(() => {
            updateDashboardTheme();
        }, 1000);
    });
</script>