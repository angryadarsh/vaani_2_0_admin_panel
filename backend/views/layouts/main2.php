<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use common\models\User;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel='icon' type='image/x-icon' href="<?= Yii::$app->request->baseUrl . '/images/favicon.png' ?>">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?php
// get the comman acces menu for the user
$url = [];
$menu['menu'] = [];
// $menu = User::userMenus('', $url);

$url = Yii::$app->requestedRoute;
?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-left main-header',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline'])
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }

    /*$chk_menu_access = false;
    foreach($menu['menu'] as $key => $value) 
    {
        if($value['sub'])
        {   
            echo menu_string($value);                  
        } else {
            $menuItems[] = '<li class="nav-item">'
                . Html::a(
                    '<i class="nav-icon '.$value['icon'].'"></i> <p> '.$value['menu_name'].'</p>',
                    ['../components/'.$value["link"]],
                    ['class' => 'nav-link']
                    // ['class' => 'nav-link', active($value['link'])]
                )
                . '</li>';
        }
    }*/

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <?php $this->render('header') ?>
</header>

<main role="main" class="flex-shrink-0">

    <!-- static menu -->
    <aside class="main-sidebar sidebar-dark-primary">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
          <img src="<?= Yii::$app->request->baseUrl . '/images/vaani-logo.png' ?>" alt="Vaani Logo" class="brand-image elevation-3" style="opacity: .8">
          <img src="<?= Yii::$app->request->baseUrl . '/images/vaani-initial-logo.png' ?>" alt="Vaani Logo" class="brand-logo elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">&nbsp;</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
          <!-- Sidebar user panel (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img src="<?= Yii::$app->request->baseUrl . '/images/vaani-initial-logo.png' ?>" class="elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="#" class="d-block">Welcome <span style="color:#c77027;">Super admin</span></a>
            </div>
          </div>

          <!-- Sidebar Menu -->
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
               <li class="nav-item">
                        <a href="../components/dashboard.php" class="nav-link ">
                          <i class="nav-icon fas fa-tachometer-alt"></i>
                          <p>
                          Call Monitoring
                          </p>
                        </a>
                      </li>  <li class="nav-item ">
                  <a href="#" '="" class="nav-link">
                  <i class="nav-icon fas fa-mug-hot"></i>
                  <p>              
                  Client
                  </p>
                  <i class="fas fa-angle-right right"></i>
                  </a><ul class="nav nav-treeview"><li class="nav-item">
                    <a href="../components/clients.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>Client List</p>
                    </a></li><li class="nav-item">
                    <a href="../components/client_form.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>Add Client</p>
                    </a></li></ul></li><li class="nav-item">
                        <a href="../components/role.php" class="nav-link ">
                          <i class="nav-icon fas fa-male"></i>
                          <p>
                          Role
                          </p>
                        </a>
                      </li>  <li class="nav-item ">
                  <a href="#" '="" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>              
                  DNI
                  </p>
                  <i class="fas fa-angle-right right"></i>
                  </a><ul class="nav nav-treeview"><li class="nav-item">
                    <a href="../components/dni_list.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>Dni List</p>
                    </a></li><li class="nav-item">
                    <a href="../components/dni_configuration.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>Add Dni</p>
                    </a></li></ul></li>
                      <li class="nav-item">
                        <?= Html::a('<i class="nav-icon fas fa-sitemap"></i> Monitoring', ['site/monitoring'], ['class' => 'nav-link']) ?>
                      </li>  
                      <li class="nav-item">
                        <?= Html::a('<i class="nav-icon fas fa-sitemap"></i> Call Report', ['report/agent-call-monitoring'], ['class' => 'nav-link active']) ?>
                      </li>
                      <li class="nav-item ">
                  <a href="#" '="" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>              
                  Campaign
                  </p>
                  <i class="fas fa-angle-right right"></i>
                  </a><ul class="nav nav-treeview"><li class="nav-item">
                    <a href="../components/campaign_list.php" class="nav-link ">
                      <i class="nav-icon fas fa-list"></i>
                      <p>Inbound</p>
                    </a></li><li class="nav-item">
                    <a href="../components/campaign_configuration.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>Add Campaign</p>
                    </a></li><li class="nav-item">
                    <a href="../components/campaign_user_list.php" class="nav-link ">
                      <i class="nav-icon "></i>
                      <p>User List</p>
                    </a></li></ul></li>  <li class="nav-item ">
                  <a href="#" '="" class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>              
                  Users
                  </p>
                  <i class="fas fa-angle-right right"></i>
                  </a><ul class="nav nav-treeview"><li class="nav-item">
                    <a href="../components/users.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>User List</p>
                    </a></li><li class="nav-item">
                    <a href="../components/add_new_user.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>Add User</p>
                    </a></li></ul></li><li class="nav-item">
                        <a href="../components/define_role.php" class="nav-link ">
                          <i class="nav-icon fas fa-user-tag"></i>
                          <p>
                          Define Role
                          </p>
                        </a>
                      </li><li class="nav-item">
                        <a href="../components/menu_master.php" class="nav-link ">
                          <i class="nav-icon far fa-address-card"></i>
                          <p>
                          Menu Master
                          </p>
                        </a>
                      </li>  <li class="nav-item ">
                  <a href="#" '="" class="nav-link">
                  <i class="nav-icon fas fa-table"></i>
                  <p>              
                  Reports
                  </p>
                  <i class="fas fa-angle-right right"></i>
                  </a><ul class="nav nav-treeview"><li class="nav-item">
                    <a href="../components/test1.php" class="nav-link ">
                      <i class="nav-icon fas fa-table"></i>
                      <p>Test</p>
                    </a></li></ul></li>  
            </ul>
          </nav>
          


          <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
      </aside>
    <!-- end of static menu -->

    <div class="content-wrapper">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>

    <footer class="footer main-footer text-muted">
      <div class="clearfix">
        <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-right">Powered by <?= Html::a('EDAS', 'https://www.edas.tech/', ['target' => '_blank']) ?></p>
      </div>
    </footer>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
