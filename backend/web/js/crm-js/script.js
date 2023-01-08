
function uppend(type) {
   
    console.log(type);
    var rowNum = (Math.random() + 1).toString(36).substring(7);

    // ********** Global variables which can use as ID **********
    var labelName = "labelName_" + rowNum;
    var placeholderName="placeholderName_"+rowNum;
    var fieldType = "fieldType_" + rowNum;
    var textBoxRequiment = "textBoxRequiment_" + rowNum;
    var addtionalCriteria = "addtionalCriteria" + rowNum;
    var addChildFields = "addChild_" + rowNum;
    var maxRange = "maxRange_" + rowNum;
    var minRange = "minRange_" + rowNum;
    var buttonAction = "buttonAction_" + rowNum;
    var previewLabel = "previewLabel_" + rowNum;
    var inputField = "inputField_" + rowNum;
    var formContainer = "formContainer_" + rowNum;
    var formDescription = "formDescription_" + rowNum;
    var formRow = "formRow_" + rowNum;
    var dropdown_select_options= "dropdown_select_options_"+rowNum //created by gd and pb

    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#" + addChildFields);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });


 
    // ********** Add form field for TextBox **********
    var textBox_html = '<div class="form-field-description" id="' + formDescription + '"> <div class="row"> <div class="col-auto"> <input type="text" class="form-control" required id="' + labelName + '"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + fieldType + '"> <option>Field Type</option>  </select> <label for="staticEmail2" class="form-label">Textbox Type</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="" aria-label="Default select example" id="' + addtionalCriteria + '" multiple required aria-label="Default select example" data-live-search="true"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label></div> <div class="col-auto"> <!--<select class="form-select" aria-label="Default select example" required> <option>Field Type As Child</option></select>--> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="' + addChildFields + '"> </ul> </div> <label for="staticEmail2" class="form-label static-fixed">Add Child</label> </div> <div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow">x</a> </div> </div> </div>';
    // $(document).ready(function(){
    // textbox_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,textBoxRequiment,fieldType);
    // });
   
    // ********** Add form field for Dropdown **********
    var dropdown_radio_checkbox_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"> <input type="text" class="form-control" required id="' + labelName + '"> <label for="staticEmail2" class="form-label">Label Name</label><span class="text-danger mandatory-mark">*</span> </div><div class="col-auto"><input type="text" class="form-control" required><label for="staticEmail2" class="form-label">01 Option name</label><span class="text-danger mandatory-mark">*</span><a href="javascript:void(0)" class="more-options">+</a><div class="dropdown-select-options mt-1" id="' +dropdown_select_options+ '"><div class="pos-rel mt-1 mb-3"><input type="text" class="form-control" required><label for="staticEmail2" class="form-label">02 Option name</label><span class="text-danger mandatory-mark">*</span></div><div class="btn-box"><button type="button" class="btn btn-sm btn-primary addDropDownOption">+</button></div></div></div><div class="col-auto ms-30"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="" aria-label="Default select example" required multiple data-live-search="true" id="' + addtionalCriteria + '"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label> </div>';

    dropdown_radio_checkbox_html += '<div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" id="' + addChildFields + '"></ul> </div><label for="staticEmail2" class="form-label static-fixed">Add Child</label></div><div class="col-auto"> <a href="javascript:void(0)" class="text-danger deleteCurrentRow">x</a> </div> </div></div>';

    // ********** Add form field for Message **********
    var message_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"> <input type="text" class="form-control" required id="' + labelName + '"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span></div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required> <option>Set Height</option><option value="default_row">Default</option><option value="row_3">Row 3</option><option value="row_4">Row 4</option><option value="row_5">Row 5</option><option value="row_6">Row 6</option><option value="row_7">Row 7</option><option value="row_8">Row 8</option><option value="row_9">Row 9</option><option value="row_10">Row 10</option> </select> <label for="staticEmail2" class="form-label">Max Row</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <div class="dropdown"> <input type="text" class="placeholder_field" required="" placeholder="placeholderName" id="'+placeholderName+'"><div class="dropdown-select-options mt-1"> <div class="form-check"> <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"> <label class="form-check-label" for="flexRadioDefault1" >Write here</label></div> <div class="form-check"> <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2"> <label class="form-check-label" for="flexRadioDefault2"> Write in brief </label></div> <div class="pos-rel mt-2"> <div class="input-group"> <input type="text" class="form-control" placeholder="Write your message" aria-label="Write your message" aria-describedby="button-addon2"> <button class="btn btn-sm btn-outline-primary" type="button" id="button-addon2">Save</button> <label for="staticEmail2" class="form-label">Write your message</label> </div> </div> </div> </div> <label for="staticEmail2" class="form-label static-fixed">Set Placeholder</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label> </div>';

    message_html+= '<div class="col-auto"> <select class="" aria-label="Default select example" id="' + addtionalCriteria + '" multiple required aria-label="Default select example" data-live-search="true"> </select> <label for="staticEmail2" class="form-label static-fixed">Add Criteria</label> </div> <div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">  Dropdown button </button> <ul class="dropdown-menu" id="' + addChildFields + '"></ul> </div> <label for="staticEmail2" class="form-label static-fixed">Add Child</label> </div> <div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow">x</a> </div> </div> </div>';

    // ********** Add form field for Range **********
    var range_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"><input type="text" class="form-control" required id="' + labelName + '"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-3"><div class="row"> <div class="col-6"><input type="text" class="form-control" required id="' + minRange + '"> <label for="staticEmail2" class="form-label">Min range</label> <span class="text-danger mandatory-mark">*</span></div> <div class="col-6"><input type="text" class="form-control" required id="' + maxRange + '"><label for="staticEmail2" class="form-label">Max range</label> <span class="text-danger mandatory-mark">*</span></div></div></div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="" aria-label="Default select example" id="' + addtionalCriteria + '" multiple required aria-label="Default select example" data-live-search="true"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label></div> <div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="' + addChildFields + '"> </ul> </div> <label for="staticEmail2" class="form-label static-fixed">Add Child</label> </div><div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow">x</a> </div> </div> </div>';



    // ********** Add form button **********
    var button_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"><input type="text" class="form-control" required id="' + labelName + '"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + buttonAction + '"> <option>Button Type</option>  </select> <label for="staticEmail2" class="form-label">Button Type</label> <span class="text-danger mandatory-mark">*</span></div> <div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow">x</a> </div> </div> </div>';
    
    // $(document).ready(function(){
    // btn_preview_html(formRow,formContainer,inputField,previewLabel,inputField,buttonAction,labelName)
    // });

    // ********** Add preview **********
    // var textbox_preview_html = '<div class="form-row" id="'+formRow+'"><div class="form-container" id="'+formContainer+'"><div class="mb-3 form-group"> <label for="' + inputField + '" class="form-label" id="'+previewLabel+'"> </label> <input type="text" class="form-control" id="' + inputField + '" placeholder=""></div></div></div>';
    // $("#formCreatedData #"+formContainer).before(textbox_preview_html);

    if (type == 'text') {
        $(document).ready(function(){
        textbox_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,textBoxRequiment,fieldType);
        });
    // textbox_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,textBoxRequiment,fieldType);
     return [textBox_html, rowNum];
    }
    else if (type == 'drop') {
       
        return [dropdown_radio_checkbox_html, rowNum];
       
    }
    else if (type == 'description') {
        $(document).ready(function(){
            message_preview_html(formRow,formContainer,inputField,previewLabel,inputField,textBoxRequiment,labelName,placeholderName);
        });
        return [message_html, rowNum];
    }
    else if (type == 'radio_btn') {
        return [dropdown_radio_checkbox_html, rowNum];
    }
    else if (type == 'checkbox_btn') {
        return [dropdown_radio_checkbox_html, rowNum];
    }
    else if (type == 'range_btn') {
        $(document).ready(function(){
            range_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,minRange,maxRange,textBoxRequiment);
        });
        return [range_html, rowNum];
    }
    else if (type == 'button_btn') {
    $(document).ready(function(){
    btn_preview_html(formRow,formContainer,inputField,previewLabel,inputField,buttonAction,labelName,textBoxFieldTypes,rowNum)
    });

    console.log(button_html);
        return [button_html, rowNum];
    }


}

