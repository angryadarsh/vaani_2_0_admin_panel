<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniQmsTemplate */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Audit Sheet';

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
                        
                    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
                        $('#LoadingBox').show(); 
                    } "]]); ?>

                        <div class="row sheet-section"> 
                        
                            <div class="col-lg-1"></div>
                            <div class="col-lg-5">
                                <?= Html::dropDownList('qms_id', null, $templates, ['prompt' => '---Select---', 'required' => true, 'label' => 'Select QMS Template', 'class' => 'form-control', 'id' => 'qms_id']) ?>
                            </div>
                            <div class="col-lg-5">
                                <?= Html::dropDownList('sheet_id', null, [], ['prompt' => '---Select---', 'required' => true, 'label' => 'Select Audit Sheet', 'class' => 'form-control', 'id' => 'sheet_id']) ?>
                            </div>
                            <div class="col-lg-1"></div>
                            
                            <div class="col-lg-12 form-group text-center mt-20">
                                <?= Html::submitButton('Preview', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                        
                    <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// ONCHANGE OF TEMPLATE FETCH SHEETS
$this->registerJs("
    function fetchSheets(qms_id)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/qms/get-sheets' ."',
            data: {qms_id : qms_id}
        }).done(function(data){
            var optionList = '<option value=\"\">---Select---</option>';
            
            $('#sheet_id').html('');
            data = JSON.parse(data);
            $.each(data, function(key, value){
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });
                
            $('#sheet_id').html(optionList);
        });
    }
    
    $('#qms_id').on('change', function(){
        var qms_id = $(this).val();

        fetchSheets(qms_id);
    });
");
?>