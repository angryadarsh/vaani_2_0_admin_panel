<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\vaani_kp_templete */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row justify-content-center form-container-box">
    
    <div class="col-4">

    <?php $form = ActiveForm::begin(); ?>

    <?php //echo $form->field($model, 'templete_id')->textInput() ?>

            <div class='form-group'>
                <div class="row">
                    <label class="form-label col-sm-4">Template name</label>
                    <div class="col-sm-8">
                    <?php echo $form->field($model, 'template_name')->textInput(['maxlength' => true,'autocomplete' => 'off'])->label(false) ?>
                    </div>
                </div>
            </div>

    <?php //echo $form->field($model, 'created_date')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'modified_date')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'modified_by')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'created_ip')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'modified_ip')->textInput(['maxlength' => true]) ?>

    <?php //echo $form->field($model, 'del_status')->textInput(['maxlength' => true]) ?>

    <div class="form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

</div>
