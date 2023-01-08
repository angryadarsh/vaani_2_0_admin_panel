
$('.nav .nav-item').click(function(){
    $('#formAttribute').removeClass("d-none");
    $('.form-container').removeClass('active');
    $(".content .form-label").removeClass("adding");
});

$(".textbox-menu").click(function(){
    
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

    var textBox_attr = '<div class="form-field-description" id="' + formDescription + '"> <div class="row"> <div class="col-auto"><input type="text" class="form-control" required id="' + labelName + '"> <label for="staticEmail2" class="form-label">Label Name</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + fieldType + '"> <option>Field Type</option>  </select> <label for="staticEmail2" class="form-label">Textbox Type</label> <span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="form-select" aria-label="Default select example" required id="' + textBoxRequiment + '"> <option >Field\'s Requirement</option> <option value="mandatory">Madatory</option> <option value="optional">Optional</option> </select> <label for="staticEmail2" class="form-label">Textbox Requirement</label><span class="text-danger mandatory-mark">*</span> </div> <div class="col-auto"> <select class="selectpicker criteria" aria-label="Default select example" id="' + addtionalCriteria + '" multiple required aria-label="Default select example" data-live-search="true"> </select> <label for="staticEmail2" class="form-label static-fixed">Additional Criteria</label></div> <div class="col-auto"> <!--<select class="form-select" aria-label="Default select example" required> <option>Field Type As Child</option></select>--> <div class="dropdown"> <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"> Field Type As Child </button> <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="' + addChildFields + '"> </ul> </div> <label for="staticEmail2" class="form-label static-fixed">Add Child</label> </div> <div class="col-auto"></div> </div> </div>';


    /* var unique_id = (Math.random() + 1).toString(36).substring(7);
    var formContainerID = unique_id; */
    
    var textBox_html = '<div class="col-6 form-maker" id="' + formMaker + '"> <div class="field-title"> Text Box </div> <div class="form-box"> <div id="'+formContainer+'" class="form-container parent"> <a href="#" class="text-danger deleteCurrentRow float-right" data-close="'+formContainer+'">x</a> <div class="mb-3 form-group"> <label for="" class="form-label"> <span class="label-text">Text box Label Name</span> </label> <input type="" class="form-control" id="" placeholder="Text box" /> </div> </div> </div> </div>';

    // var html = '<div class="form-maker" id="' + formMaker + '" > <div class="form-maker-top clearfix"> <div class="field-title float-start"> Text Box </div> <div class="float-end"> <a href="#" class="btn btn-outline-danger btn-sm closeCurrentFields"  data-close="formRow_'+uni_id+'">x</a> </div> </div>  ' + textBox_html + ' </div>';
    
    $(".crm-content").append(textBox_html);

    $('#formdata').append(textBox_attr);
    
    $("#maxRow, #dropDownOption, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
    $("#labelName, #textBoxType, #textBoxRequiment,  #additionalCriteria, #addChild").show();

    $("#"+formContainer).click(function(){
        $("#maxRow, #dropDownOption, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
        $("#labelName, #textBoxType, #textBoxRequiment,  #additionalCriteria, #addChild").show();
    });
    
    $("#inputField").val('');
    $("#fieldType").val('');
    $("#fieldRequirement").val('');
});

$(".drop-down-menu").click(function(){
    var unique_id = (Math.random() + 1).toString(36).substring(7);
    var formContainerID = unique_id;

    var dropdown_html = '<div class="col-6"> <div class="form-box"> <div id="form_container_'+formContainerID+'" class="form-container parent"> <a href="#" class="text-danger deleteCurrentRow float-right" data-close="form_container_'+formContainerID+'">x</a> <div class="mb-3 form-group"> <label for="" class="form-label"> <span class="label-text">Dropdown Label Name</span> </label> <select class="form-control" id="dropdownField01"></select> </div> </div> </div> </div>';

    $('#formRow').append(dropdown_html);

    $("#maxRow, #textBoxType, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
    $("#labelName, #textBoxRequiment, #dropDownOption, #additionalCriteria, #addChild").show();

    $("#form_container_"+formContainerID).click(function(){
        $("#maxRow, #textBoxType, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
        $("#labelName, #textBoxRequiment, #dropDownOption, #additionalCriteria, #addChild").show();
    });
    $("#inputField").val('');
});
$(".message-menu").click(function(){
    var unique_id = (Math.random() + 1).toString(36).substring(7);
    var formContainerID = unique_id;

    var message_html = '<div class="col-6"> <div class="form-box"> <div id="form_container_'+formContainerID+'" class="form-container parent"> <a href="#" class="text-danger deleteCurrentRow float-right" data-close="form_container_'+formContainerID+'">x</a> <div class="form-container"> <div class="mb-3 form-group"> <label for="" class="form-label"> <span class="label-text">Message Label Name</span> </label> <textarea class="form-control" id="" rows="2" placeholder="Write here.."></textarea> </div> </div> </div> </div> </div>';
    $('#formRow').append(message_html);

    $("#textBoxType, #dropDownOption, #minRange, #maxRange, #addButton, #buttonName").hide();
    $("#labelName, #maxRow, #setPlaceholder, #textBoxRequiment,#additionalCriteria, #addChild").show();

    $("#form_container_"+formContainerID).click(function(){
        $("#textBoxType, #dropDownOption, #minRange, #maxRange, #addButton, #buttonName").hide();
        $("#labelName, #maxRow, #setPlaceholder, #textBoxRequiment,#additionalCriteria, #addChild").show();
    });
    $("#inputField").val('');
});
$(".radio-menu").click(function(){
    var unique_id = (Math.random() + 1).toString(36).substring(7);
    var formContainerID = unique_id;

    var radio_html = '<div class="col-6"><div class="form-box"><div id="form_container_'+formContainerID+'" class="form-container parent"><a href="#" class="text-danger deleteCurrentRow float-right" data-close="form_container_'+formContainerID+'">x</a><div class="form-container"><div class="mb-3 form-group"><label class="form-label"><span class="label-text">Radio Label Name</span></label><div class="form-check"><input class="form-check-input" type="radio" name="flexRadioDefault" id="" /><label class="form-check-label" for="radioField01">Default radio</label></div> </div> </div> </div> </div> </div>';

    $('#formRow').append(radio_html);

    $("#textBoxType, #maxRow, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
    $("#labelName, #dropDownOption, #textBoxRequiment, #additionalCriteria, #addChild").show();

    $("#form_container_"+formContainerID).click(function(){
        $("#textBoxType, #maxRow, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
        $("#labelName, #dropDownOption, #textBoxRequiment, #additionalCriteria, #addChild").show();
    });
    $("#inputField").val('');
});
$(".checkbox-menu").click(function(){
    var unique_id = (Math.random() + 1).toString(36).substring(7);
    var formContainerID = unique_id;

    var checkbox_html = '<div class="col-6"><div class="form-row"><div id="form_container_'+formContainerID+'" class="form-container parent active"><div class="mb-3 form-group"><label class="form-label"><span class="label-text">Checkbox Label Name</span></label><div class="form-check"><input class="form-check-input" type="checkbox" value="" id=""> <label class="form-check-label" for=""> Default checkbox</label> </div></div> </div> </div></div>';
    $('#formDesigner #formRow').append(checkbox_html);

    $("#textBoxType, #maxRow, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
    $("#labelName, #dropDownOption, #textBoxRequiment, #additionalCriteria, #addChild").show();

    $("#form_container_"+formContainerID).click(function(){
        $("#textBoxType, #maxRow, #setPlaceholder, #minRange, #maxRange, #addButton, #buttonName").hide();
        $("#labelName, #dropDownOption, #textBoxRequiment, #additionalCriteria, #addChild").show();
    });
    $("#inputField").val('');
});
$(".range-menu").click(function(){
    var unique_id = (Math.random() + 1).toString(36).substring(7);
    var formContainerID = unique_id;

    var checkbox_html = '<div class="col-6"><div class="form-row"> <div id="form_container_'+formContainerID+'" class="form-container parent active"> <div class="mb-3 form-group"> <label for="exampleFormControlTextarea1" class="form-label"><span class="label-text">Range Label Name</span></label> <input type="range" class="form-range" id=""> </div> </div> </div>';
    $('#formDesigner #formRow').append(checkbox_html);

    $("#textBoxType, #dropDownOption, #maxRow, #setPlaceholder, #addButton, #buttonName").hide();
    $("#labelName, #minRange, #maxRange, #textBoxRequiment, #additionalCriteria, #addChild").show();

    $("#form_container_"+formContainerID).click(function(){
        $("#textBoxType, #dropDownOption, #maxRow, #setPlaceholder, #addButton, #buttonName, #buttonName").hide();
        $("#labelName, #minRange, #maxRange, #textBoxRequiment, #additionalCriteria, #addChild").show();
    });
    $("#inputField").val('');
});

$(".button-menu").click(function(){
    var unique_id = (Math.random() + 1).toString(36).substring(7);
    var formContainerID = unique_id;

    var button_html = '<span id="form_container_'+formContainerID+'" class="form-container parent active"><button type="button" class="btn btn-primary">Primary</button></span>';
    $('#formDesigner .form-button').append(button_html);

    $("#minRange, #maxRange, #textBoxRequiment, #additionalCriteria, #addChild, #textBoxType, #dropDownOption, #maxRow, #setPlaceholder, #labelName").hide();
    $("#buttonName, #addButton").show();

    $("#form_container_"+formContainerID).click(function(){
        $("#minRange, #maxRange, #textBoxRequiment, #additionalCriteria, #addChild, #textBoxType, #dropDownOption, #maxRow, #setPlaceholder, #labelName").hide();
        $("#buttonName, #addButton").show();
    });
    $("#inputField").val('');
});

// Active block
$(document).on('click', ".form-container", function () {
    $('.form-container').removeClass("active");
    if($(this).data('clicked', true)){
        $(this).addClass("active");

        // for text box active fuctionally 
        // Label name functionality
        var text = $(".form-container.active .form-label").text();
        var avoid = "*";
        var avoided = text.replace(avoid,'');
        if(avoided == 'Text box Label Name' || avoided == 'Dropdown Label Name' || avoided == 'Message Label Name' || avoided == 'Radio Label Name' || avoided == 'Checkbox Label Name' || avoided == 'Range Label Name'){
            // var textbox = $("#inputField").val();
            $("#inputField").val('');
        }
        else{
            $('#inputField').val(avoided);
        }
        
        // textbox type functionality
        var typeOf = $(".form-container.active .form-control").attr("data-type");
        $("#fieldType").val(typeOf);

        // textbox mandatory functionality
        var mandatory = $(".form-container.active .form-label").find('.mandatory-mark').length !== 0;
        var optional = $(".form-container.active .form-label").hasClass("optional");
        if(mandatory){
            $("#fieldRequirement").val('mandatory');
        } else if(optional){
            $("#fieldRequirement").val('optional');
        } else{
            $("#fieldRequirement").val('');
        }
    }
});

// ********** User can add options for dropdown, radio button and checkbox **********
var labelRow = 2;
$(document).on('click', '.addDropDownOption', function () {
    labelRow++;
    var html = '<div class="pos-rel mt-1 mb-3"><label for="staticEmail2" class="form-label">0' + labelRow + ' Option name <span class="text-danger mandatory-mark">*</span></label><input type="text" class="form-control" required><a href="javascript:void(0)" class="text-danger delete-btn">x</a></div>';
    $(".dropdown-select-options .pos-rel").after(html);
});

// To show and hide 
$(document).on("click", ".more-options", function () {
    // alert("More");
    if (!$(".dropdown-box").hasClass("opened")) {
        $(this).text("-").addClass("text-danger");
        $('.btn-box').show();
    } else {
        $(this).text("+").removeClass("text-danger");
        $('.btn-box').hide();
    }
    $(this).siblings().toggleClass("opened");
});

$(document).on("click", ".btnPlaceholder", function () {
    $(this).siblings().toggleClass("opened");
});

$("#inputField").keyup(function(){
    var val =  $(this).val();
    $(".form-container.active .form-label .label-text").html(val);
    $(".form-container.active .form-control").attr('placeholder', val);
});
$("#fieldType").change(function(){
    var opt = $(this).val();
    if (opt == "alphanumeric") {
        $(".form-container.active .form-control").attr('type', 'text').attr("data-type", opt);
    }
    if (opt == "numeric") {
        $(".form-container.active .form-control").attr('type', 'number').attr("data-type", opt);
    }
    if (opt == "email") {
        $(".form-container.active .form-control").attr('type', 'email').attr("data-type", opt);
    }
    if (opt == "text only") {
        $(".form-container.active .form-control").attr('type', 'text').attr("data-type", opt);
    }
    if (opt == "date") {
        $(".form-container.active .form-control").attr('type', 'date').attr("data-type", opt);
    }
    if (opt == "time") {
        $(".form-container.active .form-control").attr('type', 'time').attr("data-type", opt);
    }
    if (opt == "date & time") {
        $(".form-container.active .form-control").attr('type', 'datetime-local').attr("data-type", opt);
    }
    if (opt == "password") {
        $(".form-container.active .form-control").attr('type', 'password').attr("data-type", opt);
    }
    if (opt == "decimal") {
        $(".form-container.active .form-control").attr('type', 'text').attr("data-type", opt);
    }
});

// mandatory 
var madatory_html = '<span class="text-danger mandatory-mark">*</span>';
$('#fieldRequirement').change(function () {
    var requirement = $(this).val();
    // console.log(requirement);
    if(requirement == "mandatory"){
        $(".form-container.active .form-label").append(madatory_html);
        $(".form-container.active .form-control").attr('required', 'required');
    }else if(requirement == "optional"){
        $(".form-container.active .form-label .mandatory-mark").remove();
        $(".form-container.active .form-label").addClass("optional");
        $(".form-container.active .form-control").removeAttr('required');
    }
});