// ********** Additional Criteria (Should be Multi Select) **********
let addtionalOpt = ["masked", "partial masking", "searchable", "unique", "condition"];
function createSelectOpt(addtionalCriteria) {
    console.log(addtionalCriteria);
    var selectAddtionalCriteria = $("#" + addtionalCriteria);
    for (var i = 0; i < addtionalOpt.length; i++) {
        var opt = addtionalOpt[i];
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = opt + '_add_child';
        selectAddtionalCriteria.append(el);
    }
    selectAddtionalCriteria.selectpicker();
}

var textBoxFieldTypes = ["alphanumeric", "numeric", "email", "text only", "date", "time", "date & time", "password", "decimal"];
// var formMakerId = $(".form-maker").attr('id');
function fieldtype(uni_id) {
    var selectOpt = $("#fieldType_" + uni_id);
    for (var i = 0; i < textBoxFieldTypes.length; i++) {
        var opt = textBoxFieldTypes[i];
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = opt;
        selectOpt.append(el);
    }
}

function addchild_inputs(unique) {
    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#addChild_" + unique);
    console.log(selectAddChild);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });
}

// ********** Text Box onclick fuctionality **********
$(".textbox-btn").click(function () {
    $(".btn-group-box").show();

    // random string for row unique value
    var uni_id = (Math.random() + 1).toString(36).substring(7);
    var labelName = "labelName_" + uni_id;
    var fieldType = "fieldType_" + uni_id;
    var textBoxRequiment = "textBoxRequiment_" + uni_id;
    var addtionalCriteria = "addtionalCriteria" + uni_id;
    var addChildFields = "addChild_" + uni_id;
    var previewLabel = "previewLabel_" + uni_id;
    var inputField = "inputField_" + uni_id;
    var formContainer = "formContainer_" + uni_id;
    var formDescription = "formDescription_" + uni_id;
    var formRow = "formRow_" + uni_id;
    var formMaker = "formMaker_" + uni_id;

    $(".form-maker").attr("id", "formMaker_" + uni_id);

    // const last = formDescription.charAt(formDescription.length - 4);
    

    var textBox_html = '<div class="form-field-description" id="' + formDescription + '"> <div class="row"> <div class="col-auto"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_id][]"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_type][]" value="1"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="text" class="form-control" required id="' + labelName + '" name="VaaniDynamicCrmData[field_label][]"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + fieldType + '" name="VaaniDynamicCrmData[field_data_type][]"> <option>Field Type</option>  </select> <label for="staticEmail2" class="form-label">Textbox Type</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '" name="VaaniDynamicCrmData[is_required][]"> <option value="" >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="selectpicker" aria-label="Default select example" id="' + addtionalCriteria + '" multiple required aria-label="Default select example" data-live-search="true" name="VaaniDynamicCrmData[criteria][]"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label></div> <div class="col-auto"> <!--<select class="form-select" aria-label="Default select example" required> <option>Field Type As Child</option></select>--> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="' + addChildFields + '"> </ul> </div> <label for="staticEmail2" class="form-label static-fixed">Add Child</label> </div> <div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow" data-close="formContainer_'+uni_id+'">x</a> </div> </div> </div>';

    var html = '<div class="form-maker" id="' + formMaker + '"> <div class="form-maker-top clearfix"> <div class="field-title float-start"> Text Box </div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  data-close="formRow_'+uni_id+'">x</a> </div> </div>  ' + textBox_html + ' </div>';
    $(".crm-form-content").append(html);

    // rowNum++;
    // var formakerId = $(".form-maker").attr("id", "formMaker_" + rowNum);
    // var cls = document.getElementsByClassName("form-maker");
    // for (n = 0, length = cls.length; n < length; n++) {
    //     cls[n].id = "formMaker_" + (n + 1);
    // }
    createSelectOpt(addtionalCriteria);

    // ********** Type of fields **********
    var textBoxFieldTypes = ["alphanumeric", "numeric", "email", "text only", "date", "time", "date & time", "password", "decimal"];
    // var formMakerId = $(".form-maker").attr('id');
    var selectOpt = $("#fieldType_" + uni_id);
    for (var i = 0; i < textBoxFieldTypes.length; i++) {
        var opt = textBoxFieldTypes[i];
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = opt;
        selectOpt.append(el);
    }

    $('.selectpicker').selectpicker();

    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#" + addChildFields);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });

    // ********** form created data preview **********
    textbox_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,textBoxRequiment,fieldType);
});

