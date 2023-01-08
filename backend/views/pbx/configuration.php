<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'PBX Configuration';
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['id' => 'pbx-form', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                            $('#LoadingBox').show(); 
                        } "]]); ?>
                        <div class="row">

                            <?php foreach ($config_keys as $key => $value) { ?>
                                <div class="col-lg-6">
                                    <?= $form->field($model, 'config_keys['.$key.']', ['template' => '{input}{label}{error}{hint}'])->textInput(['required' => true, 'value' => reset($value)])->label(array_key_first($value),['class'=>'form-label']) ?>
                                </div>
                            <?php } ?>
                            
                        </div>
                        <div class="text-center">
                            <?= Html::submitButton('Configure', ['class' => 'btn btn-primary', 'id' => 'configure-button', 'data-confirm' => 'Are you sure you want to submit the configurations?', 'data-method' => 'post']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
?>