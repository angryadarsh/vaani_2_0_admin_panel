<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */

$this->title = 'Add Campaign';
// $this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
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
                        <?= $this->render('_form', [
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
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>