// ********** Dropdown onclick fuctionality **********
$(".dropdown-btn").click(function () {
    $(".btn-group-box").show();

    // random string for row unique value
    var uni_id = (Math.random() + 1).toString(36).substring(7);
    var labelName = "labelName_" + uni_id;
    var textBoxRequiment = "textBoxRequiment_" + uni_id;
    var addtionalCriteria = "addtionalCriteria" + uni_id;
    var addChildFields = "addChild_" + uni_id;
    var previewLabel = "previewLabel_" + uni_id;
    var inputField = "inputField_" + uni_id;
    var formContainer = "formContainer_" + uni_id;
    var formDescription = "formDescription_" + uni_id;
    var formRow = "formRow_" + uni_id;
    var formMaker = "formMaker_" + uni_id;
    var optLvl01 = "optLvl01_" + uni_id;
    var optLvl02 = "optLvl01_" + uni_id;
    var dropdown_select_options="dropdown_select_options_"+uni_id;
    var addDropDownOption="addDropDownOption_"+uni_id
    var moreOptions="moreOptions_"+uni_id;
    var drop_down="drop_down_"+uni_id;//created by gd and pb
    

    // ********** Add form field for Dropdown **********
    var dropdown_radio_checkbox_html = '<div class="form-field-description" id="' + formDescription + '"> <div class="row"> <div class="col-auto"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_id][]"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_type][]" value="2"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="text" class="form-control" required id="' + labelName + '" name="VaaniDynamicCrmData[field_label][]"> <label for="staticEmail2" class="form-label">Label Name</label><span class="text-danger mandatory-mark">*</span> </div><div class="col-auto"><input type="text" class="form-control" required id="' + optLvl01 + '" name="VaaniDynamicCrmData[field_values][][]"><label for="staticEmail2" class="form-label">01 Option name</label><span class="text-danger mandatory-mark">*</span><a href="javascript:void(0)" class="more-options" id="'+moreOptions+'">+</a><div class="dropdown-select-options mt-1" id="'+dropdown_select_options+'"><div class="pos-rel mt-1 mb-3" id="labelRow__02"><input type="text" class="form-control" required id="' + optLvl02 + '" name="VaaniDynamicCrmData[field_values][][]"><label for="staticEmail2" class="form-label">02 Option name</label><span class="text-danger mandatory-mark">*</span></div><div class="btn-box"><button type="button" class="btn btn-sm btn-primary primary '+addDropDownOption+' ">+</button></div></div></div><div class="col-auto ms-30"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '" name="VaaniDynamicCrmData[is_required][]"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div>  <div class="col-auto"> <select class="" aria-label="Default select example" required multiple data-live-search="true" id="' + addtionalCriteria + '" name="VaaniDynamicCrmData[criteria][]"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label> </div>';

    dropdown_radio_checkbox_html += '<div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" id="' + addChildFields + '"></ul> </div><label for="staticEmail2" class="form-label static-fixed">Add Child</label></div><div class="col-auto"> <a href="javascript:void(0)" class="text-danger deleteCurrentRow">x</a> </div> </div></div>';

    var html = '<div class="form-maker" id="' + formMaker + '"> <div class="form-maker-top clearfix"> <div class="field-title float-start"> Dropdown </div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  id="">x</a> </div> </div>  ' + dropdown_radio_checkbox_html + ' </div>';
    $(".crm-form-content").append(html);

    // rowNum++;
    // var formakerId = $(".form-maker").attr("id", "formMaker_" + rowNum);
    var cls = document.getElementsByClassName("form-maker");
    console.log( cls);
    for (n = 0, length = cls.length; n < length; n++) {
        cls[n].id = "formMaker_" + (n + 1);
    }
    createSelectOpt(addtionalCriteria);

    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#" + addChildFields);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });

    // ********** form created data preview **********
    
    var drop_preview_html = '<div class="form-row" id="'+formRow+'"><div class="form-container" id="'+formContainer+'"><div class="mb-3 form-group"> <label for="' + inputField + '" class="form-label" id="'+previewLabel+'"> </label> <select class="form-control" id="'+drop_down+'"> </select></div></div></div>';
    $("#formCreatedData #formdata").append(drop_preview_html);

    $('#' + labelName).keyup(function () {
        var inputText = $(this).val();
        $("#"+previewLabel).text(inputText);
        $("#"+inputField).attr('placeholder',inputText);
        // console.log(inputText);
    });

    $('#' + optLvl01).keyup(function () {
        var inputText = $(this).val();
        // $("#"+previewLabel).text(inputText);
        $("#"+inputField).attr('placeholder',inputText);
        // console.log(inputText);
    });

    $(document).on('click','.'+addDropDownOption,function(){
       addoptions(dropdown_select_options);
    });

    // testing code
    $('#dropdownSelect').on('change', function(){
        alert($(this));
        console.log($(this));
    });

});

