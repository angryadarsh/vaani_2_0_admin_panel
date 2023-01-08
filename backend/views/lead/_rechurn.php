<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniLeadDump */
/* @var $form yii\widgets\ActiveForm */
?>

<!-- re-churn modal  -->
<div id="rechurn_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p class="modal_header">Set Re-Churn disposition for the Batch</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'modal_form', 'action' => ['lead/set-batch-rechurn'], 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                $('#LoadingBox').show(); 
            } "]]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($batchModel, 'batch_id', ['template' => '{input}{label}{error}{hint}', 'options' => ['class' => 'm-0']])->hiddenInput()->label(false) ?>
                        <?= $form->field($batchModel, 'is_rechurn', ['template' => '{input}{label}{error}{hint}', 'options' => ['class' => 'm-0']])->checkbox(['class' => 'form-group', 'label' => 'Is Rechurn?'])->label(false) ?>
                    </div>
                    <div class="col-md-12 camp_dispositions">
                        <?= $form->field($batchModel, 'dispositions', ['template' => '{input}{label}{error}{hint}'])->widget(
                            Select2::classname(), [
                                'data' => $disposition_list,
                                'options' => ['prompt' => '', 'multiple' => true],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Dispositions', ['class' => 'form-label']) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12">
                    <div class="form-group text-center">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'id' => 'rechurn_submit']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php
$this->registerJs("
console.log(true);
    $('#rechurn_modal').modal('toggle');
");
?>