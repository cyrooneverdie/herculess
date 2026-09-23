<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css',
        'metronic/assets/plugins/custom/datatables/datatables.bundle.css',
        'metronic/assets/plugins/custom/jstree/jstree.bundle.css',
        'metronic/assets/plugins/global/plugins.bundle.css',
        'metronic/assets/css/style.bundle.css',
        'css/site.css?v=1'
    ];


    public $js = [
        'metronic/assets/plugins/global/plugins.bundle.js',
        'metronic/assets/js/scripts.bundle.js',
        'metronic/assets/plugins/custom/jstree/jstree.bundle.js',
        'metronic/assets/plugins/custom/datatables/datatables.bundle.js',
        'metronic/assets/plugins/custom/formrepeater/formrepeater.bundle.js',
        'metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}
