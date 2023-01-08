<?php

use yii\helpers\Html;
use common\models\EdasCampaign;

/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */

$this->title = ($model->isNewRecord ? 'Add Outbound Campaign' : ('Update Outbound Campaign: ' . $model->campaign_name));
?>

<div class="content-header">
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
                    <div class="card-body">
                        <?php /*$this->render('_outbound_form', [
                            'model' => $model,
                            'clients' => $clients,
                            'campaign_modes' => $campaign_modes,
                            'access_values' => $access_values,
                            'call_windows' => $call_windows,
                            'campaignAccessModel' => $campaignAccessModel,
                        ])*/ ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>