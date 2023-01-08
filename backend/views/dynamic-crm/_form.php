<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\VaaniDynamicCrm */
/* @var $form yii\widgets\ActiveForm */
$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'submitForm']); ?>
    
    <div class="row align-items-center form-box">
        <div class="col-lg-12">
            <?= $form->field($model, 'name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Crm Name'])->label('Crm Name', ['class' => 'form-label']) ?>
            <?= $form->field($model, 'crm_id')->hiddenInput()->label(false) ?>
        </div>
        <div class="col-md-12 top-heading p-0">
            <div class="clearfix heading">
                <h1 class="text-center">Form Designer</h1>
                <div id="fieldsBox" class="float-center">
                    <ul class="nav ms-sm-5">
                        <li class="nav-item textbox-menu" title="Text Input">
                            <a href="javascript:void(0)" class="nav-link"> <i class="fas fa-keyboard"></i></a>
                        </li>
                        <li class="nav-item drop-down-menu" title="DropDown">
                            <a href="javascript:void(0)" class="nav-link"> <i class="fas fa-caret-square-down"></i></a>
                        </li>
                        <li class="nav-item message-menu" title="Message Input">
                            <a href="javascript:void(0)" class="nav-link"> <i class="far fa-comment-alt"></i></a>
                        </li>
                        <li class="nav-item radio-menu" title="Radio Buttons">
                            <a href="javascript:void(0)" class="nav-link"> <i class="far fa-dot-circle"></i></a>
                        </li>
                        <li class="nav-item checkbox-menu" title="Checkboxes">
                            <a href="javascript:void(0)" class="nav-link"> <i class="far fa-check-square"></i></a>
                        </li>
                        <li class="nav-item range-menu" title="Range Slider">
                            <a href="javascript:void(0)" class="nav-link"> <i class="fas fa-horizontal-rule"></i></a>
                        </li>
                        <li class="nav-item button-menu" title="Button">
                            <a href="javascript:void(0)" class="nav-link"> <i class="far fa-hand-pointer"></i></a>
                        </li>
                    </ul>
                </div>
                
                <div class="float-right btn-group-box" style="display:block;">
                    <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
                    <button type="button" class="btn btn-primary" id="saveNextBtn" style="display: none;">Save & Next</button>
                    <button type="button" class="btn btn-secondary" id="cancelBtn">Close</button>
                    <button type="button" class="btn btn-primary" id="editBtn" style="display: none;">Edit</button>
                    <!-- <button type="button" class="btn btn-primary" id="previewBtn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Preview</button> -->
                </div>
            </div>
        </div>
        <!-- form elements buttons -->
        <div class="col-md-12">
            <div class="row">

                <!-- <div class="col-md-12">
                    <aside class="">
                        <div class="title-box">
                        <h4>Field Type</h4>
                        </div>
                        <ul class="nav flex-column">
                            <li class="nav-item textbox-menu">
                                <a href="#" class="nav-link"> <i class="fas fa-keyboard"></i></a>
                            </li>
                            <li class="nav-item drop-down-menu">
                                <a href="#" class="nav-link"> <i class="fas fa-caret-square-down"></i></a>
                            </li>
                            <li class="nav-item message-menu">
                                <a href="#" class="nav-link"> <i class="far fa-comment-alt"></i></a>
                            </li>
                            <li class="nav-item radio-menu">
                                <a href="#" class="nav-link"> <i class="far fa-dot-circle"></i></a>
                            </li>
                            <li class="nav-item checkbox-menu">
                                <a href="#" class="nav-link"> <i class="far fa-check-square"></i></a>
                            </li>
                            <li class="nav-item range-menu">
                                <a href="#" class="nav-link"> <i class="fas fa-horizontal-rule"></i></a>
                            </li>
                            <li class="nav-item button-menu">
                                <a href="#" class="nav-link"> <i class="far fa-hand-pointer"></i></a>
                            </li>
                        </ul>
                    </aside>
                </div> -->

                <!-- form view section -->
                <div class="row me-0 ms-0">
                    <div class="col-md-10 crm-content"></div>
                    <div id="formPreview" class="col-md-2 right-section">
                        <aside>
                            <div class="title-box">
                                <h4>Form Preview</h4>
                            </div>
                            <form action="" id="formCreatedData">
                                <div id="formdata"></div>
                                <div id="btn-footer" style="display: flex;
                                justify-content: center;"></div>
                            </form>
                    </aside>
                </div>
            </div>
        </div>
                        <!-- <div class="">
                            <form id="formDesigner" action="">
                                <div id="formRow" class="row">
                                    <div class="col-6">
                                        <div class="form-box">
                                            <div id="form_container_umkdd" class="form-container parent">
                                                <a href="#" class="text-danger deleteCurrentRow float-right" data-close="form_container_umkdd">x</a>
                                                <div class="mb-3 form-group">
                                                    <label for="" class="form-label">
                                                        <span class="label-text">Text box Label Name</span>
                                                        
                                                    </label>
                                                    <input type="" class="form-control" id="" placeholder="Text box" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-box">
                                            <div id="form_container_dptr2" class="form-container parent">
                                            <a href="#" class="text-danger deleteCurrentRow float-right" data-close="formContainer_xydo4i">x</a>
                                                <div class="mb-3 form-group">
                                                    <label for="" class="form-label">
                                                        <span class="label-text">Dropdown Label Name</span>
                                                        
                                                    </label>
                                                    <select class="form-control" id="dropdownField01"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-box">
                                            <div id="form_container_5mmcw" class="form-container parent">
                                                <a href="#" class="text-danger deleteCurrentRow float-right" data-close="formContainer_xydo4i">x</a>
                                                <div class="form-container">
                                                    <div class="mb-3 form-group">
                                                        <label for="" class="form-label">
                                                            <span class="label-text">Message Label Name</span>
                                                            
                                                        </label>
                                                        <textarea class="form-control" id="" rows="2" placeholder="Write here.."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-box">
                                            <div id="form_container_j7jl3" class="form-container parent">
                                                <a href="#" class="text-danger deleteCurrentRow float-right" data-close="formContainer_xydo4i">x</a>
                                                <div class="form-container">
                                                    <div class="mb-3 form-group">
                                                        <label class="form-label">
                                                            <span class="label-text">Radio Label Name</span>
                                                        </label>
                                                        <div class="form-check"><input class="form-check-input" type="radio" name="flexRadioDefault" id="" /><label class="form-check-label" for="radioField01">Default radio</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-box">
                                            <div id="form_container_ingkw" class="form-container parent">
                                                <a href="#" class="text-danger deleteCurrentRow float-right" data-close="formContainer_xydo4i">x</a>
                                                <div class="mb-3 form-group">
                                                    <label class="form-label">
                                                        <span class="label-text">Checkbox Label Name</span>
                                                    </label>
                                                    <div class="form-check"><input class="form-check-input" type="checkbox" value="" id="" /> <label class="form-check-label" for=""> Default checkbox</label></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-box">
                                            <div id="form_container_msttyf" class="form-container parent">
                                            <a href="#" class="text-danger deleteCurrentRow float-right" data-close="formContainer_xydo4i">x</a>
                                                <div class="mb-3 form-group">
                                                    <label for="exampleFormControlTextarea1" class="form-label">
                                                        <span class="label-text">Range Label Name</span>
                                                    </label> <input type="range" class="form-range" id="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 form-group form-button">
                                    <span id="form_container_g2hg1g" class="form-container parent active">
                                    <a href="#" class="text-danger deleteCurrentRow float-right" data-close="formContainer_xydo4i">x</a>
                                        <button type="button" class="btn btn-primary">Primary</button>
                                    </span>
                                </div>
                            </form>
                        </div> -->
                    <!-- </div>
                </div> -->

                <!-- <div class="col-md-10 content pl-0">
                    <div class="form-maker" id="formMaker_1">
                        <div class="form-maker-top clearfix">
                            <div class="field-title float-left">Text Box</div>
                            <div class="float-right"><a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" data-close="formRow_xydo4i">x</a></div>
                        </div>
                        <div class="form-field-description" id="formDescription_xydo4i">
                            <div class="row">
                                <div class="col-auto"><input type="text" class="form-control" required="" id="labelName_xydo4i" /> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span></div>
                                <div class="col-auto">
                                    <select class="form-control" aria-label="Default select example" required="" id="fieldType_xydo4i">
                                        <option>Field Type</option>
                                        <option value="alphanumeric">alphanumeric</option>
                                        <option value="numeric">numeric</option>
                                        <option value="email">email</option>
                                        <option value="text only">text only</option>
                                        <option value="date">date</option>
                                        <option value="time">time</option>
                                        <option value="date &amp; time">date &amp; time</option>
                                        <option value="password">password</option>
                                        <option value="decimal">decimal</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Textbox Type</label> <span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <select class="form-control" aria-label="Default select example" required="" id="textBoxRequiment_xydo4i">
                                        <option>Field's Requirement</option>
                                        <option value="mandatory">Madatory</option>
                                        <option value="optional">Optional</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown bootstrap-select show-tick">
                                        <select class="selectpicker" aria-label="Default select example" id="addtionalCriteriaxydo4i" multiple="" required="" data-live-search="true">
                                            <option value="masked_add_child">masked</option>
                                            <option value="partial masking_add_child">partial masking</option>
                                            <option value="searchable_add_child">searchable</option>
                                            <option value="unique_add_child">unique</option>
                                            <option value="condition_add_child">condition</option>
                                        </select>
                                        <button
                                            type="button"
                                            tabindex="-1"
                                            class="btn dropdown-toggle bs-placeholder btn-light"
                                            data-bs-toggle="dropdown"
                                            role="combobox"
                                            aria-owns="bs-select-3"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                            title="Nothing selected"
                                            data-id="addtionalCriteriaxydo4i"
                                        >
                                            <div class="filter-option">
                                                <div class="filter-option-inner"><div class="filter-option-inner-inner">Nothing selected</div></div>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-3" aria-autocomplete="list" /></div>
                                            <div class="inner show" role="listbox" id="bs-select-3" tabindex="-1" aria-multiselectable="true"><ul class="dropdown-menu inner show" role="presentation"></ul></div>
                                        </div>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label>
                                </div>
                                <div class="col-auto">
                                    <select class="form-control" aria-label="Default select example" required> <option>Field Type As Child</option></select>
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Field Type As Child</button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="addChild_xydo4i">
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="textbox">textbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="dropdown">dropdown</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="message">message</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="radio button">radio button</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="checkbox">checkbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="range">range</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="button">button</a></li>
                                        </ul>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Add Child</label>
                                </div>
                                <div class="col-auto"><a href="#" class="text-danger deleteCurrentRow" data-close="formContainer_xydo4i">x</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-maker" id="formMaker_2">
                        <div class="form-maker-top clearfix">
                            <div class="field-title float-left">Dropdown</div>
                            <div class="float-right"><a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" id="">x</a></div>
                        </div>
                        <div class="form-field-description" id="formDescription_diopf">
                            <div class="row">
                                <div class="col-auto"><input type="text" class="form-control" required="" id="labelName_diopf" /> <label for="staticEmail2" class="form-label">Label Name</label><span class="text-danger mandatory-mark">*</span></div>
                                <div class="col-auto">
                                    <input type="text" class="form-control" required="" id="optLvl01_diopf" /><label for="staticEmail2" class="form-label">01 Option name</label><span class="text-danger mandatory-mark">*</span>
                                    <a href="javascript:void(0)" class="more-options">+</a>
                                    <div class="dropdown-select-options mt-1">
                                        <div class="pos-rel mt-1 mb-3">
                                            <input type="text" class="form-control" required="" id="optLvl01_diopf" /><label for="staticEmail2" class="form-label">02 Option name</label><span class="text-danger mandatory-mark">*</span>
                                        </div>
                                        <div class="btn-box"><button type="button" class="btn btn-sm btn-primary addDropDownOption">+</button></div>
                                    </div>
                                </div>
                                <div class="col-auto ms-30">
                                    <select class="form-control" aria-label="Default select example" required="" id="textBoxRequiment_diopf">
                                        <option>Field's Requirement</option>
                                        <option value="mandatory">Madatory</option>
                                        <option value="optional">Optional</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown bootstrap-select show-tick">
                                        <select class="" aria-label="Default select example" required="" multiple="" data-live-search="true" id="addtionalCriteriadiopf">
                                            <option value="masked_add_child">masked</option>
                                            <option value="partial masking_add_child">partial masking</option>
                                            <option value="searchable_add_child">searchable</option>
                                            <option value="unique_add_child">unique</option>
                                            <option value="condition_add_child">condition</option>
                                        </select>
                                        <button
                                            type="button"
                                            tabindex="-1"
                                            class="btn dropdown-toggle bs-placeholder btn-light"
                                            data-bs-toggle="dropdown"
                                            role="combobox"
                                            aria-owns="bs-select-4"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                            title="Nothing selected"
                                            data-id="addtionalCriteriadiopf"
                                        >
                                            <div class="filter-option">
                                                <div class="filter-option-inner"><div class="filter-option-inner-inner">Nothing selected</div></div>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-4" aria-autocomplete="list" /></div>
                                            <div class="inner show" role="listbox" id="bs-select-4" tabindex="-1" aria-multiselectable="true"><ul class="dropdown-menu inner show" role="presentation"></ul></div>
                                        </div>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Field Type As Child</button>
                                        <ul class="dropdown-menu" id="addChild_diopf">
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="textbox">textbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="dropdown">dropdown</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="message">message</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="radio button">radio button</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="checkbox">checkbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="range">range</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="button">button</a></li>
                                        </ul>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Add Child</label>
                                </div>
                                <div class="col-auto"><a href="javascript:void(0)" class="text-danger deleteCurrentRow">x</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-maker" id="formMaker_3">
                        <div class="form-maker-top clearfix">
                            <div class="field-title float-left">Message</div>
                            <div class="float-right"><a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" id="">x</a></div>
                        </div>
                        <div class="form-field-description">
                            <div class="row">
                                <div class="col-auto"><input type="text" class="form-control" required="" id="labelName_2c24m" /> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span></div>
                                <div class="col-auto">
                                    <select class="form-control" aria-label="Default select example" required="">
                                        <option>Set Height</option>
                                        <option value="default_row">Default</option>
                                        <option value="row_3">Row 3</option>
                                        <option value="row_4">Row 4</option>
                                        <option value="row_5">Row 5</option>
                                        <option value="row_6">Row 6</option>
                                        <option value="row_7">Row 7</option>
                                        <option value="row_8">Row 8</option>
                                        <option value="row_9">Row 9</option>
                                        <option value="row_10">Row 10</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Max Row</label><span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-default btnPlaceholder" type="button" data-bs-toggle="dropdown" aria-expanded="false">Set Placeholder</button>
                                        <div class="dropdown-select-options mt-1">
                                            <div class="form-check"><input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" /> <label class="form-check-label" for="flexRadioDefault1">Write here</label></div>
                                            <div class="form-check"><input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" /> <label class="form-check-label" for="flexRadioDefault2"> Write in brief </label></div>
                                            <div class="pos-rel mt-2">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="Write your message" aria-label="Write your message" aria-describedby="button-addon2" />
                                                    <button class="btn btn-sm btn-outline-primary" type="button" id="button-addon2">Save</button> <label for="staticEmail2" class="form-label">Write your message</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Set Placeholder</label><span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <select class="form-control" aria-label="Default select example" required="" id="textBoxRequiment_2c24m">
                                        <option>Field's Requirement</option>
                                        <option value="mandatory">Madatory</option>
                                        <option value="optional">Optional</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Textbox Requirement</label>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown bootstrap-select show-tick">
                                        <select class="" aria-label="Default select example" id="addtionalCriteria2c24m" multiple="" required="" data-live-search="true">
                                            <option value="masked_add_child">masked</option>
                                            <option value="partial masking_add_child">partial masking</option>
                                            <option value="searchable_add_child">searchable</option>
                                            <option value="unique_add_child">unique</option>
                                            <option value="condition_add_child">condition</option>
                                        </select>
                                        <button
                                            type="button"
                                            tabindex="-1"
                                            class="btn dropdown-toggle bs-placeholder btn-light"
                                            data-bs-toggle="dropdown"
                                            role="combobox"
                                            aria-owns="bs-select-5"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                            title="Nothing selected"
                                            data-id="addtionalCriteria2c24m"
                                        >
                                            <div class="filter-option">
                                                <div class="filter-option-inner"><div class="filter-option-inner-inner">Nothing selected</div></div>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-5" aria-autocomplete="list" /></div>
                                            <div class="inner show" role="listbox" id="bs-select-5" tabindex="-1" aria-multiselectable="true"><ul class="dropdown-menu inner show" role="presentation"></ul></div>
                                        </div>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Add Criteria</label>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Dropdown button</button>
                                        <ul class="dropdown-menu" id="addChild_2c24m">
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="textbox">textbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="dropdown">dropdown</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="message">message</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="radio button">radio button</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="checkbox">checkbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="range">range</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="button">button</a></li>
                                        </ul>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Add Child</label>
                                </div>
                                <div class="col-auto"><a href="#" class="text-danger deleteCurrentRow">x</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-maker" id="formMaker_4">
                        <div class="form-maker-top clearfix">
                            <div class="field-title float-left">Radio Button</div>
                            <div class="float-right"><a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" id="">x</a></div>
                        </div>
                        <div class="form-field-description">
                            <div class="row">
                                <div class="col-auto"><input type="text" class="form-control" required="" id="labelName_31tdp" /> <label for="staticEmail2" class="form-label">Label Name</label><span class="text-danger mandatory-mark">*</span></div>
                                <div class="col-auto">
                                    <input type="text" class="form-control" required="" /><label for="staticEmail2" class="form-label">01 Option name</label><span class="text-danger mandatory-mark">*</span>
                                    <a href="javascript:void(0)" class="more-options">+</a>
                                    <div class="dropdown-select-options mt-1">
                                        <div class="pos-rel mt-1 mb-3"><input type="text" class="form-control" required="" /><label for="staticEmail2" class="form-label">02 Option name</label><span class="text-danger mandatory-mark">*</span></div>
                                        <div class="btn-box"><button type="button" class="btn btn-sm btn-primary addDropDownOption">+</button></div>
                                    </div>
                                </div>
                                <div class="col-auto ms-30">
                                    <select class="form-control" aria-label="Default select example" required="" id="textBoxRequiment_31tdp">
                                        <option>Field's Requirement</option>
                                        <option value="mandatory">Madatory</option>
                                        <option value="optional">Optional</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown bootstrap-select show-tick">
                                        <select class="" aria-label="Default select example" required="" multiple="" data-live-search="true" id="addtionalCriteria31tdp">
                                            <option value="masked_add_child">masked</option>
                                            <option value="partial masking_add_child">partial masking</option>
                                            <option value="searchable_add_child">searchable</option>
                                            <option value="unique_add_child">unique</option>
                                            <option value="condition_add_child">condition</option>
                                        </select>
                                        <button
                                            type="button"
                                            tabindex="-1"
                                            class="btn dropdown-toggle bs-placeholder btn-light"
                                            data-bs-toggle="dropdown"
                                            role="combobox"
                                            aria-owns="bs-select-6"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                            title="Nothing selected"
                                            data-id="addtionalCriteria31tdp"
                                        >
                                            <div class="filter-option">
                                                <div class="filter-option-inner"><div class="filter-option-inner-inner">Nothing selected</div></div>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-6" aria-autocomplete="list" /></div>
                                            <div class="inner show" role="listbox" id="bs-select-6" tabindex="-1" aria-multiselectable="true"><ul class="dropdown-menu inner show" role="presentation"></ul></div>
                                        </div>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Field Type As Child</button>
                                        <ul class="dropdown-menu" id="addChild_31tdp">
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="textbox">textbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="dropdown">dropdown</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="message">message</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="radio button">radio button</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="checkbox">checkbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="range">range</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="button">button</a></li>
                                        </ul>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Add Child</label>
                                </div>
                                <div class="col-auto"><a href="javascript:void(0)" class="text-danger deleteCurrentRow">x</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-maker" id="formMaker_5">
                        <div class="form-maker-top clearfix">
                            <div class="field-title float-left">Checkbox</div>
                            <div class="float-right"><a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" id="">x</a></div>
                        </div>
                        <div class="form-field-description">
                            <div class="row">
                                <div class="col-auto"><input type="text" class="form-control" required="" id="labelName_xo0rl" /> <label for="staticEmail2" class="form-label">Label Name</label><span class="text-danger mandatory-mark">*</span></div>
                                <div class="col-auto">
                                    <input type="text" class="form-control" required="" /><label for="staticEmail2" class="form-label">01 Option name</label><span class="text-danger mandatory-mark">*</span>
                                    <a href="javascript:void(0)" class="more-options">+</a>
                                    <div class="dropdown-select-options mt-1">
                                        <div class="pos-rel mt-1 mb-3"><input type="text" class="form-control" required="" /><label for="staticEmail2" class="form-label">02 Option name</label><span class="text-danger mandatory-mark">*</span></div>
                                        <div class="btn-box"><button type="button" class="btn btn-sm btn-primary addDropDownOption">+</button></div>
                                    </div>
                                </div>
                                <div class="col-auto ms-30">
                                    <select class="form-control" aria-label="Default select example" required="" id="textBoxRequiment_xo0rl">
                                        <option>Field's Requirement</option>
                                        <option value="mandatory">Madatory</option>
                                        <option value="optional">Optional</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown bootstrap-select show-tick">
                                        <select class="" aria-label="Default select example" required="" multiple="" data-live-search="true" id="addtionalCriteriaxo0rl">
                                            <option value="masked_add_child">masked</option>
                                            <option value="partial masking_add_child">partial masking</option>
                                            <option value="searchable_add_child">searchable</option>
                                            <option value="unique_add_child">unique</option>
                                            <option value="condition_add_child">condition</option>
                                        </select>
                                        <button
                                            type="button"
                                            tabindex="-1"
                                            class="btn dropdown-toggle bs-placeholder btn-light"
                                            data-bs-toggle="dropdown"
                                            role="combobox"
                                            aria-owns="bs-select-7"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                            title="Nothing selected"
                                            data-id="addtionalCriteriaxo0rl"
                                        >
                                            <div class="filter-option">
                                                <div class="filter-option-inner"><div class="filter-option-inner-inner">Nothing selected</div></div>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-7" aria-autocomplete="list" /></div>
                                            <div class="inner show" role="listbox" id="bs-select-7" tabindex="-1" aria-multiselectable="true"><ul class="dropdown-menu inner show" role="presentation"></ul></div>
                                        </div>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">Field Type As Child</button>
                                        <ul class="dropdown-menu" id="addChild_xo0rl">
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="textbox">textbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="dropdown">dropdown</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="message">message</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="radio button">radio button</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="checkbox">checkbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="range">range</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="button">button</a></li>
                                        </ul>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Add Child</label>
                                </div>
                                <div class="col-auto"><a href="javascript:void(0)" class="text-danger deleteCurrentRow">x</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-maker" id="formMaker_6">
                        <div class="form-maker-top clearfix">
                            <div class="field-title float-left">Range</div>
                            <div class="float-right"><a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" id="">x</a></div>
                        </div>
                        <div class="form-field-description">
                            <div class="row">
                                <div class="col-auto"><input type="text" class="form-control" required="" id="labelName_i54w3h" /> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span></div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-6"><input type="text" class="form-control" required="" id="minRange_i54w3h" /> <label for="staticEmail2" class="form-label">Min range</label> <span class="text-danger mandatory-mark">*</span></div>
                                        <div class="col-6"><input type="text" class="form-control" required="" id="maxRange_i54w3h" /><label for="staticEmail2" class="form-label">Max range</label> <span class="text-danger mandatory-mark">*</span></div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <select class="form-control" aria-label="Default select example" required="" id="textBoxRequiment_i54w3h">
                                        <option>Field's Requirement</option>
                                        <option value="mandatory">Madatory</option>
                                        <option value="optional">Optional</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown bootstrap-select show-tick">
                                        <select class="" aria-label="Default select example" id="addtionalCriteriai54w3h" multiple="" required="" data-live-search="true">
                                            <option value="masked_add_child">masked</option>
                                            <option value="partial masking_add_child">partial masking</option>
                                            <option value="searchable_add_child">searchable</option>
                                            <option value="unique_add_child">unique</option>
                                            <option value="condition_add_child">condition</option>
                                        </select>
                                        <button
                                            type="button"
                                            tabindex="-1"
                                            class="btn dropdown-toggle bs-placeholder btn-light"
                                            data-bs-toggle="dropdown"
                                            role="combobox"
                                            aria-owns="bs-select-8"
                                            aria-haspopup="listbox"
                                            aria-expanded="false"
                                            title="Nothing selected"
                                            data-id="addtionalCriteriai54w3h"
                                        >
                                            <div class="filter-option">
                                                <div class="filter-option-inner"><div class="filter-option-inner-inner">Nothing selected</div></div>
                                            </div>
                                        </button>
                                        <div class="dropdown-menu">
                                            <div class="bs-searchbox"><input type="search" class="form-control" autocomplete="off" role="combobox" aria-label="Search" aria-controls="bs-select-8" aria-autocomplete="list" /></div>
                                            <div class="inner show" role="listbox" id="bs-select-8" tabindex="-1" aria-multiselectable="true"><ul class="dropdown-menu inner show" role="presentation"></ul></div>
                                        </div>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Field Type As Child</button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="addChild_i54w3h">
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="textbox">textbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="dropdown">dropdown</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="message">message</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="radio button">radio button</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="checkbox">checkbox</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="range">range</a></li>
                                            <li><a class="dropdown-item select_child" href="javascript:void(0)" title="button">button</a></li>
                                        </ul>
                                    </div>
                                    <label for="staticEmail2" class="form-label static-fixed">Add Child</label>
                                </div>
                                <div class="col-auto"><a href="#" class="text-danger deleteCurrentRow">x</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-maker" id="formMaker_7">
                        <div class="form-maker-top clearfix">
                            <div class="field-title float-left">Button</div>
                            <div class="float-right"><a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields" id="">x</a></div>
                        </div>
                        <div class="form-field-description">
                            <div class="row">
                                <div class="col-auto"><input type="text" class="form-control" required="" id="labelName_jbbkt" /> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span></div>
                                <div class="col-auto">
                                    <select class="form-control" aria-label="Default select example" required="" id="buttonAction_jbbkt">
                                        <option>Button Type</option>
                                        <option value="button">button</option>
                                        <option value="submit">submit</option>
                                        <option value="reset">reset</option>
                                    </select>
                                    <label for="staticEmail2" class="form-label">Button Type</label> <span class="text-danger mandatory-mark">*</span>
                                </div>
                                <div class="col-auto"><a href="#" class="text-danger deleteCurrentRow">x</a></div>
                            </div>
                        </div>
                    </div>
                </div> -->


            <!-- </div>
        </div> -->

        <!-- <div class="col-lg-12 form-group text-center">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary submit-disposition']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
        </div> -->

    </div>
    
<?php ActiveForm::end(); ?>

<?php
// INCLUDE CSS FILES
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/bootstrap.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/bootstrap-select.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/font-awesome.min.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/roboto-font.css');
$this->registerCssFile(Yii::$app->request->baseUrl . '/css/crm-css/main.css');

// INCLUDE JS FILES
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/jquery-3.6.0.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/popper.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/bootstrap.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/bootstrap-select.min.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/sweetalert2@11.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/crm-js/main.js');
?>