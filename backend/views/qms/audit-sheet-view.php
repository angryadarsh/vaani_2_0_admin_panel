<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniQmsTemplate */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Add Parameter';

$urlManager = Yii::$app->urlManager;
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="clearfix">
            <div class="float-right">
                <?= Html::a('<i class="fas fa-chevron-left"></i>', ['index'], ['class' => 'btn btn-outline-primary btn-sm']) ?>
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
                        <div class="sheet_content">
                        <?php $form = ActiveForm::begin(['id' => 'audit_form', 'action' => null, 'method' => 'post', 'options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                            $('#LoadingBox').show(); 
                        } "]]); ?>
                            <?= $this->render('/report/_evaluate-sheet', [
                                'callModel' => null,
                                'sheetModel' => $sheetModel,
                                'evaluated_sheet' => null,
                                'is_preview' => true
                            ]) ?>
                        <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php

$this->registerJsFile($urlManager->baseUrl . "/js/audiojs/audio.min.js");

$this->registerJs("
    $('[data-tab]').on('click',function() {
        alert($(this));
        $('.nav-tabs li').removeClass('active');
        $('.tab-pane').removeClass('active');
        $(this).parent().addClass('active');
        var selectedTab = $(this).attr('data-tab');
        // $('#'+selectedTab).addClass('active').addClass('in');
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

    // audit start time & time counter
    $('#audit_form').on('click', function(){
        $('#starttime').show();
    });
    setInterval(time, 1000);

    // on change of parameters marking (scores), calculate total & percent
    // TRANSACTIONAL SHEET
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

    // ANALYTICAL SHEET
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
        audio_player.addEventListener('loadeddata', function() {
            if (audio_player.readyState > 1) {
                as[0].settings.enableAddMarker();
            }
        });
    });
    
    // FORM SUBMISSION
    $(document).on('submit', '#audit_form', function(e){
        return false;
    });
");

?>