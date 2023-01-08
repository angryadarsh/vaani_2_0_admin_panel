<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniOperators */

$this->title = 'Create Operator';
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?= $this->render('_form', [
                            'model' => $model,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>