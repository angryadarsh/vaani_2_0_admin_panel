<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Access - Campaign: ' . ucwords($campaign->campaign_name);
$urlManager = Yii::$app->urlManager;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <div class="float-left">
                <h1 class="m-0"><?= $this->title ?></h1>
            </div>
            <div class="float-right">
                <?= Html::a('Back', ['update', 'id' => $campaign->id], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <?php $form = ActiveForm::begin(['action' => ['campaign-access', 'id' => $campaign->id], 'method' => 'post', 'options' => ['autocomplete' => 'off']]); ?>
                    <div class="card-body">
                        <div class="row queue-section">
                            <div class="col-md-3 pr-0">
                                <?= $form->field($model, 'is_conference', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_conference'])->label('Conference?', ['class' => 'form-label']) ?>
                            </div>
                            <div class="col-md-3 pr-0">
                                <?= $form->field($model, 'is_transfer', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_transfer'])->label('Transfer?', ['class' => 'form-label']) ?>
                            </div>
                            <div class="col-md-3 pr-0">
                                <?= $form->field($model, 'is_consult', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_consult'])->label('Consult?', ['class' => 'form-label']) ?>
                            </div>
                            <div class="col-md-3 pr-0">
                                <?= $form->field($model, 'is_manual', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values, ['id' => 'queue-is_manual'])->label('Manual?', ['class' => 'form-label']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 form-group text-center">
                                <?= Html::submitButton('Finish', ['class' => 'btn btn-primary', 'id' => 'campaign-submit']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>