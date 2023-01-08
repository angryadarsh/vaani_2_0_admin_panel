<?php

/* @var $this yii\web\View */
/* @var $model \common\models\InboundEdas */

use yii\bootstrap4\Html;
use fedemotta\datatables\DataTables;
use common\models\User;
use common\models\VaaniCallRecordings;
use yii\widgets\ActiveForm;

$this->title = 'Recordings';

$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <h1 class="m-0 float-left"><?= $this->title ?></h1>
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['recordings'], ['class' => 'btn btn-sm btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-4"><b>Dates :</b> <?= $start_date . ' - ' . $end_date ?></div>
                            <div class="col-lg-4"><b>Time :</b> <?= $start_time . ' - ' . $end_time ?></div>
                            <div class="col-lg-4"><b>Campaigns :</b> <?= implode(", ", $campaign_list) ?></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive" style="overflow-x:initial;">
                            <table id="recordings_report_data" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                    <?php 
                                    if(empty($report_columns)){
                                        $report_columns = [
                                            // 'SR'=>'SR',
                                            'UNIQUE ID'=>'UNIQUE ID',
                                            'START TIME'=>'START TIME',
                                            'DURATION'=>'DURATION',
                                            'USER ID'=>'USER ID',
                                            'NUMBER'=>'NUMBER',
                                            'CAMPAIGN'=>'CAMPAIGN',
                                            // 'DISPOSITION'=>'DISPOSITION',
                                            'DOWNLOAD'=>'DOWNLOAD',
                                            'EVALUATION'=>'EVALUATION'
                                        ];
                                    }

                                    foreach($report_columns as $column){?>
                                        <th><?= $column ?></th>
                                    <?php }?>

                                        <!-- <th>#</th>
                                        <th>START TIME</th>
                                        <th>DURATION</th>
                                        <th>UNIQUE ID</th>
                                        <th>USER ID</th> 
                                        <th>NUMBER</th>
                                        <th>CAMPAIGN</th>
                                        <th>DOWNLOAD</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($dataProvider->allModels){
                                    foreach ($dataProvider->allModels as $key => $model) {
                                        $user = $model->user;
                                        $user_name = ($user ? ($user->user_name) : '-');

                                        // $arr['SR']=$key+1;
                                        $arr['UNIQUE ID']=($model->unique_id ? $model->unique_id : '-');
                                        $arr['START TIME']=($model->start_time ? $model->start_time : '-');
                                        $arr['DURATION']=($model->duration ? $model->duration : '00:00:00');
                                        $arr['USER ID']=($user ? ($user->user_id . ' - ' . $user->user_name) : '-');
                                        $arr['NUMBER']=($model->customer_number ? $model->customer_number : '-');
                                        $arr['CAMPAIGN']=($model->campaign ? $model->campaign : '-');
                                        // $arr['DISPOSITION']=($model->disposition ? $model->disposition : '-');
                                        $arr['DOWNLOAD']='<a href="'. Yii::$app->params['recordings_path'] . $model->recording_path.'.wav" download class="btn btn-sm pt-0"> <i class="fas fa-download text-secondary"> Download</i></a>';
                                        // $arr['RECORDING']=GetRecording($model->recording_path);
                                        
                                        if($model->status == VaaniCallRecordings::STATUS_NOT_AUDITED){
                                            $arr['EVALUATION']= Html::a(' <i class="fas fa-link text-secondary"> Evaluate</i>', null, [
                                                'class' => 'btn btn-sm pt-0 evaluate', 'data' => [
                                                    'agent_id' => $model->agent_id,
                                                    'unique_id' => $model->unique_id,
                                                    'campaign' => $model->campaign,
                                                ]
                                            ]);
                                        }else{
                                            $arr['EVALUATION'] = '<i class="fas fa-check text-warning ml-10"> '.VaaniCallRecordings::$statuses[$model->status].'</i>';
                                        }
                                ?>
                                    <tr>
                                        
                                                <?php 
                                                $html ='';
                                                foreach($report_columns as $column){
                                                    // if($column == 'Date'){
                                                        $html .="<td>".$arr[$column]."</td>";
                                                    // }?>
                                                    
                                                
                                                <?php }
                                                echo $html;
                                                ?>
                                    </tr>
                                <?php } } ?>
                                </tbody>
                            </table>
                            <?php /*  DataTables::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    [
                                            'attribute' => 'time',
                                            'label' => 'START TIME',
                                            'value' => function($model){
                                                return ($model->date ? ($model->date . ' ' . $model->time) : '-');
                                            }
                                        ],
                                    
                                    [
                                        'label' => 'DURATION',
                                        'value' => function($model){
                                            return ($model->duration ? $model->duration : '00:00:00');
                                        }
                                    ],
                                    [
                                        'attribute' => 'unique_id',
                                        'label' => 'UNIQUE ID'
                                    ],
                                    [
                                        'attribute' => 'agent_id',
                                        'label' => 'USER ID',
                                        'value' => function($model){
                                            $user = User::findOne(['user_id' => $model->agent_id]);
                                            return ($user ? ($user->user_id . ' - ' . $user->user_name) : '-');
                                        }
                                    ],
                                    [
                                        'attribute' => 'mobile_no',
                                        'label' => 'NUMBER'
                                    ],
                                    [
                                        'attribute' => 'campaign',
                                        'label' => 'CAMPAIGN',
                                        'value' => function($model){
                                            return ($model->campaign ? $model->campaign : '-');
                                        }
                                    ],
                                    [
                                        'label' => 'RECORDING',
                                        'format' => 'raw',
                                        'value' => function($model){
                                            if(isset($model->recording_path) && file_exists('/var/www/html/' . Yii::$app->params['recordings_path'] . $model->recording_path.'.wav')){
                                                return '
                                                    <audio controls>
                                                        <source src="'.Yii::$app->params['recordings_path'] . $model->recording_path.'.wav" type="audio/wav">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                ';
                                            }else{
                                                return "-";
                                            }
                                        }
                                    ],
                                ],
                                'clientOptions' => [
                                    "lengthMenu" => [[10, 20, 50, -1], [10, 20, 50, 'All']],
                                    "responsive"=>true, 
                                    "dom"=> 'Blfrtip',
                                    "buttons"=> [  
                                        'copy', 'excel', 'csv', 'pdf', 'print'
                                    ]
                                ],
                            ]); */ ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- sheet modal  -->
<div id="sheet_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-full">
        <div class="modal-content" style="height:100vh;">
            <div class="modal-header text-center">
                <p class="modal_header">Select Sheet To Evaluate</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'modal_form', 'action' => null, 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                $('#LoadingBox').show(); 
            } "]]); ?>
            <div class="modal-body">
                <div class="row justify-content-center" style="padding-top:20%;">
                    <div class="col-md-6">
                        <?= Html::hiddenInput('unique_id', null, ['id' => 'sheet-unique_id']) ?>
                        <div class="form-group field-sheet">
                            <?= Html::dropDownList('sheet_id', null, [], ['prompt' => '---Select---', 'class' => 'form-control', 'id' => 'evaluate_sheet_id']) ?>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- evaluate modal  -->
