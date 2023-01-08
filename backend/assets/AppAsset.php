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
        // 'css/site.css',
        // 'css/master_menu.css',
        'css/ionicons.min.css',
        'css/fontawesome.min.css',
        'css/jquery.dataTables.min.css',
        // 'css/tempusdominus-bootstrap-4.min.css',
        'css/daterangepicker.css',
        'https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css',
        'fonts/fonts.css',
        'css/fonts.css',
        'css/select2.sortable.css',
        'css/style.css',
        'css/light-mode.css',
    ];
    public $js = [
        'js/jquery-ui.js',
        'js/jquery.easing.min.js',
        'js/moment.min.js',
        'js/custom.js',
        // 'js/tempusdominus-bootstrap-4.min.js',
        'js/jquery.dataTables.min.js',
        'js/jquery.mjs.nestedSortable.js',
        'js/sweetalert.js',
        'js/admin.js',
        'js/daterangepicker.min.js',
        'https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js',
        'https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js',
        'https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js',
        'https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js',
        'js/html5sortable.min.js',
        'js/select2.sortable.min.js',
        'js/Chart.min.js',
        // 'js/jquery.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
