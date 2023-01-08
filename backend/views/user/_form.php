<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;
use kartik\select2\Select2;
use common\models\EdasCampaign;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
?>

<?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'onsubmit' => "if(!$('.help-block').text()) {
    $('#LoadingBox').show(); 
} "], 'enableAjaxValidation' => true, 'validateOnChange' => false]); ?>
<div class="justify-content-center row form-containter-box">
    <div class="col-sm-7 col-xl-7">
        <div class="row ">
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">User Id</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'user_id', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'User ID', 'readOnly' => ($model->isNewRecord ? false : 'readOnly'), 'required' => true])->label('User Id', ['class' => 'form-label'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">Password</label>
                    <div class="col-sm-8">
                         <?= $form->field($model, 'user_password', ['template' => '{input}{label}{error}{hint}'])->passwordInput(['placeholder' => 'Password', 'required' => true])->label('Password', ['class' => 'form-label'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">User Name</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'user_name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'User Name', 'required' => true])->label('User Name', ['class' => 'form-label'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">Gender</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'gender', ['template' => '{input}{label}{error}{hint}'])->dropdownList(User::$gender_types, ['prompt' => 'Gender', 'required' => true])->label('Gender', ['class' => 'form-label'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">Client</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'client', ['template' => '{input}{label}{error}{hint}'])->dropdownList($clients, ['prompt' => 'Select...', 'required' => true])->label('Client', ['class' => 'form-label'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">Campaigns</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'campaigns', ['template' => '{input}{label}{error}{hint}'])->widget(Select2::classname(), [
                            'data' => $campaigns,
                            'options' => ['placeholder' => 'Campaigns'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => true
                            ],
                        ])->label('Campaigns', ['class' => 'form-label'])->label(false); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">Queues</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'queues', ['template' => '{input}{label}{error}{hint}'])->widget(Select2::classname(), [
                            'data' => $queues,
                            'options' => ['placeholder' => 'Queues'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'multiple' => true
                            ],
                            ])->label('Queues', ['class' => 'form-label'])->label(false); ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">Role</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'role', ['template' => '{input}{label}{error}{hint}'])->dropdownList($roles, ['prompt' => 'Role', 'required' => true])->label(' &nbsp;Role &nbsp; ', ['class' => 'form-label'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 manager_section">
                <div class="row form-group">
                    <label for="" class="form-label col-sm-4">Manager</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'manager_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($managers, ['prompt' => 'Select...'])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 supervisor_section">
                <?= $form->field($model, 'supervisor_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($supervisors, ['prompt' => 'Select...'])->label('Reporting To', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-2 is_two_leg_section">
                <?= $form->field($model, 'is_two_leg')->checkbox(['label' => ' Is Two Leg?'])->label(false) ?>
            </div>
            <div class="col-lg-4 contact_section">
                <?= $form->field($model, 'contact_number', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Contact Number'])->label('Contact Number', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-6 operator_section">
                <?= $form->field($model, 'operator', ['template' => '{input}{label}{error}{hint}'])->dropdownList($operators, ['prompt' => 'Operator...'])->label('Operator', ['class' => 'form-label']) ?>
            </div>
            <div class="col-lg-12 call_access_section">
                <div class="row">
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_conference')->checkbox(['label' => ' Conference?'])->label(false) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_transfer')->checkbox(['label' => ' Transfer?'])->label(false) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_consult')->checkbox(['label' => ' Consult?'])->label(false) ?>
                    </div>
                    <div class="col-lg-3">
                        <?= $form->field($model, 'is_manual')->checkbox(['label' => ' Manual?'])->label(false) ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 webRTC_section">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <label for="" class="form-label col-sm-4">Web RTC</label>
                            <div class="col-sm-8">
                                <?= $form->field($model, 'web_rtc', ['template' => '
                                <div class="toggle-checked"><label class="switch">{input}<div class="slider round"><span class="on">Enabled</span><span class="off">Disabled</span></div></label></div>{error}{hint}'])->checkbox()->label(false) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 priorities-section">
                <!-- queue wise priority -->
                <?php if(!$model->isNewRecord && $queue_list){ ?>
                    <div class="row">
                        <div class="col-lg-12"><b><u>Set priority for the queue</u></b></div><br><br>
                        <?php foreach($queue_list as $q_id => $q_item){ ?>
                        <div class="col-lg-3">
                            <?= $form->field($model, 'priority['.$q_id.']', ['template' => '{input}{label}{error}{hint}'])->dropdownList($priorities, ['prompt' => 'Select priority...', 'value' => $q_item['priority']])->label($q_item['name'], ['class' => 'form-label']) ?>
                        </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <div class="col-lg-12 form-group text-center">
                <?= Html::submitButton('Save', ['class' => 'btn btn-outline-primary']) ?>
                <?= Html::resetButton('Cancel', ['class' => 'btn btn-outline-secondary']) ?>
            </div>
        </div>
    </div>
</div>


<?php ActiveForm::end(); ?>

<!-- save temporary campaign type value -->
<div class="hidden temp_camp_type"></div>

<?php
$this->registerCss("
    .btn-toggle:before, .btn-toggle:after{
        display:none;
    }
    .toggle-checked{
        display:inline-block;
    }
    .on, .off {
        color: white;
    }
");

$this->registerJs("

    $('label > #user-web_rtc').insertBefore($('label > #user-web_rtc').parent());
    $('#user-web_rtc').next().remove();

    var role_name = $('#user-role :selected').text();
    roleWiseVisibility(role_name);

    // fetch campaigns
    function fetchCampaigns(client_id)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/user/get-client-campaigns' ."',
            data: {client_id : client_id}
        }).done(function(data){
            var optionList = '';

            $('#user-campaigns').html('');
            $('#user-queues').html('');
            data = JSON.parse(data);
            $.each(data, function(key, value){
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });

            $('#user-campaigns').html(optionList);
            let camps = ".json_encode($model->campaigns).";
            if(camps){
                $('#user-campaigns').val(camps);
                fetchQueues(camps);
            }
        });
    }

    // fetch queues
    function fetchQueues(campaign_ids)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/user/get-campaigns-queues' ."',
            data: {campaign_ids : campaign_ids}
        }).done(function(data){
            var optionList = '<option value=\"\">Select...</option>';
            let queue_ids = [];
            
            $('#user-queues').html('');
            data = JSON.parse(data);
            $.each(data, function(key, value){
                queue_ids.push(key);
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });
                
            $('#user-queues').html(optionList);
            let user_queues = ".json_encode($model->queues).";  
            if(user_queues){
                $('#user-queues').val(user_queues).trigger('change');
            }else{
                $('#user-queues').val(queue_ids).trigger('change');
            }
        });
    }

    // fetch roles
    function fetchRoles(client_id = null, campaign_id = null)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/user/get-roles' ."',
            data: {client_id : client_id, campaign_id : campaign_id}
        }).done(function(data){
            var optionList = '<option value=\"\">Select...</option>';

            data = JSON.parse(data);
            $.each(data, function(key, value){
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });

            $('#user-role').html(optionList);
            let userrole = ".json_encode($model->role).";
            $('#user-role').val(userrole);
        });
    }

    // fetch  operators
    function fetchOperators(client_id)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/user/get-operators' ."',
            data: {client_id : client_id}
        }).done(function(data){
            var optionList = '<option value=\"\">Select...</option>';

            data = JSON.parse(data);
            $.each(data, function(key, value){
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });

            $('#user-operator').html(optionList);
            let useroperator = ".json_encode($model->operator).";
            $('#user-operator').val(useroperator);
        });
    }

    // FETCH CLIENT RELATED ACTIONS VALUES
    function clientChangeActions(client_id)
    {
        // fetch campaigns
        fetchCampaigns(client_id);

        // fetch roles
        fetchRoles(client_id);

        // fetch operators
        fetchOperators(client_id);
    }
    
    //ONCHANGE OF CLIENT, FETCH IT'S CAMPAIGNS
    $('#user-client').on('change', function(){
        var client_id = $(this).val();

        // fetch client related options
        clientChangeActions(client_id);
    });

    var clientid = $('#user-client').val();
    // fetch client related options
    clientChangeActions(clientid);
    
    //ONCHANGE OF CAMPAIGNS, FETCH ROLES & QUEUES
    $('#user-campaigns').on('change', function(){
        var client_id = $('#user-client').val();
        var campaign_ids = $(this).val();
        
        // fetch roles
        fetchRoles(client_id, campaign_ids);

        // FETCH QUEUES
        fetchQueues(campaign_ids);
    });
    
    //ONCHANGE OF CAMPAIGNS && ROLE, FETCH SUPERVISOR LIST
    $('#user-role').on('change', function(){
        var role_id = $(this).val();
        var campaign_ids = $('#user-campaigns').val();
        var user_id = $('#user-user_id').val();
        var client_id = $('#user-client').val();
        
        var role_name = $('#user-role :selected').text();

        console.log(role_name.toLowerCase());

        if((role_name.toLowerCase() == 'agent' || role_name.toLowerCase() == 'supervisor') && role_id && client_id){

            // fetch managers             
            $.ajax({
                method: 'POST',
                url: '". $urlManager->baseUrl . '/index.php/user/get-managers' ."',
                data: {client_id : client_id, role_id : role_id, user_id : user_id}
            }).done(function(data){
                var optionList = '<option value=\"\">Select...</option>';
                if(data){
                    data = JSON.parse(data);
                    $.each(data, function(key, value){
                        // exclude self user id
                        if(key != $('#user-user_id').val()){
                            optionList += '<option value=\"'+key+'\">'+ value +'</option>';
                        }
                    });
                }
                $('#user-manager_id').html(optionList);
            });

            // fetch supervisors
            fetchSupervisors(client_id, campaign_ids, role_id, user_id);
        }

        roleWiseVisibility(role_name);
    });

    function roleWiseVisibility(role_name)
    {
        // ONCHANGE OF ROLE, IF ADMIN, HIDE CALL ACCESS        
        if(role_name.toLowerCase() == 'agent'){
            $('.call_access_section').show();
            $('.is_two_leg_section').show();
            $('.priorities-section').show();
        }else{
            $('.call_access_section').hide();
            $('.is_two_leg_section').hide();
            $('.priorities-section').hide();
        }

        // ONCHANGE OF ROLE, IF AGENT/SUPERVISOR, SHOW MANAGER/SUPERVISOR  
        if(role_name.toLowerCase() == 'agent'){
            $('.webRTC_section').show();
            $('.manager_section').show();
            $('#user-manager_id').attr('required', true);
        }else if(role_name.toLowerCase() == 'supervisor'){
            $('.webRTC_section').hide();
            $('.manager_section').show();
            $('.supervisor_section').hide();
            $('#user-manager_id').attr('required', true);
            $('#user-supervisor_id').attr('required', false);
        }else{
            $('.webRTC_section').hide();
            $('.manager_section').hide();
            $('.supervisor_section').hide();
            $('#user-manager_id').attr('required', false);
            $('#user-supervisor_id').attr('required', false);
        }
    }

    // FETCH SUPERVISORS
    function fetchSupervisors(client_id = null, campaign_ids = null, role_id = null, user_id = null, manager = null)
    {
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/user/get-supervisors' ."',
            data: {client_id : client_id, campaign_ids : campaign_ids, role_id : role_id, user_id : user_id, manager : manager}
        }).done(function(data){
            var optionList = '<option value=\"\">Select...</option>';
            if(data){
                data = JSON.parse(data);
                $.each(data, function(key, value){
                    // exclude self user id
                    if(key != $('#user-user_id').val()){
                        optionList += '<option value=\"'+key+'\">'+ value +'</option>';
                    }
                });
            }
            $('#user-supervisor_id').html(optionList);
        });
    }
    
    //ONCHANGE OF QUEUES, FETCH QUEUES LIST WITH PRIORITY
    $('#user-queues').on('change', function(){
        var queues = $(this).val();
        fetchPriority(queues);
    });

    function fetchPriority(queues=null) {
        var queue_names = [];

        $('.field-user-queues .select2-selection__choice').each(function (index, element) {
            queue_names.push($(element).attr('title'));
        });

        let htmldata = '<div class=\"row\">';
        if(queues){
            let subdata = '';
            htmldata += '<div class=\"col-lg-12\"><b><u>Set priority for the queue</u></b></div><br><br>';

            $.each(queues, function(key, value){
                // fetch queue camp type
                var camp_type = null;

                $.ajax({
                    method: 'GET',
                    url: '". $urlManager->baseUrl . '/index.php/campaign/get-camp-type' ."',
                    data: {queue_id : value},
                    success: function(result){
                        $('.temp_camp_type').text(result);
                        camp_type = result;
                    }
                });

            console.log($('.temp_camp_type').text());
            console.log(camp_type);
                // if($('.temp_camp_type').text() == ".EdasCampaign::TYPE_INBOUND."){

                    subdata += '<div class=\"col-lg-3\">';
                    subdata += '<div class=\"form-group field-user-priority has-success\">';
                    subdata += '<select id=\"user-priority\" class=\"form-control\" name=\"User[priority]['+value+']\">';
                        subdata += '<option value=\"\">Select Priority...</option>';
                        subdata += '<option value=\"0\">High</option>';
                        subdata += '<option value=\"10\">Medium</option>';
                        subdata += '<option value=\"20\">Low</option>';
                    subdata += '</select>';
                    subdata += '<label class=\"form-label\" for=\"user-priority\">'+queue_names[key]+'</label>';
                    subdata += '<div class=\"help-block\"></div>';
                    subdata += '</div>';
                    subdata += '</div>';
                // }

                $('.temp_camp_type').text('');
            });
            htmldata += subdata + '</div>';
            // console.log(htmldata);
            $('.priorities-section').html(htmldata);
        }
    }

    // onchange of managers, fetch supervisors
    $('#user-manager_id').on('change', function(){
        var manager = $(this).val();
        var user_id = $('#user-user_id').val();
        var role_id = $('#user-role').val();
        var client_id = $('#user-client').val();
        var campaign_ids = $('#user-campaigns').val();
        var campaign_ids = $('#user-campaigns').val();

        var role_name = $('#user-role :selected').text();

        if(manager && role_id && campaign_ids && role_name.toLowerCase() == 'agent'){
            $('.supervisor_section').show();
            $('#user-supervisor_id').attr('required', true);

            // fetch supervisors
            fetchSupervisors(client_id, campaign_ids, role_id, user_id, manager);
        }else{
            $('.supervisor_section').hide();
            $('#user-supervisor_id').attr('required', false);
        }

    });
");

// 2 leg configurations
$this->registerJs("
    if($('#user-is_two_leg').is(':checked')){
        $('.contact_section').show();
        $('.operator_section').show();
        $('#user-contact_number').attr('required', true);
        $('#user-operator').attr('required', true);
    }else{
        $('.contact_section').hide();
        $('.operator_section').hide();
        $('#user-contact_number').attr('required', false);
        $('#user-operator').attr('required', false);
    }

    $('#user-is_two_leg').on('change', function(){
        if($(this).is(':checked')){
            $('.contact_section').show();
            $('.operator_section').show();
            $('#user-contact_number').attr('required', true);
            $('#user-operator').attr('required', true);
        }else{
            $('.contact_section').hide();
            $('.operator_section').hide();
            $('#user-contact_number').attr('required', false);
            $('#user-operator').attr('required', false);
        }
    });

");
?>