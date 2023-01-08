<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\vaani_kp_templete */

$this->title = 'Create Template';
// $this->params['breadcrumbs'][] = ['label' => 'Vaani Kp Templetes', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="card mt-3">
        <div class="card-body">
            <div class="clearfix mb-3 top-heading">
                <h4 class="m-0 float-left"><?= Html::encode($this->title) ?></h4>
                <div class="float-right">
                    <?php echo Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                    <?php //echo Html::a('<span class="btn btn-outline-primary btn-sm"><i class="fas fa-chevron-left"></i></span>', ['index']); ?>
                </div>
            </div>

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
