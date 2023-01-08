<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\User;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniQmsTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Qms';

$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fa fa-plus"></i> QMS Template', ['create'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                <!-- <?php //Html::a('<i class="fa fa-plus"></i> Sheet', ['add-sheet'], ['class' => 'btn btn-sm btn-outline-primary']) ?> -->
                <?= Html::a('<i class="fa fa-plus"></i> Parameter', ['add-parameter'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                <?= Html::a('<i class="fa fa-eye"></i> Audit Sheet', ['audit-sheet'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <?php //if($dataProvider->query->all()){ ?>
            <?= DataTables::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'template_name',
                    'date_created',
                    
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{sheets} | {assignCamps} | {update} | {delete}',
                        'buttons' => [
                            'sheets' => function ($url, $model) {
                                return Html::a('<span class="fas fa-table"></span>', ['sheets', 'id' => User::encrypt_data($model->qms_id)], ['title' => 'View Sheets']);
                            },
                            /* 'addSheet' => function ($url, $model) {
                                return Html::a('<span class="fas fa-folder-plus"></span>', ['add-sheet', 'id' => User::encrypt_data($model->qms_id)], ['title' => 'Add Sheet']);
                            }, */
                            'assignCamps' => function ($url, $model) {
                                return Html::a('<span class="fas fa-user-plus"></span>', null, [
                                    'title' => 'Assign Campaigns',
                                    'class' => 'assign_campaigns',
                                    'data' => [
                                        'id' => User::encrypt_data($model->qms_id),
                                    ]
                                ]);
                            },
                            'update' => function ($url, $model) {
                                return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => User::encrypt_data($model->qms_id)], ['title' => 'Edit']);
                            },
                            'delete' => function ($url, $model) {
                                return Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => User::encrypt_data($model->qms_id)], [
                                    'data-message' => 'Are you sure you want to delete the template ' . $model->template_name . '?',
                                    'data-method' => 'post',
                                    'class' => 'delete_qms',
                                    'title' => 'Delete'
                                ]);
                            },
                        ]
                    ],
                ],
                'clientOptions' => [
                    "lengthMenu" => [[10, 20, 50, -1], [10, 20, 50, "All"]],
                    "responsive"=>true, 
                    "dom"=> 'frtip',
                ],
            ]); ?>
        <?php //} ?>
    </div>
</section>

<div id="assign_camp_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p class="modal_header">Assign Campaigns to the Qms Template</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'modal_form', 'action' => ['assign-campaigns'], 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                $('#LoadingBox').show(); 
            } "]]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <input type="hidden" id="assign-qms_id" name="qms_id">
                        <label class="form-label">Campaigns</label>
                    </div>
                    <div class="col-md-10">
                        <?= Select2::widget([
                            'name' => 'campaigns[]',
                            'data' => $campaign_list,
                            'options' => [
                                'placeholder' => 'Select campaigns...',
                                'id' => 'select_campaigns',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => true
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12">
                    <div class="form-group text-center">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'id' => 'assign_submit']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
// display loader on delete
$this->registerJs("
    $('.delete_qms').on('click', function(){
        var content = $(this).data('message');
        if(confirm(content)){
            $('#LoadingBox').show();
        }else{
            return false;
        }
    });

    // Open assign campaigns modal
    $(document).on('click', '.assign_campaigns', function(){
        var qms_id = $(this).data('id');
        
        if(qms_id){
            $('#assign-qms_id').val(qms_id);
            $('#select_campaigns').val('').trigger('change');

            // fetch if previous assigned campaigns exist
            $.ajax({
                method: 'POST',
                url: '".$urlManager->baseUrl . '/index.php/qms/get-assigned-campaigns'."',
                data: {qms_id: qms_id}
            }).done( function(result){
                if(result){
                    result = JSON.parse(result);
                    $('#select_campaigns').val(result).trigger('change');
                }
            });

            $('#assign_camp_modal').modal('toggle');
        }
    });
");
?>