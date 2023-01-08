<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniClientMaster */

$this->title = 'Add Client';
// $this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Client</a></li>
				<li class="breadcrumb-item active" aria-current="page">Add Client</li>
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
                            'operators' => $operators,
                            'role_login_counts' => $role_login_counts,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>