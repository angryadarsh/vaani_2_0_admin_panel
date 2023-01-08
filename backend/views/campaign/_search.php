<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\EdasCampaignSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edas-campaign-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'campaign_id') ?>

    <?= $form->field($model, 'campaign_name') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'campaign_dni') ?>

    <?php // echo $form->field($model, 'campaign_status') ?>

    <?php // echo $form->field($model, 'service_supervisior_user_level') ?>

    <?php // echo $form->field($model, 'campaign_inbound_ivr') ?>

    <?php // echo $form->field($model, 'campaign_inbound_timeover_url') ?>

    <?php // echo $form->field($model, 'campaign_inbound_timeover_flow') ?>

    <?php // echo $form->field($model, 'campaign_inbound_days') ?>

    <?php // echo $form->field($model, 'campaign_inbound_start_time') ?>

    <?php // echo $form->field($model, 'campaign_inbound_end_time') ?>

    <?php // echo $form->field($model, 'campaign_inbound_agent_selection_criteria') ?>

    <?php // echo $form->field($model, 'campaign_sticky_agent') ?>

    <?php // echo $form->field($model, 'campaign_inbound_wrapup_time') ?>

    <?php // echo $form->field($model, 'campaign_inbound_auto_wrapup_disp') ?>

    <?php // echo $form->field($model, 'campaign_inbound_blacklisted_url') ?>

    <?php // echo $form->field($model, 'campaign_inbound_blacklisted_flow') ?>

    <?php // echo $form->field($model, 'campaign_inbound_clicktohelp') ?>

    <?php // echo $form->field($model, 'campaign_sms_mode') ?>

    <?php // echo $form->field($model, 'campaign_email_mode') ?>

    <?php // echo $form->field($model, 'campaign_chat_mode') ?>

    <?php // echo $form->field($model, 'campaign_whatsapp_mode') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'created_ip') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <?php // echo $form->field($model, 'modified_date') ?>

    <?php // echo $form->field($model, 'modified_ip') ?>

    <?php // echo $form->field($model, 'last_activity') ?>

    <?php // echo $form->field($model, 'service_level_calls') ?>

    <?php // echo $form->field($model, 'service_level_seconds') ?>

    <?php // echo $form->field($model, 'sub_disposition') ?>

    <?php // echo $form->field($model, 'del_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
