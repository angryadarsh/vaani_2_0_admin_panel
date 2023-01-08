<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniDispositions;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniDispositions */
/* @var $form yii\widgets\ActiveForm */

?>

<?php if($data){
    foreach($data as $key => $model){ ?>
        <div class="col-lg-12 disposition-row">
            <hr class="row-break <?=($key != 0 ? '' : 'hidden')?>">
            <div class="row justify-content-center align-items-center form-box">
                <div class="col-lg-4">
                    <?= Html::hiddenInput('VaaniDispositions[disposition_id][]', $model->id, ['id' => 'vaanicampaigndisposition-disposition_id']) ?>

                    <div class="form-group field-vaanicampaigndisposition-disposition">
                        <?= Html::textInput('VaaniDispositions[disposition_name][]', $model->disposition_name, ['id' => 'vaanicampaigndisposition-disposition', 'class' => 'form-control', 'required' => 'required']) ?>
                        <label class="form-label" for="vaanicampaigndisposition-disposition">Disposition Name</label>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group field-vaanicampaigndisposition-short_code">
                        <?= Html::textInput('VaaniDispositions[short_code][]', $model->short_code, ['id' => 'vaanicampaigndisposition-short_code', 'class' => 'form-control', 'required' => 'required']) ?>
                        <label class="form-label" for="vaanicampaigndisposition-short_code">Short Code</label>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group field-vaanicampaigndisposition-type">
                        <?= Html::dropdownList('VaaniDispositions[type][]', $model->type, VaaniDispositions::$types , ['id' => 'vaanicampaigndisposition-type', 'class' => 'form-control']) ?>
                        <label class="form-label" for="vaanicampaigndisposition-type">Type</label>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group disposition-actions">
                        <?= Html::a('<i class="fas fa-code-branch"></i>', null, ['class' => 'btn btn-sm add-sub-disposition p-5 pr-10 pl-10', 'title' => 'Add Sub Disposition']) ?>
                        <?= Html::a('<i class="fas fa-minus"></i>', null, ['class' => 'btn btn-sm remove-disposition p-5 pr-10 pl-10', 'title' => 'Delete Disposition']) ?>
                        <?= Html::a('<i class="fas fa-plus"></i>', null, ['class' => 'btn btn-sm add-disposition p-5 pr-10 pl-10', 'title' => 'Add Disposition']) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- sub dispositions if any -->
        <?php if($model->subDispositions){ ?>
        <?php foreach($model->subDispositions as $k => $sub_disposition){ ?>
            <div class="col-lg-12 sub-disposition-row">
                <div class="row justify-content-center align-items-center form-box">
                    <div class="col-lg-4 sub-icon">
                        <?= Html::hiddenInput('VaaniDispositions[disposition_id][][]', $sub_disposition->id, ['id' => 'vaanicampaigndisposition-disposition_id']) ?>

                        <div class="form-group field-vaanicampaigndisposition-disposition">
                            <?= Html::textInput('VaaniDispositions[disposition_name][][]',  $sub_disposition->disposition_name, ['id' => 'vaanicampaigndisposition-disposition', 'class' => 'form-control', 'required' => 'required']) ?>
                            <label class="form-label" for="vaanicampaigndisposition-disposition">Disposition Name</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group field-vaanicampaigndisposition-short_code">
                            <?= Html::textInput('VaaniDispositions[short_code][][]', $sub_disposition->short_code, ['id' => 'vaanicampaigndisposition-short_code', 'class' => 'form-control', 'required' => 'required']) ?>
                            <label class="form-label" for="vaanicampaigndisposition-short_code">Short Code</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group field-vaanicampaigndisposition-type">
                            <?= Html::dropdownList('VaaniDispositions[type][][]', $model->type, VaaniDispositions::$types , ['id' => 'vaanicampaigndisposition-type', 'class' => 'form-control']) ?>
                            <label class="form-label" for="vaanicampaigndisposition-type">Type</label>
                            <div class="help-block"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group sub-disposition-actions">
                            <?= Html::a('<i class="fas fa-code-branch"></i>', null, ['class' => 'btn btn-sm add-sub2-disposition p-5 pr-10 pl-10', 'title' => 'Add Sub Disposition']) ?>
                            <?= Html::a('<i class="fas fa-minus"></i>', null, ['class' => 'btn btn-sm remove-disposition p-5 pr-10 pl-10', 'title' => 'Delete Disposition']) ?>
                            <?php // Html::a('<i class="fas fa-plus"></i>', null, ['class' => 'btn btn-sm add-disposition p-5 pr-10 pl-10', 'title' => 'Add Disposition']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- sub dispositions if any -->
            <?php if($sub_disposition->subDispositions){ ?>
            <?php foreach($sub_disposition->subDispositions as $sub_k => $sub2_disposition){ ?>
                <div class="col-lg-12 sub2-disposition-row">
                    <div class="row justify-content-center align-items-center form-box">
                        <div class="col-lg-1 m-0 p-0"></div>
                        <div class="col-lg-3 sub-icon">
                            <?= Html::hiddenInput('VaaniDispositions[disposition_id][][][]', $sub2_disposition->id, ['id' => 'vaanicampaigndisposition-disposition_id']) ?>

                            <div class="form-group field-vaanicampaigndisposition-disposition">
                                <?= Html::textInput('VaaniDispositions[disposition_name][][][]',  $sub2_disposition->disposition_name, ['id' => 'vaanicampaigndisposition-disposition', 'class' => 'form-control', 'required' => 'required']) ?>
                                <label class="form-label" for="vaanicampaigndisposition-disposition">Disposition Name</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group field-vaanicampaigndisposition-short_code">
                                <?= Html::textInput('VaaniDispositions[short_code][][][]', $sub2_disposition->short_code, ['id' => 'vaanicampaigndisposition-short_code', 'class' => 'form-control', 'required' => 'required']) ?>
                                <label class="form-label" for="vaanicampaigndisposition-short_code">Short Code</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group field-vaanicampaigndisposition-type">
                                <?= Html::dropdownList('VaaniDispositions[type][][][]', $model->type, VaaniDispositions::$types , ['id' => 'vaanicampaigndisposition-type', 'class' => 'form-control']) ?>
                                <label class="form-label" for="vaanicampaigndisposition-type">Type</label>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group sub-disposition-actions">
                                <?= Html::a('<i class="fas fa-minus"></i>', null, ['class' => 'btn btn-sm remove-disposition p-5 pr-10 pl-10', 'title' => 'Delete Disposition']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php } ?>
        <?php } ?>
        <?php } ?>
    <?php } ?>
<?php }else{ ?>
    <div class="col-lg-12 disposition-row">
        <div class="row justify-content-center align-items-center form-box">
            <div class="col-lg-4">
                <?= Html::hiddenInput('VaaniDispositions[disposition_id][]',null, ['id' => 'vaanicampaigndisposition-disposition_id']) ?>

                <div class="form-group field-vaanicampaigndisposition-disposition">
                    <label class="form-label" for="vaanicampaigndisposition-disposition">Disposition Name</label>
                    <?= Html::textInput('VaaniDispositions[disposition_name][]', null, ['id' => 'vaanicampaigndisposition-disposition', 'class' => 'form-control', 'required' => 'required']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group field-vaanicampaigndisposition-short_code">
                    <label class="form-label" for="vaanicampaigndisposition-short_code">Short Code</label>
                    <?= Html::textInput('VaaniDispositions[short_code][]', null, ['id' => 'vaanicampaigndisposition-short_code', 'class' => 'form-control', 'required' => 'required']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group field-vaanicampaigndisposition-type">
                    <label class="form-label" for="vaanicampaigndisposition-type">Type</label>
                    <?= Html::dropdownList('VaaniDispositions[type][]', null, VaaniDispositions::$types , ['id' => 'vaanicampaigndisposition-type', 'class' => 'form-control']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group disposition-actions">
                    <?= Html::a('<i class="fas fa-code-branch"></i>', null, ['class' => 'btn btn-sm add-sub-disposition p-5 pr-10 pl-10', 'title' => 'Add Sub Disposition']) ?>
                    <?= Html::a('<i class="fas fa-minus"></i>', null, ['class' => 'btn btn-sm remove-disposition p-5 pr-10 pl-10', 'title' => 'Delete Disposition']) ?>
                    <?= Html::a('<i class="fas fa-plus"></i>', null, ['class' => 'btn btn-sm add-disposition p-5 pr-10 pl-10', 'title' => 'Add Disposition']) ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<!-- sub disposition clone block - level 2 -->
<div class="col-lg-12 sub-disposition-row hidden demo-sub-row">
    <div class="row justify-content-center align-items-center form-box">
        <div class="col-lg-4 sub-icon">
            <?= Html::hiddenInput('VaaniDispositions[disposition_id][][]',null, ['id' => 'vaanicampaigndisposition-disposition_id']) ?>

            <div class="form-group field-vaanicampaigndisposition-disposition">
                <label class="form-label" for="vaanicampaigndisposition-disposition">Disposition Name</label>
                <?= Html::textInput('VaaniDispositions[disposition_name][][]', null, ['id' => 'vaanicampaigndisposition-disposition', 'class' => 'form-control', 'required' => 'required']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group field-vaanicampaigndisposition-short_code">
                <label class="form-label" for="vaanicampaigndisposition-short_code">Short Code</label>
                <?= Html::textInput('VaaniDispositions[short_code][][]', null, ['id' => 'vaanicampaigndisposition-short_code', 'class' => 'form-control', 'required' => 'required']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group field-vaanicampaigndisposition-type">
                <label class="form-label" for="vaanicampaigndisposition-type">Type</label>
                <?= Html::dropdownList('VaaniDispositions[type][][]', null, VaaniDispositions::$types , ['id' => 'vaanicampaigndisposition-type', 'class' => 'form-control']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group sub-disposition-actions">
                <?= Html::a('<i class="fas fa-code-branch"></i>', null, ['class' => 'btn btn-sm add-sub2-disposition p-5 pr-10 pl-10', 'title' => 'Add Sub Disposition']) ?>
                <?= Html::a('<i class="fas fa-minus"></i>', null, ['class' => 'btn btn-sm remove-disposition p-5 pr-10 pl-10', 'title' => 'Delete Disposition']) ?>
                <?php // Html::a('<i class="fas fa-plus"></i>', null, ['class' => 'btn btn-sm add-sub-disposition p-5 pr-10 pl-10', 'title' => 'Add Disposition']) ?>
            </div>
        </div>
    </div>
</div>

<!-- sub disposition clone block - level 3 -->
<div class="col-lg-12 sub2-disposition-row hidden demo-sub2-row">
    <div class="row justify-content-center align-items-center form-box">
        <div class="col-lg-1 m-0 p-0"></div>
        <div class="col-lg-3 sub-icon">
            <?= Html::hiddenInput('VaaniDispositions[disposition_id][][][]',null, ['id' => 'vaanicampaigndisposition-disposition_id']) ?>

            <div class="form-group field-vaanicampaigndisposition-disposition">
                <label class="form-label" for="vaanicampaigndisposition-disposition">Disposition Name</label>
                <?= Html::textInput('VaaniDispositions[disposition_name][][][]', null, ['id' => 'vaanicampaigndisposition-disposition', 'class' => 'form-control', 'required' => 'required']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group field-vaanicampaigndisposition-short_code">
                <label class="form-label" for="vaanicampaigndisposition-short_code">Short Code</label>
                <?= Html::textInput('VaaniDispositions[short_code][][][]', null, ['id' => 'vaanicampaigndisposition-short_code', 'class' => 'form-control', 'required' => 'required']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group field-vaanicampaigndisposition-type">
                <label class="form-label" for="vaanicampaigndisposition-type">Type</label>
                <?= Html::dropdownList('VaaniDispositions[type][][][]', null, VaaniDispositions::$types , ['id' => 'vaanicampaigndisposition-type', 'class' => 'form-control']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group sub-disposition-actions">
                <?= Html::a('<i class="fas fa-minus"></i>', null, ['class' => 'btn btn-sm remove-disposition p-5 pr-10 pl-10', 'title' => 'Delete Disposition']) ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->registerJs("
        
    $('.submit-disposition').on('submit', function(){
        $('#vaanidispositionplan-name').attr('required', true);
        $('#LoadingBox').hide();
    });
            
");
?>
<script>
    $('.submit-disposition').on('click', function(){
        var array = []; //array for storing  entered values in text
        var chk = 0;
        $('.dispositions-section').children().each(function(){ 
            //.each function for find all entered values in text
            //var short_code = $(this).attr('id');
            // find each id in class dispositions-section
            var text = $(this).find('#vaanicampaigndisposition-short_code').val();
            //console.log(text);  //value entered in short_code
            
            if(text == ''){
                //check for text is null
                $(this).find('#vaanicampaigndisposition-short_code').next().next('.help-block' ).html( 'Short code cannot be blank' );
                chk = 1;
                return false;
            }else{
                if($.inArray( text, array ) != -1){
                    // find the entered element in array
                    $(this).find('#vaanicampaigndisposition-short_code').next().next('.help-block' ).html( 'Short code cannot be same' );
                    chk = 1;
                    return false;
                }
                else{
                    $(this).find('#vaanicampaigndisposition-short_code').next().next('.help-block' ).html('');
                }
                array.push(text);
                //push entered element in array
            }
        });
        if(chk == 1){
            return false;
        }
    }); 
</script>