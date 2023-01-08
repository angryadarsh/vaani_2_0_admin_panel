<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniCrm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniCampaignBreak */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row form-box">
    <div class="col-lg-2">
        <?= Html::hiddenInput('VaaniCrm[crm_id]', $crm_id, ['id' => 'vaanicrm-crm_id']) ?>
    </div>
</div>
<div class="row form-box pb-20">
    <div class="bold col-lg-2">Field Name</div>
    <div class="bold col-lg-2">Field Label</div>
    <div class="bold col-lg-2">Field Type</div>
    <div class="bold col-lg-2">Field Sequence</div>
    <div class="bold col-lg-1 mr-20 p-0">Is Required?</div>
    <div class="bold col-lg-1 m-0 p-0">Is Editable?</div>
</div>

<?php if($data){
    foreach($data as $key => $model){ ?>
        <div class="row form-box fields-list">
            <div class="col-lg-2">
                <?= Html::hiddenInput('VaaniCrm[field_ids][]', $model->crm_field_id, ['id' => 'vaanicrm-field_ids']) ?>

                <div class="form-group field-vaanicrm-field_names">
                    <?= Html::textInput('VaaniCrm[field_names][]', $model->field_name, ['id' => 'vaanicrm-field_names', 'class' => 'form-control', 'required' => 'required']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group field-vaanicrm-field_labels">
                    <?= Html::textInput('VaaniCrm[field_labels][]', $model->field_label, ['id' => 'vaanicrm-field_labels', 'class' => 'form-control', 'required' => 'required']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group field-vaanicrm-field_types">
                    <select id="vaanicrm-field_types" class="form-control" name="VaaniCrm[field_types][]" required="">
                        <option value="">Select...</option>
                        <option value="1" <?= ($model->field_type == 1) ? 'selected' : ''?>>Text</option>
                        <option value="2" <?= ($model->field_type == 2) ? 'selected' : ''?>>TextArea</option>
                        <option value="3" <?= ($model->field_type == 3) ? 'selected' : ''?>>Number</option>
                        <option value="4" <?= ($model->field_type == 4) ? 'selected' : ''?>>Email</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group field-vaanicrm-sequences">
                    <?= Html::textInput('VaaniCrm[sequences][]', $model->sequence, ['id' => 'vaanicrm-sequences', 'class' => 'form-control', 'required' => 'required']) ?>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-1 pl-5 mr-20">
                <div class="form-group field-vaanicrm-field_required">
                    <select id="vaanicrm-field_required" class="form-control" name="VaaniCrm[field_required][]" required="">
                        <option value="2" <?=$model->is_required == 2 ? 'selected' : ''?>>No</option>
                        <option value="1" <?=$model->is_required == 1 ? 'selected' : ''?>>Yes</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-1 pl-5">
                <div class="form-group field-vaanicrm-field_editable">
                    <select id="vaanicrm-field_editable" class="form-control" name="VaaniCrm[field_editable][]" required="">
                        <option value="2" <?=$model->is_required == 2 ? 'selected' : ''?>>No</option>
                        <option value="1" <?=$model->is_required == 1 ? 'selected' : ''?>>Yes</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-lg-1 m-0 p-0">
                <div class="form-group ml-20">
                    <?= Html::a('+', null, ['class' => 'btn btn-sm btn-success add-field']) ?>
                    <?= Html::a('-', null, ['class' => 'btn btn-sm btn-danger remove-field']) ?>
                </div>
            </div>
        </div>
    <?php } ?>
<?php }else{ ?>
    
    <div class="row form-box fields-list">
        <div class="col-lg-2">
            <div class="form-group field-vaanicrm-field_names">
                <?= Html::textInput('VaaniCrm[field_names][]', null, ['id' => 'vaanicrm-field_names', 'class' => 'form-control', 'required' => 'required']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group field-vaanicrm-field_labels">
                <?= Html::textInput('VaaniCrm[field_labels][]', null, ['id' => 'vaanicrm-field_labels', 'class' => 'form-control', 'required' => 'required']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group field-vaanicrm-field_types">
                <select id="vaanicrm-field_types" class="form-control" name="VaaniCrm[field_types][]" required="">
                    <option value="">Select...</option>
                    <option value="1">Text</option>
                    <option value="2">TextArea</option>
                    <option value="3">Number</option>
                    <option value="4">Email</option>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group field-vaanicrm-sequences">
                <?= Html::textInput('VaaniCrm[sequences][]', 0, ['id' => 'vaanicrm-sequences', 'class' => 'form-control', 'required' => 'required']) ?>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-1 pl-5 mr-20">
            <div class="form-group field-vaanicrm-field_required">
                <select id="vaanicrm-field_required" class="form-control" name="VaaniCrm[field_required][]" required="">
                    <option value="2">No</option>
                    <option value="1">Yes</option>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-1 pl-5">
            <div class="form-group field-vaanicrm-field_editable">
                <select id="vaanicrm-field_editable" class="form-control" name="VaaniCrm[field_editable][]" required="">
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                </select>
                <div class="help-block"></div>
            </div>
        </div>
        <div class="col-lg-1 m-0 p-0">
            <div class="form-group ml-20">
                <?= Html::a('+', null, ['class' => 'btn btn-sm btn-success add-field']) ?>
                <?= Html::a('-', null, ['class' => 'btn btn-sm btn-danger remove-field']) ?>
            </div>
        </div>
    </div>
<?php } ?>