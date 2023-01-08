<?php

use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniKpTab */
//echo"<pre>";print_r($_GET['id']);exit;
$this->title = 'Update Tab';
// $id = trim($_GET['templete_id']);
 $id = User::decrypt_data($templete_id);
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//echo"<pre>";print_r($templete_id);exit;
?>

<div class="container-fluid">
    <div class="card mt-3">
        <div class="card-body">
            <div class="clearfix mb-3 top-heading">
                <h4 class="m-0 float-left"><?= Html::encode($this->title) ?></h4>
                <div class="float-right">
                <?php //echo Html::a('<span class="btn btn-outline-primary">Back</span>', ['index', 'id' =>$id]); ?>
                <?= Html::a('<span class="btn btn-outline-primary btn-sm"><i class="fas fa-chevron-left"></i></span>', ['index', 'id' =>User::encrypt_data($id)]); ?>
                </div>
            </div>
    <?= $this->render('_form', [
        'model' => $model,
        'templete_id'=> $templete_id,
        'file' => $file,
    ]) ?>
</div>
</div>
</div>

