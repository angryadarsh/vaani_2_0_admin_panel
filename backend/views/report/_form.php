<?php

/* @var $this yii\web\View */
/* @var $model \common\models\VaaniClientMaster */

use yii\bootstrap4\Html;
use kartik\select2\Select2;
use common\models\EdasCampaign;

$urlManager = Yii::$app->urlManager;

?>

<!-- <div id="qmsProcess">
    <div class="clearfix mb-3 top-heading">
        <h4 class="m-0 float-left">QMS Process</h4>
        <div class="float-right">
            <a class="btn btn-sm btn-outline-primary" id="addQmsProcessBtn"><i class="fas fa-plus"></i></a>
        </div>
    </div>
    <table id="processTable" class="table table-striped table-bordered">
        <thead>
            <tr role="row">
                <th>Process</th>
                <th>Created By</th>
                <th>CQ Score Target</th>
                <th>CAP Level</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th></tr>
        </thead>
        <tbody>
            <tr>
                <td>AkbrTrvl</td>
                <td>admin</td>
                <td>100</td>
                <td>30</td>
                <td>2022-07-19 15:24:00</td>
                <td><label class="switch"> <input type="checkbox" class="status" onclick="setStatus(0,7)" checked=""> <span class="slider round"></span> </label></td>
                <td><a href="#" class="text-warning mr-10" id="editprocess" onclick="editprocess(7)"><i class="fas fa-edit"></i></a></td>
            </tr>
            <tr>
                <td>NATCLG</td>
                <td>6898</td>
                <td>85</td>
                <td>30</td>
                <td>2022-05-03 12:23:58</td>
                <td><label class="switch"> <input type="checkbox" class="status" onclick="setStatus(0,6)" checked=""> <span class="slider round"></span> </label></td>
                <td><a href="#" class="text-warning mr-10" id="editprocess" onclick="editprocess(6)"><i class="fas fa-edit"></i></a></td>
            </tr>
            <tr>
                <td>TEST_CAM</td>
                <td>admin</td>
                <td>95</td>
                <td>30</td>
                <td>2022-04-21 11:51:22</td>
                <td><label class="switch"> <input type="checkbox" class="status" onclick="setStatus(0,4)" checked=""> <span class="slider round"></span> </label></td>
                <td><a href="#" class="text-warning mr-10" id="editprocess" onclick="editprocess(4)"><i class="fas fa-edit"></i></a></td>
            </tr>
            <tr>
                <td>vakrange</td>
                <td>3019</td>
                <td>150</td>
                <td>30</td>
                <td>2022-04-21 16:00:43</td>
                <td><label class="switch"> <input type="checkbox" class="status" onclick="setStatus(1,5)"> <span class="slider round"></span> </label></td>
                <td><a href="#" class="text-warning mr-10 disabled" id="editprocess" onclick="editprocess(5)"><i class="fas fa-edit"></i></a></td>
            </tr>
        </tbody>
    </table>
</div>

