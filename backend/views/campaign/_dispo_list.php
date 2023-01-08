<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniCampDispositions;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCampDispositions */
/* @var $form yii\widgets\ActiveForm */

?>

<?php if($dispositions){  ?>
    
    <?= Html::hiddenInput('VaaniCampDispositions[campaign_id][]', $campaign_id, ['id' => 'vaanicampdispositions-campaign_id']) ?>
    
    <?php foreach($dispositions as $key => $model){ 
            if($model->subDispositions){
                foreach($model->subDispositions as $k => $sub_disposition){
                    
                    if ($sub_disposition->subDispositions) {
                        foreach($sub_disposition->subDispositions as $sub_k => $sub2_disposition){
                            ?>
                            <div class="col-lg-12 sub2-disposition-row">
                                <div class="row justify-content-center align-items-center form-box">
                                    <!-- <div class="col-lg-1 m-0 p-0"></div> -->
                                    <div class="col-lg-3 sub-icon">
                                        <?= Html::hiddenInput('VaaniCampDispositions[disposition_type][]', $sub2_disposition->type, ['id' => 'vaanicampdispositions-type']) ?>
                                        <?= Html::hiddenInput('VaaniCampDispositions[disposition_id][]', $sub2_disposition->disposition_id, ['id' => 'vaanicampdispositions-disposition_id']) ?>
                                        <div class="form-group field-vaanidispositions-disposition_name">
                                            <?= Html::textInput('VaaniDispositions[disposition_name][]', $sub2_disposition->disposition_name, ['id' => 'vaanidispositions-disposition_name', 'class' => 'form-control', 'placeholder' => 'Disposition Name', 'readonly' => true]) ?>
                                            <label class="form-label" for="vaanidispositions-disposition_name">Disposition Name</label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group field-vaanidispositions-short_code">
                                            <?= Html::textInput('VaaniDispositions[short_code][]', $sub2_disposition->short_code, ['id' => 'vaanidispositions-short_code', 'class' => 'form-control', 'placeholder' => 'Short Code', 'readonly' => true]) ?>
                                            <label class="form-label" for="vaanidispositions-short_code">Short Code</label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group field-vaanicampdispositions-max_retry_count">
                                            <?= Html::textInput('VaaniCampDispositions[max_retry_count][]', (($camp_dispositions && isset($camp_dispositions[$sub2_disposition->disposition_id])) ? $camp_dispositions[$sub2_disposition->disposition_id]['max_retry_count'] : 1), ['id' => 'vaanicampdispositions-max_retry_count', 'class' => 'form-control', 'placeholder' => 'Max Retry Count', 'required' => true]) ?>
                                            <label class="form-label" for="vaanicampdispositions-max_retry_count">Max Retry Count</label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="form-group field-vaanicampdispositions-retry_delay">
                                            <?= Html::textInput('VaaniCampDispositions[retry_delay][]', (($camp_dispositions && isset($camp_dispositions[$sub2_disposition->disposition_id])) ? $camp_dispositions[$sub2_disposition->disposition_id]['retry_delay'] : 30), ['id' => 'vaanicampdispositions-retry_delay', 'class' => 'form-control', 'placeholder' => 'Retry Delay (secs)', 'required' => true]) ?>
                                            <label class="form-label" for="vaanicampdispositions-retry_delay">Retry Delay (secs)</label>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                  <?php      }
                    }else{
                  
                    ?>
                        <div class="col-lg-12 sub-disposition-row">
                            <div class="row justify-content-center align-items-center form-box">
                                <div class="col-lg-3 sub-icon">
                                    <?= Html::hiddenInput('VaaniCampDispositions[disposition_type][]', $sub_disposition->type, ['id' => 'vaanicampdispositions-type']) ?>
                                    <?= Html::hiddenInput('VaaniCampDispositions[disposition_id][]', $sub_disposition->disposition_id, ['id' => 'vaanicampdispositions-disposition_id']) ?>
                        
                                    <div class="form-group field-vaanidispositions-disposition_name">
                                        <?= Html::textInput('VaaniDispositions[disposition_name][]', $sub_disposition->disposition_name, ['id' => 'vaanidispositions-disposition_name', 'class' => 'form-control', 'placeholder' => 'Disposition Name', 'readonly' => true]) ?>
                                        <label class="form-label" for="vaanidispositions-disposition_name">Disposition Name</label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group field-vaanidispositions-short_code">
                                        <?= Html::textInput('VaaniDispositions[short_code][]', $sub_disposition->short_code, ['id' => 'vaanidispositions-short_code', 'class' => 'form-control', 'placeholder' => 'Short Code', 'readonly' => true]) ?>
                                        <label class="form-label" for="vaanidispositions-short_code">Short Code</label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group field-vaanicampdispositions-max_retry_count">
                                        <?= Html::textInput('VaaniCampDispositions[max_retry_count][]', (($camp_dispositions && isset($camp_dispositions[$sub_disposition->disposition_id])) ? $camp_dispositions[$sub_disposition->disposition_id]['max_retry_count'] : 1), ['id' => 'vaanicampdispositions-max_retry_count', 'class' => 'form-control', 'placeholder' => 'Max Retry Count', 'required' => true]) ?>
                                        <label class="form-label" for="vaanicampdispositions-max_retry_count">Max Retry Count</label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group field-vaanicampdispositions-retry_delay">
                                        <?= Html::textInput('VaaniCampDispositions[retry_delay][]', (($camp_dispositions && isset($camp_dispositions[$sub_disposition->disposition_id])) ? $camp_dispositions[$sub_disposition->disposition_id]['retry_delay'] : 30), ['id' => 'vaanicampdispositions-retry_delay', 'class' => 'form-control', 'placeholder' => 'Retry Delay (secs)', 'required' => true]) ?>
                                        <label class="form-label" for="vaanicampdispositions-retry_delay">Retry Delay (secs)</label>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                   <?php }
                }
            }else{
                echo $model->disposition_name;?>
                <div class="col-lg-12 disposition-row">
                    <hr class="row-break <?=($key != 0 ? '' : 'hidden')?>">
                    <div class="row justify-content-center align-items-center form-box">
                        <div class="col-lg-3">
                            <?= Html::hiddenInput('VaaniCampDispositions[disposition_type][]', $model->type, ['id' => 'vaanicampdispositions-type']) ?>
                            <?= Html::hiddenInput('VaaniCampDispositions[disposition_id][]', $model->disposition_id, ['id' => 'vaanicampdispositions-disposition_id']) ?>
                    
                            <div class="form-group field-vaanidispositions-disposition_name">
                                <?= Html::textInput('VaaniDispositions[disposition_name][]', $model->disposition_name, ['id' => 'vaanidispositions-disposition_name', 'class' => 'form-control', 'placeholder' => 'Short Code', 'readonly' => true]) ?>
                                <label class="form-label" for="vaanidispositions-disposition_name">Disposition Name</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group field-vaanidispositions-short_code">
                                <?= Html::textInput('VaaniDispositions[short_code][]', $model->short_code, ['id' => 'vaanidispositions-short_code', 'class' => 'form-control', 'placeholder' => 'Short Code', 'readonly' => true]) ?>
                                <label class="form-label" for="vaanidispositions-short_code">Short Code</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group field-vaanicampdispositions-max_retry_count">
                                <?= Html::textInput('VaaniCampDispositions[max_retry_count][]',  (($camp_dispositions && isset($camp_dispositions[$model->disposition_id])) ? $camp_dispositions[$model->disposition_id]['max_retry_count'] : 1), ['id' => 'vaanicampdispositions-max_retry_count', 'class' => 'form-control', 'placeholder' => 'Max Retry Count', 'required' => true]) ?>
                                <label class="form-label" for="vaanicampdispositions-max_retry_count">Max Retry Count</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group field-vaanicampdispositions-retry_delay">
                                <?= Html::textInput('VaaniCampDispositions[retry_delay][]', (($camp_dispositions && isset($camp_dispositions[$model->disposition_id])) ? $camp_dispositions[$model->disposition_id]['retry_delay'] : 30), ['id' => 'vaanicampdispositions-retry_delay', 'class' => 'form-control', 'placeholder' => 'Retry Delay (secs)', 'required' => true]) ?>
                                <label class="form-label" for="vaanicampdispositions-retry_delay">Retry Delay (secs)</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php    }
        } }
        ?>
        <!-- <div class="col-lg-12 disposition-row">
            <hr class="row-break <?=($key != 0 ? '' : 'hidden')?>">
            <div class="row justify-content-center align-items-center form-box">
                <div class="col-lg-3">
                    <? Html::hiddenInput('VaaniCampDispositions[disposition_type][]', $model->type, ['id' => 'vaanicampdispositions-type']) ?>
                    <? Html::hiddenInput('VaaniCampDispositions[disposition_id][]', $model->disposition_id, ['id' => 'vaanicampdispositions-disposition_id']) ?>
                    
                    <div class="form-group field-vaanidispositions-disposition_name">
                        <? Html::textInput('VaaniDispositions[disposition_name][]', $model->disposition_name, ['id' => 'vaanidispositions-disposition_name', 'class' => 'form-control', 'placeholder' => 'Short Code', 'readonly' => true]) ?>
                        <label class="form-label" for="vaanidispositions-disposition_name">Disposition Name</label>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group field-vaanidispositions-short_code">
                        <? Html::textInput('VaaniDispositions[short_code][]', $model->short_code, ['id' => 'vaanidispositions-short_code', 'class' => 'form-control', 'placeholder' => 'Short Code', 'readonly' => true]) ?>
                        <label class="form-label" for="vaanidispositions-short_code">Short Code</label>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group field-vaanicampdispositions-max_retry_count">
                        <? Html::textInput('VaaniCampDispositions[max_retry_count][]',  (($camp_dispositions && isset($camp_dispositions[$model->disposition_id])) ? $camp_dispositions[$model->disposition_id]['max_retry_count'] : 1), ['id' => 'vaanicampdispositions-max_retry_count', 'class' => 'form-control', 'placeholder' => 'Max Retry Count', 'required' => true]) ?>
                        <label class="form-label" for="vaanicampdispositions-max_retry_count">Max Retry Count</label>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group field-vaanicampdispositions-retry_delay">
                        <? Html::textInput('VaaniCampDispositions[retry_delay][]', (($camp_dispositions && isset($camp_dispositions[$model->disposition_id])) ? $camp_dispositions[$model->disposition_id]['retry_delay'] : 30), ['id' => 'vaanicampdispositions-retry_delay', 'class' => 'form-control', 'placeholder' => 'Retry Delay (secs)', 'required' => true]) ?>
                        <label class="form-label" for="vaanicampdispositions-retry_delay">Retry Delay (secs)</label>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- sub dispositions if any -->
        <!-- <?php //if($model->subDispositions){ ?>
        <?php //foreach($model->subDispositions as $k => $sub_disposition){ ?>
            <div class="col-lg-12 sub-disposition-row">
                <div class="row justify-content-center align-items-center form-box">
                    <div class="col-lg-3 sub-icon">
                        <? Html::hiddenInput('VaaniCampDispositions[disposition_id][]', $sub_disposition->disposition_id, ['id' => 'vaanicampdispositions-disposition_id']) ?>
                        
                        <div class="form-group field-vaanidispositions-disposition_name">
                            <? Html::textInput('VaaniDispositions[disposition_name][]', $sub_disposition->disposition_name, ['id' => 'vaanidispositions-disposition_name', 'class' => 'form-control', 'placeholder' => 'Disposition Name', 'readonly' => true]) ?>
                            <label class="form-label" for="vaanidispositions-disposition_name">Disposition Name</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group field-vaanidispositions-short_code">
                            <? Html::textInput('VaaniDispositions[short_code][]', $sub_disposition->short_code, ['id' => 'vaanidispositions-short_code', 'class' => 'form-control', 'placeholder' => 'Short Code', 'readonly' => true]) ?>
                            <label class="form-label" for="vaanidispositions-short_code">Short Code</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group field-vaanicampdispositions-max_retry_count">
                            <? Html::textInput('VaaniCampDispositions[max_retry_count][]', (($camp_dispositions && isset($camp_dispositions[$sub_disposition->disposition_id])) ? $camp_dispositions[$sub_disposition->disposition_id]['max_retry_count'] : 1), ['id' => 'vaanicampdispositions-max_retry_count', 'class' => 'form-control', 'placeholder' => 'Max Retry Count', 'required' => true]) ?>
                            <label class="form-label" for="vaanicampdispositions-max_retry_count">Max Retry Count</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group field-vaanicampdispositions-retry_delay">
                            <? Html::textInput('VaaniCampDispositions[retry_delay][]', (($camp_dispositions && isset($camp_dispositions[$sub_disposition->disposition_id])) ? $camp_dispositions[$sub_disposition->disposition_id]['retry_delay'] : 30), ['id' => 'vaanicampdispositions-retry_delay', 'class' => 'form-control', 'placeholder' => 'Retry Delay (secs)', 'required' => true]) ?>
                            <label class="form-label" for="vaanicampdispositions-retry_delay">Retry Delay (secs)</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                </div>
            </div> -->
            <!-- echo"<pre>"; print_r($sub_disposition->subDispositions); exit; -->
            <!-- sub dispositions if any -->
            <!-- <?php //if($sub_disposition->subDispositions){ ?>
            <?php //foreach($sub_disposition->subDispositions as $sub_k => $sub2_disposition){ ?> -->
                <!-- <div class="col-lg-12 sub2-disposition-row">
                    <div class="row justify-content-center align-items-center form-box">
                        <div class="col-lg-1 m-0 p-0"></div>
                        <div class="col-lg-3 sub-icon">
                            <? Html::hiddenInput('VaaniCampDispositions[disposition_id][]', $sub2_disposition->disposition_id, ['id' => 'vaanicampdispositions-disposition_id']) ?>
                            
                            <div class="form-group field-vaanidispositions-disposition_name">
                                <? Html::textInput('VaaniDispositions[disposition_name][]', $sub2_disposition->disposition_name, ['id' => 'vaanidispositions-disposition_name', 'class' => 'form-control', 'placeholder' => 'Disposition Name', 'readonly' => true]) ?>
                                <label class="form-label" for="vaanidispositions-disposition_name">Disposition Name</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group field-vaanicampdispositions-max_retry_count">
                                <? Html::textInput('VaaniCampDispositions[max_retry_count][]', (($camp_dispositions && isset($camp_dispositions[$sub2_disposition->disposition_id])) ? $camp_dispositions[$sub2_disposition->disposition_id]['max_retry_count'] : 1), ['id' => 'vaanicampdispositions-max_retry_count', 'class' => 'form-control', 'placeholder' => 'Max Retry Count', 'required' => true]) ?>
                                <label class="form-label" for="vaanicampdispositions-max_retry_count">Max Retry Count</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group field-vaanicampdispositions-retry_delay">
                                <? Html::textInput('VaaniCampDispositions[retry_delay][]', (($camp_dispositions && isset($camp_dispositions[$sub2_disposition->disposition_id])) ? $camp_dispositions[$sub2_disposition->disposition_id]['retry_delay'] : 30), ['id' => 'vaanicampdispositions-retry_delay', 'class' => 'form-control', 'placeholder' => 'Retry Delay (secs)', 'required' => true]) ?>
                                <label class="form-label" for="vaanicampdispositions-retry_delay">Retry Delay (secs)</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div> -->
            <!-- <?php// } ?>
            <?php// } ?>
        <?php// } ?>
        <?php// } ?>
    <?php //} ?>
<?php //} ?> -->


<?php $this->registerCss("
    
    .sub-icon {
        padding-left: 4%;
    }
    .sub-icon::after {
        display: block;
        content: ' ';
        height: 2px;
        width: 8%;
        background-color: grey;
        position: absolute;
        top: 30%;
        left: 4%;
    }

    .sub-icon::before {
        display: block;
        content: ' ';
        width: 2px;
        height: 50%;
        position: absolute;
        left: 4%;
        background: grey;
        margin-top: -4%;
    }
");