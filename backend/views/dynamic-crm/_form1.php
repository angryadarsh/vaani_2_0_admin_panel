<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniDynamicCrm */
/* @var $form yii\widgets\ActiveForm */
$urlManager = Yii::$app->urlManager;

// INCLUDE CSS FILES
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/bootstrap.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/bootstrap-select.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/font-awesome.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/roboto-font.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/style.css');

?>

<main>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
        $('#LoadingBox').show(); 
    } "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>
    <div id="formDesigner" class="container-fluid mt-3">
        <div class="row top-heading">
            <div class="col-lg-12">
                <?= $form->field($model, 'name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Crm Name'])->label('Crm Name', ['class' => 'form-label']) ?>
                <?= $form->field($model, 'crm_id')->hiddenInput()->label(false) ?>
            </div>
            <div class="col-md-12">
                <div class="clearfix heading">
                    <!-- <h1 class="float-start">Form Designer</h1> -->
                    <div id="fieldsBox" class="float-center">
                        <ul class="nav ms-sm-5">
                            <li class="nav-item textbox-btn">
                                <a href="javascript:void(0)" class="nav-link"> Text box</a>
                            </li>
                            <li class="nav-item dropdown-btn">
                                <a href="javascript:void(0)" class="nav-link"> Dropdown</a>
                            </li>
                            <li class="nav-item message-btn">
                                <a href="javascript:void(0)" class="nav-link"> Message</a>
                            </li>
                            <li class="nav-item radio-btn">
                                <a href="javascript:void(0)" class="nav-link"> Radio button</a>
                            </li>
                            <li class="nav-item checkbox-btn">
                                <a href="javascript:void(0)" class="nav-link"> Checkbox</a>
                            </li>
                            <li class="nav-item range-btn">
                                <a href="javascript:void(0)" class="nav-link"> Range</a>
                            </li>
                            <li class="nav-item button-btn">
                                <a href="javascript:void(0)" class="nav-link"> Button</a>
                            </li>
                        </ul>
                    </div>
                    <div class="float-end btn-group-box">
                        <button type="button" class="btn btn-warning" id="saveBtn">Save</button>
                        <button type="button" class="btn btn-success" id="submitBtn" style="display: none;">Submit</button>
                        <!-- <button type="button" class="btn btn-warning" id="saveNextBtn" style="display: none;">Save & Next</button> -->
                        <button type="button" class="btn btn-secondary" id="cancelBtn">Close</button>
                        <button type="button" class="btn btn-primary" id="editBtn" style="display: none;">Edit</button>
                        <!-- <button type="button" class="btn btn-primary" id="previewBtn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Preview</button> -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row me-0 ms-0">
            <div class="col-md-12 crm-form-content"></div>
            
            <div id="formPreview" class="col-md-2 right-section hidden">
                <aside>
                    <div class="title-box">
                        <h4>Form Preview</h4>
                    </div>
                    <div id="formCreatedData">
                        <div id="formdata"></div>
                        <div id="btn-footer" style="text-align:center;"></div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <!-- <div id="ruleCreation" class="container-fluid mt-3 rule-creation" style="display: none;">
        <div class="row top-heading">
            <div class="col-md-12">
                <div class="clearfix heading">
                    <h1 class="float-start">Rule Creation</h1>
                    <div class="float-end">
                        <button type="button" class="btn btn-primary" id="previousBtn">Previous</button>
                        <button type="button" class="btn btn-theme" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Proceed</button> -->
                        <!-- <button type="button" class="btn btn-secondary">Reset</button> -->
                        <!-- <button type="button" class="btn btn-primary" id="previewBtn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Preview</button> -->
                    <!-- </div>
                </div>
            </div>
        </div>
        <div class="row me-0 ms-0">
            <div class="form-box ">
                <div class="row justify-content-md-center">
                    <div class="col-sm-6">
                        <div class="form-group row mb-3 mt-3">
                            <label for="inputField_5zvss" class="form-label col-md-3">Apply this on</label>
                            <div class="col-md-9">
                                <select class="selectpicker form-select" aria-label="Default select example" multiple required aria-label="Default select example" data-live-search="true">
                                    <option value="">New Lead</option>
                                    <option value="">Existing lead</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3 mt-3">
                            <label for="inputField_5zvss" class="form-label col-md-3">Created Fields list</label>
                            <div class="col-md-9">
                                <select class="selectpicker form-select" aria-label="Default select example" multiple required aria-label="Default select example" data-live-search="true">
                                    <option value="">Lead 01</option>
                                    <option value="">Lead 02</option>
                                    <option value="">Lead 03</option>
                                    <option value="">Lead 04</option>
                                    <option value="">Lead 05</option>
                                    <option value="">Lead 06</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-3 mt-3">
                            <label for="inputField_5zvss" class="form-label col-md-3">Value</label>
                            <div class="col-md-9">
                                <input class="form-control" type="text" placeholder="Value">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</main>

<?php
// INCLUDE JS FILES
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/jquery-3.6.0.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/popper.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/bootstrap.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/sweetalert2@11.js');
// $this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/bootstrap-select.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/script.js');
?>