<div id="addProcessBox" style="display:none;">
    <div class="clearfix mb-3 top-heading">
        <h4 class="m-0 float-left">Add Process</h4>
        <div class="float-right">
        <a class="btn btn-sm btn-outline-primary backCampaignBtn" href="#" id="backQmsProcess"><i class="fas fa-chevron-left"></i></a>
        </div>
    </div>

    <div class="row align-items-center justify-content-center form-box">
        <div class="col-sm-5">
            <div class="row">
                <div class="col-sm-12 col-xl-12">
                    <div class="form-group row">
                        <label class="form-label col-sm-4">Select Process</label>
                        <div class="col-sm-8">
                            <select id="process" class="form-control">
                                <option value="0"> Select Process </option><option value="123456"> 123456</option><option value="2021"> 2021</option><option value="AkbrTrvl"> AkbrTrvl</option><option value="ANCH_DI1"> ANCH_DI1</option><option value="AXISSOLN"> AXISSOLN</option><option value="CalibHR"> CalibHR</option><option value="DirOBD"> DirOBD</option><option value="EdasIBD"> EdasIBD</option><option value="EDASMult"> EDASMult</option><option value="EdasOBD"> EdasOBD</option><option value="GblBlast"> GblBlast</option><option value="GlobalGW"> GlobalGW</option><option value="K811REM"> K811REM</option><option value="Kotak811"> Kotak811</option><option value="KotakInv"> KotakInv</option><option value="LMSCAMP"> LMSCAMP</option><option value="MHL_INB"> MHL_INB</option><option value="NATCLG"> NATCLG</option><option value="PLCC"> PLCC</option><option value="PNBMET"> PNBMET</option><option value="PNBOUT"> PNBOUT</option><option value="SMTSAVE"> SMTSAVE</option><option value="Survey"> Survey</option><option value="TEST_CAM"> TEST_CAM</option><option value="Test_eml"> Test_eml</option><option value="TEST_ORG"> TEST_ORG</option><option value="TEST_OWN"> TEST_OWN</option><option value="TestOBD"> TestOBD</option><option value="TheoBrom"> TheoBrom</option><option value="UPSTOX"> UPSTOX</option><option value="UPSTOX_SALES"> UPSTOX_SALES</option><option value="UPX_SALE"> UPX_SALE</option><option value="UTI_MF"> UTI_MF</option><option value="vakrange"> vakrange</option><option value="VIPB"> VIPB</option><option value="WELLTHY"> WELLTHY</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-12">
                    <div class="form-group row">
                        <label class="form-label col-sm-4">CQ Score Target</label>
                        <div class="col-sm-8">
                            <input type="number" name="cqscore" id="cqscore" class="form-control" placeholder="CQ Score Target (%)" required="">
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12 col-xl-12">
                    <div class="form-group row">
                        <label class="form-label col-sm-4">CAP Level : </label>
                        <div class="col-sm-8">
                            <select class="form-control" name="Outof" id="caplevel">
                                <option value="">Select CAP Level</option>
                                <option value="30">30</option>
                                <option value="60">60</option>
                                <option value="90">90</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-12">
                    <div class="form-group row">
                        <label class="form-label col-sm-4">PIP Status</label>
                        <div class="col-sm-8">
                            <input type="text" name="pipstatus" id="pipstatus" class="form-control" required="" placeholder="PIP Status">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-12">
                    <div class="form-group row">
                        <label class="form-label col-sm-3">Categorization(%)</label>
                    
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-sm-3 pl-1 pr-0">
                                    <input type="text" name="A" id="cat_a" maxlength="7" class="form-control required cat_validate" placeholder="A Categorization" required="">
                                </div>
                                <div class="col-sm-3 pl-1 pr-0">
                                    <input type="text" name="B" id="cat_b" maxlength="7" class="form-control required cat_validate" placeholder="B Categorization" required="">
                                </div>
                                <div class="col-sm-3 pl-1 pr-0">
                                    <input type="text" name="C" id="cat_c" maxlength="7" class="form-control required cat_validate" placeholder="C Categorization" required="">
                                </div>
                                <div class="col-sm-3 pl-1">
                                    <input type="text" name="D" id="cat_d" maxlength="7" class="form-control required cat_validate" placeholder="D Categorization" required="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button type="button" id="addprocess" class="btn btn-outline-primary"> Keep Adding </button>
                <button type="button" id="addback" class="btn btn-outline-primary"> Add &amp; Back</button>
            </div>
        </div>
    </div>
