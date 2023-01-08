<?php

use yii\helpers\Html;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\VaaniRoleMaster;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\VaaniRoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $this->title = 'Roles Access';
// $this->params['breadcrumbs'][] = $this->title;

$urlManager = Yii::$app->urlManager;
?>
<div class="content-header">
    <div class="container-fluid">
        <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item"><a href="#" class="barIcon">Role</a></li>
				<li class="breadcrumb-item active" aria-current="page">Role Access</li>
			</ol>
		</nav>
        <div class="clearfix top-header">
            <h1 class="float-left">Role Access</h1>
            <div class="float-right">
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class='row justify-content-center'>
            <div class="col-lg-2">
                <?= Html::radioList('statusName', VaaniRoleMaster::STATUS_DEFAULT_SETTING, $statuses, [
                    'class' => 'form-checkbox',
                    'prompt' => 'Select Type',
                    'id' => 'status_id',
                    'itemOptions' => [
                        'class' => 'client_type'
                    ]
                ]); ?>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3 role_section">
                <label for="selected_role_id" class="form-label">Role</label>
                <div class="form-group">
                    <?= Html::dropDownList('roleName', null, $roles, [
                        'class' => 'form-control',
                        'prompt' => 'Select Role',
                        'id' => 'selected_role_id'
                    ]); ?>
                </div>
            </div>
            <div class="col-lg-3 client_section">
                <label for="selected_client_id" class="form-label">Client</label>
                <div class="form-group">
                    <?= Html::dropDownList('clientName', null, $clients, [
                        'class' => 'form-control',
                        'prompt' => 'Select Client',
                        'id' => 'selected_client_id'
                    ]); ?>
                </div>
            </div>
            <!-- <div class="col"></div> -->
            <div class="col-lg-3 campaign_section">
                <label for="selected_campaign_id" class="form-label">Campaign</label>
                <div class="form-group">
                    <?= Html::dropDownList('campaignName', null, [], [
                        'class' => 'form-control',
                        'prompt' => 'Select Campaign',
                        'id' => 'selected_campaign_id'
                    ]); ?>
                </div>
            </div>
            <div class="col-lg-3 queue_section">
                <label for="selected_queue_id" class="form-label">Queue</label>
                <div class="form-group">
                    <?= Html::dropDownList('queueName', null, [], [
                        'class' => 'form-control',
                        'prompt' => 'Select Queue',
                        'id' => 'selected_queue_id'
                    ]); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <table id="dniList" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                        <th width="30%">
                            <div class="float-left">
                            Menu Name
                            </div>
                            <div class="float-right">
                            
                            </div>
                        </th>
                        <th class="text-center">Add</th>
                        <th class="text-center">View</th>
                        <th class="text-center">Modify</th>
                        <th class="text-center">Download</th>
                        <th class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody id="role_managment">
                        <td colspan="6">Select Role.</td>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/bootstrap-switch.min.js');