// ********** Message onclick fuctionality **********
$(".message-btn").click(function () {
    $(".btn-group-box").show();

    // random string for row unique value
    var uni_id = (Math.random() + 1).toString(36).substring(7);
    var labelName = "labelName_" + uni_id;
    var placeholderName="placeholderName_"+uni_id;
    var textBoxRequiment = "textBoxRequiment_" + uni_id;
    var addtionalCriteria = "addtionalCriteria" + uni_id;
    var addChildFields = "addChild_" + uni_id;
    var previewLabel = "previewLabel_" + uni_id;
    var inputField = "inputField_" + uni_id;
    var formContainer = "formContainer_" + uni_id;
    var formRow = "formRow_" + uni_id;

    var message_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_id][]"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_type][]" value="3"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="text" class="form-control" required id="' + labelName + '" name="VaaniDynamicCrmData[field_label][]"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span></div>';
    
    message_html += '<div class="col-auto"> <select class="form-select" aria-label="Default select example" required> <option>Set Height</option><option value="default_row">Default</option><option value="row_3">Row 3</option><option value="row_4">Row 4</option><option value="row_5">Row 5</option><option value="row_6">Row 6</option><option value="row_7">Row 7</option><option value="row_8">Row 8</option><option value="row_9">Row 9</option><option value="row_10">Row 10</option> </select> <label for="staticEmail2" class="form-label">Max Row</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <div class="dropdown"> <input type="text" class="placeholder_field" required="" placeholder="Placeholder" id="'+placeholderName+'"><div class="dropdown-select-options mt-1"> <div class="form-check"> <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"> <label class="form-check-label" for="flexRadioDefault1">Write here</label></div> <div class="form-check"> <input class="form-check-input" type="radio" name="flexRadioDefault1" id="flexRadioDefault2"> <label class="form-check-label" for="flexRadioDefault2"> Write in brief </label></div> <div class="pos-rel mt-2"> <div class="input-group"> <input type="text" class="form-control" placeholder="Write your message" aria-label="Write your message" aria-describedby="button-addon2"> <button class="btn btn-sm btn-outline-primary" type="button" id="button-addon2">Save</button> <label for="staticEmail2" class="form-label">Write your message</label> </div> </div> </div> </div> <label for="staticEmail2" class="form-label static-fixed">Set Placeholder</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '" name="VaaniDynamicCrmData[is_required][]"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label> </div>';

    message_html+= '<div class="col-auto"> <select class="" aria-label="Default select example" id="' + addtionalCriteria + '" multiple required aria-label="Default select example" data-live-search="true" name="VaaniDynamicCrmData[criteria][]"> </select> <label for="staticEmail2" class="form-label static-fixed">Add  Criteria</label></div> <div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">  Dropdown button </button> <ul class="dropdown-menu" id="' + addChildFields + '"></ul> </div> <label for="staticEmail2" class="form-label static-fixed">Add Child</label></div> <div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow">x</a> </div> </div> </div>';

    var html = '<div class="form-maker"> <div class="form-maker-top clearfix"> <div class="field-title float-start"> Message </div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  id="">x</a> </div> </div>  ' + message_html + ' </div>';
    $(".crm-form-content").append(html);

    // rowNum++;
    // var formakerId = $(".form-maker").attr("id", "formMaker_" + rowNum);
    var cls = document.getElementsByClassName("form-maker");
    for (n = 0, length = cls.length; n < length; n++) {
        cls[n].id = "formMaker_" + (n + 1);
    }
    createSelectOpt(addtionalCriteria);

    // ********** Type of fields **********
    var textBoxFieldTypes = ["alphanumeric", "numeric", "email", "text only", "date", "time", "date & time", "password", "decimal"];
    // var formMakerId = $(".form-maker").attr('id');
    var selectOpt = $("#fieldType_" + uni_id);
    for (var i = 0; i < textBoxFieldTypes.length; i++) {
        var opt = textBoxFieldTypes[i];
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = opt;
        selectOpt.append(el);
    }

    $('.selectpicker').selectpicker();

    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#" + addChildFields);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });

    /////////////////////// created by pb and gd
    message_preview_html(formRow,formContainer,inputField,previewLabel,inputField,textBoxRequiment,labelName,placeholderName);
//     $(".form-check input[type='radio']").click(function(){
//         checkedRadios()

//     });
//     function checkedRadios(){
//     var radios = document.getElementsByName('flexRadioDefault');
//     // console.log(radios);
//     for (var radio of radios)
//     {
//         console.log(radio);
//         console.log(radio.attr['id']);
//     if (radio. checked) {
    
//     radioChecked=radio;
//     // alert(radioChecked);
    
//     }
// }
//     };
   

    //////////////////////

});

// ********** Radio Button onclick fuctionality **********
$(".radio-btn").click(function () {
    $(".btn-group-box").show();

    // random string for row unique value
    var uni_id = (Math.random() + 1).toString(36).substring(7);
    var labelName = "labelName_" + uni_id;
    var textBoxRequiment = "textBoxRequiment_" + uni_id;
    var addtionalCriteria = "addtionalCriteria" + uni_id;
    var addChildFields = "addChild_" + uni_id;

    // ********** Add form field for Dropdown **********
    var dropdown_radio_checkbox_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_id][]"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_type][]" value="4"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="text" class="form-control" required id="' + labelName + '" name="VaaniDynamicCrmData[field_label][]"> <label for="staticEmail2" class="form-label">Label Name</label><span class="text-danger mandatory-mark">*</span> </div><div class="col-auto"><input type="text" class="form-control" required><label for="staticEmail2" class="form-label">01 Option name</label><span class="text-danger mandatory-mark">*</span><a href="javascript:void(0)" class="more-options">+</a><div class="dropdown-select-options mt-1"><div class="pos-rel mt-1 mb-3"><input type="text" class="form-control" required><label for="staticEmail2" class="form-label">02 Option name</label><span class="text-danger mandatory-mark">*</span></div><div class="btn-box"><button type="button" class="btn btn-sm btn-primary addDropDownOption">+</button></div></div></div><div class="col-auto ms-30"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '" name="VaaniDynamicCrmData[is_required][]"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div>  <div class="col-auto"> <select class="" aria-label="Default select example" required multiple data-live-search="true" id="' + addtionalCriteria + '" name="VaaniDynamicCrmData[criteria][]"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label> </div>';
    dropdown_radio_checkbox_html += '<div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" id="' + addChildFields + '"></ul> </div><label for="staticEmail2" class="form-label static-fixed">Add Child</label></div><div class="col-auto"> <a href="javascript:void(0)" class="text-danger deleteCurrentRow">x</a> </div> </div></div>';

    var html = '<div class="form-maker"> <div class="form-maker-top clearfix"> <div class="field-title float-start"> Radio Button </div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  id="">x</a> </div> </div>  ' + dropdown_radio_checkbox_html + ' </div>';
    $(".crm-form-content").append(html);

    // rowNum++;
    // var formakerId = $(".form-maker").attr("id", "formMaker_" + rowNum);
    var cls = document.getElementsByClassName("form-maker");
    for (n = 0, length = cls.length; n < length; n++) {
        cls[n].id = "formMaker_" + (n + 1);
    }
    createSelectOpt(addtionalCriteria);

    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#" + addChildFields);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });

});

