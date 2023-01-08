<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniDynamicCrmSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Vaani Dynamic Crms';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- testing dropdown dependency -->

<?php /* $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']);

    $uni_id = rand();
    $labelName = "labelName_" . $uni_id;
    $textBoxRequiment = "textBoxRequiment_" . $uni_id;
    $addtionalCriteria = "addtionalCriteria" . $uni_id;
    $addChildFields = "addChild_" . $uni_id;
    $previewLabel = "previewLabel_" . $uni_id;
    $inputField = "inputField_" . $uni_id;
    $formContainer = "formContainer_" . $uni_id;
    $formDescription = "formDescription_" . $uni_id;
    $formRow = "formRow_" . $uni_id;
    $formMaker = "formMaker_" . $uni_id;
    $optLvl01 = "optLvl01_" . $uni_id;
    $optLvl02 = "optLvl02_" . $uni_id;
    $dropdown_select_options="dropdown_select_options_" . $uni_id; 
    $addDropDownOption="addDropDownOption_" . $uni_id;
    $moreOptions="moreOptions_" . $uni_id;
    $drop_down="drop_down_" . $uni_id;
    $optionSelected="optionSelected_" . $uni_id;
?>

    <div class="col-sm-3 col-xl-3 text-center">
        <div class="form-group">
            <div class="form-maker" id="<?= $formMaker ?>">
                <div class="form-maker-top clearfix">
                    <div class="field-title float-start"> Dropdown </div>
                    <div class="float-end">
                        <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" data-close="formRow_'+uni_id+'">x</a>
                    </div>
                </div>

                <div class="form-field-description" id="<?= $formDescription ?>">
                    <div class="row">
                        <div class="col-auto " >
                            <input type="text" class="form-control" required id="<?= $labelName ?>">
                            <input type="hidden" class="form-control" required id="<?= $optionSelected ?>">
                            <label for="staticEmail2" class="form-label">Label Name</label>
                            <span class="text-danger mandatory-mark">*</span>
                        </div>
                        <div class="col-auto dropdown_options">
                            <input type="text" class="form-control first_option" required id="<?= $optLvl01 ?>" >
                            <label for="staticEmail2" class="form-label">Option name</label>
                            <span class="text-danger mandatory-mark">*</span>
                            <i class="fa fa-check"></i>
                            <a href="javascript:void(0)" class="more-options" id="'+moreOptions+'">+</a>
                            <div class="dropdown-select-options mt-1" id="'+dropdown_select_options+'">
                                <div class="pos-rel mt-1 mb-3 "  id="labelRow__02">
                                    <input type="text" class="form-control options" required id="<?= $optLvl02 ?>">
                                    <label for="staticEmail2" class="form-label">Option name</label>
                                    <span class="text-danger mandatory-mark">*</span>
                                    <i class="fa fa-check"></i>
                                </div>
                                <div class="btn-box">
                                    <button type="button" class="btn btn-sm btn-primary '+addDropDownOption+'">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto ms-30">
                            <select class="form-select" aria-label="Default select example" required id="<?= $textBoxRequiment ?>">
                                <option >Field\'s Requirement</option>
                                <option value="mandatory">Madatory</option>
                                <option value="optional">Optional</option>
                            </select>
                            <label for="staticEmail2" class="form-label">Textbox Requirement</label>
                            <span class="text-danger mandatory-mark">*</span>
                        </div>
                        <div class="col-auto">
                            <select class="select_criteria" aria-label="Default select example" required multiple data-live-search="true" id="<?= $addtionalCriteria ?>">
                                
                            </select>
                            <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label>
                        </div>

                        <div class="col-auto">
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button>
                                <ul class="dropdown-menu" id="<?= $addChildFields ?>"></ul>
                            </div>
                            <label for="staticEmail2" class="form-label static-fixed">Add Child</label>
                        </div>
                        <div class="col-auto"> </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php ActiveForm::end(); */ ?>

<div class="vaani-dynamic-crm-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Vaani Dynamic Crm', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'crm_id',
            'name',
            'date_created',
            'date_modified',
            //'created_by',
            //'modified_by',
            //'created_ip',
            //'modified_ip',
            //'del_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
