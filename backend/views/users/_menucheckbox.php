<?php
use yii\helpers\Html;

/**
 * @var $model common\models\User
 * @var $position string|null  <-- WAJIB: dikirim dari AJAX ketika user pilih position
 */

$permissions = ['lihat', 'tambah', 'ubah', 'hapus', 'cetak', 'persetujuan'];

// Bahasa
$language = Yii::$app->lang->getLang();
$langField = $language === 'en' ? 'L.langtext_en' : 'L.langtext_id';

// Tentukan sumber permission:
// 1. Jika edit user -> gunakan usermenu
// 2. Jika create user / via AJAX -> gunakan usergroup
$isEdit = !empty($model->userid);

$joinPermissionLevel1 = $isEdit
    ? "LEFT JOIN usermenu U ON U.menuid = A.menu_id AND U.userid::text = :userid"
    : "LEFT JOIN usergroup U ON U.menuid = A.menu_id AND U.positionid::text = :positionid";

$joinPermissionLevel2 = $isEdit
    ? "LEFT JOIN usermenu U ON U.menuid = A.menu_id AND U.userid::text = :userid"
    : "LEFT JOIN usergroup U ON U.menuid = A.menu_id AND U.positionid::text = :positionid";

// Query LEVEL 1
$sqlLevel1 =
    "SELECT DISTINCT
            A.menu_id,
            COALESCE($langField, A.menu_name) AS menu_name,
            A.menu_url,
            A.menu_icon,

            COALESCE(U.lihat,'0') AS lihat,
            COALESCE(U.tambah,'0') AS tambah,
            COALESCE(U.ubah,'0') AS ubah,
            COALESCE(U.hapus,'0') AS hapus,
            COALESCE(U.cetak,'0') AS cetak,
            COALESCE(U.persetujuan,'0') AS persetujuan
        FROM menus A
        LEFT JOIN lang L
            ON L.langno = A.menu_name
        AND L.langtype = 'extrasidebar'
        $joinPermissionLevel1
        WHERE A.menu_status='1'
        AND A.menu_level='1'
        AND EXISTS (
            SELECT 1
            FROM menus B
            WHERE B.menu_refid = A.menu_id
                AND B.menu_level='2'
                AND B.menu_status='1'
        )
        ORDER BY A.menu_id
    ";

$cmd1 = Yii::$app->db->createCommand($sqlLevel1);

if ($isEdit) {
    $cmd1->bindValue(':userid', $model->userid);
} else {
    $cmd1->bindValue(':positionid', $position);
}

$menusLevel1 = $cmd1->queryAll();

$permLabels = [
    'lihat'       => 'LIH',
    'tambah'      => 'TAM',
    'ubah'        => 'UBA',
    'hapus'       => 'HAP',
    'cetak'       => 'CET',
    'persetujuan' => 'PER',
];
?>

<?php foreach ($menusLevel1 as $menu1): ?>
    <?php
        $isDashboard = strtolower($menu1['menu_name']) === 'dashboard'
                    || strtolower($menu1['menu_url']) === 'dashboard';
    ?>

    <div class="table-responsive mb-5">
        <table class="table table-bordered table-sm align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="position-sticky start-0 z-1 bg-light" style="min-width: 160px;">
                        Menu
                    </th>
                    <?php foreach ($permissions as $perm): ?>
                        <th class="text-center" style="min-width: 70px;">
                            <span class="d-none d-md-inline"><?= ucfirst($perm) ?></span>
                            <span class="d-md-none"><?= $permLabels[$perm] ?></span>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>

                <!-- LEVEL 1 -->
                <tr class="table-secondary">
                    <td class="position-sticky start-0 z-1 bg-secondary-subtle">
                        <?php if (!$isDashboard): ?>
                            <?= Html::hiddenInput("permissions[{$menu1['menu_id']}][userid]", $model->userid ?? '') ?>
                        <?php endif; ?>

                        <div class="d-flex align-items-center">
                            <?php if (!empty($menu1['menu_icon'])): ?>
                                <i class="fa fa-<?= $menu1['menu_icon'] ?> me-2"></i>
                            <?php endif; ?>
                            <strong><?= Html::encode($menu1['menu_name']) ?></strong>
                        </div>
                    </td>

                    <?php foreach ($permissions as $perm): ?>
                        <td class="text-center">
                            <?php if (!$isDashboard): ?>
                                <?= Html::checkbox(
                                    "permissions[{$menu1['menu_id']}][$perm]",
                                    $menu1[$perm] == '1',
                                    [
                                        'class' => 'form-check-input',
                                        'style' => 'display:none;'
                                    ]
                                ) ?>
                            <?php else: ?>
                                &mdash;
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>

                <?php
                // Query LEVEL 2
                $sqlLevel2 =
                    "SELECT DISTINCT
                            A.menu_id,
                            COALESCE($langField, A.menu_name) AS menu_name,
                            A.menu_icon,

                            COALESCE(U.lihat,'0') AS lihat,
                            COALESCE(U.tambah,'0') AS tambah,
                            COALESCE(U.ubah,'0') AS ubah,
                            COALESCE(U.hapus,'0') AS hapus,
                            COALESCE(U.cetak,'0') AS cetak,
                            COALESCE(U.persetujuan,'0') AS persetujuan
                        FROM menus A
                        LEFT JOIN lang L
                            ON L.langno = A.menu_name
                        AND L.langtype = 'extrasidebar'
                        $joinPermissionLevel2
                        WHERE A.menu_status='1'
                        AND A.menu_level='2'
                        AND A.menu_refid=:refid
                        ORDER BY A.menu_id
                    ";

                $cmd2 = Yii::$app->db->createCommand($sqlLevel2)
                    ->bindValue(':refid', $menu1['menu_id']);

                if ($isEdit) {
                    $cmd2->bindValue(':userid', $model->userid);
                } else {
                    $cmd2->bindValue(':positionid', $position);
                }

                $menusLevel2 = $cmd2->queryAll();
                ?>

                <!-- LEVEL 2 -->
                <?php foreach ($menusLevel2 as $menu2): ?>
                    <tr>
                        <td class="position-sticky start-0 z-1 bg-white">
                            <div class="d-flex align-items-center ms-4">
                                <?php if (!empty($menu2['menu_icon'])): ?>
                                    <i class="fa fa-<?= $menu2['menu_icon'] ?> me-2"></i>
                                <?php endif; ?>
                                <span>&mdash; <?= Html::encode($menu2['menu_name']) ?></span>
                            </div>
                        </td>

                        <?php foreach ($permissions as $perm): ?>
                            <td class="text-center">
                                <?= Html::checkbox(
                                    "permissions[{$menu2['menu_id']}][$perm]",
                                    $menu2[$perm] == '1',
                                    ['class' => 'form-check-input']
                                ) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

<?php endforeach; ?>