// ********** Checkbox onclick fuctionality **********
$(".checkbox-btn").click(function () {
    $(".btn-group-box").show();
    // random string for row unique value
    var uni_id = (Math.random() + 1).toString(36).substring(7);
    var labelName = "labelName_" + uni_id;
    var textBoxRequiment = "textBoxRequiment_" + uni_id;
    var addtionalCriteria = "addtionalCriteria" + uni_id;
    var addChildFields = "addChild_" + uni_id;

    // ********** Add form field for Dropdown **********
    var dropdown_radio_checkbox_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_id][]"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_type][]" value="5"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="text" class="form-control" required id="' + labelName + '" name="VaaniDynamicCrmData[field_label][]"> <label for="staticEmail2" class="form-label">Label Name</label><span class="text-danger mandatory-mark">*</span> </div><div class="col-auto"><input type="text" class="form-control" required><label for="staticEmail2" class="form-label">01 Option name</label><span class="text-danger mandatory-mark">*</span><a href="javascript:void(0)" class="more-options">+</a><div class="dropdown-select-options mt-1"><div class="pos-rel mt-1 mb-3"><input type="text" class="form-control" required><label for="staticEmail2" class="form-label">02 Option name</label><span class="text-danger mandatory-mark">*</span></div><div class="btn-box"><button type="button" class="btn btn-sm btn-primary addDropDownOption">+</button></div></div></div><div class="col-auto ms-30"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '" name="VaaniDynamicCrmData[is_required][]"> <option >Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div>  <div class="col-auto"> <select class="" aria-label="Default select example" required multiple data-live-search="true" id="' + addtionalCriteria + '" name="VaaniDynamicCrmData[criteria][]"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label> </div>';
    dropdown_radio_checkbox_html += '<div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" id="' + addChildFields + '"></ul> </div><label for="staticEmail2" class="form-label static-fixed">Add Child</label></div><div class="col-auto"> <a href="javascript:void(0)" class="text-danger deleteCurrentRow">x</a> </div> </div></div>';

    var html = '<div class="form-maker"> <div class="form-maker-top clearfix"> <div class="field-title float-start"> Checkbox</div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  id="">x</a> </div> </div>  ' + dropdown_radio_checkbox_html + ' </div>';
    $(".crm-form-content").append(html);

    // rowNum++;
    // var formakerId = $(".form-maker").attr("id", "formMaker_" + rowNum);
    var cls = document.getElementsByClassName("form-maker");
    for (n = 0, length = cls.length; n < length; n++) {
        cls[n].id = "formMaker_" + (n + 1);
    }
    createSelectOpt(addtionalCriteria);

    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#" + addChildFields);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });

});

// ********** Range onclick fuctionality **********
$(".range-btn").click(function () {
    $(".btn-group-box").show();

    // random string for row unique value
    var uni_id = (Math.random() + 1).toString(36).substring(7);
    var labelName = "labelName_" + uni_id;
    var textBoxRequiment = "textBoxRequiment_" + uni_id;
    var addtionalCriteria = "addtionalCriteria" + uni_id;
    var addChildFields = "addChild_" + uni_id;
    var maxRange = "maxRange_" + uni_id;
    var minRange = "minRange_" + uni_id;
    var previewLabel = "previewLabel_" + uni_id;
    var inputField = "inputField_" + uni_id;
    var formContainer = "formContainer_" + uni_id;
    var formRow = "formRow_" + uni_id;


    // ********** Add form field for Range **********
    var range_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_id][]"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_type][]" value="6"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="text" class="form-control" required id="' + labelName + '" name="VaaniDynamicCrmData[field_label][]"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-3"><div class="row"> <div class="col-6"><input type="text" class="form-control" required id="' + minRange + '"> <label for="staticEmail2" class="form-label">Min range</label> <span class="text-danger mandatory-mark">*</span></div> <div class="col-6"><input type="text" class="form-control" required id="' + maxRange + '"><label for="staticEmail2" class="form-label">Max range</label> <span class="text-danger mandatory-mark">*</span></div></div></div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '" name="VaaniDynamicCrmData[is_required][]"> <option>Field\'s Requirement</option> <option value="mandatory">Mandatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="" aria-label="Default select example" id="' + addtionalCriteria + '" multiple required aria-label="Default select example" data-live-search="true" name="VaaniDynamicCrmData[criteria][]"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label></div> <div class="col-auto"> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="' + addChildFields + '"> </ul> </div> <label for="staticEmail2" class="form-label static-fixed">Add Child</label> </div><div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow">x</a> </div> </div> </div>';

    var html = '<div class="form-maker"> <div class="form-maker-top clearfix"> <div class="field-title float-start"> Range </div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  id="">x</a> </div> </div>  ' + range_html + ' </div>';
    $(".crm-form-content").append(html);

    // rowNum++;
    // var formakerId = $(".form-maker").attr("id", "formMaker_" + rowNum);
    var cls = document.getElementsByClassName("form-maker");
    for (n = 0, length = cls.length; n < length; n++) {
        cls[n].id = "formMaker_" + (n + 1);
    }
    createSelectOpt(addtionalCriteria);

    // for (var i = 0; i < fieldTypes.length; i++) {
    //     var opt = fieldTypes[i];
    //     var el = document.createElement("li");
    //     el.textContent = opt;
    //     el.value = opt + '_add_child';
    //     selectAddChild.append(el);
    // }

    $('.selectpicker').selectpicker();

    // ********** Type of fields that user can add as child **********
    var obj = {
        "achor": [{
            "name": "textbox",
            "title": "textbox"
        }, {
            "name": "dropdown",
            "title": "dropdown"
        }, {
            "name": "message",
            "title": "message"
        }, {
            "name": "radio button",
            "title": "radio button"
        }, {
            "name": "checkbox",
            "title": "checkbox"
        }, {
            "name": "range",
            "title": "range"
        }, {
            "name": "button",
            "title": "button"
        }]
    };
    var selectAddChild = $("#" + addChildFields);
    $(obj.achor).each(function (i, item) {
        $("<li/>").html($("<a class='dropdown-item select_child' href='javascript:void(0)'>").attr("title", item.title).text(item.name)).appendTo(selectAddChild);
    });


    range_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,minRange,maxRange,textBoxRequiment);
});


