<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use fedemotta\datatables\DataTables;
use common\models\VaaniLeadBatch;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniLeadBatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lead Batches';

$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('Upload Leads', ['upload'], ['class' => 'btn btn-outline-primary']) ?>
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
                            <tbody>
                                <?php if($dataProvider->query->all()){ ?>
                                    <?= DataTables::widget([
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],

                                            'batch_name',
                                            [
                                                'label' => 'Campaign',
                                                'value' => function($model){
                                                    return ($model->campaign ? $model->campaign->campaign_name : '-');
                                                }
                                            ],
                                            [
                                                'attribute' => 'is_active',
                                                'label' => 'Status',
                                                'value' => function($model){
                                                    return ($model->is_active ? VaaniLeadBatch::$statuses[$model->is_active] : '-');
                                                }
                                            ],
                                            'date_created',
                                            [
                                                'class' => 'yii\grid\ActionColumn',
                                                'template' => '{set_status} | {rechurn}',
                                                'buttons' => [
                                                    'set_status' => function($url, $data){
                                                        return (
                                                            $data->is_active == VaaniLeadBatch::STATUS_ACTIVE ? 
                                                        
                                                            Html::a('<i class="far fa-check-square btn-sm text-success"></i>', ['set-batch-inactive', 'id' => $data->id], [
                                                                'title' => 'Set Inactive',
                                                                'data-confirm' => 'Are you sure you want to set this Batch inactive?',
                                                                'data-method' => 'post',
                                                            ]) : 
                                                        
                                                            Html::a('<i class="far fa-square btn-sm text-danger"></i>', null /* ['set-status', 'id' => $data->id, 'status' => VaaniLeadBatch::STATUS_ACTIVE] */, [
                                                            'title' => 'Set Active',
                                                            'class' => 'add_filter',
                                                                'data' => [
                                                                    'id' => $data->id,
                                                                    'batch_id' => $data->batch_id,
                                                                ]
                                                            ])
                                                        );
                                                    },
                                                    'rechurn' => function($url, $data){
                                                        return (
                                                            $data->is_rechurn
                                                            ?
                                                            Html::a('<i class="fas fa-sync-alt btn-sm text-success"></i>', null, [
                                                                'title' => 'Re-churn Mode On',
                                                                'class' => 'set_rechurn',
                                                                    'data' => [
                                                                        'id' => $data->id,
                                                                        'batch_id' => $data->batch_id,
                                                                        'campaign_id' => $data->campaign_id,
                                                                    ]
                                                                ]
                                                            )
                                                            :
                                                            Html::a('<i class="fas fa-undo btn-sm text-primary"></i>', null, [
                                                            'title' => 'Set Re-churn',
                                                            'class' => 'set_rechurn',
                                                                'data' => [
                                                                    'id' => $data->id,
                                                                    'batch_id' => $data->batch_id,
                                                                    'campaign_id' => $data->campaign_id,
                                                                ]
                                                            ])
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php // echo "<pre>"; print_r($filterModel);exit; ?>
<div id="filter_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p class="modal_header">Add Fitler for the Batch</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'modal_form', 'action' => ['lead/set-batch-active'], 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                $('#LoadingBox').show(); 
            } "]]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="select_query"></div>
                        
                        <?= $form->field($filterModel, 'lead_batch_id', ['template' => '{input}{label}{error}{hint}', 'options' => ['class' => 'm-0']])->hiddenInput()->label(false) ?>
                        <?= $form->field($filterModel, 'batch_id', ['template' => '{input}{label}{error}{hint}', 'options' => ['class' => 'm-0']])->hiddenInput()->label(false) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-muted pull-right m-0 p-0">
                                    eg. <i>column_name</i> &nbsp; &nbsp;<i>operator</i> &nbsp;&nbsp; <i>value</i>
                                    <i class="fas fa-info-circle" title="
eg. name  LIKE  'john',
mobile = '1234567890',
...

NOTE: Add on new line for each column
                                    "></i>
                                </div>
                            </div>
                        </div>
                        <?= $form->field($filterModel, 'filter_query', ['template' => '{input}{label}{error}{hint}'])->textarea(['placeholder' => 'Filter Window', 'rows' => 5])->label('Filter Window', ['class' => 'form-label']) ?>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-muted pull-right m-0 p-0">
                                    eg. <i>column_name</i> &nbsp; &nbsp;<i>ASC/DESC</i>
                                    <i class="fas fa-info-circle" title="
eg. name ASC,
mobile DESC,
...

NOTE: Add on new line for each column
                                    "></i>
                                </div>
                            </div>
                        </div>
                        <?= $form->field($filterModel, 'sort_query', ['template' => '{input}{label}{error}{hint}'])->textarea(['placeholder' => 'Sort Window', 'rows' => 2])->label('Sort Window', ['class' => 'form-label']) ?>
                        <?= $form->field($filterModel, 'query', ['template' => '{input}{label}{error}{hint}'])->hiddenInput()->label(false) ?>
                        
                        <div class="row">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="batch_fields ">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12">
                    <div class="form-group text-center">
                        <?= Html::button('Test', ['class' => 'btn btn-primary', 'id' => 'test_query']) ?>
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary hidden', 'id' => 'filter_submit']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- re-churn modal  -->
<div id="rechurn_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p class="modal_header">Set Re-Churn disposition for the Batch</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'modal_form', 'action' => ['lead/set-batch-rechurn'], 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                $('#LoadingBox').show(); 
            } "]]); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <?= $form->field($batchModel, 'batch_id', ['template' => '{input}{label}{error}{hint}', 'options' => ['class' => 'm-0']])->hiddenInput()->label(false) ?>
                        <?= $form->field($batchModel, 'is_rechurn', ['template' => '{input}{label}{error}{hint}', 'options' => ['class' => 'm-0']])->checkbox(['class' => 'form-group', 'label' => 'Is Rechurn?'])->label(false) ?>
                    </div>
                    <div class="col-md-10 row rechurn_details">
                        <div class="col-md-4">
                            <?= $form->field($batchModel, 'call_attempts', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Attempts'])->label('Call Attempts', ['class' => 'form-label']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($batchModel, 'rechurn_time', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Rechurn Time'])->label('Rechurn Time', ['class' => 'form-label']) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($batchModel, 'last_dial_datetime', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Last Dailed Date & Time'])->label('Last Dailed Date & Time', ['class' => 'form-label']) ?>
                            
                        </div>
                        
                        <div class="col-md-12 ">
                        <div class="select_query_rechurn m-2 text-muted"></div>
                            <div class="text-muted pull-right m-0 p-0">
                                    eg. <i>column_name</i> &nbsp; &nbsp;<i>operator</i> &nbsp;&nbsp; <i>value</i>
                                    <i class="fas fa-info-circle" title="
eg. name  LIKE  'john',
mobile = '1234567890',
...

NOTE: Add on new line for each column
                                    "></i>
                                </div>
                                    
                                <?= $form->field($batchModel, 'filter_query_rechurn', ['template' => '{input}{label}{error}{hint}'])->textarea(['placeholder' => 'Filter Window', 'rows' => 5])->label('Filter Window', ['class' => 'form-label']) ?>
                               
                        </div>
                        <div class="row">
                         </div>
                    </div>
                    <div class="col-md-2">
                        <div class="batch_fields_rechurn text-muted">

                        </div>
                    </div>
                    
                    <div class="col-md-12 camp_dispositions">
                        <?= $form->field($batchModel, 'dispositions', ['template' => '{input}{label}{error}{hint}'])->widget(
                            Select2::classname(), [
                                'data' => null,
                                // 'theme' => Select2::THEME_MATERIAL,
                                'options' => ['prompt' => '', 'multiple' => true],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ])->label('Dispositions', ['class' => 'form-label']) ?>
                        <?= $form->field($batchModel, 'query', ['template' => '{input}{label}{error}{hint}'])->hiddenInput()->label(false) ?>
                    </div>
                    
                    
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12">
                    <div class="form-group text-center">
                    <?= Html::button('Test', ['class' => 'btn btn-primary', 'id' => 'test_query_rechurn']) ?>
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'id' => 'rechurn_submit']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerCss("
    .table-striped tbody tr{
        cursor: auto;
    }
    .table-striped tbody tr a{
        cursor: pointer;
    }
    .select2-results__option{
        // border-top : 1px solid grey;
    }
");

$this->registerJs("
    $('#rechurn_submit').show();
    $('#vaanileadbatch-is_rechurn').on('click', function(){
        $('#rechurn_submit').hide();
    });
    $('#vaanileadbatch-filter_query_rechurn').on('change', function(){
        $('#rechurn_submit').hide();
    });

    $(document).on('click', '.add_filter', function(){
        var batch_id = $(this).data('batch_id');
        var id = $(this).data('id');
        
        if(batch_id){
            $('#vaanibatchfilter-lead_batch_id').val(id);
            $('#vaanibatchfilter-batch_id').val(batch_id);

            // fetch batch fields
            $.ajax({
                method: 'POST',
                url: '".$urlManager->baseUrl . '/index.php/lead/get-batch-fields'."',
                data: {batch_id: batch_id}
            }).done( function(result){
                var fields = '<b><u>Columns</u></b><br>';
                if(result){
                    fields = fields + result;

                    let query_fields = '`' + result.replace(/<br>/g, '`,`') + '`';
                    $('.select_query').html(
                        'SELECT * FROM `lead_table` WHERE '
                    );
                }
                $('.batch_fields').html(fields);

                // fetch previous filter if any
                $.ajax({
                    method: 'POST',
                    url: '".$urlManager->baseUrl . '/index.php/lead/get-batch-filter'."',
                    data: {batch_id: batch_id}
                }).done( function(result){
                    if(result){
                        var data = $.parseJSON(result);

                        if(data.filter_query){
                            $('#vaanibatchfilter-filter_query').val(data.filter_query);
                        }
                        if(data.sort_query){
                            $('#vaanibatchfilter-sort_query').val(data.sort_query);
                        }
                    }
                });
            });
        }
        $('#filter_modal').modal('toggle');
    });

    // test query
    $(document).on('click', '#test_query', function(){
        var filter_query = $('#vaanibatchfilter-filter_query').val();
        var sort_query = $('#vaanibatchfilter-sort_query').val();
        var batch_id = $('#vaanibatchfilter-batch_id').val();

        if(batch_id){
            $.ajax({
                method: 'POST',
                url: '".$urlManager->baseUrl . '/index.php/lead/test-query'."',
                data: {batch_id: batch_id, filter_query: filter_query, sort_query: sort_query}
            }).done( function(result){
                if(result){
                    var result = $.parseJSON(result);
                    
                    if(result.message == 'success'){
                        alert('Query successful!');
                        $('#vaanibatchfilter-query').val(result.data);
                        // $('#test_query').hide();
                        $('#filter_submit').show();
                        $('#filter_submit').css('display', 'initial');
                    }else{
                        alert(result.data);
                        $('#filter_submit').hide();
                    }
                }else{
                    alert('Kindly add proper filters!');
                }
            });
        }
    });

    // rechurn modal
    $(document).on('click', '.set_rechurn', function(){
        var batch_id = $(this).data('batch_id');
        var id = $(this).data('id');
        var campaign_id = $(this).data('campaign_id');
        
        $('#vaanileadbatch-is_rechurn').prop('checked', false);
        $('.camp_dispositions').hide();
        $('.rechurn_details').hide();
        $('#test_query_rechurn').hide();
        $('#rechurn_submit').show();
        $('.batch_fields_rechurn').hide();
        $('#vaanileadbatch-dispositions').val(' ').trigger('change');
        
        if(batch_id){
            $('#vaanileadbatch-batch_id').val(batch_id);

            // fetch camp dispositions
            $.ajax({
                method: 'POST',
                url: '".$urlManager->baseUrl . '/index.php/lead/get-campaign-dispositions'."',
                data: {campaign_id: campaign_id}
            }).done( function(result){
                var optionList = '<option value=\"\">Select...</option>';
                if(result){
                    result = JSON.parse(result);
                    $.each(result, function(key, value){
                        if($.type(value) == 'object'){
                            optionList += '<optgroup label=\"'+key+'\">';
                            $.each(value, function(key2, val2){
                                if($.type(val2) == 'object'){
                                    optionList += '<optgroup label=\"&nbsp; &nbsp; &nbsp;> '+key2+'\">';
                                    $.each(val2, function(key3, val3){
                                        optionList += '<option value=\"'+key3+'\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;>> '+ val3 +'</option>';
                                    });
                                    optionList += '</optgroup>';
                                }else{
                                    optionList += '<option value=\"'+key2+'\">&nbsp; &nbsp; &nbsp;> '+ val2 +'</option>';
                                }
                            });
                            optionList += '</optgroup>';
                        }else{
                            optionList += '<option value=\"'+key+'\">'+ value +'</option>';
                        }
                    });
                }
                $('#vaanileadbatch-dispositions').html(optionList);
            });

            // fetch batch rechurn data
            $.ajax({
                method: 'GET',
                url: '".$urlManager->baseUrl . '/index.php/lead/get-batch-rechurn'."',
                data: {batch_id : batch_id}
            }).done(function(result){
                if(result){
                    result = JSON.parse(result);
                    if(result.is_rechurn){
                        $('.camp_dispositions').show();
                        $('.rechurn_details').show();
                        $('.batch_fields_rechurn').show();
                        $('#test_query_rechurn').show();
                        $('#rechurn_submit').show();

                        // SET VALUES IN MODAL
                        $('#vaanileadbatch-is_rechurn').prop('checked', true);
                        $('#vaanileadbatch-dispositions').val(result.dispositions).trigger('change');
                        $('#vaanileadbatch-call_attempts').val(result.call_attempts).trigger('change');
                        $('#vaanileadbatch-rechurn_time').val(result.rechurn_time).trigger('change');
                        $('#vaanileadbatch-last_dial_datetime').val(result.last_dial_datetime).trigger('change');
                    }
                }
            });
        }
        $('#rechurn_modal').modal('toggle');
    });

    $('#vaanileadbatch-is_rechurn').on('click', function(){
        
        var is_rechurn = $(this).prop('checked');
        if(is_rechurn){
            $('.camp_dispositions').show();
            $('.rechurn_details').show();
            $('#test_query_rechurn').show();
            // $('#rechurn_submit').show();
            $('.batch_fields_rechurn').show();
        }else{
            $('.camp_dispositions').hide();
            $('.rechurn_details').hide();
            $('#test_query_rechurn').hide();
            $('#rechurn_submit').show();
            $('.batch_fields_rechurn').hide();
            $('#vaanileadbatch-dispositions').val(' ').trigger('change');
        }
    });

    // test Query rechrn

    $('#test_query_rechurn').on('click', function(){
        var filter_query_rechurn = $('#vaanileadbatch-filter_query_rechurn').val();
        var batch_id = $('#vaanileadbatch-batch_id').val();
        // console.log(filter_query_rechurn);
        if(batch_id){
            $.ajax({
                method: 'POST',
                url: '".$urlManager->baseUrl . '/index.php/lead/test-query-rechurn'."',
                data: {batch_id: batch_id, filter_query_rechurn: filter_query_rechurn}
            }).done( function(result){
                if(result){
                    var result = $.parseJSON(result);
                    
                    if(result.message == 'success'){
                        alert('Query successful!');
                        $('#vaanileadbatch-query').val(result.data);
                        // $('#test_query').hide();
                        $('#rechurn_submit').show();
                        $('#rechurn_submit').css('display', 'initial');
                    }else{
                        alert(result.data);
                        $('#rechurn_submit').hide();
                    }
                }else{
                    alert('Kindly add proper filters!');
                }
            });
        }
    });
    
    // Fetch Columns
    $('.set_rechurn').on('click', function(){
        var batch_id = $(this).data('batch_id');
        // console.log(batch_id);

        var id = $(this).data('id');
        if(batch_id){
           
            $('#vaanileadbatch-batch_id').val(batch_id);
            // console.log($('#vaanileadbatch-batch_id').val(batch_id));

            // fetch batch fields
            $.ajax({
                method: 'POST',
                url: '".$urlManager->baseUrl . '/index.php/lead/get-batch-fields-rechurn'."',
                data: {batch_id: batch_id}
            }).done( function(result){
                var fields = '<b><u>Columns</u></b><br>';
                if(result){
                    fields = fields + result;

                    let query_fields = '`' + result.replace(/<br>/g, '`,`') + '`';
                    $('.select_query_rechurn').html(
                        'SELECT * FROM `lead_table` WHERE '
                    );
                }
                $('.batch_fields_rechurn').html(fields);

                // fetch previous filter if any
                $.ajax({
                    method: 'POST',
                    url: '".$urlManager->baseUrl . '/index.php/lead/get-batch-filter-rechurn'."',
                    data: {batch_id: batch_id}
                }).done( function(result){
                    if(result){
                        var data = $.parseJSON(result);

                        if(data.filter_query_rechurn){
                            $('#vaanileadbatch-filter_query_rechurn').val(data.filter_query_rechurn);
                        }
                        
                    }
                });
            });
        }
        $('#rechurn_modal').modal('toggle');
    });
");
?>