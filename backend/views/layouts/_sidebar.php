<?php
use yii\helpers\Url;
use yii\helpers\Html;

$link = Yii::$app->controller->id . "/" . Yii::$app->controller->action->id;
$roleId = Yii::$app->user->identity->role_id;
$userId = Yii::$app->user->identity->id;
$roleName = Yii::$app->function->findByField(
    "enum_name",
    "enums",
    " and enum_type='users_roles' and enum_no = '$roleId'"
);

$restrictedMenus = ['extrasidebar22', 'extrasidebar23']; // menu yang dilarang untuk role non-admin
?>

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-sticky="true" data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false, md: true, sm:true}"
    data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle" data-kt-sticky-animation="false">

    <!-- Logo -->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="<?= Url::to(['site/index']) ?>">
            <img alt="Logo" src="<?= Yii::getAlias('@web') . "/assets/media/logos/rbg.png" ?>"
                class="h-95px app-sidebar-logo-default" />
            <img alt="Logo" src="<?= Yii::getAlias('@web') . "/assets/media/logos/rbg.png" ?>"
                class="h-35px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>


    <!-- Sidebar Menu -->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="fa">

                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">

                    <?php
                    // Ambil menu level 1
                    $sql = "SELECT menu_id, menu_name, menu_url, menu_icon 
                            FROM menus 
                            WHERE menu_status='1' AND menu_level='1' 
                            ORDER BY menu_no ASC";
                    $rows = Yii::$app->db->createCommand($sql)->queryAll();
                    // echo $sql;exit;

                    foreach ($rows as $row) {
                        if (
                            in_array($row['menu_name'], ['extrasidebar3', 'extrasidebar6']) ||
                            (!in_array($roleName, ['Superadmin', 'Admin']) && in_array($row['menu_name'], $restrictedMenus))
                        ) {
                            continue;
                        }

                        $menuUrl = $row['menu_url'];
                        if (strpos($menuUrl, 'enum/index?enumtype=') === 0) {
                            $enumtype = str_replace('enum/index?enumtype=', '', $menuUrl);

                            if ($enumtype === 'contacttype') {
                                $menuUrl = '/customer'; // default bisa ubah sesuai kebutuhan
                            } else {
                                $menuUrl = '/' . $enumtype;
                            }
                        } elseif (strpos($menuUrl, 'contact/index?contacttype=') === 0) {
                            $contacttype = str_replace('contact/index?contacttype=', '', $menuUrl);
                            $menuUrl = '/' . $contacttype;
                        }

                        $activeparent = ($link == $row['menu_url']) ? "active" : "";
                        ?>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <?php if ($menuUrl == "#") { ?>
                                <span class="menu-link">
                                    <span class="menu-icon"><i class="fa fa-<?= $row['menu_icon'] ?>"></i></span>
                                    <span class="menu-title"><?= Yii::$app->lang->t('extrasidebar', $row['menu_name']) ?></span>
                                    <span class="menu-arrow"></span>
                                </span>
                            <?php } else { ?>
                                <a class="menu-link <?= $activeparent ?>" href="<?= Url::to([$menuUrl]) ?>">
                                    <span class="menu-icon"><i class="fa fa-<?= $row['menu_icon'] ?>"></i></span>
                                    <span class="menu-title"><?= Yii::$app->lang->t('extrasidebar', $row['menu_name']) ?></span>
                                </a>
                            <?php }

                            // ambil menu level 2 (submenu)
                            if ($roleName === 'Superadmin') {
                                $sqlDetail = "SELECT m.menu_id, m.menu_name, m.menu_url, m.menu_icon
                                              FROM menus m
                                              WHERE m.menu_status='1'
                                                AND m.menu_level='2'
                                                AND m.menu_refid='" . $row['menu_id'] . "'
                                              ORDER BY m.menu_no ASC";
                                $rowsdetail = Yii::$app->db->createCommand($sqlDetail)->queryAll();
                            } else {
                                $sqlDetail = "SELECT m.menu_id, m.menu_name, m.menu_url, m.menu_icon
                                              FROM menus m
                                              INNER JOIN usermenu um ON um.menuid = m.menu_id
                                              WHERE m.menu_status='1'
                                                AND m.menu_level='2'
                                                AND m.menu_refid=:parent_id
                                                AND um.userid = :user_id
                                                AND um.lihat = '1'
                                              ORDER BY m.menu_no ASC";

                                $rowsdetail = Yii::$app->db->createCommand($sqlDetail, [
                                    ':parent_id' => $row['menu_id'],
                                    ':user_id' => $userId
                                ])->queryAll();
                            }

                            if (!empty($rowsdetail)) {
                                ?>
                                <div class="menu-sub menu-sub-accordion">
                                    <?php foreach ($rowsdetail as $rowdetail) {
                                        if (
                                            in_array($rowdetail['menu_name'], ['extrasidebar3', 'extrasidebar6']) ||
                                            (!in_array($roleName, ['Superadmin', 'Admin']) && in_array($row['menu_name'], $restrictedMenus))
                                        ) {
                                            continue;
                                        }

                                        $menuUrlDetail = $rowdetail['menu_url'];

                                        // 🔁 Rewrite URL submenu juga
                                        if (strpos($menuUrlDetail, 'enum/index?enumtype=') === 0) {
                                            $enumtype = str_replace('enum/index?enumtype=', '', $menuUrlDetail);
                                            if ($enumtype === 'contacttype') {
                                                $menuUrlDetail = '/customer';
                                            } else {
                                                $menuUrlDetail = '/' . $enumtype;
                                            }
                                        } elseif (strpos($menuUrlDetail, 'contact/index?contacttype=') === 0) {
                                            $contacttype = str_replace('contact/index?contacttype=', '', $menuUrlDetail);
                                            $menuUrlDetail = '/' . $contacttype;
                                        }
                                        ?>
                                        <div class="menu-item">
                                            <a class="menu-link" href="<?= Url::to([$menuUrlDetail]) ?>">
                                                <span class="menu-icon"><i class="fa fa-<?= $rowdetail['menu_icon'] ?>"></i></span>
                                                <span
                                                    class="menu-title"><?= Yii::$app->lang->t('extrasidebar', $rowdetail['menu_name']) ?></span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #kt_app_sidebar {
        z-index: 1050 !important;
    }
</style>
<script>
    const drawer = KTDrawer.getInstance(document.querySelector("#kt_app_sidebar"));
    if (window.innerWidth < 992 && !drawer) {
        KTDrawer.createInstances();
    }

    document.addEventListener("DOMContentLoaded", function () {
        const currentUrl = window.location.pathname;

        document.querySelectorAll("#kt_app_sidebar_menu .menu-sub .menu-link").forEach(link => {
            const linkHref = link.getAttribute("href");
            if (linkHref === currentUrl || linkHref === window.location.pathname + window.location.search) {
                link.classList.add("active"); // tandai menu level 2 aktif

                const parentAccordion = link.closest(".menu-accordion");
                if (parentAccordion) {
                    parentAccordion.classList.add("hover", "show"); // Metronic class untuk submenu
                }
            }
        });


    });
</script>