// ********** Button onclick fuctionality **********
$(".button-btn").click(function () {
    $(".btn-group-box").show();

    // random string for row unique value
    var uni_id = (Math.random() + 1).toString(36).substring(7);
    var labelName = "labelName_" + uni_id;
    var buttonAction = "buttonAction_" + uni_id;
    var previewLabel = "previewLabel_" + uni_id;
    var inputField = "inputField_" + uni_id;
    var formContainer = "formContainer_" + uni_id;
    var formRow = "formRow_" + uni_id;
    var addtionalCriteria = "addtionalCriteria" + uni_id;


    // ********** Add form button **********
    var button_html = '<div class="form-field-description"> <div class="row"> <div class="col-auto"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_id][]"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[field_type][]" value="7"> <input type="hidden" class="form-control" name="VaaniDynamicCrmData[level][]" value="1"> <input type="text" class="form-control" required id="' + labelName + '" name="VaaniDynamicCrmData[field_label][]"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + buttonAction + '"> <option>Button Type</option>  </select> <label for="staticEmail2" class="form-label">Button Type</label> <span class="text-danger mandatory-mark">*</span></div> <div class="col-auto"> <a href="#" class="text-danger deleteCurrentRow">x</a> </div> </div> </div>';

    // id="' + addtionalCriteria + '"

    var html = '<div class="form-maker"> <div class="form-maker-top clearfix"> <div class="field-title float-start"> Button </div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  id="">x</a> </div> </div>  ' + button_html + ' </div>';
    $(".crm-form-content").append(html);

    // rowNum++;
    // var formakerId = $(".form-maker").attr("id", "formMaker_" + rowNum);
    var cls = document.getElementsByClassName("form-maker");
    for (n = 0, length = cls.length; n < length; n++) {
        cls[n].id = "formMaker_" + (n + 1);
    }

    // // ********** Type of button **********
    // var textBoxFieldTypes = ["button", "submit", "reset"];
    // // var formMakerId = $(".form-maker").attr('id');
    // var selectOpt = $("#buttonAction_" + uni_id);
    // for (var i = 0; i < textBoxFieldTypes.length; i++) {
    //     var opt = textBoxFieldTypes[i];
    //     var el = document.createElement("option");
    //     el.textContent = opt;
    //     el.value = opt;
    //     selectOpt.append(el);
    // }

   
    btn_preview_html(formRow,formContainer,inputField,previewLabel,inputField,buttonAction,labelName,textBoxFieldTypes,uni_id);
});

// ********** User can add options for dropdown, radio button and checkbox **********

// ********** User can add options for dropdown, radio button and checkbox **********
function addoptions(dropdown_select_options){
    var ele_id = ('#'+dropdown_select_options);
    var count=0;
    // labelRow=$('.btn-box').prev().find('.pos-rel').prevObject[0].id;
    labelRow=$(ele_id).find('.btn-box').prev().find('.pos-rel').prevObject[0].id;
    console.log(labelRow);

    if(labelRow.slice(-2) > 01){
      count=labelRow.slice(-2);
      count++;
      if(count<10){
        count='0'+count;
       }
       else{
           count=count;
       }
    }
    
    var html = '<div class="pos-rel mt-1 mb-3"  id="labelRow__'+count+'"><input type="text" class="form-control options" required name="VaaniDynamicCrmData[field_values][][]"><label for="staticEmail2" class="form-label">' + count + ' Option name</label><span class="text-danger mandatory-mark">*</span><a href="javascript:void(0)" class="text-danger delete-btn">x</a></div>';
    
    newLabelrow='#'+labelRow;
    $(ele_id).find(newLabelrow).after(html);


    //////////////
    console.log($(ele_id).find('.pos-rel').length);
    for(var i=0;i<$('.pos-rel').length;i++){
        $option_arr=[];
        if($('.pos-rel .options').change(function(){
            $option_arr[i]=$('.pos-rel .options').val();
        //     console.log($option_arr);
        //  console.log($('.options').val());
        }))
        

        
        console.log($('.pos-rel .options').val());
    }

};

/* var labelRow = 2;
$(document).on('click', '.addDropDownOption', function () {
    //console.log(11);
    
    // alert(labelRow);
    labelRow++;
    // dropdown_select_options_;
    var html = '<div class="pos-rel mt-1 mb-3" data-prev='+labelRow+' id="labelRow__'+labelRow+'"><input type="text" class="form-control" required><label for="staticEmail2" class="form-label">0' + labelRow + ' Option name</label><span class="text-danger mandatory-mark">*</span><a href="javascript:void(0)" class="text-danger delete-btn">x</a></div>';
    
    // console.log("labelRow__"+labelRow);
    // $(".dropdown-select-options .pos-rel").after(html);
    $(this).find('.pos-rel').after(html);
//    console.log(($("labelRow__"+labelRow).attr('prev')));
//    $(this).prev().
 prevData=$(".dropdown-select-options").attr('id');
 console.log(prevData);
//  if(prevData==2 || prevData >2){
// labelRow++;
//  }

}); */

// ********** User can delete options for dropdown, radio button and checkbox **********
$(document).on('click', '.delete-btn', function () {
    $(this).parent().remove();
});

// ********** Create and add childs **********
$(document).on('click', ".select_child", function () {
    var getTitletxt = $(this).attr('title');
    // alert(getTitletxt);
    var tmp_html = '';
    var $id = $(this).parents(".form-field-description");
    
    // ********** Add child as textbox **********
    if (getTitletxt == "textbox") {
        // var nextHtml = $('.form-field-description.current').clone().addClass('child-0' + rowNum).attr('data-title', getTitletxt)
        tmp_html = uppend('text');
        $id.parent().append(tmp_html[0]).attr('data-title', getTitletxt);
        // $id.addClass("parent current");
        // $('.form-field-description.current').after(nextHtml);
        // $('.form-field-description.current').removeClass('current');
    }
    // ********** Add child as dropdown **********
    else if (getTitletxt == "dropdown") {
        tmp_html = uppend('drop');
        $id.parent().append(tmp_html[0]).attr('data-title', getTitletxt);
    }
    // ********** Add child as message **********
    else if (getTitletxt == "message") {
        tmp_html = uppend('description');
        $id.parent().append(tmp_html[0]).attr('data-title', getTitletxt);
    }
    // ********** Add child as radio button **********
    else if (getTitletxt == "radio button") {
        tmp_html = uppend('radio_btn');
        $id.parent().append(tmp_html[0]).attr('data-title', getTitletxt);
    }
    // ********** Add child as checkbox **********
    else if (getTitletxt == "checkbox") {
        tmp_html = uppend('checkbox_btn');
        $id.parent().append(tmp_html[0]).attr('data-title', getTitletxt);
    }
    // ********** Add child as Range **********
    else if (getTitletxt == "range") {
        tmp_html = uppend('range_btn');
        $id.parent().append(tmp_html[0]).attr('data-title', getTitletxt);
    } 
    // ********** Add child as Button **********
    else if (getTitletxt == "button") {
        tmp_html = uppend('button_btn');
        $id.parent().append(tmp_html[0]).attr('data-title', getTitletxt);
    }
    $('.selectpicker').selectpicker();
    createSelectOpt("addtionalCriteria" + tmp_html[1]);
    fieldtype(tmp_html[1]);
    addchild_inputs(tmp_html[1]);
});

