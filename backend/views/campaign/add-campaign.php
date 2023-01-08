<?php

use yii\helpers\Html;
use common\models\EdasCampaign;

/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */

$this->title = ($model->isNewRecord ? 'Add Inbound Campaign' : ('Update Inbound Campaign: ' . $model->campaign_name));
// $this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
// $this->params['breadcrumbs'][] = 'Update';
?>

<!-- <div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body"> -->
                        <?= $this->render('_form', [
                            'cloneid'=>$cloneid, // assign cloneid to $cloneid
                            'model' => $model,
                            'queueModel' => $queueModel,
                            'clients' => $clients,
                            'week_days' => $week_days,
                            'criterias' => $criterias,
                            'campaign_types' => $campaign_types,
                            'campaign_modes' => $campaign_modes,
                            'call_mediums' => $call_mediums,
                            'dnis' => $dnis,
                            'access_values' => $access_values,
                            'queues' => $queues,
                            'call_windows' => $call_windows,
                            'campaignAccessModel' => $campaignAccessModel,
                            'clientsoperator' => $clientsoperator,
                            'disposition_plans' => $disposition_plans,
                            'add_skills' => $add_skills,
                            'skill_assigned_users' => $skill_assigned_users,
                            'skill_assigned_active_users' => $skill_assigned_active_users,
                            'dispostion_data' => $dispostion_data,
                            'users'=> $users,
                            'inbound_campaign' => $inbound_campaign,
                            'qms_names'=> $qms_names,
                            'kptemplates' => $kptemplates,
                        ]) ?>
                        <!-- <div class="row">
                            <div class="col-lg-12">
                                <?php if($model->call_medium == EdasCampaign::MEDIUM_QUEUE){ ?>
                                    <?php // Html::a('Queues', ['add-queue', 'id' => $model->id], ['class' => 'btn btn-info pull-right']) ?>
                                <?php }else if($model->call_medium == EdasCampaign::MEDIUM_IVR){ ?>
                                    <?php // Html::a('Ivr Details', ['ivr', 'id' => $model->id], ['class' => 'btn btn-info pull-right']) ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->