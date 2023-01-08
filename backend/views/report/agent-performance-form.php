<?php

/* @var $this yii\web\View */
/* @var $model \common\models\VaaniClientMaster */

use yii\bootstrap4\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

$this->title = 'Agent Performance Report';

$urlManager = Yii::$app->urlManager;
?>


<div class="content-header">
    <div class="container-fluid">
        <!-- <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Report</a></li>
				<li class="breadcrumb-item active" aria-current="page">Agent Performance Report</li>
			</ol>
		</nav> -->
        <div class="clearfix top-header">
            <h1 class="float-left">Agent Performance Report</h1>
        </div>
    </div>
</div>

<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                            $('#LoadingBox').show(); 
                        } ", 'name' => 'performance_form'], 'id' => 'performance_form', 'action' => ['report/agent-performance-report']]); ?>
                            <?= $this->render('_form', [
                                'campaigns' => $campaigns,
                                'queues' => $queues,
                                'users' => $users,
                                'is_hourly' => true,
                                'report_columns' => $report_columns,
                            ]) ?>
                            <input type="hidden" name="formName" value="performance_form" />
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerCss("
    .select2-selection__rendered { text-align: left; }
    /* select {
        opacity: 0;
        visibility: visible !important;
    } */
");

$this->registerJs("

    
    
    selected_report_columns = ".json_encode($selected_report_columns).";
    report_columns = ".json_encode($report_columns).";


    selected_report_columns.forEach((element) => {
       
        var option =  $('#selected_report_columns').children('[value=\"'+element+'\"]');
        option.detach();
        $('#selected_report_columns').append(option).trigger('change');
        //$('#selected_report_columns').val(element).trigger('change');

        // var newOption = new Option(element, element, false, false);
        // $('#selected_report_columns').append(newOption).trigger('change');
        // $('#selected_report_columns').find('option').prop('selected',true);
        // $('#selected_report_columns').trigger('change');
    });
    
    $('#selected_report_columns').val(selected_report_columns).trigger('change');
    // $('#selected_report_columns1').val(selected_report_columns).trigger('change');
    
    //13-04-2022
    
    $('#selected_report_columns').parent().find('ul.select2-selection__rendered').sortable({
        containment: 'parent',
        update: function() {
            orderSortedValues();
            console.log(''+$('#selected_report_columns').val())
            // alert($('#selected_report_columns').val());return;
            // var option =  $('#selected_report_columns').children('[value=\"'+obj.title+'\"]');
            // option.detach();
            // $('#selected_report_columns').append(option).trigger('change');
        }
    });

    orderSortedValues = function() {
       $('#selected_report_columns').parent().find('ul.select2-selection__rendered').children('li[title]').each(function(i, obj){
        
                var element = $('#selected_report_columns').children('option').filter(function () { return $(this).html() == obj.title });
                moveElementToEndOfParent(element)
            });
        };
        
        moveElementToEndOfParent = function(element) {
            var parent = element.parent();
        
            element.detach();
        
            parent.append(element);
        };


    $('#selected_report_columns').on('select2:select', function (evt) {
        
        var element = evt.params.data.text;
        var option =  $('#selected_report_columns').children('[value=\"'+element+'\"]');
        option.detach();
        $('#selected_report_columns').append(option).trigger('change');
        // return;
        //  selectItem(evt.target, evt.params.data.id);

        // selected_report_columns.push(element);
        // $(this).val(selected_report_columns).trigger('change');
        // return;
        // var element = evt.params.data.element.value;
        // $('#selected_report_columns option[value=\"'+element+'\"]').remove();
        // var newOption = new Option(element, element, false, false);
        // $('#selected_report_columns').append(newOption).trigger('change');
        // $('#selected_report_columns').find('option').prop('selected',true);
        // $('#selected_report_columns').val(element).prop('selected',true);
        // $('#selected_report_columns').trigger('change');
        // test = $('#selected_report_columns').val();
        // $('#selected_report_columns1').val(test).trigger('change');
        // $(this).append(element);
        // $(this).trigger('change');
        // $(this).select2('open');
    });
   


    // daterange picker
    $(function() {
        var start = moment();
        var end = moment();

        $('input[name=\"dates\"]').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
            },
            startDate: start,
            endDate: end,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        // display type only for todays date
        $('#select_date').on('change', function(){
            var selected_date = $(this).val();
            var today_date = moment().format('YYYY-MM-DD');
            
            if(selected_date == (today_date + ' - ' + today_date)){
                $('.type_input').css('display', 'flex');
            }else{
                $('.type_input').hide();
            }
        });
    });

    // daterange picker for time
    $(function() {
        var start = moment();
        var end = moment();

        $('input[name=\"times\"]').daterangepicker({
            timePicker: true,
            locale: {
                format: 'H:mm',
            },
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
        });
    });
");
?>