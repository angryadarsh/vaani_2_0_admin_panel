<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Edas Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="edas-campaign-view">

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
            'campaign_id',
            'campaign_name',
            'client_id',
            'campaign_dni',
            'campaign_status',
            'service_supervisior_user_level',
            'campaign_inbound_ivr',
            'campaign_inbound_timeover_url:url',
            'campaign_inbound_timeover_flow',
            'campaign_inbound_days',
            'campaign_inbound_start_time',
            'campaign_inbound_end_time',
            'campaign_inbound_agent_selection_criteria',
            'campaign_sticky_agent',
            'campaign_inbound_wrapup_time',
            'campaign_inbound_auto_wrapup_disp',
            'campaign_inbound_blacklisted_url:url',
            'campaign_inbound_blacklisted_flow',
            'campaign_inbound_clicktohelp',
            'campaign_sms_mode',
            'campaign_email_mode:email',
            'campaign_chat_mode',
            'campaign_whatsapp_mode',
            'created_by',
            'created_date',
            'created_ip',
            'modified_by',
            'modified_date',
            'modified_ip',
            'last_activity',
            'service_level_calls',
            'service_level_seconds',
            'sub_disposition',
            'del_status',
        ],
    ]) ?>

</div>
