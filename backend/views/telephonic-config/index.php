<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\User;
use common\models\VaaniTelephonicConfig;
use common\models\VaaniOperators;

$this->title = 'Vaani Telephonic Config';

// echo "<pre>";print_r(VaaniOperators::getOperatorList());exit;
$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
    </div>
</div>
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off'], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>
                        <div class="row">

                            <div class="col-lg-12">
                                <?= $form->field($model , 'configtype', ['template' => '{input}{label}{error}{hint}'])->dropdownList(VaaniTelephonicConfig::$config_type, ['prompt' => 'Select...', 'required' => true, 'id'=> 'config_type'])->label('Configtype', ['class' => 'form-label']) ?>
                            </div>
                            <div class="col-lg-12 config_type_section" style="display:none;">
                                <div class="row">
                                    
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'codecs')->checkboxList([
                                            'ulaw' => 'ulaw',
                                            'alaw' => 'alaw',
                                            'gsm' => 'gsm',
                                            'g729' => 'g729'], 
                                            ['separator' => '<br>']); ?>
                                    </div>

                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'externip', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Externip', 'required' => true])->label('Externip', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'localnet', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Localnet', 'required' => true])->label('Localnet', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'nat', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Nat', 'required' => true])->label('Nat', ['class' => 'form-label']) ?>
                                    </div>
                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'register', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Register', 'required' => true])->label('Register', ['class' => 'form-label']) ?>
                                    </div>  
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'operator', ['template' => '{input}{label}{error}{hint}'])->dropdownList($operators,['prompt' => 'select...' ,'id'=> 'Operators','required' => true])->label('Operator', ['class' => 'form-label']) 
                                        ?>
                                    </div>
                                    <div class="col-lg-12 operator_section" style="display:none;">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <?= $form->field($model, 'fromuser', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Fromuser', 'required' => true])->label('Fromuser', ['class' => 'form-label']) ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <?= $form->field($model, 'host', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Host', 'required' => true])->label('Host', ['class' => 'form-label']) ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <?= $form->field($model, 'username', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Username', 'required' => true])->label('Username', ['class' => 'form-label']) ?>
                                            </div>
                                            <div class="col-lg-6">
                                                <?= $form->field($model, 'secret', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Secret', 'required' => true])->label('Secret', ['class' => 'form-label']) ?>
                                            </div>
                                            <div class="col-lg-12">
                                                <?= $form->field($model, 'fromdomain', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Fromdomain', 'required' => true])->label('Fromdomain', ['class' => 'form-label']) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12 form-group text-center">
                                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                                <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
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
// loader ajax message
$this->registerJs("

$('#config_type').change(function() {
  if($(this).val() == 'SIP'){
    $('.config_type_section').show();
  }
  else{
   $('.config_type_section').hide();
  }

});

$('#Operators').change(function() {

    if($(this).val() == ''){
        $('.operator_section').hide();
      
    }else{
        $('.operator_section').show();
    }
});



");
?>