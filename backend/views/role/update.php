<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniRole */

$this->title = 'Update Role: ' . $model->role_name;
// $this->params['breadcrumbs'][] = ['label' => 'Roles', 'url' => ['index']];
// $this->params['breadcrumbs'][] = 'Update';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-outline-primary']) ?>
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
                            'roles' => $roles,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>