<?php
use yii\helpers\Html;

/** @var $model common\models\User */

$permissions = ['lihat', 'tambah', 'ubah', 'hapus', 'cetak', 'persetujuan'];

$permLabels = [
    'lihat'       => 'LIH',
    'tambah'      => 'TAM',
    'ubah'        => 'UBA',
    'hapus'       => 'HAP',
    'cetak'       => 'CET',
    'persetujuan' => 'PER',
];

$language = Yii::$app->lang->getLang();
$langField = $language === 'en' ? 'L.langtext_en' : 'L.langtext_id';


$sqlLevel1 = 
    "SELECT DISTINCT A.menu_id,
        COALESCE($langField, A.menu_name) AS menu_name,
        A.menu_url,
        A.menu_icon,
        U.usergroupid,
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
    LEFT JOIN usergroup U 
        ON U.menuid = A.menu_id 
    AND U.positionid::text = :positionid
    WHERE A.menu_status='1' 
    AND A.menu_level='1'
    AND EXISTS (
        SELECT 1 
        FROM menus B 
        WHERE B.menu_refid = A.menu_id 
            AND B.menu_level='2' 
            AND B.menu_status='1'
    )
    ORDER BY A.menu_id";


$menusLevel1 = Yii::$app->db->createCommand($sqlLevel1)
    ->bindValue(':positionid', $model->positionid)
    ->queryAll();
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
                <!-- Baris menu level 1 -->
                <tr class="table-secondary">
                    <td class="position-sticky start-0 z-1 bg-secondary-subtle">
                        <?php if (!$isDashboard): ?>
                            <?= Html::hiddenInput("permissions[{$menu1['menu_id']}][usergroupid]", $menu1['usergroupid'] ?? '') ?>
                            <?= Html::hiddenInput("permissions[{$menu1['menu_id']}][positionid]", $model->positionid) ?>
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
                            <!-- Checkbox level 1 dikomentari -->
                            <!--
                            <?php if (!$isDashboard): ?>
                                <?= Html::checkbox(
                                    "permissions[{$menu1['menu_id']}][$perm]",
                                    $menu1[$perm] === '1',
                                    ['class' => 'form-check-input']
                                ) ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                            -->
                        </td>
                    <?php endforeach; ?>
                </tr>

                <?php
                // Ambil menu level 2 anak dari menu1
                //    $sqlLevel2 = "
                //                 SELECT 
                //                     A.menu_id,
                //                     COALESCE($langField, A.menu_name) AS menu_name,
                //                     A.menu_icon,
                //                     U.usergroupid,
                //                     COALESCE(U.lihat,'0') AS lihat,
                //                     COALESCE(U.tambah,'0') AS tambah,
                //                     COALESCE(U.ubah,'0') AS ubah,
                //                     COALESCE(U.hapus,'0') AS hapus,
                //                     COALESCE(U.cetak,'0') AS cetak,
                //                     COALESCE(U.persetujuan,'0') AS persetujuan
                //                 FROM menus A
                //                 LEFT JOIN lang L 
                //                     ON L.langno = A.menu_name 
                //                 AND L.langtype = 'extrasidebar'
                //                 LEFT JOIN usergroup U 
                //                     ON U.menuid = A.menu_id 
                //                 AND U.positionid::text = :positionid
                //                 WHERE A.menu_status='1' 
                //                 AND A.menu_level='2'
                //                 AND A.menu_refid=:refid
                //                 ORDER BY A.menu_id";

                //     $menusLevel2 = Yii::$app->db->createCommand($sqlLevel2)
                //         ->bindValue(':refid', $menu1['menu_id'])
                //         ->bindValue(':positionid', $model->positionid)
                //         ->queryAll();
                ?>

                <?php
                foreach ($model as $menu2):
                    if ($menu2['menu_refid'] != $menu1['menu_id']) {
                        continue;
                    }
                ?>
                    <tr>
                        <td class="position-sticky start-0 z-1 bg-white">
                            <?= Html::hiddenInput($model->usergroupid) ?>

                            <div class="d-flex align-items-center ms-4">
                                <?php if (!empty($menu2['menu_icon'])): ?>
                                    <i class="fa fa-<?= $menu2['menu_icon'] ?> me-2"></i>
                                <?php endif; ?>
                                <span>&mdash; <?= Html::encode($menu2['menuname']) ?></span>
                            </div>
                        </td>

                        <?php foreach ($permissions as $perm): ?>
                            <td class="text-center">
                                <?= Html::checkbox(
                                    "permissions[{$menu2['menuid']}][$perm]",
                                    $menu2[$perm] === 1,
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