$this->registerJs("
$('.client_section').hide();
$('.campaign_section').hide();
$('.queue_section').hide();

// DISPLAY/HIDE CLIENT & CAMPAIGN DROPDOWN ON CHANGE OF STATUS
$('#status_id').on('change', function(){
    var status = $('input[name=\"statusName\"]:checked').val();
    if(status == '".VaaniRoleMaster::STATUS_DEFAULT_SETTING."'){
        $('.client_section').hide();
        $('.campaign_section').hide();
        $('.queue_section').hide();
        $('#selected_client_id').val('');
        $('#selected_campaign_id').val('');
        $('#selected_queue_id').val('');
    }else{
        $('.client_section').show();
        $('.campaign_section').show();
        $('.queue_section').show();
    }
    $('#selected_role_id').val('');
    $('#role_managment').html('<td colspan=\"6\">Select Role.</td>');
});

// DISPLAY CLIENT'S CAMPAIGNS ON CHANGE OF CLIENT
$('#selected_client_id').on('change', function(){
    var client_id = $(this).val();
    var role_id = $('select#selected_role_id').val();
    fetch_menus(role_id, client_id);
    $.ajax({
        method: 'POST',
        url: '". $urlManager->baseUrl . '/index.php/user/get-client-campaigns' ."',
        data: {client_id : client_id}
    }).done(function(data){
        data = JSON.parse(data);
        var optionList = '<option value=\"\">Select Campaign</option>';
        $.each(data, function(key, value){
            optionList += '<option value=\"'+key+'\">'+ value +'</option>';
        });
        $('#selected_campaign_id').html(optionList);
    });
});

function fetch_menus(role_id, client_id=null, campaign_id=null, queue_id=null)
{
    $('#LoadingBox').show();
    $.ajax({
        method: 'POST',
        url: '". $urlManager->baseUrl . '/index.php/role/get-assigned-menus' ."',
        data: {role_id : role_id, client_id : client_id, campaign_id : campaign_id, queue_id : queue_id}
    }).done(function(data){
        $('#LoadingBox').hide();
        var data = $.parseJSON(data);
        $('#role_managment').html(data.assigned);

        if(data.assigned){
            $('.class_role_update').each(function () {
                let statusClass = $(this).parents('.define_role');
                if ($(this).is(':checked')) {
                    statusClass.addClass('active');
                    statusClass.removeClass('inactive');
                }else{
                    statusClass.addClass('inactive');
                    statusClass.removeClass('active');
                }
            });
        }
        /* if(data.unassigned){
            var optionList = '<option value=\"\">Select Menu To Assign</option>';
            $.each(data.unassigned, function(key, value){
                console.log($.type(value));
                if($.type(value) == 'string'){
                    optionList += '<option value=\"'+key+'\">'+ value +'</option>';
                }else if($.type(value) == 'object'){
                    $.each(value, function(k, val){
                        optionList += '<option value=\"'+k+'\">'+ val +'</option>';
                    });
                }
            });
            $('#selected_menu_id').html(optionList);
        } */
    });
}

// FETCH ASSIGNED & UNASSIGNED MENUS ON CHANGE OF ROLE
$('#selected_role_id').on('change', function(){
    var status = $('input[name=\"statusName\"]:checked').val();
    var role_id = $(this).val();
    var client_id = $('select#selected_client_id').val();
    var campaign_id = $('select#selected_campaign_id').val();
    var queue_id = $('select#selected_queue_id').val();

    if(status == '".VaaniRoleMaster::STATUS_DEFAULT_SETTING."' || (role_id && client_id && campaign_id && queue_id)){
        fetch_menus(role_id);
    }
});
$('#selected_campaign_id').on('change', function(){
    var role_id = $('select#selected_role_id').val();
    var client_id = $('select#selected_client_id').val();
    var campaign_id = $('select#selected_campaign_id').val();
    
    fetch_menus(role_id, client_id, campaign_id);

    $.ajax({
        method: 'POST',
        url: '". $urlManager->baseUrl . '/index.php/user/get-campaigns-queues' ."',
        data: {campaign_ids : campaign_id}
    }).done(function(data){
        var optionList = '<option value=\"\">Select Queue</option>';
        if(data){
            data = JSON.parse(data);
            $.each(data, function(key, value){
                optionList += '<option value=\"'+key+'\">'+ value +'</option>';
            });
        }
        $('#selected_queue_id').html(optionList);
    });
    
});
$('#selected_queue_id').on('change', function(){
    var role_id = $('select#selected_role_id').val();
    var client_id = $('select#selected_client_id').val();
    var campaign_id = $('select#selected_campaign_id').val();
    var queue_id = $('select#selected_queue_id').val();
    
    fetch_menus(role_id, client_id, campaign_id, queue_id);
    
});

// Add menu for the role
/* $('#selected_menu_id').on('change', function(){
    var menu = $(this).val();
    var role_id = $('#selected_role_id').val();
    var type = $('input[name=\"statusName\"]:checked').val();
    var check_val = 1;

    $.ajax({
        method: 'POST',
        url: '". $urlManager->baseUrl . '/index.php/role/assign-menu' ."',
        data: {type : type, role_id : role_id, menu : menu, check_val : check_val}
    }).done(function(result){
        var result = $.parseJSON(result);
        if(result.is_success){
            fetch_menus(role_id);
        }else{
            alert('Something went wrong.');
        }
    });
}); */

$(document).on('click', '.define_role a', function(){
    act = $(this).attr('data-menu');
    let parent_item = $(this).parents('tr');
    let item = $(this).parent().parent();
    let menu = $(item).attr('data-menu');
    let status = $(item).find('input');
    let disabledClass = $(this).parents('td').siblings('td');
    if(menu.length){
        $('.define_role').removeClass('current');
        item.addClass('current');
        // $('.class_role_update').attr('disabled', 'disabled');
        status.removeAttr('disabled');
        $('.action').addClass('disabled');
        disabledClass.removeClass('disabled');
        
        if ($(status).is(':checked')) {
            disabledClass.removeClass('opacitycls');
        }

        // call ajax to update the action part.
        let type = $('input:radio.client_type:checked').val();
        let role_id = $('select#selected_role_id').val();
        let queue_id = $('select#selected_queue_id').val();
        let campaign_id = $('select#selected_campaign_id').val();
        let client_id = $('select#selected_client_id').val();
        // loadershow();

        $('#LoadingBox').show();

        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/role/sub-menu-actions' ."',
            data: {type : type, menu : menu, role_id : role_id, client_id : client_id, campaign_id : campaign_id, queue_id : queue_id}
        }).done(function(data){
            $('#LoadingBox').hide();
            var data = $.parseJSON(data);
            if(data != null)
            {
                parent_item.find('td:not(td:first-child)').each (function() {
                    var chkbox = $(this).find(':checkbox');
                    var chk = chkbox.attr('data-check-action');
                    var slc = '';
                    chkbox.attr('data-check-id',data.menu_ids);

                    if(data){
                        if(chk=='a' && data.add==1){
                            chkbox.removeAttr('disabled');
                            chkbox.prop('checked', true);
                        }else if(chk=='b' && data.view==1){
                            chkbox.removeAttr('disabled');
                            chkbox.prop('checked', true);
                        }else if(chk=='c' && data.modify==1){
                            chkbox.removeAttr('disabled');
                            chkbox.prop('checked', true);
                        }else if(chk=='d' && data.download==1){
                            chkbox.removeAttr('disabled');
                            chkbox.prop('checked', true);
                        }else if(chk=='e' && data.delete==1){
                            chkbox.removeAttr('disabled');
                            chkbox.prop('checked', true);
                        }
                    }
                }); 
            }else{
                parent_item.find('td:not(td:first-child)').each (function() {
                    var chkbox  = $(this).find(':checkbox');
                    // chkbox.attr('data-check-action','');
                    chkbox.attr('data-check-id','');
                });
            }          
        });
        setTimeout(function(){
            $('#LoadingBox').hide();
        },1000);
    }
});

$(function($){
    $('#role_managment').on('change','.custom-control-input', function (event, state){
        $('#LoadingBox').show();
        let menu_id = $(this).attr('data-check');
        let type = $('input:radio.client_type:checked').val();
        let sub_action = $(this).attr('data-check-action');
        let check_value  = '2';

        if($(this).is(':checked')){
            check_value  = '1';
        }

        let role_id = $('select#selected_role_id').val();
        let queue_id = $('select#selected_queue_id').val();
        let campaign_id = $('select#selected_campaign_id').val();
        let client_id = $('select#selected_client_id').val();
        
        $.ajax({
            method: 'POST',
            url: '". $urlManager->baseUrl . '/index.php/role/set-menu-access' ."',
            data: {type : type, menu_id : menu_id, role_id : role_id, check_value : check_value, client_id : client_id, campaign_id : campaign_id, sub_action : sub_action, queue_id : queue_id}
        }).done(function(result){
            $('#LoadingBox').hide();
            if(result){
                var result = $.parseJSON(result);
                if(result.is_success){
                    fetch_menus(role_id, client_id, campaign_id, queue_id);
                }else{
                    alert('Something went wrong.');
                }
            }else{
                alert('Something went wrong.');
            }
        });
    });
});
");
?>