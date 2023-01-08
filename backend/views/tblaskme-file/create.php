<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TblaskmeFile */

$this->title = 'Add Knowledge Portal';
// $this->params['breadcrumbs'][] = ['label' => 'Tblaskme Files', 'url' => ['index']];
// $this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page">Knowledge Portal</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-outline-primary']) ?>
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
                            'clients' => $clients,
                            'campaigns' => $campaigns,
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

