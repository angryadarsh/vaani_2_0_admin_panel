<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use fedemotta\datatables\DataTables;
use common\models\VaaniCampaignQueue;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCampaignQueue */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Queues - Campaign: ' . ucwords($model->campaign->campaign_name);
$urlManager = Yii::$app->urlManager;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <div class="float-left">
                <h1 class="m-0"><?= $this->title ?></h1>
            </div>
            <div class="float-right">
                <span class="client-id hidden"><?= $model->campaign->client_id ?></span>
                <?= Html::a('Add Queue', null, [
                    'class' => 'btn btn-primary add-queues',
                    'data' => [
                        'toggle' => 'modal',
                        'target' => '#queue-modal'
                    ]
                ]) ?>
                <?= Html::a('Back', ['update', 'id' => $model->campaign->id], ['class' => 'btn btn-primary']) ?>
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
                        <div class="queue-count" data-value=<?= count($dataProvider->query->all()) ?>></div>
                        <div class="table-responsive queues-list">
                        <?php if($dataProvider->query->all()){ ?>
                            <?= DataTables::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],

                                    'queue_name',
                                    [
                                        'attribute' => 'dni_id',
                                        'value' => function($model){
                                            return ($model->dni ? $model->dni->DNI_name : '-');
                                        }
                                    ],
                                    [
                                        'attribute' => 'criteria',
                                        'value' => function($model){
                                            return ($model->criteria ? VaaniCampaignQueue::$queue_criterias[$model->criteria] : '-');
                                        }
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{update} | {delete}',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="fas fa-pencil-alt"></i>', [null], [
                                                        'title' => 'Update',
                                                        'class' => 'edit-icon',
                                                        'data' => [
                                                            'toggle' => 'modal',
                                                            'target' => '#update-queue',
                                                            'id' => $model->id,
                                                            'name' => $model->queue_name,
                                                            'dni_id' => $model->dni_id,
                                                            'criteria' => $model->criteria,
                                                            'is_conference' => $model->callAccess->is_conference,
                                                            'is_transfer' => $model->callAccess->is_transfer,
                                                            'is_consult' => $model->callAccess->is_consult,
                                                            'is_manual' => $model->callAccess->is_manual,
                                                        ]
                                                    ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="fas fa-trash-alt"></i>', ['delete-queue', 'id' => $model->campaign->id, 'queue_id' => $model->id], [
                                                        'title' => 'Delete',
                                                        'class' => 'delete-icon',
                                                        'data' => [
                                                            'confirm' => 'Are you sure you want to delete this queue?',
                                                            'method' => 'post',
                                                        ]
                                                    ]
                                                );
                                            }
                                        ]
                                    ],
                                ],
                                'clientOptions' => [
                                    "lengthMenu" => [[10, 20, 50, -1], [10, 20, 50, "All"]],
                                    "responsive"=>true, 
                                    "dom"=> 'frtip',
                                ],
                            ]); ?>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-12 form-group text-center">
                                <?= Html::submitButton('Finish', ['class' => 'btn btn-primary', 'id' => 'queue-submit']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- modal to add multiple queue form -->
<div class="modal fade" id="queue-modal" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal_header">Add Queue</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['add-queue', 'id' => $model->campaign->id], 'method' => 'post', 'options' => ['autocomplete' => 'off']]); ?>
            <div class="modal-body">
                <div class="row queue-section">
                    <div class="col-md-12"> <hr> </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'queue_name[]', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Queue Name'])->label('Queue Name', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'dni_id[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($dnis, ['prompt' => 'Select...', 'id' => 'selected_dni_id'])->label('DNI', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'criteria[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...'])->label('Criteria', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_manual[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Manual Dialer?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_conference[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Conference?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_transfer[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Transfer?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_consult[]', ['template' => '{input}{label}{error}{hint}'])->dropdownList($access_values)->label('Consult?', ['class' => 'form-label']) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= Html::a('+', null, ['class' => 'btn btn-success add-queue']) ?>
                        <?= Html::a('-', null, ['class' => 'btn btn-danger remove-queue']) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12 form-group text-center">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'id' => 'submit_btn']) ?>
                    <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- modal to edit queue form -->
<div class="modal fade" id="update-queue" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal_header">Edit Queue</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['action' => ['update-queue', 'id' => $model->campaign->id], 'method' => 'post', 'options' => ['autocomplete' => 'off']]); ?>
            <div class="modal-body">
                <div class="row queue-section">
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= Html::hiddenInput('queue_id', null, ['id' => 'edit-queue_id']) ?>
                            <?= Html::textInput('queue_name', null, ['placeholder' => 'Queue Name', 'class' => 'form-control', 'id' => 'edit-queue_name']) ?>
                            <label class="form-label" for="edit-queue_name">Queue Name</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= Html::dropDownList('dni_id', null, $dnis, ['prompt' => 'Select...', 'class' => 'form-control', 'id' => 'edit-dni_id']) ?>
                            <label class="form-label" for="edit-dni_id">DNI</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <?= Html::dropDownList('criteria', null, $criterias, ['prompt' => 'Select...', 'class' => 'form-control', 'id' => 'edit-criteria']) ?>
                            <label class="form-label" for="edit-criteria">DNI</label>
                        </div>
                    </div>
                    <div class="col-md-3 pr-0">
                        <div class="form-group">
                            <?= Html::checkbox('is_conference', false, ['label' => 'Conference? ', 'id' => 'edit-is_conference', 'uncheck' => 2]) ?>
                        </div>
                    </div>
                    <div class="col-md-3 pr-0">
                        <div class="form-group">
                            <?= Html::checkbox('is_transfer', false, ['label' => 'Transfer? ', 'id' => 'edit-is_transfer', 'uncheck' => 2]) ?>
                        </div>
                    </div>
                    <div class="col-md-3 pr-0">
                        <div class="form-group">
                            <?= Html::checkbox('is_consult', false, ['label' => 'Consult? ', 'id' => 'edit-is_consult', 'uncheck' => 2]) ?>
                        </div>
                    </div>
                    <div class="col-md-3 pr-0">
                        <div class="form-group">
                            <?= Html::checkbox('is_manual', false, ['label' => 'Manual? ', 'id' => 'edit-is_manual', 'uncheck' => 2]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12 form-group text-center">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'id' => 'submit_edit_btn']) ?>
                    <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    $('.remove-queue').first().hide();

    $(document).on('click', '.add-queue', function(){
        var thisRow = $(this).closest('.queue-section' );
        var cloneRow = thisRow.clone();
        cloneRow.insertAfter( thisRow ).find( 'input:text' ).val( '' );
        cloneRow.find('.remove-queue').show();
    });
    $(document).on('click', '.remove-queue', function(){
        var thisRow = $(this).closest('.queue-section' );
        thisRow.remove();
    });

    $('#queue-submit').on('click', function(){
        if($('.queue-count').data('value') == 0){
            Swal.fire('Kindly add atleast one queue!', '', 'info');
        }else{
            window.location.href = '".$urlManager->baseUrl . '/index.php/campaign/index'."';
        }
    });

    $('.add-queues').on('click', function() {
        var client_id = $('.client-id').html();
        $.ajax({
            method: 'GET',
            url: '".$urlManager->baseUrl . '/index.php/campaign/get-dnis'."',
            data: {client_id: client_id}
        }).done(function(data){
            var data = $.parseJSON(data);

            if(data){
                var optionList = '<option value=\"\">Select...</option>';
                $.each(data, function(key, value){
                    optionList += '<option value=\"'+key+'\">'+ value +'</option>';
                });
                $('#selected_dni_id').html(optionList);
            }
        });
    });

    // populate the edit modal with queue values
    $('.edit-icon').on('click', function(){
        var queue_id = $(this).data('id');
        var queue_name = $(this).data('name');
        var queue_dni_id = $(this).data('dni_id');
        var queue_criteria = $(this).data('criteria');
        var is_conference = $(this).data('is_conference');
        var is_transfer = $(this).data('is_transfer');
        var is_consult = $(this).data('is_consult');
        var is_manual = $(this).data('is_manual');

        $('#edit-queue_id').val(queue_id);
        $('#edit-queue_name').val(queue_name);
        $('#edit-dni_id').val(queue_dni_id);
        $('#edit-criteria').val(queue_criteria);
        if(is_conference == 1)
            $('#edit-is_conference').prop('checked', true);
        if(is_transfer == 1)
            $('#edit-is_transfer').prop('checked', true);
        if(is_consult == 1)
            $('#edit-is_consult').prop('checked', true);
        if(is_manual == 1)
            $('#edit-is_manual').prop('checked', true);
    });

    // submit edit queue form
    $('#submit_edit_btn').on('click', function(){
        
    });
");
?>