// ********** Delete all parent and childs fields **********
$(document).on('click', '.closeCurrentFields', function () {
    Swal.fire({
        title: "Are you sure?",
        text: "You will not be able to recover the item!",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $(this).parent().parent().parent().remove();
            // $("#formCreatedData").html('');
            $rmv_div = $(this).attr('data-close');
            $('#formCreatedData').find('#'+$rmv_div).remove();
            Swal.fire("Deleted!", "The item has been deleted.", "success");
        }
    });
});

// **********  Delete parent and childs fields **********
$("#cancelBtn").click(function () {
    Swal.fire({
        title: "Are you sure?",
        text: "This action will delete everything",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $(".form-maker").remove();
            $("#formCreatedData").html('');
            Swal.fire("Deleted!", "The item has been deleted.", "success");
        }
    });

    $(".btn-group-box").hide(); 
});

// $(document).on("click", ".addChild", function () {
//     rowNum++;
//     var $id = $(this).parents(".form-field-description");
//     var nextHtml = $id.clone();
//     nextHtml.attr("id", "id_" + rowNum);
//     $id.after(nextHtml);
//     $id.addClass("parent child-01");
//     $(this).parents(".form-field-description:first-child").removeClass("child-01");
//     $(this).attr("id", "addChild_" + rowNum);
//     // $id.children().find(".deleteCurrentRow").attr("id", "id_" + rowNum);
// });

// ********** Delete selective parent and child fields **********
$(document).on("click", ".deleteCurrentRow", function () {

    $rmv_div = $(this).attr('data-close');
    $('#formCreatedData').find('#'+$rmv_div).remove();
    // console.log($(this).parents('.form-maker').children().length);
    // var formMaker_ID = document.querySelector('.form-maker').id;
    // var formRow_ID = document.querySelector('.form-row').id;
    var form_maker = $('.form-maker').length;

    // var fm_uni_id = formMaker_ID.substr(formMaker_ID.length - 5);
    // var fr_uni_id = formRow_ID.substr(formRow_ID.length - 5);
    // console.log(fr_uni_id);
    // if($('.form-field-description').length == 2){
        console.log($(this).parents('.form-maker').children().length);
    if ($(this).parents('.form-maker').children().length == 2) {
        console.log("Inside If");
        // $(".form-maker").remove();
        $(this).parents('.form-maker').remove();
        // var formDescription = $(this).parents('#'+formDescription);
        // console.log(formDescription);
        // $(this).parents('.li').remove();
        if (form_maker == 1)
            $(".btn-group-box").hide();

        // } else if($('.form-field-description').length < 2){
    } else {
        console.log("Inside else");
        $('.form-field-description').removeClass("parent child-01");
        $(this).parents('.form-field-description').remove();
        
        // var fd_uni_id = formDescription.substr(formDescription.length - 5);
    }
});

$(document).on("click", "#closeCurrentAllFieldsBtn", function () {
    $(this).parent().parent().parent().parent().parent().remove();
    // $(this).parent().parent().parent().parent().parent().remove();
    // $(this).attr('data-obj').parent('.form-maker');
    $('#closeCurrentFieldsModal').hide();
});

// To open and close 
$(document).on("click", ".more-options", function () {
    // alert("More");
    if (!$(".dropdown-select-options").hasClass("opened")) {
        $(this).text("-").addClass("text-danger");
    } else {
        $(this).text("+").removeClass("text-danger");
    }
    $(this).siblings().toggleClass("opened");
});

// $(document).on("click", ".btnPlaceholder", function () {
//     $(this).siblings().toggleClass("opened");
// });

$("#saveBtn").click(function(){
    $("#fieldsBox, .crm-form-content").hide();
    $("#formPreview").removeClass();
    $("#formPreview").addClass("form-box");
    $("#formPreview aside").addClass("container");
    $("#formCreatedData #formdata").addClass("row");
    $(".form-row").addClass("col-lg-6");
    $("#submitBtn, #editBtn").show();
    // $("#saveNextBtn, #editBtn").show();
    $("#saveBtn, #cancelBtn").hide();
});

$("#editBtn").click(function(){
    $("#fieldsBox, .crm-form-content").show();
    $("#formPreview").addClass("col-md-2 right-section hidden");
    $("#formPreview").removeClass("form-box");
    $("#formPreview aside").removeClass("container");
    $("#formCreatedData #formdata").removeClass("row");
    $(".form-row").removeClass("col-lg-6");
    $("#submitBtn, #editBtn").hide();
    // $("#saveNextBtn, #editBtn").hide();
    $("#saveBtn, #cancelBtn").show();
});

$("#previousBtn").click(function(){
    $("#fieldsBox, .crm-form-content").hide();
    $("#formPreview").removeClass();
    $("#formPreview").addClass("form-box");
    $("#formPreview aside").addClass("container");
    $("#formCreatedData #formdata").addClass("row");
    $(".form-row").addClass("col-lg-6");
    $("#submitBtn, #editBtn").show();
    // $("#saveNextBtn, #editBtn").show();
    $("#saveBtn, #cancelBtn").hide();
    $("#formDesigner").show();
    $("#ruleCreation").hide();
});

$("#saveNextBtn").click(function(){
    // alert("Hi");
    $("#formDesigner").hide();
    $("#ruleCreation").show();
});

// SUBMIT CRM FIELDS FORM
$("#submitBtn").click(function(){
    $("#submitForm").submit();
});


