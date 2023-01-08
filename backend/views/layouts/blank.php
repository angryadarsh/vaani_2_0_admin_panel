<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\LoginAsset;
use yii\helpers\Html;

LoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
	<link rel='icon' type='image/x-icon' href='<?= Yii::$app->request->baseUrl . '/images/favicon.png' ?>'>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- IonIcons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<!-- loader  -->
<div id="LoadingBox">
    <p>Loading...</p>
    <div class="logo-wrap tran">
      <img class="edas-logo img-responsive" src="<?= Yii::$app->request->baseUrl . '/images/vaani-logo.png' ?>" alt="logo">
      <svg class="tran" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
          style="background: rgba(255, 255, 255, 0); display: block;" width="100%" height="200px"
          viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
          <circle cx="50" cy="50" r="25" stroke-width="3" stroke="#fff"
              stroke-dasharray="39.269908169872416 39.269908169872416" fill="none" stroke-linecap="round">
              <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite"
                  dur="0.6666666666666666s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
          </circle>
      </svg>
    </div>
</div>
<!-- loader  -->

<main role="main">
    <?= $content ?>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
