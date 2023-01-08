<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\User;
use common\models\VaaniQmsSheet;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniQmsTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $qms->template_name;

$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fa fa-plus"></i> Sheet', ['add-sheet', 'id' => User::encrypt_data($qms->qms_id)], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="table-responsive">
            <table id="recordings_report_data" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Sheet Name</th>
                        <th>Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($models as $key => $model) { ?>
                        <tr>
                            <td><?=$key+1?></td>
                            <td><?=$model->sheet_name?></td>
                            <td><?=VaaniQmsSheet::$types[$model->type]?></td>
                            <td>
                                <?php if($model->del_status == 1){
                                    $set_status = 2;
                                    $icon_status = '<span class="fa fa-trash"></span>';
                                    $title_status = 'Set Inactive';
                                    $class_status = 'inactive_sheet';
                                    $status = 'inactive';
                                }else{
                                    $set_status = 1;
                                    $icon_status = '<i class="fas fa-undo"></i>';
                                    $title_status = 'Set Active';
                                    $class_status = 'active_sheet';
                                    $status = 'active';
                                } ?>
                                
                                <?= Html::a($icon_status, ['set-sheet', 'qms_id' => User::encrypt_data($model->qms_id), 'id' => User::encrypt_data($model->id), 'status' => $set_status], [
                                    'data-message' => 'Are you sure you want to set the sheet ' . $model->sheet_name . ' to '.$status.'?',
                                    'data-method' => 'post',
                                    'class' => $class_status,
                                    'title' => $title_status
                                ]); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</section>