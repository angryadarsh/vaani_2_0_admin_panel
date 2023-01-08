<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\EdasDniMaster */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Edas Dni Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="edas-dni-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'client_id',
            'DNI_from',
            'DNI_to',
            'DNI_other',
            'DNI_prefix',
            'DNI_name',
            'carrier_name',
            'service_outbound_max_days',
            'service_outbound_max_attempts_total',
            'service_outbound_max_attempts_per_day',
            'service_outbound_eod_processed_till',
            'service_outbound_mix_fresh_retry',
            'service_outbound_lead_expire_fail_flag',
            'service_leadstructure_attempts_tablename',
            'service_outbound_msc_code',
            'service_outbound_max_attempts_per_day_flag',
            'service_server_numbers',
            'service_sms_mode',
            'service_email_mode:email',
            'service_chat_mode',
            'service_social_media_mode',
            'service_transfer_inbound_flow',
            'service_transfer_outbound_flow',
            'service_template_id_disposition',
            'service_template_id_lookup',
            'service_template_id_module',
            'service_template_id_zone',
            'service_template_id_crm',
            'service_ini_parameters',
            'created_by',
            'created_date',
            'created_ip',
            'modified_by',
            'modified_date',
            'modified_ip',
            'last_activity',
            'change_set',
            'service_outbound_days',
            'service_grp_id',
            'service_crmmanager_enabled',
            'service_non_voice_outbound_url:url',
            'service_non_voice_outbound_days',
            'service_non_voice_outbound_start_time',
            'service_non_voice_outbound_end_time',
            'service_non_voice_outbound_autogap_between_2_calls',
            'service_non_voice_outbound_wrapup_time:datetime',
            'service_non_voice_outbound_autowrapup_disp_code',
            'service_email_timeover_autoreply_template_id:email',
            'service_email_lead_selection_criteria:email',
            'service_email_lead_selection_criteria_cb:email',
            'service_non_voice_lead_selection_name_cb',
            'service_non_voice_lead_selection_id_cb',
            'service_email_queue_selection_criteria_cb:email',
            'service_email_actions_allowed:email',
            'service_email_compose_features:email',
            'service_email_compose_attachment_enabled:email',
            'service_email_compose_max_attachment_size:email',
            'service_email_compose_max_attachment_count:email',
            'service_email_compose_attachment_file_types:email',
            'service_email_compose_mailbox_enabled:email',
            'service_email_compose_default_mailbox_id:email',
            'service_email_compose_template_enabled:email',
            'service_email_compose_cannedmsg_enabled:email',
            'service_email_signature_id:email',
            'service_email_compose_method:email',
            'service_current_leadmaintenance_schedule_id',
            'service_next_leadmaintenance_schedule_id',
            'service_leadmaintenance_auto_repeat',
            'service_outbound_lead_db_path_ldf',
            'service_activity_id',
            'del_status',
        ],
    ]) ?>

</div>
