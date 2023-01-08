<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\VaaniCrm;
use common\models\EdasCampaign;
use common\models\User;

/* @var $this yii\web\View */

$this->title = 'Campaign CRMs';
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Add CRM', ['add'], ['class' => 'btn btn-outline-primary']) ?>
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
                        <table id="report_data" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Campaign</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($data){
                                    foreach ($data as $key => $value) {
                                        $campaign = EdasCampaign::find()->where(['campaign_id' => $value['campaign_id']])->one(); ?>
                                    <tr>
                                        <td><?= $key+1 ?></td>
                                        <td><?= ($campaign ? $campaign->campaign_name : '-') ?></td>
                                        <td>
                                            <?= Html::a('<span class="fas fa-pencil-alt"></span>', ['add', 'id' => User::encrypt_data($value['id'])]) ?>
                                            |
                                            <?= Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => User::encrypt_data($value['id'])], [
                                                'data-message' => 'Are you sure you want to delete this CRM?',
                                                'data-method' => 'post',
                                                'class' => 'delete_crm'
                                            ]) ?>
                                            |
                                            <?= Html::a('<span class="fas fa-pen-nib"></span>',['add', 'clone_id' => $value['id']])?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// display loader on delete
$this->registerJs("
    $('.delete_crm').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });
");
?>