<div id="evaluate_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-full">
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

function GetRecording($path){ //$model->recording_path
    if(isset($path) && file_exists('/var/www/html/' . Yii::$app->params['recordings_path'] . $path.'.wav')){
        return '
            <audio controls>
                <source src="'.Yii::$app->params['recordings_path'] . $path.'.wav" type="audio/wav">
                Your browser does not support the audio element.
            </audio>
        ';
    }else{
        return "-";
    } 
}
?>
<?php
$this->registerCss("
	.dataTables_length{
		margin-left : 2%
	}
    // #evaluate_modal{
    //     overflow : scroll;
    // }
    tr {
        cursor : auto !important;
    }
");

$this->registerJs("
	// $('table').addClass('table-responsive');
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
    
    // evaluate_sheet modal
    $(document).on('click', '.evaluate', function(){
        var unique_id = $(this).data('unique_id');
        $('#sheet-unique_id').val(unique_id);

        var campaign = $(this).data('campaign');

        $.ajax({
            method: 'POST',
            url: '".$urlManager->baseUrl . '/index.php/qms/get-campaign-sheets'."',
            data: {campaign: campaign}
        }).done( function(result){
            var optionList = '<option value=\"\">---Select---</option>';
            
            $('#evaluate_sheet_id').html('');
            data = JSON.parse(result);
            $.each(data, function(key, value){
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });
                
            $('#evaluate_sheet_id').html(optionList);
        });
        
        $('#sheet_modal').modal('toggle');
    });
    
    // calculate audit time
    function time() {
        var d = new Date();
        var s = d.getSeconds();
        var m = d.getMinutes();
        var h = d.getHours();
        var duration = ( ('0' + h).substr(-2) + ':' + ('0' + m).substr(-2) + ':' + ('0' + s).substr(-2) );
        $('#audit_time').text(duration);
    }

    // evaluate modal
    $(document).on('change', '#evaluate_sheet_id', function(){
        $('#LoadingBox').show();
        $('#sheet_modal').modal('hide');
        var unique_id = $('#sheet-unique_id').val();
        var sheet_id = $(this).val();
        $('#evaluate-unique_id').val(unique_id);
        $('#evaluate-sheet_id').val(sheet_id);
        $('body').addClass('modal-open');
        // FETCH SHEET TABS & PARAMETERS
        $.ajax({
            method: 'GET',
            url: '".$urlManager->baseUrl . '/index.php/qms/get-evaluate-sheet'."',
            data: {sheet_id : sheet_id, unique_id : unique_id}
        }).done(function(data){
            $('.sheet_content').html(data);
            $('body').addClass('modal-open');

            // audit start time & time counter
            $('#audit_form').on('click', function(){
                $('#starttime').show();
            });
            setInterval(time, 1000);

            // on change of parameters marking (scores), calculate total & percent
            // TRANSACTIONAL SHEET CALCULATION
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

            // ANALYTICAL SHEET CALCULATION
            $('.analytical_marking').on('change', function(){
                var parameter_count = 0;
                var yes_count = 0;
                var percent = 0;
                
                $.each($('.analytical_marking'), function(key, marking_ele){
                    parameter_count++;
                            
                    if($(marking_ele).val()){
                        if($(marking_ele).val().toLowerCase() == 'yes'){
                            yes_count++;
                        }
                    }
                });

                percent = ((yes_count / parameter_count) * 100).toFixed(2) ;
                
                $('#yes_count').val(yes_count);
                $('#total_percent').val(percent);
            });

            // audio js
            audiojs.events.ready(function() {
                var as = audiojs.createAll();
                var audio_player = document.getElementById('current_audio');
                if(audio_player){
                    audio_player.addEventListener('loadeddata', function() {
                        if (audio_player.readyState > 1) {
                            as[0].settings.enableAddMarker();
                        }
                    });
                }
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