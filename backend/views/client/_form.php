<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use common\models\VaaniRole;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'autocomplete' => 'off'], 'enableAjaxValidation' => true, 'validateOnChange' => false, 'id' => 'client_form_submit']); ?>

<div class="row">
<div class="col-lg-12 text-center">
        <p class="h5 mt-0 section-header">Client Configuration</p>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'client_name', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Client Name', 'required' => true])->label('Client Name', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'operators', ['template' => '{label}{input}{error}{hint}'])->widget(Select2::classname(), [
            'data' => $operators,
            'options' => ['required' => true , 'placeholder' => 'Operators...'],
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true,
            ],
        ])->label('Operators', ['class' => 'form-label']) ?>
    </div>
    <div class="col-lg-6">
        <label class="form-label">Upload Logo</label>
        <div class="row">
            <div class="col-4">
                <?= $form->field($model, 'logoFile', ['options' => ['class' => 'upload_file_input form-group']])->fileInput()->label('<span class="logo_label btn btn-default"><i class="fa fa-upload"></i> Upload Logo</span> <span class="logo_name"></span>') ?>
            </div>
            <div class="col-8">
                <div id="updatedLogoPreview" class="preview-uploaded-logo" style="background-image: url(<?= ($model->logo ? Yii::$app->request->baseUrl . '/uploads/client_logo/' . $model->logo : Yii::$app->request->baseUrl . '/images/image-preview.png') ?>)">
                    <a class="removeAddedLogo " href="#"><i class="fas fa-times"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <?= $form->field($model, 'client_descreption', ['template' => '{label}{input}{error}{hint}'])->textarea(['placeholder' => 'Description'])->label('Description', ['class' => 'form-label']) ?>
    </div>
    
</div>

