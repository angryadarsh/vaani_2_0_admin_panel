<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\VaaniLeadDump;
use common\models\VaaniCrmLeadMapping;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniLeadDump */
/* @var $form yii\widgets\ActiveForm */

?>

<?php if($mapping_data || $columns){
    $column_count = 0; ?>
    
<!-- crm id -->
<div class="form-group field-vaanileadbatch-crm_group_id">
    <input type="hidden" id="vaanileadbatch-crm_group_id" class="form-control" name="VaaniLeadBatch[crm_group_id]" value="<?= $crm_id ?>">
    <div class="help-block"></div>
</div>

<!-- lead fields -->
<table class="table-responsive">
    <thead>
        <tr>
            <h4 class="text-center mb-20 pb-10">Lead & CRM Mapping Configuration</h4>
        </tr>
        <tr class="text-center">
            <th width="20%">Column Name</th>
            <th width="10%">Is Primary</th>
            <th width="10%">Is Callable</th>
            <th width="20%">Secondary Sequence</th>
            <th width="20%">CRM Field</th>
        </tr>
    </thead>
    <tbody>
    <?php if($mapping_data){ ?>
        <?php foreach($mapping_data as $key => $model){
            $crm_mapping = VaaniCrmLeadMapping::find()->where(['field_id' => $model['field_id']])->asArray()->one();
            // echo "<pre>"; print_r($crm_mapping);
        ?>
            <tr>
                <td>
                    <div class="form-group field-vaanileadmapping-field_name mr-10">
                        <?= Html::hiddenInput('VaaniLeadMapping[id]['.$column_count.']', $model['id'], ['id' => 'vaanileadmapping-id']) ?>
                        <?= Html::textInput('VaaniLeadMapping[field_name]['.$column_count.']', $model['field_name'], ['id' => 'vaanileadmapping-field_name', 'class' => 'form-control', 'required' => 'required', 'readOnly' => 'readOnly']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group field-vaanileadmapping-is_primary text-center">
                        <?= Html::checkbox('VaaniLeadMapping[is_primary]['.$column_count.']', $model['is_primary'], ['value' => $model['is_primary'],'id' => 'vaanileadmapping-is_primary', 'class' => 'h1 is_primary_checkbox', 'placeholder' => 'Is Primary?']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group field-vaanileadmapping-is_callable text-center">
                        <?= Html::checkbox('VaaniLeadMapping[is_callable]['.$column_count.']', $model['is_callable'], ['id' => 'vaanileadmapping-is_callable', 'class' => 'h1 is_callable_checkbox', 'placeholder' => 'Is Callable?']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group field-vaanileadmapping-secondary_sequence mr-10">
                        <?= Html::textInput('VaaniLeadMapping[secondary_sequence]['.$column_count.']', ($model ? $model['secondary_sequence'] : null), ['id' => 'vaanileadmapping-secondary_sequence', 'class' => 'form-control secondary_sequence', 'required' => 'required']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <!-- crm mapping fields -->
                <td>
                    <div class="form-group field-vaanileadmapping-crm_field_id mr-10">
                        <?= Html::dropDownList('VaaniLeadMapping[crm_field_id]['.$column_count.']', ($crm_mapping ? $crm_mapping['crm_field_id'] : null), $crm_fields, ['prompt' => 'Select...', 'class' => 'form-control','required' => 'required']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
            </tr>
        <?php $column_count++; ?>
        <?php } ?>
    <?php }else if($columns){ ?>
        <?php foreach($columns as $key => $value){
            if($value){ ?>
            <tr>
                <td>
                    <div class="form-group field-vaanileadmapping-field_name mr-10">
                        <?= Html::textInput('VaaniLeadMapping[field_name]['.$column_count.']', $value, ['id' => 'vaanileadmapping-field_name', 'class' => 'form-control', 'required' => 'required', 'readOnly' => 'readOnly']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group field-vaanileadmapping-is_primary text-center">
                        <?= Html::checkbox('VaaniLeadMapping[is_primary]['.$column_count.']', 0, ['id' => 'vaanileadmapping-is_primary', 'class' => 'h1 is_primary_checkbox', 'placeholder' => 'Is Primary?','required' => 'required']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group field-vaanileadmapping-is_callable text-center">
                        <?= Html::checkbox('VaaniLeadMapping[is_callable]['.$column_count.']', 0, ['id' => 'vaanileadmapping-is_callable', 'class' => 'h1 is_callable_checkbox', 'placeholder' => 'Is Callable?','required' => 'required']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <td>
                    <div class="form-group field-vaanileadmapping-secondary_sequence mr-10">
                        <?= Html::textInput('VaaniLeadMapping[secondary_sequence]['.$column_count.']', 0, ['id' => 'vaanileadmapping-secondary_sequence', 'class' => 'form-control secondary_sequence', 'required' => 'required']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
                <!-- crm mapping fields -->
                <td>
                    <div class="form-group field-vaanileadmapping-crm_field_id mr-10">
                        <?= Html::dropDownList('VaaniLeadMapping[crm_field_id]['.$column_count.']', null, $crm_fields, ['prompt' => 'Select...', 'class' => 'form-control crm_fields','required' => 'required']) ?>
                        <div class="help-block"></div>
                    </div>
                </td>
            </tr>
        <?php } ?>
        <?php $column_count++; ?>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
<?php } ?>
<script>
$(document).ready(function(){
    var is_primary_checkboxes = $('.is_primary_checkbox');
    var is_callable_checkboxes = $('.is_callable_checkbox');
    var crm_fields = $('.crm_fields');
    // for is_primary
    is_primary_checkboxes.change(function(){
        if($('.is_primary_checkbox:checked').length>0) {
            is_primary_checkboxes.removeAttr('required');
            is_callable_checkboxes.removeAttr('required');
        } else {
            is_primary_checkboxes.attr('required', 'required');
        }
    });
    // for is_callable
    is_callable_checkboxes.change(function(){
        console.log(is_callable_checkboxes.length);
        if($('.is_callable_checkbox:checked').length>0) {
            is_callable_checkboxes.removeAttr('required');
        } else {
            is_callable_checkboxes.attr('required', 'required');
        }
    });
    // for select crm fields
    $(".crmField").click(function(){
        var hasInput=false;
        $('.crm_fields').each(function () {
            if($(this).val()  !== ""){
                hasInput=true;
            }
        }); 
       if(!hasInput){
            crm_fields.attr('required', 'required');
        }else{
           crm_fields.removeAttr('required');
        }
    });
});
</script>