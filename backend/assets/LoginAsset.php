<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'css/site.css',
        // 'css/master_menu.css',
        'css/ionicons.min.css',
        'css/fontawesome.min.css',
        'css/daterangepicker.css',
        'fonts/fonts.css',
        'fonts/font-awesome/all.min.css',
        'css/adminlte.min.css',
        'css/login.css',
    ];
    public $js = [
        'js/jquery.min.js',
        // 'js/jquery-ui.js',
        'js/moment.min.js',
        'js/custom.js',
        'js/sweetalert.js',
        'js/admin.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
