<?php

use yii\helpers\Html;
use fedemotta\datatables\DataTables;
use common\models\User;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniQmsTemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Disputes Audit';
// $this->params['breadcrumbs'][] = $this->title;
$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['/report/recordings'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
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
                        <div class="table-responsive">
                            <table id="recordings_report_data" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr. No.</th>
                                        <th>Unique ID</th>
                                        <th>Agent</th>
                                        <th>Campaign</th>
                                        <th>Audit Date</th>
                                        <th>Duration</th>
                                        <th>Disposition</th>
                                        <th>Feedback</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($models as $key => $model){
                                        $audit = $model->disputeAudit; ?>
                                        <tr>
                                            <td><?= $key+1 ?></td>
                                            <td><?= $model->unique_id ?></td>
                                            <td><?= $model->user->user_id ?> - <?= $model->user->user_name ?></td>
                                            <td><?= $model->campaign ?></td>
                                            <td><?= $audit->date_created ?></td>
                                            <td><?= $audit->call_duration ?></td>
                                            <td><?= $audit->sub_disposition ?></td>
                                            <td><?= $audit->feedback ?></td>
                                            <td>
                                                <?= Html::a(' <i class="fas fa-link text-secondary"> Re-Audit</i>', null, [
                                                    'class' => 'btn btn-sm p-0 re-audit',
                                                    'title' => 'Re-Audit',
                                                    'data' => [
                                                        'agent_id' => $model->agent_id,
                                                        'unique_id' => $model->unique_id,
                                                        'sheet' => $model->disputeAudit->sheet_id,
                                                    ]
                                                ]); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- evaluate modal  -->
<div id="evaluate_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">EVALUATION</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body evaluate_sheet_content">
                <?php $form = ActiveForm::begin(['id' => 'audit_form', 'action' => null, 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                    $('#LoadingBox').show(); 
                } "]]); ?>
                
                    <div class="row hidden_fields">
                        <div class="col-md-12">
                            <?= Html::hiddenInput('unique_id', null, ['id' => 'evaluate-unique_id']) ?>
                            <?= Html::hiddenInput('sheet_id', null, ['id' => 'evaluate-sheet_id']) ?>

                        </div>
                    </div>

                    <div class="sheet_content">
                    </div>
               
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJsFile($urlManager->baseUrl . "/js/audiojs/audio.min.js");

$this->registerCss("
    tr {
        cursor : auto !important;
    }
");

$this->registerJs("
    $(document).ready(function() {
        $('#recordings_report_data').DataTable( {
            'lengthMenu' : [[10, 20, 50, -1], [10, 20, 50, 'All']],
            'responsive' : true,
            'dom': 'Bflrtip',
            'buttons': [  
                'copy', 'excel', 'csv', 'print',
                {
                    'text' : 'PDF',
                    'extend' : 'pdfHtml5',
                    'orientation' : 'landscape',
                    'pageSize' : 'A3',
                    'exportOptions' : {
                        'columns' : ':visible',
                        'search' : 'applied',
                        'order' : 'applied'
                    },
                }
            ],
        });
    } );

    
    // calculate audit time
    function time() {
        var d = new Date();
        var s = d.getSeconds();
        var m = d.getMinutes();
        var h = d.getHours();
        var duration = ( ('0' + h).substr(-2) + ':' + ('0' + m).substr(-2) + ':' + ('0' + s).substr(-2) );
        $('#audit_time').text(duration);
    }
    
    // re-evaluate_sheet modal
    $(document).on('click', '.re-audit', function(){
        var unique_id = $(this).data('unique_id');
        var sheet_id = $(this).data('sheet');
        
        $('#evaluate-unique_id').val(unique_id);
        $('#evaluate-sheet_id').val(sheet_id);

        $.ajax({
            method: 'GET',
            url: '".$urlManager->baseUrl . '/index.php/qms/get-evaluate-sheet'."',
            data: {sheet_id : sheet_id, unique_id : unique_id}
        }).done( function(data){
            $('.sheet_content').html(data);

            // audit start time & time counter
            $('#audit_form').on('click', function(){
                $('#starttime').show();
            });
            setInterval(time, 1000);

            // on change of parameters marking (scores), calculate total & percent
            $('.marking').on('change', function(){
                var final_score = 0;
                var quality_score = 0;
                var out_of = $('.hidden_out_of').attr('value');
                var percent = 0;
                let is_fatal = false;

                $.each($('.marking'), function(key, marking_ele){
                    
                    if($(marking_ele).val()){
                        if($(marking_ele).val() == 'Fatal'){
                            // if fatal
                            final_score = 0;
                            is_fatal = true;
                        }else if($(marking_ele).val() == 'NA'){
                            // if NA
                            var marking_list = [];
                            var marking_options = $(marking_ele).children();
                            
                            $.each(marking_options, function(k, v){
                                var opt = parseInt($(v).val());
                                
                                if(!isNaN(opt)){
                                    marking_list.push(opt);
                                }
                            });
                            var max_opt = (Math.max.apply(Math, marking_list));

                            out_of = out_of - max_opt;
                        }else {
                            // if integer
                            quality_score = quality_score + parseInt($(marking_ele).val());
                            final_score = final_score + parseInt($(marking_ele).val());
                        }
                    }
                });
                if(is_fatal){
                    final_score = 0;
                }

                percent = ((quality_score / out_of) * 100).toFixed(2) ;

                $('#quality_score').val(quality_score);
                $('#final_score').val(final_score);
                $('#out_of_score').val(out_of);
                $('#total_percent').val(percent);
            });

            // audio js
            audiojs.events.ready(function() {
                var as = audiojs.createAll();
                var audio_player = document.getElementById('current_audio');
                audio_player.addEventListener('loadeddata', function() {
                    if (audio_player.readyState > 1) {
                        as[0].settings.enableAddMarker();

                        // add recording markers
                        let marker_content = [];
                        $('.marker_content').each(function(k, val){
                            audiojs.settings.addMarkers([$(this).attr('value')], $(this).data('count'));
                        });
                    }
                });
            });

            
            // FORM SUBMISSION
            $(document).on('submit', '#audit_form', function(e){
                e.preventDefault();

                var form = $(this);
                
                $.ajax({
                    type: 'POST',
                    url: '".$urlManager->baseUrl . '/index.php/qms/evaluate'."',
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data)
                    {
                        alert('Audit Sheet submitted successfully.'); // show response from the php script.
                        console.log(data);
                        window.location.reload();
                        $('#evaluate_modal').modal('hide');
                        $('#LoadingBox').hide();
                    },
                    error: function (data) {
                        alert('Something went wrong. Kindly try again later.');
                        console.log(data);
                        $('#evaluate_modal').modal('hide');
                        $('#LoadingBox').hide();
                    },
                });
            });
        });
        
        $('#evaluate_modal').modal('toggle');
        $('#LoadingBox').hide();
    });
");
?>