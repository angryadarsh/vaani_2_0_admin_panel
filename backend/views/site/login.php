<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use common\widgets\Alert;

$this->title = 'Vaani DashBoard | Login';
?>

<div class="container-fluid">
    <?= Alert::widget() ?>
    <div class="row align-items-center justify-content-center form-box">
        <div class="col-sm-8 col-xl-8 form-container">
            <div class="row align-items-center">
                <div class="col-sm-4 col-xl-4">
                    <img class="logo" src="<?= Yii::$app->request->baseUrl . '/images/vaani-logo.png' ?>" alt="logo">
                    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['autocomplete' => 'off', 'onsubmit' => "$('#LoadingBox').show(); "]]); ?>

                        <?= $form->field($model, 'username', ['template' => '{label}{input}{error}{hint}'])->textInput(['autofocus' => true, 'placeholder' => 'User ID', 'required' => true])->label('User Id',['class'=>'form-label']) ?>
                        
                        <?= $form->field($model, 'password', ['template' => '{label}{input}{error}{hint}'])->passwordInput(['placeholder' => 'Password', 'required' => true])->label('Password',['class'=>'form-label']) ?>
                        
                        <div class="text-center">
                            <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-sm-8 col-xl-8">
                    <img src="<?= Yii::$app->request->baseUrl . '/images/vaanicall.png' ?>" alt="vaani call">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- <div id="moveIcons">
    <div class="telephony"></div>
    <div class="callcenter"></div>
    <div class="bot"></div>
</div> -->

<?php
$this->registerCss("
    .alert a{ color : #c50000 !important; }
")
?>