<div class="row">
    <div class="col-lg-12 text-center">
        <p class="h5 section-header">Connection Settings</p>
    </div>
    <div class="col">
        <?= $form->field($model, 'server', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Server', 'required' => true])->label('Server', ['class' => 'form-label']) ?>
    </div>
    <!-- <div class="col">
        <?php // $form->field($model, 'dbhost', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Host', 'required' => true])->label('Host', ['class' => 'form-label']) ?>
    </div> -->
    <div class="col">
        <?= $form->field($model, 'username', ['template' => '{label}{input}{error}{hint}'])->textInput(['placeholder' => 'Username', 'required' => true])->label('Username', ['class' => 'form-label']) ?>
    </div>
    <div class="col">
        <?= $form->field($model, 'password', ['template' => '{label}{input}{error}{hint}'])->passwordInput(['placeholder' => 'Password', 'required' => true])->label('Password', ['class' => 'form-label']) ?>
    </div>
    <div class="col">
        <?= $form->field($model, 'dbport', ['template' => '{label}{input}{error}{hint}', 'options' => ['class' => 'form-group field-vaaniclientmaster-dbport m-0']])->textInput(['placeholder' => 'Port', 'required' => true])->label('Port', ['class' => 'form-label']) ?>
        
    </div>
    <div class="col text-center">
        <label class="form-label mt-3"></label>
        <br>
        <?= Html::a('Test Connection', null, ['class' => 'btn btn-primary test-connection']) ?>
    </div>
    <div class="col-lg-12">
    </div>
</div>

<div class="row">
    <div class="col-lg-12 text-center">
        <p class="h5 section-header">License Count</p>
    </div>
    <?php
    $roles_arr = [];

    if($model->isNewRecord){
        $defaultRoles = VaaniRole::getDefaultRoles();
        if($defaultRoles){ ?>
            <!-- <div class="col-lg-2"> <b>Default Roles:</b> </div>
            <div class="col-lg-10">
            <?php foreach($defaultRoles as $role){
                if($role){
                    $roles_arr[$role->role_id] = $role->role_name; ?>
                    <span class="badge badge-default"> <?= ucwords($role->role_name) ?> </span>
                <?php } ?>
            <?php } ?>
            </div> -->
        <?php } ?>
    <?php }else{ 
        $defaultRoles = $model->roleMasters;
        if($defaultRoles){ ?>
            <div class="col-lg-2"> <b>Default Roles:</b> </div>
            <div class="col-lg-10">
            <?php foreach($defaultRoles as $role){
                if($role && $role->role){
                    $roles_arr[$role->role->role_id] = $role->role->role_name; ?>
                    <span class="badge badge-default"> <?= ucwords($role->role->role_name) ?> </span>
                <?php } ?>
            <?php } ?>
            </div>
        <?php } ?>
    <?php } ?>
    <!-- <div class="col-lg-12"> <b>Role Wise Login Count:</b> </div> -->
    <br><br>
    <?php foreach ($roles_arr as $role_id => $role_name) { ?>
        <div class="col-md-4">
            <?= $form->field($model, 'role_login_count['.$role_id.']', ['template' => '{label}{input}{error}{hint}'])->textInput(['value' => ((isset($role_login_counts) && isset($role_login_counts[$role_id])) ? $role_login_counts[$role_id] : 1)])->label($role_name, ['class' => 'form-label']) ?>
        </div>
    <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 form-group text-center">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary form_loader', 'id' => 'savebutton']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-secondary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerCss("
    .logo_label{ cursor: pointer;  }
    .upload-icon-btn{ display:none;}
");

if($model->isNewRecord){
    $this->registerJs('
        $("#savebutton").hide();
    ');
}

$this->registerJs('
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
      
        reader.onload = function(e) {
            $("#updatedLogoPreview").css("background-image", "url("+e.target.result +")");
            $("#updatedLogoPreview").addClass("addedLogo");
            $(".removeAddedLogo").remove();
            $(".addedLogo").append("<a class=\'removeAddedLogo\'><i class=\'fas fa-times\'></i></a>");
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Remove 
function removeAddedLogo() {
    $("#updatedLogoPreview").css("background-image", "url('. Yii::$app->request->baseUrl . '/images/image-preview.png)");
    $("#updatedLogoPreview").removeClass("addedLogo");
    $(".removeAddedLogo").remove();
    $(".logo_name").html("");
    $("#vaaniclientmaster-logofile").val("");
    $(".logo_label .btn-default").show();
}

$("#vaaniclientmaster-logofile").change(function(e) {
    var filename = e.target.files[0].name;
    $(".logo_name").html(`<span class="logo-text">` + filename + `</span> <span class="upload-icon-btn"><i class="fa fa-upload"></i></span>`);
    readURL(this);
    $(".logo_label .btn-default").hide();
});

$(document).on("click", ".removeAddedLogo", function(){
    removeAddedLogo();
});

$("#savebutton").hide();

$(document).on("click", ".test-connection", function(){
    var server = $("#vaaniclientmaster-server").val();
    var username = $("#vaaniclientmaster-username").val();
    var password = $("#vaaniclientmaster-password").val();
    var dbport = $("#vaaniclientmaster-dbport").val();

    if(server && username && password && dbport){
        $.ajax({
            method: "POST",
            url: "'. $urlManager->baseUrl . '/index.php/client/test-connection' .'",
            data: {server : server, username : username, password : password, dbport : dbport}
        }).done(function(message){
            if(message){
                alert(message);
                $("#savebutton").show();
            }
        }).fail(function(message){
            alert("Connection Failed!");
            $("#savebutton").hide();
        });
    }
});

');
?>

<?php
// loader ajax message
$this->registerJs("
$(document).ready(function(){

    var loader = '';
    var loadercnt = 0 ;

    var refreshLoader = function () {
        $('.customized_loader_text span').html('<marquee scrollamount=\"8\">Hey! Hold Back! &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  We are processing your request...</marquee>');
        loader = setInterval(function(){ 
            loadercnt++;
            if(loadercnt==18)
            {
                $('.customized_loader_text span').html('<marquee scrollamount=\"8\">Just a moment!</marquee>');
            }
            else if(loadercnt==50)
            {
                $('.customized_loader_text span').html('<marquee scrollamount=\"5\"> We are almost Done! <i class=\"fas fa-check-circle\"></i></marquee>');
            }
        }, 1000);
    }

    $('#client_form_submit').on('submit', function(){
        
        if(!$('.help-block').text()) {
            $('#LoadingBox').show();
            refreshLoader();

            $.ajax({
                method: 'POST',
                url: $(this).prop('action'),
                data: $(this).serialize(),
            }).done(function(data){

            });
        }

    });

    $('#client_form_submit').on('change', function(){
        $('#savebutton').hide();
    });
});
");
?>