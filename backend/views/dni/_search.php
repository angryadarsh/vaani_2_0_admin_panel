<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\EdasDniMasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="edas-dni-master-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'client_id') ?>

    <?= $form->field($model, 'DNI_from') ?>

    <?= $form->field($model, 'DNI_to') ?>

    <?= $form->field($model, 'DNI_other') ?>

    <?php // echo $form->field($model, 'DNI_prefix') ?>

    <?php // echo $form->field($model, 'DNI_name') ?>

    <?php // echo $form->field($model, 'carrier_name') ?>

    <?php // echo $form->field($model, 'service_outbound_max_days') ?>

    <?php // echo $form->field($model, 'service_outbound_max_attempts_total') ?>

    <?php // echo $form->field($model, 'service_outbound_max_attempts_per_day') ?>

    <?php // echo $form->field($model, 'service_outbound_eod_processed_till') ?>

    <?php // echo $form->field($model, 'service_outbound_mix_fresh_retry') ?>

    <?php // echo $form->field($model, 'service_outbound_lead_expire_fail_flag') ?>

    <?php // echo $form->field($model, 'service_leadstructure_attempts_tablename') ?>

    <?php // echo $form->field($model, 'service_outbound_msc_code') ?>

    <?php // echo $form->field($model, 'service_outbound_max_attempts_per_day_flag') ?>

    <?php // echo $form->field($model, 'service_server_numbers') ?>

    <?php // echo $form->field($model, 'service_sms_mode') ?>

    <?php // echo $form->field($model, 'service_email_mode') ?>

    <?php // echo $form->field($model, 'service_chat_mode') ?>

    <?php // echo $form->field($model, 'service_social_media_mode') ?>

    <?php // echo $form->field($model, 'service_transfer_inbound_flow') ?>

    <?php // echo $form->field($model, 'service_transfer_outbound_flow') ?>

    <?php // echo $form->field($model, 'service_template_id_disposition') ?>

    <?php // echo $form->field($model, 'service_template_id_lookup') ?>

    <?php // echo $form->field($model, 'service_template_id_module') ?>

    <?php // echo $form->field($model, 'service_template_id_zone') ?>

    <?php // echo $form->field($model, 'service_template_id_crm') ?>

    <?php // echo $form->field($model, 'service_ini_parameters') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'created_ip') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <?php // echo $form->field($model, 'modified_date') ?>

    <?php // echo $form->field($model, 'modified_ip') ?>

    <?php // echo $form->field($model, 'last_activity') ?>

    <?php // echo $form->field($model, 'change_set') ?>

    <?php // echo $form->field($model, 'service_outbound_days') ?>

    <?php // echo $form->field($model, 'service_grp_id') ?>

    <?php // echo $form->field($model, 'service_crmmanager_enabled') ?>

    <?php // echo $form->field($model, 'service_non_voice_outbound_url') ?>

    <?php // echo $form->field($model, 'service_non_voice_outbound_days') ?>

    <?php // echo $form->field($model, 'service_non_voice_outbound_start_time') ?>

    <?php // echo $form->field($model, 'service_non_voice_outbound_end_time') ?>

    <?php // echo $form->field($model, 'service_non_voice_outbound_autogap_between_2_calls') ?>

    <?php // echo $form->field($model, 'service_non_voice_outbound_wrapup_time') ?>

    <?php // echo $form->field($model, 'service_non_voice_outbound_autowrapup_disp_code') ?>

    <?php // echo $form->field($model, 'service_email_timeover_autoreply_template_id') ?>

    <?php // echo $form->field($model, 'service_email_lead_selection_criteria') ?>

    <?php // echo $form->field($model, 'service_email_lead_selection_criteria_cb') ?>

    <?php // echo $form->field($model, 'service_non_voice_lead_selection_name_cb') ?>

    <?php // echo $form->field($model, 'service_non_voice_lead_selection_id_cb') ?>

    <?php // echo $form->field($model, 'service_email_queue_selection_criteria_cb') ?>

    <?php // echo $form->field($model, 'service_email_actions_allowed') ?>

    <?php // echo $form->field($model, 'service_email_compose_features') ?>

    <?php // echo $form->field($model, 'service_email_compose_attachment_enabled') ?>

    <?php // echo $form->field($model, 'service_email_compose_max_attachment_size') ?>

    <?php // echo $form->field($model, 'service_email_compose_max_attachment_count') ?>

    <?php // echo $form->field($model, 'service_email_compose_attachment_file_types') ?>

    <?php // echo $form->field($model, 'service_email_compose_mailbox_enabled') ?>

    <?php // echo $form->field($model, 'service_email_compose_default_mailbox_id') ?>

    <?php // echo $form->field($model, 'service_email_compose_template_enabled') ?>

    <?php // echo $form->field($model, 'service_email_compose_cannedmsg_enabled') ?>

    <?php // echo $form->field($model, 'service_email_signature_id') ?>

    <?php // echo $form->field($model, 'service_email_compose_method') ?>

    <?php // echo $form->field($model, 'service_current_leadmaintenance_schedule_id') ?>

    <?php // echo $form->field($model, 'service_next_leadmaintenance_schedule_id') ?>

    <?php // echo $form->field($model, 'service_leadmaintenance_auto_repeat') ?>

    <?php // echo $form->field($model, 'service_outbound_lead_db_path_ldf') ?>

    <?php // echo $form->field($model, 'service_activity_id') ?>

    <?php // echo $form->field($model, 'del_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
