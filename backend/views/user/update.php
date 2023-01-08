<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Update User: ' . $model->user_id;
// $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
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
                            'clients' => $clients,
                            'roles' => $roles,
                            'campaigns' => $campaigns,
                            'queues' => $queues,
                            'queue_list' => $queue_list,
                            'supervisors' => $supervisors,
                            'managers' => $managers,
                            'priorities' => $priorities,
                            'operators' => $operators,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>