</div> -->
<div >
    <div class="row align-items-center justify-content-center form-box">
        <div class="col-sm-8 col-xl-8">
            <div class="row">
                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                            <label class="form-label col-sm-4">Start Date</label>
                            <div class="col-sm-8">
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                            <label class="form-label col-sm-4">End Date</label>
                            <div class="col-sm-8">
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= date('Y-m-d') ?>" required max="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                            <label class="form-label col-sm-4">Start Time</label>
                            <div class="col-sm-8">
                                <input type="time" id="start_time" name="start_time" class="form-control" value="<?= date('H:00', strtotime('-9 hours')) ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                            <label class="form-label col-sm-4">End Time</label>
                            <div class="col-sm-8">
                                <input type="time" id="end_time" name="end_time" class="form-control" value="<?= date('H:00') ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                            <label class="form-label col-sm-4">Campaign Types</label>
                            <div class="col-sm-8">
                                <?php echo Select2::widget([
                                    'name' => 'campaign_type[]',
                                    'data' => EdasCampaign::$campaign_types,
                                    'options' => [
                                        'placeholder' => 'Select a Campaign Types...',
                                        'id' => 'select_types'
                                    ],
                                    'pluginOptions' => [

                                        'allowClear' => true
                                        
                                    ],
                                ]); ?>
                            
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                        <label class="form-label col-sm-4">Campaigns</label>
                            <div class="col-sm-8">
                                <?php echo Select2::widget([
                                    'name' => 'campaigns[]',
                                    'data' => $campaigns,
                                    'options' => [
                                        'placeholder' => 'Select a campaign...',
                                        'id' => 'select_campaigns',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                                
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                        <label class="form-label col-sm-4">Queues</label>
                            <div class="col-sm-8">
                                <?php echo Select2::widget([
                                    'name' => 'queues[]',
                                    'data' => $queues,
                                    'options' => [
                                        'placeholder' => 'Select the queue...',
                                        'id' => 'select_queues'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => true
                                    ],
                                ]); ?>
                                
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-xl-6">
                        <div class="form-group row">
                        <label class="form-label col-sm-4">Users</label>
                            <div class="col-sm-8">
                                <?php echo Select2::widget([
                                    'name' => 'users[]',
                                    'data' => $users,
                                    'options' => [
                                        'placeholder' => 'Select the user...',
                                        'id' => 'select_users',
                                        'All' => 'All',
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => true,
                                    ],
                                ]); ?>
                                <?php //echo $form->field($model, 'users[]', ['template' => '{label}{input}{error}{hint}'])->dropdownList($users, ['prompt' => 'Select...', 'required' => true,'id' => 'select_users'])->label(false) ?>
                                
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>

                    <?php if(isset($is_hourly) && $is_hourly){ ?>
                        <div class="col-sm-6 col-xl-6">
                            <div class="form-group row">
                                <label class="form-label col-sm-4">Type</label>
                                <div class="col-sm-8">
                                    <?= Select2::widget([
                                        'name' => 'time_type',
                                        'data' => [1 => 'Default', 2 => 'Hourly'],
                                        'options' => [
                                            'placeholder' => 'Select...',
                                            'id' => 'select_time_type',
                                        ],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]); ?>
                                    
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="col-sm-12 col-xl-12">
                        <div class="form-group row">
                        <label class="form-label col-sm-2">Report Columns</label>
                        <div class="col-sm-10">
                                <?php echo Select2::widget([
                                    'name' => 'report_columns',
                                    'data' => $report_columns,
                                    'options' => [
                                        'placeholder' => 'Select the Columns...',
                                        'id' => 'selected_report_columns'
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'multiple' => true
                                    ],
                                ]); ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <div class="form-group">
                <button type="submit" class="btn btn-outline-primary report-submit">Submit</button>
            </div>
        </div>
    </div>
</div>



<?php 

// $this->registerJs("

// $('#s1').select2Sortable();

// ");

$this->registerJs("

function fetchcampaigns(campaign_type){
    //var campaign_type = $('#select_types').val();
    $.ajax({
        method: 'GET',
        url: '". $urlManager->baseUrl . '/index.php/report/get-campaigns' ."',
        data: {campaign_type : campaign_type}
    
    }).done(function(data){
        var coptionList = '<option value=\"\">Select Campaigns</option>';
        var campaign_ids = [];
        if(data){
            $('#select_campaigns').html('');
            data = JSON.parse(data);
            $.each(data, function(key, value){
                coptionList += '<option value=\"'+key+'\">'+ value +'</option>';
                campaign_ids.push(key);
            });
        }
        $('#select_campaigns').html(coptionList);
        $('#select_campaigns').val(campaign_ids).trigger('change');
    });
}   


function fetchqueues(campaign_ids){
    //var campaign_ids = $('#select_campaigns').val();
    //console.log(campaign_ids);
    $.ajax({
        method: 'GET',
        url: '". $urlManager->baseUrl . '/index.php/report/get-queues' ."',
        data: {campaign_id : campaign_ids}
    }).done(function(data){
        var qoptionList = '<option value=\"\">Select Queues</option>';
        var queue_ids = [];
        if(data){
            $('#select_queues').html('');
            data = JSON.parse(data);
            $.each(data, function(key, value){
                qoptionList += '<option value=\"'+key+'\">'+ value +'</option>';
            queue_ids.push(key);
            });
        }
        $('#select_queues').html(qoptionList);
        $('#select_queues').val(queue_ids).trigger('change');
    });
}

function fetchusers(campaign_ids,queue_ids){
    //var queue_ids = $('#select_queues').val();
    $.ajax({
        method: 'GET',
        url: '". $urlManager->baseUrl . '/index.php/report/get-users' ."',
        data: {campaigns : campaign_ids, queues : queue_ids}
    }).done(function(udata){
        var uoptionList = '<option value=\"\">Select Users</option>';
        var user_ids = [];
        if(udata){
            $('#select_users').html('');
            udata = JSON.parse(udata);
            $.each(udata, function(key, value){
                if(key==!2){
                    key = 'Active Users';
                }else{
                    key = 'Inactive Users';
                }
                uoptionList += '<optgroup label=\"'+key+'\">';
                $.each(value, function(k, v){
                uoptionList += '<option value=\"'+k+'\">'+ v +'</option></optgroup>';
                user_ids.push(k);
                });
            });
        }
        $('#select_users').html(uoptionList);
        $('#select_users').val(user_ids).trigger('change');
    });
}

$('#select_types').on('change', function(){
    var campaign_type = $(this).val();

    fetchcampaigns(campaign_type);
    if(campaign_type){
        $('#select_types').next().next().next().html('');
    }else{
        $('#select_types').next().next().next().html('Type cannot be empty');
    }

});

$('#select_campaigns').on('change', function(){
    var campaign_ids = $(this).val();
    
    fetchqueues(campaign_ids);
    if(campaign_ids){
        $('#select_campaigns').next().next().next().html('');
    }else{
        $('#select_campaigns').next().next().next().html('Campaigns cannot be empty');
    }
});


$('#select_queues').on('change', function(){
    var queue_ids = $(this).val();
    var campaign_ids = $('#select_campaigns').val();
    
    fetchusers(campaign_ids,queue_ids);
    if(queue_ids.length){
        $('#select_queues').next().next().next().html('');
    }else{
        $('#select_queues').next().next().next().html('Queues cannot be empty');
    }
});

$('#select_users').on('change', function(){
    var user_ids = $(this).val();
    
    if(user_ids.length){
        $('#select_users').next().next().next().html('');
    }else{
        $('#select_users').next().next().next().html('Users cannot be empty');
    }
});

// validation on click of submit button
$('.report-submit').on('click', function() {
    
    if(!$('#select_types').val()) {
        $('#select_types').next().next().next().html('Type cannot be empty');
        return false;
    
    }else if(!$('#select_campaigns').val()) {
        $('#select_campaigns').next().next().next().html('Campaigns cannot be empty');
        return false;
    
    }else if(!$('#select_queues').val()) {
        $('#select_queues').next().next().next().html('Queues cannot be empty');
        return false;
    
    }else if(!$('#select_users').val()) {
        $('#select_users').next().next().next().html('Users cannot be empty');
        return false;
    
    }
});

");
?>

<script>
    $("#addQmsProcessBtn").click(function(){
        // alert("Hi");
        $("#qmsProcess").hide();
        $("#addProcessBox").show();
    });
    $("#backQmsProcess").click(function(){
        // alert("Hi");
        $("#qmsProcess").show();
        $("#addProcessBox").hide();
    });
    
</script>