//Textbox form preview 
function textbox_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,textBoxRequiment,fieldType){
    var textbox_preview_html = '<div class="form-row" id="'+formRow+'"><div class="form-container" id="'+formContainer+'"><div class="mb-3 form-group"> <label for="' + inputField + '" class="form-label" id="'+previewLabel+'"> </label> <input type="text" class="form-control" id="' + inputField + '" placeholder=""></div></div></div>';
    // console.log(textbox_preview_html);
    $("#formCreatedData #formdata").append(textbox_preview_html);

    $('#' + labelName).keyup(function () {
        var inputText = $(this).val();
        $("#"+previewLabel).text(inputText);
        $("#"+inputField).attr('placeholder',inputText);
        // console.log(inputText);
    });

    var mandatory_html = '<span class="text-danger mandatory-mark">*</span>';
    $('#' + textBoxRequiment).change(function () {
        var requirement = $(this).val();
        // console.log(requirement);
        if(requirement == "mandatory"){
            $("#"+previewLabel).append(mandatory_html);
            $("#"+inputField).attr('required', 'required');
        }else if(requirement == "optional"){
            $("#"+previewLabel+" .mandatory-mark").remove();
            $("#"+inputField).removeAttr('required');
        }
    });

    $('#' + fieldType).change(function () {
    var fieldVal = $(this).val();
        if(fieldVal == "alphanumeric"){
            $("#"+inputField).attr('type', 'text');  
        }else if(fieldVal == "numeric"){
            $("#"+inputField).attr('type', 'number');
        }else if(fieldVal == "email"){
            $("#"+inputField).attr('type', 'email');
        }else if(fieldVal == "text only"){
            $("#"+inputField).attr('type', 'text');
        }else if(fieldVal == "date"){
            $("#"+inputField).attr('type', 'date');
        }else if(fieldVal == "time"){
            $("#"+inputField).attr('type', 'time');
        }else if(fieldVal == "date & time"){
            $("#"+inputField).attr('type', 'datetime-local');
        }else if(fieldVal == "password"){
            $("#"+inputField).attr('type', 'password');
        }
    });
}


//Button Preview
function btn_preview_html(formRow,formContainer,inputField,previewLabel,inputField,buttonAction,labelName,textBoxFieldTypes,uni_id){ 
    var btn_preview_html = '<div class="form-row" id="'+formRow+'"><div class="form-container" id="'+formContainer+'"><div class="mb-3 form-group"> <label for="' + inputField + '" class="form-label" id="'+previewLabel+'"> </label> <input type="button" class="btn btn-primary" id="' + inputField + '" placeholder=""></div></div></div>';

    $("#formCreatedData #formdata").append(btn_preview_html);

    $('#'+buttonAction).on('change',function(){
        $("#"+inputField).prop('type',$('#'+buttonAction).val());
    });
    $('#' + labelName).keyup(function () {
        var inputText =$('#'+labelName).val();
        // $("#"+previewLabel).text(inputText);
        $("#"+inputField).val(inputText);
        
    });

    // ********** Type of button **********
    var textBoxFieldTypes = ["button", "submit", "reset"];
    // var formMakerId = $(".form-maker").attr('id');
    var selectOpt = $("#buttonAction_" + uni_id);
    for (var i = 0; i < textBoxFieldTypes.length; i++) {
        var opt = textBoxFieldTypes[i];
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = opt;
        selectOpt.append(el);
    }
}
function message_preview_html(formRow,formContainer,inputField,previewLabel,inputField,textBoxRequiment,labelName,placeholderName){
    var message_preview_html = '<div class="form-row" id="'+formRow+'"><div class="form-container" id="'+formContainer+'"><div class="mb-3 form-group"> <label for="' + inputField + '" class="form-label" id="'+previewLabel+'"> </label> <input type="text" class="form-control" id="' + inputField + '" placeholder=""></div></div></div>';

    console.log(message_preview_html);
    $("#formCreatedData #formdata").append(message_preview_html);



    $('#' + labelName).keyup(function () {
        var inputText = $(this).val();
        $("#"+previewLabel).text(inputText);
        // $("#"+inputField).attr('placeholder',btnPlaceholder);
        console.log("#"+inputField);
    });
   
    $('#' + placeholderName).keyup(function () {
        var inputText = $(this).val();
        // $("#"+placeholderName).text(inputText);
        $("#"+inputField).attr('placeholder',inputText);
        console.log("#"+inputField);
    });

    var mandatory_html = '<span class="text-danger mandatory-mark">*</span>';
    $('#' + textBoxRequiment).change(function () {
        var requirement = $(this).val();
        // console.log(requirement);
        if(requirement == "mandatory"){
            $("#"+previewLabel).append(mandatory_html);
            $("#"+inputField).attr('required', 'required');
        }else if(requirement == "optional"){
            $("#"+previewLabel+" .mandatory-mark").remove();
            $("#"+inputField).removeAttr('required');
        }
    });
}

function range_preview_html(formRow,formContainer,inputField,previewLabel,inputField,labelName,minRange,maxRange,textBoxRequiment){
var range_preview_html = '<div class="form-row" id="'+formRow+'"><div class="form-container" id="'+formContainer+'"><div class="mb-3 form-group"> <label for="' + inputField + '" class="form-label" id="'+previewLabel+'"> </label> <input type="text" class="form-control" id="' + inputField + '" placeholder=""></div></div></div>';

    $("#formCreatedData #formdata").append(range_preview_html);
 

    $('#' + labelName).keyup(function () {
        var inputText =$('#'+labelName).val();
        $("#"+previewLabel).text(inputText);
    });

    $arr1=[];
    $arr2=[];
    $('#' + minRange).keyup(function () {
        min=($arr2!=''?$arr2[$arr2.length-1]:'');
        var minText = $('#'+minRange).val();
        $arr1.push(minText);
        $("#"+inputField).attr('placeholder',minText+"-"+min);
    });

    $('#' + maxRange).keyup(function () {
        max=($arr1!=''?$arr1[$arr1.length-1]:'');
        var maxText= $('#'+maxRange).val();
        $arr2.push(maxText);
        $("#"+inputField).attr('placeholder',max+"-"+maxText);
    });

    
    var mandatory_html = '<span class="text-danger mandatory-mark">*</span>';
    $('#' + textBoxRequiment).change(function () {
        var requirement = $(this).val();
        // console.log(requirement);
        if(requirement == "mandatory"){
            $("#"+previewLabel).append(mandatory_html);
            $("#"+inputField).attr('required', 'required');
        }else if(requirement == "optional"){
            $("#"+previewLabel+" .mandatory-mark").remove();
            $("#"+inputField).removeAttr('required');
        }
    });
}