<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniCampaignBreak;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCampaignBreak */
/* @var $form yii\widgets\ActiveForm */

?>

<?php if($data){
    foreach($data as $key => $model){ ?>
        <div class="col-lg-12 break-row">
            <div class="row justify-content-center align-items-center form-box">
                <div class="col-lg-4">
                    <?= Html::hiddenInput('VaaniCampaignBreak[b_id][]', $model['id'], ['id' => 'vaanicampaignbreak-b_id']) ?>

                    <div class="form-group field-vaanicampaignbreak-break">
                        <label class="form-label" for="vaanicampaignbreak-break">Break Name</label>
                        <?= Html::textInput('VaaniCampaignBreak[break][]', $model['break_name'], ['id' => 'vaanicampaignbreak-break', 'class' => 'form-control', 'placeholder' => 'Break Name', 'required' => 'required']) ?>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <label class="form-label" for="vaanicampaignbreak-break">Status</label>
                    <div class="form-group field-vaanicampaignbreak-is_active">
                        <?= Html::dropdownList('VaaniCampaignBreak[is_active][]', $model['is_active'], VaaniCampaignBreak::$active_statuses , ['id' => 'vaanicampaignbreak-is_active', 'class' => 'form-control']) ?>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group" style="margin-top: 30px;">
                        <?= Html::a('+', null, ['class' => 'btn btn-sm btn-success add-break']) ?>
                        <?= Html::a('-', null, ['class' => 'btn btn-sm btn-danger remove-break']) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php }else{ ?>
    <div class="col-lg-12 break-row">
        <div class="row justify-content-center align-items-center form-box">
            <div class="col-lg-4">
                <?= Html::hiddenInput('VaaniCampaignBreak[b_id][]', null, ['id' => 'vaanicampaignbreak-b_id']) ?>

                <div class="form-group field-vaanicampaignbreak-break">
                    <label class="form-label" for="vaanicampaignbreak-break">Break Name</label>
                    <?= Html::textInput('VaaniCampaignBreak[break][]', null, ['id' => 'vaanicampaignbreak-break', 'class' => 'form-control', 'placeholder' => 'Break Name', 'required' => 'required']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group field-vaanicampaignbreak-is_active">
                    <label class="form-label" for="vaanicampaignbreak-break">Status</label>
                    <?= Html::dropdownList('VaaniCampaignBreak[is_active][]', VaaniCampaignBreak::STATUS_ACTIVE, VaaniCampaignBreak::$active_statuses , ['id' => 'vaanicampaignbreak-is_active', 'class' => 'form-control']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group" style="margin-top: 30px;">
                    <?= Html::a('+', null, ['class' => 'btn btn-sm btn-success add-break']) ?>
                    <?= Html::a('-', null, ['class' => 'btn btn-sm btn-danger remove-break']) ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>