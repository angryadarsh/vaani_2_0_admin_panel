<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniDispostionList */

$this->title = 'Assign Disposition';
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-primary btn-sm']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                            $('#LoadingBox').show(); 
                        } "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>

                            <div class="row">
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'disposition_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Disposition Name', 'required' => true])->label('Disposition Name', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'short_code', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Short Code', 'required' => true])->label('Short Code', ['class' => 'form-label']) ?>
                                </div>
                                <div class="col-lg-4">
                                    <?= $form->field($model, 'queues', ['template' => '{label}{input}{error}{hint}'])->widget(Select2::classname(), [
                                        'data' => $queuesList,
                                        'options' => ['placeholder' => 'Queues'],
                                        'pluginOptions' => [
                                            'allowClear' => true,
                                            'multiple' => true
                                        ],
                                        ])->label('Queues', ['class' => 'form-label']); ?>
                                </div>
                                
                                <div class="col-lg-12 form-group text-center">
                                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary form_loader']) ?>
                                    <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
                                </div>
                            </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>