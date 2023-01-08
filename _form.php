<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use common\models\EdasCampaign;
use common\models\User;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use common\models\VaaniCampaignQueue;
/* @var $this yii\web\View */
/* @var $model common\models\EdasCampaign */
/* @var $form yii\widgets\ActiveForm */

$urlManager = Yii::$app->urlManager;
$queues_ids = array();
foreach ($model->queues as $key => $queue) {
    array_push($queues_ids,$queue->id);
    
}
// echo"<pre>";print_r($cloneid);
$this->registerJs("
$('.dataTable').DataTable( {} );
");
// kp tabs fetch

 ?>

<script>

  
function updateSlider(passObj) {
        var obj = $(passObj);
        var value = obj.val();
        var min = obj.attr('min');
        var max = obj.attr('max');
        var range = Math.round(max - min);
        var percentage = Math.round((value - min) * 100 / range);
        var nextObj = obj.next();
    
        var btn = nextObj.find('span.bar-btn');
    
        if (value == min) {
            nextObj.find('span.bar-btn').css('left', percentage + '%');
            nextObj.find('.bar span').css('width', '0%');
        }
        else if (value == max) {
            nextObj.find('span.bar-btn').css('left', 'calc(' + percentage + '% - ' + btn.width() + 'px)');
            nextObj.find('.bar span').css('width', '100%');
        }
        else {
            nextObj.find('span.bar-btn').css('left', 'calc(' + percentage + '% - ' + btn.width() / 2 + 'px)');
            nextObj.find('.bar span').css('width', '50%');
        }
        
    
        if (value == 5) {
            nextObj.find('span.bar-btn > span').text('Default');
        }
        else if (value == 13) {
            nextObj.find('span.bar-btn > span').text('Medium');
        }
        else if (value == 20) {
            nextObj.find('span.bar-btn > span').text('High');
        }
    };


</script>
<div class="container-fluid steps" id="tabsSection" >
    <div class="card-header p-0 border-bottom-0">
        <!-- <div class="float-right" style="margin-right: 19px;">
            <a class="btn btn-primary backCampaignBtn" href="javascript:void(0)">Back</a>
        </div> -->
        
        <ul id="progressbar" role="tablist" style="display:none;">
            <li class="active">General</li>
            <li style="display:none;">Skill</li>
            <li style="display:none;">Queues</li>
            <li>Disposition</li>
            <li>Agent</li>
            <li>Dialer</li>
            <!-- <li>Recording</li> -->
            <li>Knowledge Portal</li>
        </ul>
    </div>
    <div class="card mt-3">
        <!-- /.card-header -->
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="tabs-tab" role="tablist" style="display:none;">
                <li class="nav-item">
                    <a class="nav-link active" id="tabs-general-tab" data-toggle="pill" href="#tabs-general"
                    role="tab" aria-controls="tabs-general" aria-selected="true">General</a>
                </li>
                <li class="nav-item" style="display:none;">
                <a class="nav-link" id="tabs-skill-tab" data-toggle="pill" href="#tabs-skill" role="tab"
                    aria-controls="tabs-skill" aria-selected="false">Skill</a>
                </li>
                <li class="nav-item" style="display:none;">
                    <a class="nav-link" id="tabs-queues-tab" data-toggle="pill" href="#tabs-queues" role="tab"
                    aria-controls="tabs-queues" aria-selected="false">Queues</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-disposition-tab" data-toggle="pill" href="#tabs-disposition"
                    role="tab" aria-controls="tabs-disposition" aria-selected="false">Disposition</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-agent-tab" data-toggle="pill" href="#tabs-agent" role="tab"
                    aria-controls="tabs-agent" aria-selected="false">Agent</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tabs-dialer-tab" data-toggle="pill" href="#tabs-dialer" role="tab"
                    aria-controls="tabs-dialer" aria-selected="false">Dialer</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" id="tabs-recording-tab" data-toggle="pill" href="#tabs-recording" role="tab"
                    aria-controls="tabs-recording" aria-selected="false">Recording</a>
                </li> -->
                
                <li class="nav-item">
                    <a class="nav-link" id="tabs-knowledge-portal-tab" data-toggle="pill" href="#tabs-knowledge-portal"
                    role="tab" aria-controls="tabs-knowledge-portal" aria-selected="false">Knowledge Portal</a>
                </li>
            </ul>
            <div class="tab-content" id="tabs-tabContent">
                <!-- General Tab - Start -->

                <div class="tab-pane active" id="tabs-general" role="tabpanel" aria-labelledby="tabs-general-tab">
                    <?php $form = ActiveForm::begin(['options' => ['autocomplete' => 'off', 'class' => 'validate-form'], 'enableAjaxValidation' => true, 'validateOnChange' => false,'enableClientValidation' => true, 'enableAjaxValidation' => false, 'validateOnSubmit' => true, 'validateOnBlur' => true,'validateOnChange' => true]); ?>
                    <div id="addCampiagnForm">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Add Campaign</h4>
                            <div class="float-right">
                                <a class="btn btn-sm btn-outline-primary backCampaignBtn" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="row">
                                    <!-- <div class="col-lg-4"> -->
                                        <!-- <label class="form-label" for="edascampaign-client_id">Client Name</label> -->
                                        <!--//Html::dropDownList('client_id', null, 'client_id', 'name')--> <span class="camp_id" value="<?= isset($model->id) ? $model->id : $cloneid  ?>"></span>
                                        
                                    <!-- </div> -->
                                    
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-campaign_name">Campaign Name</label>
                                            <div class=" col-sm-8">
                                                <?= $form->field($model, 'campaign_name', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Campaign Name', 'required' => true])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-campaign_type">Campaign Type</label>
                                            <div class=" col-sm-8">
                                                <?= $form->field($model, 'campaign_type', ['template' => '{input}{label}{error}{hint}'])->dropdownList($campaign_types, ['prompt' => 'Select...', 'required' => true, 'disabled' => !$model->isNewRecord])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="call_medium">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-call_medium">Call Medium</label>
                                            <div class="col-sm-8">
                                                <?= $form->field($model, 'call_medium', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_mediums, ['prompt' => 'Select...', 'required' => true, 'disabled' => !$model->isNewRecord])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-mode">CRM Name</label>
                                            <div class="col-sm-8">
                                                <select id="edascampaign-crm_name" class="form-control" name="EdasCampaign[mode]">

                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="importMusic">Import Music</label>
                                            <div class="col-sm-8">
                                                <?= $form->field($model, 'logoFile', ['options' => ['class' => 'upload_file_input upload-audio-file', 'id'=> 'uploadAudioFile' ]], '<span>tesfadfs</span>')->fileInput(['accept'=> 'audio/mp3,audio/'])->label('<span class="logo_label btn btn-default"><i class="fa fa-upload"></i> Audio</span> <span class="logo_name"></span>') ?>
                                                <span class="text-preview">Choose file to upload</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-select_audio">Hold Music</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <select id="edascampaign-select_audio" class="form-control" name="EdasCampaign[select_audio]" >
                                                    </select>
                                                    <!-- options apend dynamically using js -->

                                                    <div class="input-group-append">
                                                        <div class="input-group-btn">
                                                            <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#preview_audio" disabled>Listen</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-audioList">Welcome Prompt</label>
                                            <div class="col-sm-8">
                                                <select id="edascampaign-audioList" class="form-control" name="EdasCampaign[audioList]"
                                                >
                                                    <option value="">Select...</option>
                                                    <option value="audio_01">Audio 01</option>
                                                    <option value="audio_02">Audio 02</option>
                                                    <option value="audio_03">Audio 03</option>
                                                    <option value="audio_04">Audio 04</option>
                                                </select>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-cli_number">CLI Number</label>
                                            <div class="col-sm-8">
                                                <?= $form->field($model, 'cli_number', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'CLI Number', 'required' => true])->label(false) ?>
    
                                                    <!-- <input type="text" id="edascampaign-cli_number" class="form-control" name="" placeholder="CLI Number" >
                                                <div class="help-block"></div>  -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-6" id="outbound_criteria">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4" for="edascampaign-Criteria">Criteria</label>
                                            <div class="col-sm-8">
                                                <? //$form->field($model, 'outbound_criteria', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'value' => ($model->outbound_criteria ? $model->outbound_criteria: '')])->label(false) ?>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-lg-6" id="campaign_operators">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4">Operators</label>
                                            <div class="col-sm-8">
                                                <?= $form->field($model, 'campaign_operators', ['template' => '{input}{label}{error}{hint}'])->dropdownList($clientsoperator, ['prompt' => 'Select.....', 'value' => isset($model->operator_id) ? $model->operator_id : null])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- qms_id -->
                                    <div class="col-lg-6" id="campaign_operators">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4">Audit Template</label>
                                            <div class="col-sm-8">
                                                <?= $form->field($model, 'qms_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($qms_names, ['prompt' => 'Select.....', 'value' => isset($model->qms_id) ? $model->qms_id : null])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4">Call Timeout</label>
                                            <div class="col-sm-8">
                                                <?= $form->field($model, 'call_timeout', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Timeout', 'value' => ($model->call_timeout ? $model->call_timeout : 60)])->label(false) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row form-group">
                                            <label class="form-label col-sm-4">Status </label>
                                            <div class='col-sm-8 mt-1'>
                                                <?php $model->campaign_status==1 ? $val = "true" : $val = "false" ?>
                                                <button type="button" id="status-btn" class="btn btn-toggle" data-toggle="button"
                                                aria-pressed=<?= ($val ? $val : "false") ?> id="edascampaign-campaign_status" autocomplete="off">
                                                <div class="handle"></div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <div class="row">
                                            <label class="form-label col-sm-4">Run Time </label>
                                            <div class="col-sm-8">
                                                <div class="input-group" id="removeElement">
                                                    <?= $form->field($model, 'call_window', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'selected_call_window', 'class'=> 'form-control'])->label(false) ?>
                                                    <!-- <select id="selected_call_window" class="form-control" name="EdasCampaign[call_window]">
                                                        <option value="">Select...</option>
                                                        <option value="100">Edas_OB_CALLWINDOW</option>
                                                        <option value="99">EDAS_01</option>
                                                        <option value="98">Edas_QA_01</option>
                                                        <option value="96">12-Hours Shift</option>
                                                        <option value="75">All 24 hours</option>
                                                        <option value="16">All 24 hour</option>
                                                        <option value="14">Krishna </option>
                                                        <option value="12">Edas Shift</option>
                                                        <option value="11">Indian Shift Timing (9 am - 6 pm)</option>
                                                        <option value="10">Privi_Eng</option>
                                                        <option value="9">Default Callling 10-7</option>
                                                        <option value="8">Default Calling</option>
                                                        <option value="7">Custom Shift 10pm - 5am</option>
                                                        <option value="4">Custom Shift Timing 10pm - 5am</option>
                                                    </select> -->
                                                    <div class="input-group-append">
                                                        <div class="input-group-btn">
                                                            <button type="button" class="btn btn-outline-primary" id="viewMore" disabled>View</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-12 form-group text-center btnGroup">
                                        <button type="reset" class="btn btn-outline-secondary" id="reset">Reset</button>
                                        <button type="button" class="btn btn-outline-primary form_loader skip-next-next generalnext" id="camp_form" value="Submit">Next</button>
                                        <button type="button" class="btn btn-outline-primary form_loader saveCurrentForm  generalnext" >Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <!-- General Tab - End -->
                <!-- Skill Tab - Start -->
                <div class="tab-pane" id="tabs-skill" role="tabpanel" aria-labelledby="tabs-skill-tab">
                    <div id="skillList">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Skill List</h4>
                            <div class="float-right">
                                <!-- <a class="btn btn-primary backCampaignBtn" href="javascript:void(0)">Back</a> -->
                                 <button type="button" id="addusermodal" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#addUserModal">Assign User</button>
                                 <a class="btn btn-sm btn-outline-primary backCampaignBtn" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <form>
                        <div class="add-skill-form row pt-4 mb-3">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="form-label" for="edascampaign-add_skill">Add Skill</label>
                                    </div>
                                    <div class="col-lg-8" id="changeOnUpdate">
                                        <!-- <div id="addskillhideshow"> -->
                                            <input type="text" id="edascampaign-add_skill" class="form-control" value="" placeholder="Add Skill" >
                                            <div class="help-block"></div>
                                        <!-- <div> -->
                                        <!-- <div id="addskillshowhide"> -->
                                        <?= $form->field($model, 'campaign_id', ['template' => '{input}{label}{error}{hint}'])->widget(Select2::classname(), [
                                                'data' => $add_skills,
                                                'options' => ['placeholder' => 'Add skills','id' => 'addskillshowhide'],
                                                'pluginOptions' => [
                                                    'allowClear' => true,
                                                    'multiple' => false,
                                                    'tags' => true,
                                                    'tokenSeparators' => [',', ' '],
                                                    // 'maximumInputLength' => 10
                                                ],
                                                ])->label(false); ?>
                                        <!-- </div> -->
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class=" row">
                                    <div class="col-lg-4">
                                        <label class="form-label" for="edascampaign-sliderUserWeight">Skill Weight</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <!-- <input id="edascampaign-sliderUserWeight" class="slider slider-blue" value="0" min="0" max="2"step="1" name="007" type="range" required="true"/> -->
                                        <span class="slider slider-blue">
                                            <input class="slider slider-blue" value="5" min="5" max="20" step="1" name="007" type="range" id="edascampaign-sliderUserWeight" oninput="updateSlider(this)"/>
                                            <span class="slider-container slider-blue">
                                                <span class="bar">
                                                    <span style="width: 0%;"></span>
                                                    <div class="dot" id="1" style="left:50%;"></div>
                                                </span>
                                                <span class="bar-btn"><span>Default</span>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="form-label" for="edascampaign-call_window">Call window</label>
                                    </div>
                                    <div class="col-lg-8">
                                    <?= $form->field($queueModel, 'call_window', ['template' => '{input}{label}{error}{hint}'])->dropdownList($call_windows, ['prompt' => 'Select...', 'id' => 'queue_call_window', 'class' => 'form-control queue-call-window'])->label(false)?>
                                    <input type="hidden" id="queue_modal_id">
                                </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="form-label" for="edascampaign-call_timeout">Call Timeout</label>
                                    </div>
                                    <div class="col-lg-8">
                                            <?= $form->field($model, 'call_timeout', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Call Timeout', 'id' => 'call_timeout'])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="form-label" for="edascampaign-dni">DNI</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($queueModel, 'dni_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($dnis, ['prompt' => 'Select...', 'id' => 'queue_dni_id'])->label(false) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label class="form-label" for="edascampaign-criteria">Criteria</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <?= $form->field($queueModel, 'criteria', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...'])->label(false) ?>
                                    </div>
                                </div>
                            </div>

                                    <div class="col-lg-4">
                                        <div class="row">
                                            <label class="col-lg-4" for="uploadMusic">Upload Music</label>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'musicfile', ['options' => ['class' => 'upload_file_input upload-audiomusic-file', 'id'=> 'uploadAudiomusicFile' ]], '<span>tesfadfs</span>')->fileInput(['accept'=> 'audio/mp3,audio/'])->label('<span class="logo_label btn btn-default"><i class="fa fa-upload"></i> Hold music</span> <span class="logo_name"></span>') ?>
                                                <span class="text-preview-audio">Choose file to upload</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="row">
                                            <label class="col-lg-4" for="soundMusic">sound Music</label>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'soundfile', ['options' => ['class' => 'upload_file_input upload-soundmusic-file', 'id'=> 'uploadsoundmusicFile' ]], '<span>tesfadfs</span>')->fileInput(['accept'=> 'audio/mp3,audio/'])->label('<span class="logo_label btn btn-default"><i class="fa fa-upload"></i>Sound music</span> <span class="logo_name"></span>') ?>
                                                <span class="text-preview-sound">Choose file to upload</span>
                                            </div>
                                        </div>
                                    </div>


                            <div class="col-lg-12 text-center" style="margin-bottom: 15px">
                                <button type="button" id="add_skill" class="btn btn-outline-primary">Add Skill</button>
                                <!-- <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addUserModal">Assign User</button> -->
                            </div>
                        </div>
                        </form>
                        <div class="row">
                            <div class="col-lg-8">
                                <table class="table table-striped dataTable">
                                    <thead>
                                        <tr>
                                            <th>Agent Id</th>
                                            <th>Agent Name</th>
                                            <th>Skill Name</th>
                                            <th>User Weight</th>
                                            <th>Manager</th>
                                            <th>Supervisor</th>
                                            <th style="width: 0;">&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody  id="displayAssignedUser">
                                            <?php if(count($skill_assigned_users) > 0 ){ foreach ($skill_assigned_users as $key => $value) {
                                                    $queue_name = VaaniCampaignQueue::find()->select(['queue_name'])->where(['del_status' => User::STATUS_NOT_DELETED, 'queue_id' => $value['queue_id'] ])->one();
                                                   
                                                ?>
                                                <tr>
                                                    <td class="db"><?= $value['user_id'] ?></td>
                                                    <td><?= $value['user_name'] ?></td>
                                                    <td><?= $queue_name['queue_name'] ?></td>
                                                    <td>0</td>
                                                    <td><?= $value['manager_id'] ?></td>
                                                    <td><?= $value['supervisor_id'] ?></td>
                                                    <td><a href="javascript:void(0)"    class="moveUnassignAgent">Unassigned</a> | <a href="javascript:void(0)" class="text-danger"><span class="fa fa-trash"></span></a></td>
                                                </tr>
                                            <?php }} ?>
                                        <!-- <tr data-key="189" class="even">
                                            
                                        </tr> -->
                                        
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <canvas id="pieChart"
                                style="min-height: 550px; height: 550px; max-height: 550px; max-width: 100%;"></canvas>
                            </div>
                            <div class="col-lg-12 form-group text-center btnGroup mb-0">
                                <button type="button" class="btn btn-outline-primary previous">Previous</button>
                                <button type="reset" class="btn btn-outline-secondary" id="reset">Reset</button>
                                <button type="button" class="btn btn-outline-primary form_loader next tab_skill" id="tab_skill" value="Submit">Next</button>
                                <button type="button" class="btn btn-outline-primary form_loader saveCurrentForm tab_skill" id="tab_skill">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Skill Tab - End -->
                <!-- Queue Tab - Start -->
                <div class="tab-pane" id="tabs-queues" role="tabpanel" aria-labelledby="tabs-queues-tab">
                    <div class="queues_list" id="queuesList">
                        <div class="clearfix mb-3 top-heading">
                            <!-- <h4 class="m-0 float-left">Queues List</h4> -->
                            <div class="float-left">
                                <div class="row">
                                    <div class="col-lg-5 p-0">
                                        <label class="form-label" for="edascampaign-campaign_name">Queues Type</label>
                                    </div>
                                    <div class="col-lg-7">
                                        <select id="edascampaign-mode" class="form-control" name="EdasCampaign[mode]"
                                        required="">
                                        <option value="">Select...</option>
                                        <option value="1">Longest Idle</option>
                                        <option value="2">Fixed</option>
                                        <option value="3">Robin round</option>
                                        <option value="4">Skill Based</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="float-right">
                            <a class="btn btn-sm btn-outline-primary backCampaignBtn" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="col-lg-45-percent">
                                <h4 class="title text-center">Unassigned Agent List</h4>
                                <!-- <div class="mb-3">
                                    <div class="row">
                                        <div class="col-lg-3">
                                        <label class="form-label" for="edascampaign-call_medium">Search By column</label>
                                        </div>
                                        <div class="col-lg-4">
                                        <select class="form-control" id="selectColumnType">
                                            <option>Select Column Type</option>
                                            <option value="Agent ID">Agent ID</option>
                                            <option value="Agent Name">Agent Name</option>
                                            <option value="Skill Weight">Skill Weight</option>
                                            <option value="Manager">Manager</option>
                                            <option value="Supervisor">Supervisor</option>
                                        </select>
                                        </div>
                                        <div class="col-lg-4">
                                        <input type="search" name="" id="seachByColumnInput" placeholder="Search" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div> -->
                                <table id="unassignedAgentTable" class="table table-striped " width="100%" cellspacing="0" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th style="width: 53.2px;">Agent Id</th>
                                            <th style="width: 77.2px;">Agent Name</th>
                                            <th style="width: 76.2px;">Skill Name</th>
                                            <th style="width: 56.2px;">Manager</th>
                                            <th style="width: 70.2px;">Supervisor</th>
                                            <th style="width: 44.2px;">Assign</th>
                                        </tr>
                                    </thead>
                                    <tbody id="unassignedd">
                                    <?php //echo"<pre>"; print_r($skill_assigned_users);
                                     foreach ($skill_assigned_users as $key => $value) {
                                                    $queue_name = VaaniCampaignQueue::find()->select(['queue_name'])->where(['del_status' => User::STATUS_NOT_DELETED, 'queue_id' => $value['queue_id'] ])->one();
                                                 if ($value['is_active']== 2) {
                                                    # code...
                                                 
                                                 ?>
                                                <tr>
                                                    <td hidden="hidden"><?= $value['queue_id'] ?></td>
                                                    <td class="db"><?= $value['user_id'] ?></td>
                                                    <td><?= $value['user_name'] ?></td>
                                                    <td><?= $queue_name['queue_name'] ?></td>
                                                    <td><?= $value['manager_id'] ?></td>
                                                    <td><?= $value['supervisor_id'] ?></td>
                                                    <td><a href="javascript:void(0)" class="moveUnassignAgent">Assign <i class="fas fa-chevron-right"></i></a></td>
                                                </tr>
                                            <?php }
                                         } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-9-percent top-to-bottom-border">
                                <div class="fetch-left-and-right">
                                <button id="fetchLeftToRightAll" type="button" class="btn btn-primary mb-2"><i class="fas fa-angle-double-right"></i></button>
                                <button id="fetchRightToLeftAll" type="button" class="btn btn-primary mb-2" disabled><i class="fas fa-angle-double-left"></i></button>
                                <button id="fetchLeftToRight" type="button" class="btn btn-primary mb-2" disabled><i class="fas fa-angle-right"></i></button>
                                <button id="fetchRightToLeft" type="button" class="btn btn-primary" disabled><i class="fas fa-angle-left"></i></button>
                                </div>
                            </div>
                            <div class="col-lg-45-percent">
                                <h4 class="title text-center">Assigned Agent List</h4>
                                <!-- <div class="mb-3">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label class="form-label" for="edascampaign-call_medium">Search By column</label>
                                        </div>
                                        <div class="col-lg-4">
                                            <select class="form-control" id="selectColumnType">
                                                <option>Select Column Type</option>
                                                <option value="Agent ID">Agent ID</option>
                                                <option value="Agent Name">Agent Name</option>
                                                <option value="Skill Weight">Skill Weight</option>
                                                <option value="Manager">Manager</option>
                                                <option value="Supervisor">Supervisor</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <input type="search" name="" id="seachByColumnInput" placeholder="Search" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div> -->
                                <table id="assignedAgentTable" class="table table-striped" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            
                                            <th style="width: 61.2px;">Agent Id</th>
                                            <th style="width: 95.2px;">Agent Name</th>
                                            <th style="width: 74.2px;">Skill Name</th>
                                            <th style="width: 56.2px">Manager</th>
                                            <th style="width: 70.2px;">Supervisor</th>
                                            <th style="width: 47px;">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assinedUserlist">
                                            <?php //echo"<pre>"; print_r($skill_assigned_active_users); 
                                            foreach ($skill_assigned_active_users as $key => $value) {
                                                    $queue_name = VaaniCampaignQueue::find()->select(['queue_name'])->where(['del_status' => User::STATUS_NOT_DELETED, 'queue_id' => $value['queue_id'] ])->one();
                                                 ?>
                                                <tr>
                                                    <td hidden="hidden"><?= $value['queue_id'] ?></td>
                                                    <td class="db"><?= $value['user_id'] ?></td>
                                                    <td><?= $value['user_name'] ?></td>
                                                    <td><?= $queue_name['queue_name'] ?></td>
                                                    <td><?= $value['manager_id'] ?></td>
                                                    <td><?= $value['supervisor_id'] ?></td>
                                                    <td><a href="javascript:void(0)" class="moveUnassignAgent">Unassign<i class="fas fa-chevron-left"></i></a></td>
                                                </tr>
                                            <?php } ?>
                                        <?php if (!$skill_assigned_active_users) { ?>
                                            <tr id="noData">
                                                <!-- <td colspan="6" class="text-center">No Data</td> -->
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-12 form-group text-center btnGroup mt-15 mb-0">
                            <button type="reset" class="btn btn-outline-primary previous" id="">Previous</button>
                            <button type="reset" class="btn btn-outline-secondary" id="clickevent">Reset</button>
                            <button type="button" id="add_queues" class="btn btn-outline-primary form_loader next add_queues">Next</button>
                            <button type="button" class="btn btn-outline-primary form_loader saveCurrentForm add_queues" id="add_queues">Save</button>
                        </div>
                    </div>
                </div>
                <!-- Queue Tab - End -->
                <!-- Disposition List Tab - Start -->
                <div class="tab-pane" id="tabs-disposition" role="tabpanel" aria-labelledby="tabs-disposition-tab">
                    <div id="dispositionList">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Disposition List</h4>
                            <div class="float-right selectBox d-flex">
                                <div class="row">
                                    <div class="col-lg-6 p-0">
                                        <label class="form-label mt-1">Disposition Plan</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <?= $form->field($model, 'disposition_plan_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($disposition_plans, ['prompt' => 'Select...', 'value' => isset($model->disposition_plan_id) ? $model->disposition_plan_id : null , 'class' => 'disposition_plan form-control' ])->label(false) ?>
                                       
                                    </div>
                                </div>
                                <a class="btn btn-sm btn-outline-primary backCampaignBtn ml-2" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <table id="dispositionTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Disposition</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Result</th>
                                    <th>Retry Count</th>
                                    <th>Retry Delay</th>
                                    <th style="width: 60px;">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody id="displosotion_data">

                               <?php foreach ($dispostion_data as $key => $value) {

                                    if(isset(($value['disporecord'][0]))){

                                if($value['disporecord'][0]['type'] === 1){
                                    $type = 'Success';
                                }else if($value['disporecord'][0]['type'] === 2){
                                    $type = 'Failed';
                                }else if($value['disporecord'][0]['type'] === 3){
                                    $type = 'Callback';
                                }else if($value['disporecord'][0]['type'] === 4){
                                    $type = 'DNC';
                                }else{
                                    $type = 'Other';
                                }
                            
                                ?>
                                <tr>
                                    <td id="despoName"><?php echo $value['disporecord'][0]['disposition_name'] ?></td>
                                    <td><?php echo $value['disporecord'][0]['short_code'] ?></td>
                                    <td>Custom</td>
                                    <td><?php echo $type ?></td>
                                    <td><?php echo $value['max_retry_count'] ?></td>
                                    <td><?php echo $value['retry_delay'] ?></td>
                                    <td><a href="#" class="text-primary updisposition" data-id="<?php echo $value['disposition_id'] ?>" id="updisposition<?php echo $value['disposition_id'] ?>"><span class="fa fa-pen"></span></a> | <a href="#" class="text-danger"><span class="fa fa-trash"></span></a></td>
                                </tr>
                               <?php }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 form-group text-center btnGroup mb-0 pt-3">
                        <button type="reset" class="btn btn-outline-primary previous" id="dispositionPrevious">Previous</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        <button type="button" id="dispositionTab" class="btn btn-outline-primary form_loader next dispositionTab">Next</button>
                        <button type="button" id="dispositionTab" class="btn btn-outline-primary form_loader saveCurrentForm dispositionTab">Save</button>
                    </div>
                </div>
                <!-- Disposition List Tab - End -->
                <!-- Agent Tab - Start -->
                <div class="tab-pane" id="tabs-agent" role="tabpanel" aria-labelledby="tabs-agent-tab">
                    <div id="addAgentForm">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Add Agent</h4>
                            <div class="float-right">
                                <a class="btn btn-sm btn-outline-primary backCampaignBtn" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                            <?php  
                            // echo"<pre>";print_r($model->callAccess['is_manual']);
                            ?>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label" for="edascampaign-manual_call">Manual Call </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <?php isset($model->callAccess['is_manual']) ==1 || isset($cloneid) ? $manual="true" : $manual="false" ?>
                                            <button type="button" id="edascampaign-manual_call" class="btn btn-toggle" value="" data-toggle="button" aria-pressed="<?= $manual ? $manual : "false" ?>" autocomplete="off">
                                            <div class="handle"></div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Call Hangup </label>
                                    </div>
                                    <div class="col-lg-8">
                                        <button type="button" id="edascampaign-call_hangup" class="btn btn-toggle active" data-toggle="button"
                                        aria-pressed="true" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Conference </label>
                                    </div>
                                    <div class="col-lg-8">
                                    <?php isset($model->callAccess['is_conference']) ==1 || isset($cloneid) ? $is_conference="true" : $is_conference="false" ?>
                                        <button type="button" id="edascampaign-conference" class="btn btn-toggle" data-toggle="button"
                                        aria-pressed="<?= $is_conference ? $is_conference : "false" ?>" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Blind Transfer </label>
                                    </div>
                                    <div class="col-lg-8">
                                        <?php isset($model->callAccess['is_transfer']) ==1 || isset($cloneid) ? $is_transfer="true" : $is_transfer="false" ?>
                                        <button type="button" id="edascampaign-blind_transfer" class="btn btn-toggle" data-toggle="button"
                                        aria-pressed="<?= $is_transfer ? $is_transfer : "false" ?>" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Consultation</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <?php isset($model->callAccess['is_consult']) ==1 || isset($cloneid) ? $is_consult="true" : $is_consult="false" ?>
                                        <button type="button" id="edascampaign-consultation"  class="btn btn-toggle" data-toggle="button"
                                        aria-pressed="<?= $is_consult ? $is_consult : "false" ?>" autocomplete="off">
                                        <div class="handle"></div>
                                        </button>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Enable Next Call Button </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <button type="button" id="edascampaign-call_button" class="btn btn-toggle active" data-toggle="button"aria-pressed="true" autocomplete="off">
                                            <div class="handle"></div>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Warm Transfer to Inb </label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select id="edascampaign-warm_transfer_to_inb" class="form-control"
                                        name="EdasCampaign[warm_transfer_to_inb]" required="">
                                        <option value="">Select...</option>
                                        <option value="1">Campaign </option>
                                        <option value="2">Agent</option>
                                        <option value="3">Supervisor </option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 campain_list">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Campain List</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select id="edascampaign-campain_list" class="form-control"name="EdasCampaign[campain_list]">
                                            <option value="">Select...</option>
                                            <?php foreach($inbound_campaign as $key => $value) {
                                            ?>
                                                <option value=<?php echo $value['campaign_id'] ?>><?php echo  $value['campaign_name'] ?></option>
                                            
                                            <?php } ?>
                                            
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Require Disposition </label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select id="edascampaign-require_disposition" class="form-control"
                                        name="EdasCampaign[require_disposition]" required="">
                                        <option value="" selected>Select...</option>
                                        <option value="1">Required </option>
                                        <option value="2">Optional </option>
                                        <option value="3">Not Required </option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 Disposition">
                                    <div class="form-group row">
                                    <div class="col-lg-4">
                                        <label class="form-label">Disposition </label>
                                    </div>
                                    <div class="col-lg-8">
                                        <select id="edascampaign-disposition" class="form-control" name="EdasCampaign[disposition]" required="">
                                            <option>select....</option>
                                        </select>
                                        
                                    </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6 wrap_warning_delay">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label" for="edascampaign-wrap_warning_delay">Wrap Warning Delay</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="number" id="edascampaign-wrap_warning_delay" class="form-control" name="EdasCampaign[wrap_warning_delay]"   value="10" required="">
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="col-lg-6 auto_wrap">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label" for="edascampaign-auto_wrap">Auto Wrap</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="number" id="edascampaign-auto_wrap" class="form-control" name="EdasCampaign[auto_wrap]"   value="60" required="">
                                        </div>
                                    </div>
                                </div> -->
                                
                                </div>
                                <div class="col-lg-12 form-group text-center btnGroup mb-0">
                                    <button type="reset" class="btn btn-outline-primary previous">Previous</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                    <button type="button" id="add_agents" class="btn btn-outline-primary form_loader next add_agents agentform">Next</button>
                                    <button type="button" id="add_agents" class="btn btn-outline-primary form_loader saveCurrentForm add_agents">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Agent Tab - End -->
                <!-- Dialer Tab - Start -->
                <div class="tab-pane" id="tabs-dialer" role="tabpanel" aria-labelledby="tabs-dialer-tab">
                    <div id="adddialerForm">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Add Dialer</h4>
                            <div class="float-right"> 
                                <a class="btn btn-sm btn-outline-primary backCampaignBtn" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-lg-6" id="inbound_campaign">
                                        <div class="col-lg-12">
                                            <h5 class="mb-4  title text-center">For Inbound campaign</h5>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Campaign SL Time</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" id="edascampaign-campaign_sl_time" class="form-control" placeholder="Campaign SL Time" required="" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Campaign SL Percent</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" id="edascampaign-campaign_sl_percent" class="form-control" placeholder="Campaign SL Percent"
                                                required="" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Call Answer Delay </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" id="edascampaign-call_answer_delay" class="form-control" placeholder="Call Answer Delay" required="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6" id="outbound_campaign">
                                        <div class="col-lg-12">
                                            <h5 class="mb-4 title text-center">For outbound campaign</h5>
                                        </div>
                                        <!-- <div class="form-group row">
                                            <div class="col-lg-4 P-0">
                                                <label class="form-label">Dial Mode</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <select id="edascampaign-dial_mode" class="form-control"
                                                name="EdasCampaign[dial_mode]" required="">
                                                <option value="">Select...</option>
                                                <option value="1" selected>Automatic </option>
                                                <option value="2">Blaster </option>
                                                <option value="3">Predictive </option>
                                                <option value="4">Preview </option>
                                                <option value="5">Timed Preview </option>
                                                </select>
                                            </div>
                                        </div> -->
                                        <div class=" row" id="CampaignSubType">
                                            <div class="col-lg-4">
                                                <label class="form-label">Dial Mode</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'campaign_sub_type', ['template' => '{input}{label}{error}{hint}'])->dropdownList($campaign_modes, ['prompt' => 'Select...',  'disabled' => !$model->isNewRecord])->label(false) ?>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="Preview">
                                            <div class="col-lg-4">
                                                <label class="form-label">Preview</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" id="edascampaign-preview" class="form-control" name="" placeholder="Preview" aria-required="true">
                                            </div>
                                        </div>
                                        <div class=" row" id="TimePreview">
                                            <div class="col-lg-4">
                                                <label class="form-label">Time Preview</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'preview_time', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Preview Time','id'=> 'edascampaign-time_preview','value' => ($model->preview_time ? $model->preview_time : 10)])->label(false) ?>
                                            </div>
                                            
                                        </div>
                                        <div class=" row" id="outbound_criteria">
                                            <div class="col-lg-4">
                                                <label class="form-label">Criteria</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'outbound_criteria', ['template' => '{input}{label}{error}{hint}'])->dropdownList($criterias, ['prompt' => 'Select...', 'value' => ($model->outbound_criteria ? $model->outbound_criteria: '')])->label(false) ?>
                                            </div>
                                            
                                        </div>
                                        <div class=" row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Preview Autodial</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" id="edascampaign-preview_autodial" class="form-control seconds-input" placeholder="Preview Autodial" required="" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class=" row pacing_ratio">
                                            <div class="col-lg-4">
                                                <label class="form-label">Pacing ratio </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'pacing_value', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Pacing Value', 'id' => 'edascampaign-pacing_ratio','value' => ($model->pacing_value ? $model->pacing_value : 1)])->label(false) ?>
                                            </div>
                                        </div>
                                        <div class=" row basket_count">
                                            <div class="col-lg-4">
                                                <label class="form-label">Basket Count</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'hopper_count', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Hopper Count', 'id' => 'edascampaign-basket_count','value' => ($model->hopper_count ? $model->hopper_count : 0)])->label(false) ?>
                                            </div>
                                        </div>
                                        <div class="row abandoned_percent">
                                            <div class="col-lg-4">
                                                <label class="form-label">Abandoned Percent</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <?= $form->field($model, 'abandoned_percent', ['template' => '{input}{label}{error}{hint}'])->textInput(['placeholder' => 'Abandoned Percent', 'id' => 'edascampaign-abandoned_percent','value' => ($model->abandoned_percent ? $model->abandoned_percent : 5)])->label(false) ?>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Max callback days </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" id="edascampaign-max_callback_days" class="form-control" placeholder="Max callback days" required="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 form-group text-center btnGroup mb-0">
                                    <button type="reset" class="btn btn-outline-primary previous">Previous</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                    <button type="button" id="add_dailer" class="btn btn-outline-primary form_loader next add_dailer">Next</button>
                                    <button type="button" id="add_dailer" class="btn btn-outline-primary form_loader saveCurrentForm add_dailer">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Dialer Tab - End -->
                <!-- Recording Manager Tab - Start -->
                <!-- <div class="tab-pane" id="tabs-recording" role="tabpanel" aria-labelledby="tabs-recording-tab">
                    <div id="searchRecordingForm">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Recording Manager</h4>
                            <div class="float-right">
                                <a class="btn btn-sm btn-outline-primary backCampaignBtn" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Direction</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <select class="form-control" name="Direction" id="edascampaign-direction" required="">
                                                <option value="">Select Direction</option>
                                                <option value="All">All</option>
                                                <option value="Inbound">Inbound</option>
                                                <option value="Outbound">Outbound</option>
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Campaign/Queue </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <select id="edascampaign-campaign_queue" class="form-control"
                                                name="EdasCampaign[call_medium]" required="">
                                                <option value="">Select...</option>
                                                <option value="1">DemoCamp</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Start Date</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input value="2022-06-22" id="edascampaign-start_date" class="form-control" type="date" required="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">End Date</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input value="2022-06-22" id="edascampaign-end_date" class="form-control" type="date" required="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Agent ID </label>
                                            </div>
                                            <div class="col-lg-8">
                                                <select name="Agent" id="edascampaign-agent_id" class="form-control">
                                                <option value="All">All </option>
                                                <option value="105080"> 105080 - Shyam Maurya </option>
                                                <option value="111836"> 111836 - Jestin Joshi </option>
                                                <option value="6748"> 6748 - Wilson Nadar </option>
                                                <option value="100377"> 100377 - Vibin Vincent </option>
                                                <option value="67488"> 67488 - Wilson Nadar </option>
                                                <option value="150003"> 150003 - POC DM 3 </option>
                                                <option value="150010"> 150010 - POC DM SU 2 </option>
                                                <option value="150013"> 150013 - POC DM </option>
                                                <option value="150025"> 150025 - DEMO Email </option>
                                                <option value="150026"> 150026 - 150026 </option>
                                                <option value="150027"> 150027 - Demo Email 2 </option>
                                                <option value="Ed001"> Ed001 - Kalpana C </option>
                                                <option value="3013"> 3013 - Vaibhav </option>
                                                <option value="120001"> 120001 - Test Agent </option>
                                                <option value="User1"> User1 - User1 </option>
                                                <option value="3013user"> 3013user - vaibhav user </option>
                                                <option value="30133013"> 30133013 - vaibhav 3013 </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group row">
                                            <div class="col-lg-4">
                                                <label class="form-label">Phone Number</label>
                                            </div>
                                            <div class="col-lg-8">
                                                <input class="form-control" id="edascampaign-phone_number" type="text" required="" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center mb-4">
                                    <button type="button" id="recordingSubmitBtn" class="btn btn-outline-info">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="recordingList" style="display: none;">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Recording Manager</h4>
                            <div class="float-right">
                                <a class="btn btn-sm btn-outline-primary" href="#" id="backToSearchRecording"><i class="fas fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <table class="table table-striped recordingManagerTable">
                            <thead>
                                <tr>
                                    <th>Recording Id</th>
                                    <th>Start Time</th>
                                    <th>Sec</th>
                                    <th>Lead Id</th>
                                    <th>User Id</th>
                                    <th>Number</th>
                                    <th>Disposition</th>
                                    <th>Campaign Id</th>
                                    <th>Evaluation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>11441</td>
                                    <td>2022-06-02 10:52:06</td>
                                    <td>11</td>
                                    <td>387637</td>
                                    <td>150025 - DEMO Email</td>
                                    <td>9820289831</td>
                                    <td>Call not Disposed or Transfer</td>
                                    <td>DEMOCAMP</td>
                                    <td><a href="#" class="view-recording" data-toggle="modal" data-target="#recordingModal">Evaluate</a></td>
                                </tr>
                                <tr>
                                    <td>11441</td>
                                    <td>2022-06-02 10:52:06</td>
                                    <td>11</td>
                                    <td>387637</td>
                                    <td>150025 - DEMO Email</td>
                                    <td>9820289831</td>
                                    <td>Call not Disposed or Transfer</td>
                                    <td>DEMOCAMP</td>
                                    <td><a href="#" class="view-recording" data-toggle="modal" data-target="#recordingModal">Evaluate</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 form-group text-center btnGroup mb-0">
                        <button type="reset" class="btn btn-outline-primary previous">Previous</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        <button type="button" id="" class="btn btn-outline-primary form_loader next">Next</button>
                        <button type="button" class="btn btn-outline-primary form_loader saveCurrentForm">Save</button>
                    </div>
                </div> -->
                <!-- Recording Manager Tab - End -->
                
                <!-- Knowledge Portal Tab - Start -->
                <div class="tab-pane" id="tabs-knowledge-portal" role="tabpanel" aria-labelledby="tabs-knowledge-portal-tab">
                    <div id="KnowledgePotal">
                        <div class="clearfix mb-3 top-heading">
                            <h4 class="m-0 float-left">Knowledge</h4>
                            <div class="float-right selectBox d-flex">
                                <div class="row">
                                    <div class="col-lg-7 p-0">
                                        <label class="form-label mt-1">Knowledge Template</label>
                                    </div>
                                    <div class="col-lg-5">
                                        <?= $form->field($model, 'kp_id', ['template' => '{input}{label}{error}{hint}'])->dropdownList($kptemplates, ['prompt' => 'Select.....', 'value' => isset($model->kp_id) ? $model->kp_id : null])->label(false) ?>
                                    </div>
                                </div>
                                <a class="btn btn-sm btn-outline-primary backCampaignBtn ml-2" href="index" title="Back To Campaign List"><i class="fas fa-chevron-left"></i></a>
                            </div>
                            
                        </div>
                        <!-- here -->
                        <div id="CampaignpreviewTabs"></div>
                        <!-- <div class="row" id="CampaignpreviewTabs">
                            
                        </div> -->
                        
                        <!-- <table class="table table-striped table-bordered dataTable" width="100%" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Tab Name</th>
                                    <th>Created By</th>
                                    <th>File Name</th>
                                    <th>Created Date</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Test A</td>
                                    <td>Shrikant</td>
                                    <td>call_register_report (9)</td>
                                    <td>2022-04-20 16:03:22</td>
                                    <td>
                                        <a href="#" data-confirm="Are you sure you want to delete this User?" data-method="post"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Test B</td><td>Shrikant</td>
                                    <td>crm_history_report_20220420160350_PANANC</td>
                                    <td>2022-04-20 16:04:31</td>
                                    <td><a href="#" data-confirm="Are you sure you want to delete this User?" data-method="post"><span class="fa fa-trash"></span></a></td>
                                </tr>
                                <tr>
                                    <td>Test A</td>
                                    <td>Shrikant</td>
                                    <td>agentcallmaster</td>
                                    <td>2022-04-20 19:06:20</td>
                                    <td>
                                        <a href="#" data-confirm="Are you sure you want to delete this User?" data-method="post"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>
                    <div class="col-lg-12 form-group text-center btnGroup mb-0 pt-3">
                        <button type="reset" class="btn btn-outline-primary previous">Previous</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        <button type="button" id="finalsubmit" class="btn btn-outline-primary form_loader finalsubmit">submit</button>
                    </div>
                </div>
                <!-- Lead Upload Tab - End -->
            </div>
        </div>
    </div>
</div>

<!-- DISPOSITION MODAL -->
<div id="dispo_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p class="">Set Dispositions Configurations</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="dispo_details">

                </div>
            </div>
        </div>
    </div>
</div>

<!-- dispostion update modal  -->
<div id="dispo_update_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header text-center">
                <p class="">Set Dispositions Configurations</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="dispo_update_details">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- New UI for Tabs and it's related contents - End - Krunal -->
<!-- To view "Campaign Run Time" right side popup - Start - Krunal -->
<div class="offcanvas offcanvas-end" id="offcanvasRight">
    <div class="offcanvas-header">
      <h5 id="offcanvasRightLabel">Campaign Run Time </h5>
      <a class="close" href="javascript:void(0)" id="closeOffCanvasBtn"><i class="fas fa-times"></i></a>
    </div>
    <div class="offcanvas-body">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Days</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody id="callruntimes">
                
            </tbody>
        </table>
    </div>
</div>
<!-- To view "Campaign Run Time" right side popup - End - Krunal -->

<!-- Hold Music Audio - Start - Krunal -->
<div class="modal fade" id="preview_audio" tabindex="-1" aria-labelledby="preview_audioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preview_audioLabel">Listen Hold Music</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="player">
                    <!-- Progress Bar -->
                    <div id="track">
                        <div id="progress"></div>
                    </div>
                    <!-- Controls -->
                    <div id="controls">
                        <div class="icon" id="play"></div>
                        <div class="icon" id="pause"></div>
                        <div class="icon" id="stop"></div>
                        <div class="icon" id="mute"></div>
                        <div id="volume">
                            <div id="level"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
        </div>
    </div>
</div>
<!-- Hold Music Audio - End - Krunal -->

<div id="queueMusicModal" class="modal fade" role="dialog">
</div>

<!-- Recording Modal - Start - Krunal -->
<div class="modal fade" id="recordingModal" tabindex="-1" aria-labelledby="recordingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="addUserModalLabel">Assign User</h5> -->
                <!-- <audio controls>
                    <source src="horse.ogg" type="audio/ogg">
                </audio> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="tabs-tab" role="tablist">
                    <li class="nav-item">
                    <a class="nav-link active" id="tabs-quality-feedback-tab" data-toggle="pill" href="#tabs-quality-feedback"
                        role="tab" aria-controls="tabs-quality-feedback" aria-selected="true">Quality Feedback</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" id="tabs-opening-tab" data-toggle="pill" href="#tabs-opening" role="tab"
                        aria-controls="tabs-opening" aria-selected="false">Opening</a>
                    </li>
                </ul>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tabs-quality-feedback" role="tabpanel" aria-labelledby="tabs-quality-feedback-tab">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Agent Name</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                                <option>Select Agent Name</option>
                                                <option>Agent 01</option>
                                                <option>Agent 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Campaign</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                                <option>Select Campaign</option>
                                                <option>Campaign 01</option>
                                                <option>Campaign 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Language</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                                <option>Select Language</option>
                                                <option>Language 01</option>
                                                <option>Language 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Audit Type</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                                <option>Select Audit Type</option>
                                                <option>Audit Type 01</option>
                                                <option>Audit Type 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Location</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                                <option>Select Location</option>
                                                <option>Location 01</option>
                                                <option>Location 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Call Duration</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" placeholder="00:11">
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Call Date</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="date" class="form-control" placeholder="00:11">
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Week</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Week</option>
                                            <option>Week 01</option>
                                            <option>Week 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Month</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Month</option>
                                            <option>Month 01</option>
                                            <option>Month 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Call Id</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Analysis Finding</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Analysis Finding</option>
                                            <option>Analysis Finding 01</option>
                                            <option>Analysis Finding 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Agent Type</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Agent Type</option>
                                            <option>Agent Type 01</option>
                                            <option>Agent Type 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Unique Id</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">PIP Status</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select PIP Status</option>
                                            <option>PIP Status 01</option>
                                            <option>PIP Status 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="
                                            -label">Disposition</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Disposition</option>
                                            <option>Disposition 01</option>
                                            <option>Disposition 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Sub Disposition</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Sub Disposition</option>
                                            <option>Sub Disposition 01</option>
                                            <option>Sub Disposition 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Categorization</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Categorization</option>
                                            <option>Categorization 01</option>
                                            <option>Categorization 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-4">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Action Status</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <select class="form-control">
                                            <option>Select Action Status</option>
                                            <option>Action Status 01</option>
                                            <option>Action Status 02</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <div class="col-lg-2">
                                            <label class="form-label">Gist of Case</label>
                                        </div>
                                        <div class="col-lg-10">
                                            <textarea class="form-control" name="" id="" cols="30" rows="2" placeholder="Gist of Case"></textarea>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <div class="col-lg-2">
                                            <label class="form-label">Analysis Finding</label>
                                        </div>
                                        <div class="col-lg-10">
                                            <textarea class="form-control" name="" id="" cols="30" rows="2" placeholder="Analysis Finding"></textarea>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <div class="col-lg-2">
                                            <label class="form-label">Area Of Improvement</label>
                                        </div>
                                        <div class="col-lg-10">
                                            <textarea class="form-control" name="" id="" cols="30" rows="2" placeholder="Area Of Improvement"></textarea>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-12">
                                    <div class="form-group row">
                                        <div class="col-lg-2">
                                            <label class="form-label">Reason For Error Call</label>
                                        </div>
                                        <div class="col-lg-10">
                                            <textarea class="form-control" name="" id="" cols="30" rows="2" placeholder="Reason For Error Call"></textarea>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Quality Scrore</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Out of</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Final Score</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Percentage (%)</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabs-opening" role="tabpanel" aria-labelledby="tabs-opening-tab">
                            <table id="" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>Parameter</th>
                                        <th>Sub-Parameter</th>
                                        <th>Marking</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Opening <input type="hidden" name="parameter[]" value="Opening">
                                        </td>
                                        <td>Standard Opening<input type="hidden" name="subparameter[]" value="Standard Opening">
                                        </td>
                                        <td>
                                            <select name="Rating[]" class="form-control required_field marking Non_Fatal" id="Rating_0_Opening"
                                            required="" placeholder="Marking">
                                            <option value="">--Select--</option>
                                            <option value="0">0</option>
                                            <option value="3">3</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="remark[]" class="form-control" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Opening <input type="hidden" name="parameter[]" value="Opening"></td>
                                        <td>Purpose of the call<input type="hidden" name="subparameter[]" value="Purpose of the call"></td>
                                        <td>
                                            <select name="Rating[]" class="form-control required_field marking Non_Fatal" id="Rating_1_Opening" required="" placeholder="Marking">
                                            <option value="">--Select--</option>
                                            <option value="0"> 0</option>
                                            <option value="3"> 3</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="remark[]" class="form-control" value="">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row mt-4">
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Quality Scrore</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Out of</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Final Score</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="col-lg-3">
                                    <div class="form-group row">
                                        <div class="col-lg-4">
                                            <label class="form-label">Percentage (%)</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="form-control" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Recording Modal - End - Krunal -->

<!-- Assign User Modal - Start - Krunal -->
 <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Assign User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="form-label">Select Skill</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control" id="options" required> </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label class="form-label" for="userWeight">User Weight</label>
                            </div>
                            <div class="col-lg-8">
                                <span class="slider slider-blue">
                                    <input class="slider slider-blue" value="5" min="5" max="20" step="1" name="007" type="range" id="userWeight" oninput="updateSlider(this)"/>
                                    <span class="slider-container slider-blue">
                                        <span class="bar">
                                            <span style="width: 0%;"></span>
                                            <div class="dot" id="1" style="left:50%;"></div>
                                        </span>
                                        <span class="bar-btn"><span>Default</span>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="filter-by-fields mb-3">
                    <div class="row">
                        <div class="col-lg-2 pr-0">
                            <label class="form-label" for="selectColumnType">Search By column</label>
                        </div>
                        <div class="col-lg-4">
                            <select class="form-control" id="selectColumnType">
                            <option>Select Column Type</option>
                            <option value="Agent ID">Agent ID</option>
                            <option value="Agent Name">Agent Name</option>
                            <option value="Manager">Manager</option>
                            <option value="Supervisor">Supervisor</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="search" name="" id="seachByColumnInput" placeholder="Search" class="form-control" disabled>
                        </div>
                    </div>
                </div>

                <table class="table table-striped" id="userTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Agent ID</th>
                            <th>Agent Name</th>
                            <th>Manager</th>
                            <th>Supervisor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="fetchUsers">
                        <?php  foreach ($users as $key => $value) {
                            ?> 
                        <tr>
                                    <td><input type="checkbox" class="chkbox" id="user_id<?=$value['user_id']?>" name="" value=""></td>
                                    <td id="<?=$value['user_id']?>" ><?= (isset($value['user_id']) ? $value['user_id']: '-') ?></td>
                                    <td id="<?=$value['user_name']?>"><?= (isset($value['user_name']) ? $value['user_name']: '-') ?></td>
                                    <td id="<?=$value['supervisor_id']?>"><?= (isset($value['supervisor_id']) ? $value['supervisor_id']: '-') ?></td>
                                    <td id="<?=$value['manager_id']?>"><?= (isset($value['manager_id']) ? $value['manager_id']: '-') ?></td>
                                    <td>
                                        <a href="javascript:void(0)" class="text-danger">
                                            <span class="fa fa-trash"></span>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="assignUsers">Assign</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?> 
<!-- Assign User Modal - End - Krunal -->

<?php

    $this->registerCss("
    // .offcanvas {visibility: hidden;} 
    ");
    // ****** Js added by Krunal -- start ****** //
    // Recording tab
    $this->registerJs("
        $('#recordingSubmitBtn').click(function(){
            $('#searchRecordingForm').hide();
            $('#recordingList').show();
        });
        $('.recordingManagerTable').DataTable( {} );
    ");
    // Pie Chart
    // $this->registerJs("
    //     $('#dispositionTable').DataTable( {} );
    //     setInterval(function(){ 
    //     $(function () {
    //         // $('.select2').select2();
    //             //code goes here that will be run every 5 seconds.    
            
    //             var stored = JSON.parse(localStorage.getItem('assign_user'));
    //             let result = [];
    //             if(stored){
    //             	result = stored.map(a => a.selected_skill);
    //             }
    //             const count = {};
    //             result.forEach(element => {
    //             count[element] = (count[element] || 0) + 1;
    //             });
            
    //         var values = Object.values(count);
    //         var keys = Object.keys(count);
    //         var donutData = {
    //           labels: keys,
    //           datasets: [
    //             {
    //                 data: values,
    //                 backgroundColor: ['#cd84f1', '#18dcff', '#ff4d4d', '#ffaf40', '#fffa65', '#32ff7e'],
    //             }
    //           ]
    //         }
    //         //-------------
    //         //- PIE CHART -
    //         //-------------
    //         // Get context with jQuery - using jQuery's .get() method.
    //         var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    //         var pieData = donutData;
    //         var pieOptions = {
    //             maintainAspectRatio: false,
    //             responsive: true,
    //         }
    //         //Create pie or douhnut chart
    //         // You can switch between pie and douhnut using the method below.
    //         new Chart(pieChartCanvas, {
    //             type: 'pie',
    //             data: pieData,
    //             options: pieOptions
    //         });
    //     });
    //     }, 5000);
    // ");

    // Audio Player for Hold music
    $this->registerJs("
        $('.upload_hold_music').on('click', function() {
            var campaign_id = $(this).data('campaign_id');
            if(campaign_id){
                $.ajax({
                    method: 'GET',
                    url: '". $urlManager->baseUrl . "/index.php/campaign/get-queues-music" ."',
                    data: { campaign_id : campaign_id }
                }).done(function (data) {
                    $('#queueMusicModal').html(data);
                    $('#queueMusicModal').modal('toggle');

                    // on change of upload music file
                    /* $('#vaanicampaignqueue-hold_music_file').on('change', function(e) {
                        console.log($(this));
                        if(e.target.files[0]){
                            var filename = e.target.files[0].name;
                            $(this).parent().find('.music_name').html(`<span class='music-text'>` + filename + `</span> <span class=''><i class='fa fa-upload'></i></span>`);
                            $(this).parent().find('.music_label .btn-default').hide();
                        }else{
                            $(this).parent().find('.music_name').html(``);
                            $(this).parent().find('.music_label .btn-default').show();
                        }
                    }); */
                });
            }
        });
        
    ");
    // ****** Js added by Krunal -- End ****** //
?>

<?php
    $campaign_names = User::encrypt_data($model->campaign_id);

    // display loader on delete
    $this->registerJs("
        $('.delete_campaign').on('click', function(){
            var content = $(this).data('message');
            if(confirm(content)){
                $('#LoadingBox').show();
            }else{
                return false;
            }
        });
    ");

    // ****** Js for tabs and related script added by Krunal ****** //
    // Tabs
    $this->registerJs("
        
        //***** Click of ADD Campiagn Button showing tabs *****/
        $('#addCampaignBtn').click(function () {
            $('#campaignList').hide();
            $('#tabsSection').show();
        });
        
        //***** ADD Campiagn Button *****/
        $('#edascampaign-select_audio').change(function () {
            var text = $(this).find(':selected').text();
            // alert(text);
            var btn = $(this).parent().siblings().children('button').attr('data-target', '#preview_audio');
            $(btn).attr('disabled', false);
        });
        $('.backCampaignBtn').click(function () {
            // $('#campaignList').show();
            // $('#tabsSection').hide();
            window.location.href = 'index';
        });
        
        $('#audioDropDown').click(function (ev) {
            $('.dropdown-content').toggle();
            ev.stopPropagation();
            $(this).siblings().children().children().find('h');
        });
        
    ");
    // Audio Player for Hold music
    $this->registerJs("
        //Elements:
        var source = $('#audiotrack')[0],
        track = $('#track'),
        progress = $('#progress'),
        play = $('#play'),
        pause = $('#pause'),
        playPause = $('#play, #pause'),
        stop = $('#stop'),
        mute = $('#mute'),
        volume = $('#volume'),
        level = $('#level');

        //Vars:
        var totalTime,
        timeBar,
        newTime,
        volumeBar,
        newVolume,
        cursorX;

        var interval = null;

        $(document).ready(function () {
            //Track:

            //Progress bar
            function barState() {
                if (!source.ended) {
                    var totalTime = parseInt(source.currentTime / source.duration * 100);
                    progress.css({ 'width': totalTime + 1 + '%' });
                }
                else if (source.ended) {
                    play.show();
                    pause.hide();
                    clearInterval(interval);
                };
                console.log('playing...');
            };

            //Event trigger:
            track.click(function (e) {
                if (!source.paused) {
                    var timeBar = track.width();
                    var cursorX = e.pageX - track.offset().left;
                    var newTime = cursorX * source.duration / timeBar;
                    source.currentTime = newTime;
                    progress.css({ 'width': cursorX + '%' });
                };
            });

            //Button (Play-Pause)
            $('#pause').hide();
            function playPause() {
                if (source.paused) {
                    source.play();
                    pause.show();
                    play.hide();
                    interval = setInterval(barState, 50); //Active progress bar.
                    console.log('play');
                }
                else {
                    source.pause();
                    play.show();
                    pause.hide();
                    clearInterval(interval);
                    console.log('pause');
                };
            };

            // playPause.click(function () {
            //     playPause();
            // });
            function stop() {
                source.pause();
                source.currentTime = 0;
                progress.css({ 'width': '0%' });
                play.show();
                pause.hide();
                clearInterval(interval);
            };

            // stop.click(function () {
            //     stop();
            // });

            function mute() {
                if (source.muted) {
                source.muted = false;
                mute.removeClass('mute');
                console.log('soundOFF');
                }
                else {
                source.muted = true;
                mute.addClass('mute');
                console.log('soundON');
                };
            };

            // mute.click(function () {
            //     mute();
            // });
            
            //Volume bar
            volume.click(function (e) {
                var volumeBar = volume.width();
                var cursorX = e.pageX - volume.offset().left;
                var newVolume = cursorX / volumeBar;
                source.volume = newVolume;
                level.css({ 'width': cursorX + 'px' });
                source.muted = false;
                mute.removeClass('mute');
            });
        }); //Document ready end.
    ");
 
?>
<script>
    // var users = <?php //print_r($users);?>
    // console.log(users);
    //  add campaign add data to localstoreage 
// Queue tab$("#fetchLeftToRightAll").click(function () {
    
        $("#fetchLeftToRightAll").click(function () {
            var text = $('#unassignedAgentTable').find('tbody tr').prepend();
            $('#assignedAgentTable tbody').prepend(text);
            var last_td = '<a href=\"javascript:void(0)\" class=\"moveAssignAgent\" title=\"Unassign\" aria-label=\"Unassign\" data-pjax=\"0\"><i class=\"fas fa-chevron-left\"></i> Unassign</a>';
            $( '#assignedAgentTable tbody tr td:last-child' ).html(last_td);
            $('#unassignedAgentTable').find('tbody tr').remove();
            var tablebody = $('#unassignedAgentTable tbody');
            if (tablebody.children().length == 0) {
                // tablebody.html(\"<tr id='noData'><td colspan='7' class='text-center'>No Data</td></tr>\");
                $('#fetchLeftToRightAll').attr('disabled', true);
                $('#fetchRightToLeftAll').attr('disabled', false);
            }
            $('#assignedAgentTable #noData').remove();
        });
        
        $("#fetchRightToLeftAll").click(function () {
            var text = $('#assignedAgentTable').find('tbody tr').prepend();
            $('#unassignedAgentTable tbody').prepend(text);
            var last_td = '<a href=\"javascript:void(0)\" class=\"moveUnassignAgent\" title=\"Assign\" aria-label=\"Assign\" data-pjax=\"0\">Assign <i class=\"fas fa-chevron-right\"></i></a>';
            $( '#unassignedAgentTable tbody tr td:last-child' ).html(last_td);
            $('#assignedAgentTable').find('tbody tr').remove();
            var tablebody = $('#assignedAgentTable tbody');
            if (tablebody.children().length == 0) {
            // tablebody.html(\"<tr id='noData'><td colspan='7' class='text-center'>No Data</td></tr>\");
            }
            $('#unassignedAgentTable #noData').remove();
        
            $('#fetchLeftToRightAll').attr('disabled', false);
            $('#fetchRightToLeftAll').attr('disabled', true);
        });
        // Unassigned Agent Table<td hidden=\"hidden\"></td>
        $('#unassignedAgentTable').on('click', '.moveUnassignAgent', function () {
            var currentRow = $(this).closest('tr');
            var col1 = currentRow.find('td:eq(0)').html();
            var col2 = currentRow.find('td:eq(1)').html(); // get current row 1st table cell TD value
            var col3 = currentRow.find('td:eq(2)').html(); // get current row 2nd table cell TD value
            var col4 = currentRow.find('td:eq(3)').html(); // get current row 3rd table cell TD value
            var col5 = currentRow.find('td:eq(4)').html(); // get current row 4th table cell TD value
            var col6 = currentRow.find('td:eq(5)').html(); // get current row 5th table cell TD value
            $('#assignedAgentTable').prepend('<tr><td hidden=\"hidden\">' + col1 + '</td><td class=\"assinedUserlist\">' + col2 + '</td><td>' + col3 + '</td><td>' + col4 + '</td><td>' + col5 + '</td><td>' + col6 + '</td><td><a href=\"javascript:void(0)\" class=\"moveAssignAgent\" title=\"Unassign\" aria-label=\"Unassign\" data-pjax=\"0\"><i class=\"fas fa-chevron-left\"></i> Unassign</a></td></tr>');
            currentRow.remove();
            $('#noData').remove();
            var tablebody = $('#unassignedAgentTable tbody');
            if (tablebody.children().length == 0) {
                // tablebody.html(\"<tr id='noData'><td colspan='6' class='text-center'>No Data</td></tr>\");
                $('#fetchLeftToRightAll').attr('disabled', true);
                $('#fetchRightToLeftAll').attr('disabled', false);
            }
            $('#fetchRightToLeftAll').attr('disabled', false);
        });
        // Assigned Agent Table
        $('#assignedAgentTable').on('click', '.moveAssignAgent', function () {
            var currentRow = $(this).closest('tr');
            var col1 = currentRow.find('td:eq(0)').html(); 
            var col2 = currentRow.find('td:eq(1)').html(); // get current row 1st table cell TD value
            var col3 = currentRow.find('td:eq(2)').html(); // get current row 2nd table cell TD value
            var col4 = currentRow.find('td:eq(3)').html(); // get current row 3rd table cell TD value
            var col5 = currentRow.find('td:eq(4)').html(); // get current row 4th table cell TD value
            var col6 = currentRow.find('td:eq(5)').html(); // get current row 5th table cell TD value
            $('#unassignedAgentTable').prepend('<tr><td hidden=\"hidden\">' + col1 + '</td><td>' + col2 + '</td><td>' + col3 + '</td><td>' + col4 + '</td><td>' + col5 + '</td><td>' + col6+ '</td><td><a href=\"javascript:void(0)\" class=\"moveUnassignAgent\" title=\"Assign\" aria-label=\"Assign\" data-pjax=\"0\">Assign <i class=\"fas fa-chevron-right\"></i></a></td></tr>');
            currentRow.remove();
            $('#noData').remove();
            var tablebody = $('#assignedAgentTable tbody');
            if (tablebody.children().length == 0) {
                // tablebody.html(\"<tr id='noData'><td colspan='6' class='text-center'>No Data</td></tr>\");
                $('#fetchLeftToRightAll').attr('disabled', false);
                $('#fetchRightToLeftAll').attr('disabled', true);
            }
            $('#fetchLeftToRightAll').attr('disabled', false);
        });
        // $('.dataTable').DataTable( {} );
//- PIE CHART -
//    setInterval(function(){ 
        $(function () {
            // $('.select2').select2();
                //code goes here that will be run every 5 seconds.    
            
                var stored = JSON.parse(localStorage.getItem('assign_user'));
                let result = [];
                if(stored){
                	result = stored.map(a => a.selected_skill);
                }
                const count = {};
                result.forEach(element => {
                count[element] = (count[element] || 0) + 1;
                });
            
            var values = Object.values(count);
            var keys = Object.keys(count);
            var donutData = {
              labels:keys ,
              datasets: [
                {
                    data: values,
                    backgroundColor: ['#cd84f1', '#18dcff', '#ff4d4d', '#ffaf40', '#fffa65', '#32ff7e'],
                }
              ]
            }
            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = donutData;
            var pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            });
        });
    // }, 5000);
    function pieChart(){
        var stored = JSON.parse(localStorage.getItem('assign_user'));
                let result = [];
                if(stored){
                	result = stored.map(a => a.selected_skill);
                }
                const count = {};
                result.forEach(element => {
                count[element] = (count[element] || 0) + 1;
                });
            
            var values = Object.values(count);
            var keys = Object.keys(count);
            var donutData = {
              labels:keys ,
              datasets: [
                {
                    data: values,
                    backgroundColor: ['#cd84f1', '#18dcff', '#ff4d4d', '#ffaf40', '#fffa65', '#32ff7e'],
                }
              ]
            }
            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = donutData;
            var pieOptions = {
                maintainAspectRatio: false,
                responsive: true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            new Chart(pieChartCanvas, {
                type: 'pie',
                data: pieData,
                options: pieOptions
            });
    }
    
    function AddCampaign(){
        // var campaign_data = [];
        var x = document.getElementById("edascampaign-logofile");
        console.log(x.files);
        // var client_id = $('#edascampaign-client_id').val();
        var campaign_name = $('#edascampaign-campaign_name').val();
        var campaign_type = $('#edascampaign-campaign_type').val();
        var campaign_logofile = $('#edascampaign-logofile').val();//import music
        // var preview = $('#edascampaign-preview').val();
        // var time_preview = $('#edascampaign-time_preview').val();
        var call_medium = $('#edascampaign-call_medium').val();
        var crm_name = $('#edascampaign-crm_name').val();
        var select_audio = $('#edascampaign-select_audio').val();//hold music
        var audioList = $('#edascampaign-audioList').val(); //welcome prompt
        var cli_number = $('#edascampaign-cli_number').val();
        // var outbound_criteria = $('#edascampaign-outbound_criteria').val();
        var call_window = $('#selected_call_window').val();
        var campaign_call_timeout = $('#edascampaign-call_timeout').val();
        var campaign_operators = $('#edascampaign-campaign_operators').val();
        var qms_id = $('#edascampaign-qms_id').val();
        var campaign_stat = $('#edascampaign-campaign_status').attr('aria-pressed');
        if(campaign_stat=='true'){
            var campaign_status = '1'
        }else{
            var campaign_status = '2'
        }
        

        const campaigns = {
            // client_id: client_id,
            campaign_name: campaign_name,
            campaign_type: campaign_type,
            campaign_logofile: campaign_logofile,
            qms_id:qms_id,
            // preview: preview,
            // time_preview: time_preview,
            call_medium:call_medium,
            crm_name: crm_name,
            select_audio: select_audio,
            audioList : audioList,
            cli_number: cli_number,
            campaign_call_timeout:campaign_call_timeout,
            campaign_operators:campaign_operators,
            // outbound_criteria:outbound_criteria,
            campaign_status: campaign_status,
            call_window : call_window,
        }
        // campaign_data.push(campaigns);
        window.localStorage.setItem('campaign_data',JSON.stringify(campaigns));
    }
    // add skill
    function Addskill(){
        var stored = JSON.parse(localStorage.getItem("campaign_data"));
        var model_id = "<?= $model->id ?>";
        var add_skill_array1 = stored.queues;
        // console.log(add_skill_array1);
        if(add_skill_array1==null){
             var add_skill_array=[];
        }else{
           var add_skill_array = stored.queues; 
        }
        // handle when click on update '#aioConceptName option:selected'
        if (model_id) {
            var add_skill = $('#addskillshowhide option:selected').text();
            // var id = $('#addskillshowhide').val();
            // alert(add_skill);
        }else{
            var add_skill = $('#edascampaign-add_skill').val();
        }
        // var id = ; 
        var sliderUserWeight = $('#edascampaign-sliderUserWeight').val();
        if(sliderUserWeight==13){
            sliderUserWeight = 10;
        }
        var call_window = $('#queue_call_window').val();
        var queue_modal_id = $('#queue_modal_id').val();
        var call_timeout = $('#call_timeout').val();
        var dni = $('#queue_dni_id').val();
        var criteria = $('#vaanicampaignqueue-criteria').val();   
        
            const sk = {
                id : queue_modal_id,
                add_skill: add_skill,
                sliderUserWeight: sliderUserWeight,
                call_window : call_window,
                call_timeout : call_timeout,
                dni : dni,
                criteria : criteria,
            };
            add_skill_array.push(sk);
            const skills = 
                {
                    queues : add_skill_array, 
                };
        
        // stored.push(skills);
                var storedskill = $.extend( stored, skills );
        
        // stored.push(campaigns2);
                window.localStorage.setItem('campaign_data',JSON.stringify(storedskill));
                
    }
    //  kp tabs
    function addKp(){
        var stored = JSON.parse(localStorage.getItem("campaign_data"));
        var edascampaign_kp_id = $('#edascampaign-kp_id').val();
        const kp = {
            edascampaign_kp_id: edascampaign_kp_id,
        };
        var storedkp = $.extend( stored, kp );
        
        window.localStorage.setItem('campaign_data',JSON.stringify(storedkp));
    }
    // add agents
    function AddAgents(){
        var stored = JSON.parse(localStorage.getItem("campaign_data"));
        var inbound_campaign = $('#edascampaign-campain_list').val();
        var manual_call = $('#edascampaign-manual_call').attr('aria-pressed');
        var call_hangup = $('#edascampaign-call_hangup').attr('aria-pressed');
        var conference = $('#edascampaign-conference').attr('aria-pressed');
        var blind_transfer = $('#edascampaign-blind_transfer').attr('aria-pressed');
        var consultation = $('#edascampaign-consultation').attr('aria-pressed');
        var warm_transfer_to_inb = $('#edascampaign-warm_transfer_to_inb').val();
        var require_disposition = $('#edascampaign-require_disposition').val();
        var disposition = $('#edascampaign-disposition').val();
        var call_button = $('#edascampaign-call_button').attr('aria-pressed');
        // 
        var manual_call = (manual_call=='true') ?  manual_call = '1' : manual_call = '2'; 
        var call_hangup = (call_hangup=='true') ?  call_hangup = '1' : call_hangup = '2'; 
        var conference = (conference=='true') ?  conference = '1' : conference = '2'; 
        var blind_transfer = (blind_transfer=='true') ?  blind_transfer = '1' : blind_transfer = '2'; 
        var consultation = (consultation=='true') ?  consultation = '1' : consultation = '2'; 
        var call_button = (call_button=='true') ?  call_button = '1' : call_button = '2'; 
        

        const agents = 
            {
                inbound_campaign:inbound_campaign,
                manual_call: manual_call,
                call_hangup: call_hangup,
                conference: conference,
                blind_transfer: blind_transfer,
                consultation: consultation,
                warm_transfer_to_inb: warm_transfer_to_inb,
                require_disposition: require_disposition,
                disposition: disposition,
                call_button: call_button,
            };
            // stored.push(agents);
        // console.log(agents);
        var storedagents = $.extend( stored, agents );
        // console.log(storedagents);
        
        window.localStorage.setItem('campaign_data',JSON.stringify(storedagents));
    }
    // add dailer
    function Adddailer(){
        var stored = JSON.parse(localStorage.getItem("campaign_data"));

        var campaign_sl_time = $('#edascampaign-campaign_sl_time').val();//inbound
        var dial_mode = $('#edascampaign-campaign_sub_type').val();
        var campaign_sl_percent = $('#edascampaign-campaign_sl_percent').val();//inbound
        var preview_autodial = $('#edascampaign-preview_autodial').val();
        var call_answer_delay = $('#edascampaign-call_answer_delay').val();//inbound
        var pacing_ratio = $('#edascampaign-pacing_ratio').val();
        var max_callback_days = $('#edascampaign-max_callback_days').val();
        var basket_count = $('#edascampaign-basket_count').val();
        var abandoned_percent = $('#edascampaign-abandoned_percent').val();
        var preview = $('#edascampaign-preview').val();
        var time_preview = $('#edascampaign-time_preview').val();
        var outbound_criteria = $('#edascampaign-outbound_criteria').val();

        const dailers = {
            campaign_sl_time: campaign_sl_time,
            campaign_sl_percent: campaign_sl_percent,
            call_answer_delay: call_answer_delay,
            preview_autodial: preview_autodial,
            pacing_ratio: pacing_ratio,
            campaign_sub_type: dial_mode,
            abandoned_percent: abandoned_percent,
            basket_count: basket_count,
            max_callback_days: max_callback_days,
            preview:preview,
            time_preview:time_preview,
            outbound_criteria:outbound_criteria,
        };

        // stored.push(dailers);
        var storeddailers = $.extend( stored, dailers );
        
        window.localStorage.setItem('campaign_data',JSON.stringify(storeddailers));

    }
    // add recordings
    // function Addrecordings(){
    //     var stored = JSON.parse(localStorage.getItem("campaign_data"));

    //     var direction = $('#edascampaign-direction').val();
    //     var campaign_queue = $('#edascampaign-campaign_queue').val();
    //     var start_date = $('#edascampaign-start_date').val();
    //     var end_date = $('#edascampaign-end_date').val();
    //     var agent_id = $('#edascampaign-agent_id').val();
    //     var phone_number = $('#edascampaign-phone_number').val();

    //     const recordings = {
    //         direction: direction,
    //         campaign_queue: campaign_queue,
    //         start_date: start_date,
    //         end_date: end_date,
    //         agent_id: agent_id,
    //         phone_number: phone_number,
    //     };

    //     // stored.push(recordings);
    //     var storedrecordings = $.extend( stored, recordings );
        
    //     window.localStorage.setItem('campaign_data',JSON.stringify(storedrecordings));

    // }
    // validation for skills 
    $('#assignUsers').hide(); 
    $("#options").change(function(){
        var skillss =  $("#options").val();
        // var check = $(":checkbox");
        if(skillss){
            $('#assignUsers').show();
        }else{
            $('#assignUsers').hide();
        }
    });
    // assign users to localstorage 
    function AssignUsers(){
        var stored = JSON.parse(localStorage.getItem("assign_user"));
        var assign_user_array1 = stored;
        var selected_skill = $('#options').val();
        if (!$('#options').val()) {
            alert("select skills");
            return
        }
        var user_weight =  $('#userWeight').val();
        // alert(selected_skill);
        // alert(user_weight);
        if(assign_user_array1==null){
             var assign_user_array=[];
        }else{
           var assign_user_array = stored; 
        }
        var table = $("#userTable :input");
        $("#userTable :input").each(function(){
            var input = $(this).closest('tr').children('td').eq(1).attr("id");
                const cb = document.querySelector('#user_id'+input);
                if(cb.checked){
                    var agent_id = $(this).closest('tr').children('td').eq(1).attr("id");
                    var agent_name = $(this).closest('tr').children('td').eq(2).attr("id");
                    var agent_supervisor = $(this).closest('tr').children('td').eq(3).attr("id");
                    var agent_manager = $(this).closest('tr').children('td').eq(4).attr("id");
                    var Status = '2';
                    const assign_users= {
                        agent_id:agent_id,
                        agent_name:agent_name,
                        agent_supervisor:agent_supervisor,
                        agent_manager:agent_manager,
                        selected_skill:selected_skill,
                        user_weight:user_weight,
                        Status:Status,
                    }; 
                    assign_user_array.push(assign_users);
                    window.localStorage.setItem('assign_user',JSON.stringify(assign_user_array));
                    
                }
             
        });
        
    }
    // display assigned Users
    function displayAssinedUser(){
        var stored = JSON.parse(localStorage.getItem("assign_user"));
        
        let result = [];
        if(stored){
        	result = stored.map(a => a);
        }
        var tablerows ;
        if(result.length > 0){
            result.forEach(o => {
                
                tablerows += '<tr>';
                    tablerows += '<td>'+o.agent_id +'</td>';
                    tablerows += '<td>'+ o.agent_name +'</td>';
                    tablerows += '<td>'+ o.selected_skill +'</td>';
                    tablerows += '<td>'+ o.user_weight +'</td>';
                    tablerows += '<td>'+ o.agent_supervisor +'</td>';
                    tablerows += '<td>'+ o.agent_manager +'</td>';
                    tablerows += '<td><a href=\"javascript:void(0)\" class=\"moveUnassignAgent\">Unassigned</a> | <a href=\"javascript:void(0)\" class=\"text-danger\"><span class=\"fa fa-trash\"></span></a></td>';
                    tablerows += '</tr>';
                    
                });
                var table = $('#DataTables_Table_2').DataTable();
                $('#displayAssignedUser').html(tablerows);  
              
               
        }
    }
    //display Queue UnAssigned Agent list.
    function displayunassinedUser(){
        var stored = JSON.parse(localStorage.getItem("assign_user"));
        let result = [];
        if(stored){
        	result = stored.map(a => a);
        }
        var tablerows ;
        if(result.length > 0){
            result.forEach(o => {
                tablerows += '<tr>';
                tablerows += '<td hidden=\"hidden\"></td>'
                tablerows += '<td class=\"unassinedUserlist\">'+o.agent_id +'</td>';
                tablerows += '<td>'+ o.agent_name +'</td>';
                tablerows += '<td>'+ o.selected_skill +'</td>';
                tablerows += '<td>'+ o.agent_manager +'</td>';
                tablerows += '<td>'+ o.agent_supervisor +'</td>';
                tablerows += '<td><a href=\"javascript:void(0)\" class=\"moveUnassignAgent\">Assign <i class=\"fas fa-chevron-right\"></i></a></td>';
                tablerows += '</tr>';
            });
            $('#unassignedd').append(tablerows);  
               
        }
    }
    //dispostion tabsss 
    function dispositionTabData(){
        var add_disposition_array=[];
        $("[id='despoName']") .each(function(){
                    // alert($(this).html());
                    var disposition_id = $(this).next().next().next().next().next().next().children().data('id');
                    var disposition_name = $(this).html();
                    var short_code = $(this).next().html();
                    var disposition_type = $(this).next().next().html();
                    var disposition_result = $(this).next().next().next().html();
                    var max_retry_count = $(this).next().next().next().next().html();
                    var retry_delay = $(this).next().next().next().next().next().html();
                    var disposition_plan = $('#edascampaign-disposition_plan_id').val();
                
                    // storing to local storage start
                    var stored = JSON.parse(localStorage.getItem("campaign_data"));
                    
                
                    const dispo_config = {
                        disposition_id:disposition_id,
                        disposition_type:disposition_type,
                        disposition_name: disposition_name,
                        short_code: short_code,
                        disposition_result:disposition_result,
                        max_retry_count : max_retry_count,
                        retry_delay : retry_delay,
                        
                    };
                    add_disposition_array.push(dispo_config);
                    const disposition_config = {
                            set_dispositions_configurations : add_disposition_array,
                            disposition_plan : disposition_plan, 
                    };
                
                var storeddisposition = $.extend( stored, disposition_config );
                window.localStorage.setItem('campaign_data',JSON.stringify(storeddisposition));
            });
    } 


    // genral form
    $(".generalnext").click(function() {
        AddCampaign();
        $("#tabs-tab #tabs-general-tab").removeClass('active');
        $("#tabs-tabContent #tabs-general").removeClass('active show');
        
        $("#tabs-tab #tabs-skill-tab").addClass('active');
        $("#tabs-tabContent #tabs-skill").addClass('active show');
       
        // alert("campaign data added to localstorage successfully");
    });
    // skill data 
    $("#add_skill").click(function() {
        var model_id = "<?= $model->id ?>";
        // alert(model_id);
        if (model_id) {
            var add_skill = $('#addskillshowhide').val().length;
            // alert(add_skill);

        }else{
            var add_skill = $('#edascampaign-add_skill').val().length;
        }
        var sliderUserWeight = $('#edascampaign-sliderUserWeight').val().length;
        var call_window = $('#queue_call_window').val().length;
        var call_timeout = $('#call_timeout').val().length;
        var dni = $('#queue_dni_id').val().length;
        var criteria = $('#vaanicampaignqueue-criteria').val().length; 
        // console.log(add_skill);console.log(sliderUserWeight);console.log(call_window);console.log(call_timeout);console.log(dni);console.log(criteria);  
        if (add_skill===0) {
            alert("Add Skill field are required In queue !");
            return false;
        }else if(sliderUserWeight===0){
            alert("User Weight field are required In queue !");
            return false;
        }else if(call_window===0){
            alert("Call Window field are required In queue !");
            return false;
        }else if(call_timeout===0){
            alert("Call Timeout field are required In queue !");
            return false;
        }else if(dni===0){
            alert("dni field are required In queue !");
            return false;
        }else if(criteria===0){
            alert("Criteria field are required In queue !");
            return false;
        }else{ 
            alert("skill data added to localstorage successfully");
            Addskill();
            $('#addskillshowhide').val('');
            $('#edascampaign-add_skill').val('');
            $('#edascampaign-sliderUserWeight').val('');
            $('#queue_call_window').val('');
            $('#call_timeout').val('');
            $('#queue_dni_id').val('');
            $('#vaanicampaignqueue-criteria').val('');
        }
    });
    // dispositionTab
    
    $(".dispositionTab").click(function() {
        dispositionTabData();
    });
    // agent data
    $(".add_queues").click(function() {
        $("#tabs-tab #tabs-queues-tab").removeClass('active');
        $("#tabs-tab #tabs-agent-tab").addClass('active');
        $("#tabs-tabContent #tabs-queues").removeClass('active show');
        $("#tabs-tabContent #tabs-agent").addClass('active show');
        // alert("Agents data added to localstorage successfully");
        var stored = JSON.parse(localStorage.getItem("campaign_data"));
        var add_skill_array=[];
        var add_skill_array_assigned=[];
        // var add_skill_array1 = stored.unassigned_agent_list;
        // if(add_skill_array1==null){
        //      var add_skill_array=[];
        // }else{
        //    var add_skill_array = stored.assigned_agent_list; 
        // }
        // var add_skill_array2 = stored.assigned_agent_list;
        // if(add_skill_array2==null){
        //      var add_skill_array_assigned=[];
        // }else{
        //    var add_skill_array_assigned = stored.assigned_agent_list; 
        // }
        if ($('#assinedUserlist tr').find('#noData')) {
            // if no data present then no vaue should go to local storage 
        }else{
             $('#assinedUserlist tr').each(function(){
                // alert(this.value);
                // var test = $(this).children().next().next().next().next().next().next().html();
                // if(test.length !== 0){
                   //alert('123');
                   var assigned_agent_queue_id = $(this).children().html();
                var assigned_agent_id = $(this).children().next().html();
                var assigned_agent_name = $(this).children().next().next().html();
                var assigned_agent_skill_name = $(this).children().next().next().next().html();
                var assigned_agent_manager = $(this).children().next().next().next().next().html();
                var assigned_agent_supervisor = $(this).children().next().next().next().next().next().html();
                var assigned_agent_active_status = 1;
                
                // }
                const assigned_agent = {
                    assigned_agent_queue_id : assigned_agent_queue_id,
                    assigned_agent_id: assigned_agent_id,
                    assigned_agent_name: assigned_agent_name,
                    assigned_agent_skill_name : assigned_agent_skill_name,
                    assigned_agent_manager : assigned_agent_manager,
                    assigned_agent_supervisor : assigned_agent_supervisor,
                    assigned_agent_active_status : assigned_agent_active_status,
                };
                add_skill_array_assigned.push(assigned_agent);
                const AAgent = 
                    {
                        assigned_agent_list : add_skill_array_assigned, 
                    };
                // console.log(AAgent);
                var storedAAgent = $.extend( stored, AAgent );
                window.localStorage.setItem('campaign_data',JSON.stringify(storedAAgent));
            });
        }
        $('#unassignedd tr').each(function(){
                // alert(this.value);
                var unassigned_agent_queue_id = $(this).children().html();
                var unassigned_agent_id = $(this).children().next().html();
                var unassigned_agent_name = $(this).children().next().next().html();
                var unassigned_agent_skill_name = $(this).children().next().next().next().html();
                var unassigned_agent_manager = $(this).children().next().next().next().next().html();
                var unassigned_agent_supervisor = $(this).children().next().next().next().next().next().html();
                var unassigned_agent_active_status = 2;
                const unassigned_agent = {
                    unassigned_agent_queue_id : unassigned_agent_queue_id,
                    unassigned_agent_id: unassigned_agent_id,
                    unassigned_agent_name: unassigned_agent_name,
                    unassigned_agent_skill_name : unassigned_agent_skill_name,
                    unassigned_agent_manager : unassigned_agent_manager,
                    unassigned_agent_supervisor : unassigned_agent_supervisor,
                    unassigned_agent_active_status : unassigned_agent_active_status,
                };
                add_skill_array.push(unassigned_agent);
                const UAgent = 
                    {
                        unassigned_agent_list : add_skill_array, 
                    };
                var storedUAgent = $.extend( stored, UAgent );
                window.localStorage.setItem('campaign_data',JSON.stringify(storedUAgent));
        });
       

    });
    // add_dailer
    $(".add_dailer").click(function() {
        Adddailer();
        $("#tabs-tab #tabs-dialer-tab").removeClass('active');
        $("#tabs-tab #tabs-recording-tab").addClass('active');
        $("#tabs-tabContent #tabs-dialer").removeClass('active show');
        $("#tabs-tabContent #tabs-recording").addClass('active show');
        // alert("dailer data added to localstorage successfully");
    });
    // add_recordings
    $("#recordingSubmitBtn").click(function() {
        Addrecordings();
        // alert("recordings data added to localstorage successfully");
    });
    // add_leadupload
    $("#leadupload").click(function() {
        Addleadupload();
        // alert("lead Upload data added to localstorage successfully");
    });
    // reset all form
    $("#reset").click(function() {
        localStorage.clear();
        // localStorage.clear(); 
        // alert("Handler for .click() called.");
    });
    // fetching datat from local storage 
    $("#finalsubmit").click(function() {
        // get kp_id from  campaign form
        addKp();
        const campaign_data = localStorage.getItem('campaign_data');
        $('#LoadingBox').show();
        var model_id = "<?= $model->id ?>";
        // console.log(model_id);
        if(model_id) {
            // var call_access = <?php// echo $model->callAccess->id ?>;
            var queues = <?php  echo json_encode($queues_ids) ?>;
            var storedfinaldata = campaign_data;
            var action = '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/local-storage-update-data';
            // alert(action);
        }else{
            var storedfinaldata = campaign_data;
            var action = '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/local-storage-data';
            // alert(action);
        }
        $.ajax({
            url: action,
            type: 'post',
            data: {campaign_data : storedfinaldata,model_id:model_id, queues : queues},
            success: function(response){
                // alert(response);
                OnSuccess();
                audioupload();
                soundupload();
                console.log(response);
                $('#LoadingBox').hide();
                alert("data send to controller successfully");
                window.localStorage.removeItem('campaign_data');
                window.location.href = "index";
            }
        });
    });
// file upload ajax
function OnSuccess(){
        var fd = new FormData();
                var files = $('#edascampaign-logofile')[0].files[0];
                fd.append('file', files);
                $.ajax({
                    url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/hold-music',
                    type: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        alert('file uploaded');
                    },
                });
    }


    //file upload audio music
    function audioupload(){
        var ad = new FormData();
                var files = $('#edascampaign-logofile')[0].files[0];
                ad.append('file', files);
                $.ajax({
                    url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/audio-music',
                    type: 'post',
                    data: ad,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        alert('file uploaded');
                    },
                });
    }

    function soundupload(){
        var sd = new FormData();
                var files = $('#edascampaign-logofile')[0].files[0];
                sd.append('file', files);
                $.ajax({
                    url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/sound-music',
                    type: 'post',
                    data: sd,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        alert('file uploaded');
                    },
                });
    }


    // generalnext
    $(".tab_skill").click(function() {
        // alert("hello");
        // var general_tab = $('#tabs-general-tab').attr()
        $("#tabs-tab #tabs-skill-tab").removeClass('active');
        $("#tabs-tab #tabs-queues-tab").addClass('active');
        
        $("#tabs-tabContent #tabs-skill").removeClass('active show');
        $("#tabs-tabContent #tabs-queues").addClass('active show');
        
    });

    $("#recordingtab").click(function() {
        $("#tabs-tab #tabs-recording-tab").removeClass('active');
        $("#tabs-tab #tabs-disposition-tab").addClass('active');
        $("#tabs-tabContent #tabs-recording").removeClass('active show');
        $("#tabs-tabContent #tabs-disposition").addClass('active show');
    });
    const merge = (first, second) => {
        for(let i=0; i<second.length; i++) {
            first.push(second[i]);
        }
        return first;
    }
    // assign user
    $("#addusermodal").click(function() {
        // $("#options").prop('required',true);
        let result;
        var stored = JSON.parse(localStorage.getItem("campaign_data"));
        var dbskill = <?php echo json_encode($add_skills); ?>;
        var dbskillarr = Object.keys(dbskill).map(key => dbskill[key])
        if ( stored.queues != null && stored.length != 0) {
              result = stored.queues.map(a => a.add_skill);
                merge( result, dbskillarr );
            // console.log(stored.queues);
            
            }else{
                 result =  dbskillarr;
            }
        // merge( result, dbskillarr );
       
      
        // console.log(result);
        // if (result) {
            if(result.length > 0){
                var optionList = '<option value=\"\">Select...</option>';
                result.forEach(o => {
                    optionList += '<option value=\"'+o+'\">'+ o +'</option>';
                });
                $('#options').html(optionList);   
            }else if(result.length <= 0){
                var optionList = '<option value=\"\">Select...</option>';
                $('#options').html(optionList);
            }
        // }
    });
    // assign users assignUsers
    $("#assignUsers").click(function() {
        AssignUsers();
        displayAssinedUser();
        displayunassinedUser();
        pieChart();
        // alert("assign users");
    });
    // inbound hide fields 
    
    $("#edascampaign-campaign_type").change(function(){
        var campaign_type =  $("#edascampaign-campaign_type").val();
        
        if(campaign_type == 2){
            $('#progressbar li:nth-child(2)').show();
            $('#progressbar li:nth-child(3)').show();
            $(".generalnext").removeClass("skip-next-next");
            $(".generalnext").addClass("next");
            $("#dispositionPrevious").addClass("previous");
            $("#dispositionPrevious").removeClass("skip-previous-previous");
            $('#edascampaign-audioList').attr('disabled', false);
            // alert(campaign_type);
            $("#CampaignSubType").hide();
            $("#Preview").hide();
            $("#TimePreview").hide();
            $("#outbound_campaign").hide();
            $("#inbound_campaign").parent().addClass('justify-content-center');
            $("#inbound_campaign").show();
            $("#outbound_criteria").hide();
            $("#edascampaign-call_medium").attr('disabled', false);
            $("#edascampaign-campaign_sub_type").prop('required',false);
            
        }else if(campaign_type == 3){
            $('#progressbar li:nth-child(2)').hide();
            $('#progressbar li:nth-child(3)').hide();
            $(".generalnext").addClass("skip-next-next");
            $(".generalnext").removeClass("next");
            $("#dispositionPrevious").removeClass("previous");
            $("#dispositionPrevious").addClass("skip-previous-previous");
            $('#edascampaign-audioList').attr('disabled', true);
            $("#outbound_campaign").show();
            $("#inbound_campaign").hide();
            $("#outbound_campaign").parent().addClass('justify-content-center');
            $("#edascampaign-call_medium").attr('disabled', true);
            $("#CampaignSubType").show();
            $("#Preview").show();
            $("#TimePreview").show();
            $("#outbound_criteria").show();
            $("#edascampaign-campaign_sub_type").prop('required',true);
        }else{
            $('#progressbar li:nth-child(2)').show();
            $('#progressbar li:nth-child(3)').show();
            $(".generalnext").removeClass("skip-next-next");
            $(".generalnext").addClass("next");
            $("#dispositionPrevious").addClass("previous");
            $("#dispositionPrevious").removeClass("skip-previous-previous");
            $('#edascampaign-audioList').attr('disabled', true);
            $("#CampaignSubType").show();
            $("#Preview").show();
            $("#TimePreview").show();
            $("#outbound_campaign").show();
            $("#inbound_campaign").show();
            $("#edascampaign-campaign_sub_type").prop('required',true);
            $("#edascampaign-call_medium").attr('disabled', true);
        }

    });
    //sub type outbond
    $("#edascampaign-campaign_sub_type").change(function(){
        var campaign_sub_type =  $("#edascampaign-campaign_sub_type").val();
        if(campaign_sub_type==2){
            $('#TimePreview').show();
            $('#outbound_criteria').hide();
            $(".pacing_ratio").hide();
            $(".abandoned_percent").hide();
            $(".basket_count").hide();
        }else if(campaign_sub_type==3){
            $('#TimePreview').hide();
            $('#outbound_criteria').show();
            $(".pacing_ratio").show();
            $(".basket_count").show(); 
            $(".abandoned_percent").hide();
        }else if(campaign_sub_type==4){
            $('#outbound_criteria').show();
            $(".pacing_ratio").show();
            $(".abandoned_percent").show();
            $(".basket_count").show();
        }else if(campaign_sub_type==1){
            $('#TimePreview').hide();
            $('#outbound_criteria').hide();
            $(".pacing_ratio").hide();
            $(".abandoned_percent").hide();
            $(".basket_count").hide();
        }else{
            $('#TimePreview').hide();
            $('#outbound_criteria').show();
            $(".pacing_ratio").hide();
            $(".abandoned_percent").hide();
            $(".basket_count").hide();
        }
    }); 

    // call run times view 
    $("#selected_call_window").change(function(){
        var config_id = $(this).val();
        if (config_id=='') {
            $('#viewMore').prop("disabled", true);   
        }else{
            $('#viewMore').prop("disabled", false);
        }
       $.ajax({
            url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/call-run-time',
            type: 'get',
            data: {config_id : config_id},
            success: function(response){
             
                var obj = jQuery.parseJSON( response);
                var tablerows;
                $.each(obj, function( index, value ) {
                    // console.log(value['id']);
                    tablerows += '<tr>';
                    tablerows += '<td>'+value['day'] +'</td>';
                    tablerows += '<td>'+ value['start_time'] +'</td>';
                    tablerows += '<td>'+ value['end_time'] +'</td>';
                    tablerows += '</tr>';
                });
                $('#callruntimes').html(tablerows); 
                 
            }
        });
    });
    // queue tab 
    $("#edascampaign-call_medium").change(function(){
        var campaign_type =  $("#edascampaign-call_medium").val();
        if(campaign_type==1){
            // alert('onchange');
            $('.add-skill-form').show();
            $('.field-addskillshowhide').hide();
            $('#edascampaign-add_skill').show();
        }else{
            // alert('onchange');
            $('.add-skill-form').hide();
            $('.field-addskillshowhide').show();
            $('#edascampaign-add_skill').hide();
        }
    });
    // on change of selected skill 
    $("#options").change(function(){
        var queue_ids =  $("#options").val();
        // var client_id = $('#edascampaign-client_id').val();
        var client_id = '<?php echo $model->client_id ?>';
        // alert(campaign_type);
        $.ajax({
            method: 'GET',
            url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/assign-users',
            data: {queue_ids: queue_ids,client_id:client_id}
        }).done(function(data){
            var stored = JSON.parse(data);
                let result = stored.map(a => a.user_id);
                const count = {};
                result.forEach(element => {
                count[element] = (count[element] || 0) + 1;
                });
                // array of user id on select of skill 
                var not_assigned_user_ids = Object.keys(count);
                var assigned_user_ids = [];
                var assigned_mapped_skills = [];
                var indexes = [];
                var assigned_user_details = JSON.parse(localStorage.getItem("assign_user"));//localstorage data 
                if(assigned_user_details){
                	let assigned_user_details_maped = assigned_user_details.map(a => a.selected_skill);
                
                    for (let index = 0; index < assigned_user_details_maped.length; index++) {
                        // console.log(assigned_user_details_maped[index]);
                        if (assigned_user_details_maped[index] === queue_ids) {
                            indexes.push(index);
                        }
                    }
                    // 
                    assigned_user_details.forEach(function (value, key) {
                  
                        indexes.forEach(indexe =>  {
                            if(key == indexe){
                                  assigned_mapped_skills.push(assigned_user_details[indexe]);
                            }
                        });
                    })
                }
                // index.push(assigned_user_details_maped.findIndex(selected_skill => selected_skill == queue_ids)); 
                
                // console.log(assigned_mapped_skills);
                let s = stored.map(s => $.trim(s.user_id));//db
                let assigned = assigned_mapped_skills.map(aa => $.trim(aa.agent_id));
               
                var tablerows;
                var ind=[];
                
                if($.isEmptyObject(assigned)) {
                        
                        $.each(s, function( index, value ) {
                            ind.push(index);   
                        });
                }else{
                    var difference = s.filter(x => assigned.indexOf(x) === -1);
                    if($.isEmptyObject(difference)) {
                        // console.log('empty')
                        $('#fetchUsers').empty();
                    }else{
                        //    console.log(difference);
                           if($.inArray(difference, s) == -1){
                             var ind=difference.map(v=>s.indexOf(v));
                            }
                    }
                }
                // if($.isArray(ind)){
                    // console.log(ind);
                    $.each(ind, function( index, value ) {
                        // console.log(stored[index] );
                        tablerows += '<tr>';
                        tablerows += '<td><input type=\"checkbox\" class=\"chkbox\" id=\"user_id'+stored[value].user_id+'\" name=\"\" value=\"\"></td>';
                        tablerows += '<td id=\"'+stored[value].user_id+'\">'+stored[value].user_id +'</td>';
                        tablerows += '<td id=\"'+stored[value].user_name+'\">'+ stored[value].user_name+'</td>';
                        tablerows += '<td id=\"'+stored[value].manager_id+'\">'+ stored[value].manager_id+'</td>';
                        tablerows += '<td id=\"'+stored[value].supervisor_id+'\">'+ stored[value].supervisor_id +'</td>';
                        tablerows += '<td><a href=\"javascript:void(0)\" class=\"text-danger\"><span class=\"fa fa-trash\"></span></a></td>';
                        tablerows += '</tr>';
                    });
                // }
                // else{
                //     tablerows += '<tr>';
                //         tablerows += '<td><input type=\"checkbox\" class=\"chkbox\" id=\"user_id'+stored[ind].user_id+'\" name=\"\" value=\"\"></td>';
                //         tablerows += '<td id=\"'+stored[ind].user_id+'\">'+stored[ind].user_id +'</td>';
                //         tablerows += '<td id=\"'+stored[ind].user_name+'\">'+ stored[ind].user_name+'</td>';
                //         tablerows += '<td id=\"'+stored[ind].manager_id+'\">'+ stored[ind].manager_id+'</td>';
                //         tablerows += '<td id=\"'+stored[ind].supervisor_id+'\">'+ stored[ind].supervisor_id +'</td>';
                //         tablerows += '<td><a href=\"javascript:void(0)\" class=\"text-danger\"><span class=\"fa fa-trash\"></span></a></td>';
                //         tablerows += '</tr>';
                // }
                $('#fetchUsers').html(tablerows);  
 
        });

    });
    // agent tab 
    $("#edascampaign-require_disposition").change(function(){
        var require_disposition =  $("#edascampaign-require_disposition").val();
        if(require_disposition==1){
            $("#edascampaign-disposition").prop('required',true);
            $("#edascampaign-disposition").prop('disabled',true);
            $('.wrap_warning_delay').show();
            $('.auto_wrap').show();
        }else if(require_disposition==3){
            $("#edascampaign-disposition").prop('required',false);
            $('.wrap_warning_delay').hide();
            $('.auto_wrap').hide();
            $("#edascampaign-disposition").prop('disabled',false);
        }else{
            $("#edascampaign-disposition").prop('disabled',false);
            $("#edascampaign-disposition").prop('required',false);
            $('.wrap_warning_delay').hide();
            $('.auto_wrap').hide();


        }
    });


    // // campaign dispositions
    var mrt_cut ;
    var rt_de;
    function fetchDispo(plan_id, campaign_id){
        $.ajax({
            method: 'GET',
            url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign/get-dispositions',
            data: {plan_id: plan_id, campaign_id : campaign_id}
        }).done(function(data){
            // console.log(data);
            $('.dispo_details').html(data);
            var tablerows;
            var options=[];
            // options+= '<option value="">Select...</option>';
            $("[id='vaanidispositions-disposition_name']") .each(function(){
                
                var disposition_type = $(this).parent().prev().prev().val();
                var disposition_id = $(this).parent().prev().val();
                var disposition_name = $(this).val();
                var short_code = $(this).parent().parent().next().children().children().attr('value');
                var max_retry_count = $(this).parent().parent().next().next().children().children().attr('value');
                var retry_delay = $(this).parent().parent().next().next().next().children().children().attr('value');
           
                if (disposition_type==1) {
                    var type = 'Success';
                }else if(disposition_type==2){
                    var type = 'Failed';
                }else if(disposition_type==3){
                    var type = 'Callback';
                }else if(disposition_type==4){
                    var type = 'DNC';
                }else{
                    var type = 'Other';
                }
                options[disposition_id] = $(this).val();
                
                
                tablerows += '<tr>';
                tablerows += '<td id=\"despoid\" style=\"display:none;\">'+ disposition_id +'</td>';
                tablerows += '<td id=\"despoName\">'+$(this).val() +'</td>';
                tablerows += '<td>'+short_code +'</td>';
                tablerows += '<td>'+ 'Automatic' +'</td>';
                tablerows += '<td>'+type +'</td>';
                tablerows += '<td>'+ max_retry_count +'</td>';
                tablerows += '<td>'+ retry_delay +'</td>';
                
                tablerows += '<td><a href=\"#\" class=\"text-primary updisposition\" data-id=\"'+disposition_id+'\" id=\"updisposition'+disposition_id+'\"><span class=\"fa fa-pen\"></span></a> | <a href=\"#\" class=\"text-danger\"><span class=\"fa fa-trash\"></span></a></td>';
                tablerows += '</tr>';
                $('#edascampaign-disposition').append(`<option value="${disposition_id}">
                                       ${disposition_name}
                                  </option>`);
                
            })
            $('#displosotion_data').html(tablerows); 
            $('.updisposition').on('click', function() {
                var dispo_id = $(this).data("id");
                var dispo_name = $(this).closest('td').prev('td').prev('td').prev('td').prev('td').prev('td').prev('td').html();
                var dispo_short_code = $(this).closest('td').prev('td').prev('td').prev('td').prev('td').prev('td').html();
                var dispo_type = $(this).closest('td').prev('td').prev('td').prev('td').prev('td').html();
                var dispo_result = $(this).closest('td').prev('td').prev('td').prev('td').html();
                var dispo_retry_count = $(this).closest('td').prev('td').prev('td').html();
                var dispo_retry_delay = $(this).closest('td').prev('td').html();
               
                var modal;
                modal += '<div class=\"col-lg-12 disposition-row\">';
                modal += '<hr class=\"row-break hidden\">';
                modal += '<div class=\"row justify-content-center align-items-center form-box\">';
                modal += '<div class=\"col-lg-3\">';
                modal += '<input type=\"hidden\" id=\"vaanicampdispositions-disposition_id\" name=\"VaaniCampDispositions[disposition_id][]\" value=\"'+dispo_id+'\" class=\"dispo_idss\">';
                modal += '<input type=\"hidden\" id=\"vaanicampdispositions-type\" name=\"VaaniCampDispositions[type][]\" value=\"'+dispo_type+'\" class=\"\">';
                modal += '<div class=\"form-group field-vaanidispositions-disposition_name\">';
                modal += '<input type=\"text\" id=\"vaanidispositions-disposition_name\" class=\"form-control\" name=\"VaaniDispositions[disposition_name][]\" value=\"'+dispo_name+'\" readonly placeholder=\"Disposition Name\">';
                modal += '<label class=\"form-label\" for=\"vaanidispositions-disposition_name\">Disposition Name</label>';
                modal += '<div class=\"help-block\"></div>';
                modal += '</div>';
                
                modal += '</div>';
                modal += '<div class=\"col-lg-3\">';
                modal += '<div class=\"form-group field-vaanidispositions-short_code\">';
                modal += '<input type=\"text\" id=\"vaanidispositions-short_code\" class=\"form-control\" name=\"VaaniDispositions[short_code][]\" value=\"'+dispo_short_code+'\" readonly placeholder=\"Short Code\">';
                modal += '<label class=\"form-label\" for=\"vaanidispositions-short_code\">Short Code\</label>';
                modal += '<div class=\"help-block\"></div>';
                modal += '</div>';
                modal += '</div>';
                modal += '<div class=\"col-lg-3\">';
                modal += '<div class=\"form-group field-vaanidispositions-max_retry_count\">';
                modal += '<input type=\"text\" id=\"vaanidispositions-max_retry_count\" class=\"form-control\" name=\"VaaniDispositions[max_retry_count][]\" value=\"'+dispo_retry_count+'\"  placeholder=\"Max Retry Count\">';
                modal += '<label class=\"form-label\" for=\"vaanidispositions-max_retry_count\">Max Retry Count</label>';
                modal += '<div class=\"help-block\"></div>';
                modal += '</div>';
                modal += '</div>';
                modal += '<div class=\"col-lg-3\">';
                modal += '<div class=\"form-group field-vaanidispositions-retry_delay1\">';
                modal += '<input type=\"text\" id=\"vaanidispositions-retry_delay1\" class=\"form-control\" name=\"VaaniDispositions[retry_delay][]\" value=\"'+dispo_retry_delay+'\"  placeholder=\"Retry Delay (secs)\">';
                modal += '<label class=\"form-label\" for=\"vaanidispositions-retry_delay1\">Retry Delay (Minutes)</label>';
                modal += '<div class=\"help-block\"></div>';
                modal += '</div>';
                modal += '</div>';
                modal += '</div>';
                modal += '<div class=\"justify-content-center updatedispositionss\" style=\"text-align: center;\" id=\"updatedispositionss\"><a class=\"btn btn-primary\" data-dismiss=\"modal\">Submit</a></div>';

                // send data to update model of disposotion
                $('.dispo_update_details').html(modal);
                //pop up modal for dispostion     
                $('#dispo_update_modal').modal('show');
                // $('#dispo_update_modal').change(function(){
                 
                    
                    
                    $('.updatedispositionss').click(function(){
                        mrt_cut = $('#vaanidispositions-max_retry_count').val();
                        rt_de = $('#vaanidispositions-retry_delay1').val();
                        disp = $('.dispo_idss').val();
                       
                         $('#updisposition'+disp).closest('td').prev('td').prev('td').html(mrt_cut);
                         $('#updisposition'+disp).closest('td').prev('td').html(rt_de);
                         
                       
                        
                    });
                   
              
            });
        });
    }
   
    // onchange fetch disposition plan 
    $('.disposition_plan').change(function(){

        var plan_id = $(this).val();
        var campaign_id = '<?php echo $campaign_names ?>';
        //alert(campaign_id);
        fetchDispo(plan_id, campaign_id);
        // $('#dispo_modal').modal('show');
        var $option = $(this).find('option:selected');
    //Added with the EDIT
        var text = $option.text();//to get <option>Text</option> content
        //alert(text);
    
    });

    
    $( document ).ready(function() {

            // var kp_template_id =  $("#edascampaign-kp_id").val();
            // alert(kp_template_id);
            // if (kp_template_id) {
            //     $.ajax({
            //         url: '<?php //echo $urlManager->baseUrl ?>/index.php/campaign/kp-preview',
            //         type: 'get',
            //         data: {id : kp_template_id},
            //         success: function(response){
            //             // console.log(response);
            //             // var obj = JSON.parse(response);
            //             $('#CampaignpreviewTabs').html(response);
            //        }
            //     });
            // }

            function loadkp(){
                var kp_template_id =  $("#edascampaign-kp_id").val();
                $.ajax({
                    url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign/kp-preview',
                    type: 'get',
                    data: {id : kp_template_id},
                    success: function(response){
                        $('#CampaignpreviewTabs').html(response);
                   
                    }
                });
            }
            loadkp();
            

            var plan_id = $('.disposition_plan').val();
            var campaign_id = '<?php echo $campaign_names ?>';
    
            fetchDispo(plan_id, campaign_id);
           
            // warm transfer selection 
            $('.campain_list').hide();
            $('#edascampaign-warm_transfer_to_inb').change( function(){
                var selected_option = $(this).val();
                if (selected_option == 1) {
                    $('.campain_list').show();
                }else{
                    $('.campain_list').hide();
                }
            });

            // disable campaign name on update 
            var camp_id = $('.camp_id').attr('value');
            if (camp_id) {
                $('#edascampaign-campaign_name').prop('disabled', true);
            }
            var cloneid = '<?= isset($cloneid); ?>'; 
            if(cloneid){
                $('#edascampaign-campaign_name').prop('disabled', false);
                $('#edascampaign-campaign_type').prop('disabled', true);
            }
            //tab changes 
            var model_id = '<?= isset($model->id); ?>'; 
            if (model_id) {
                $('.btnGroup button').hide();
                $('.saveCurrentForm, .finalsubmit').show();
                $('#LoadingBox').show();
                setTimeout(function() {
                    $('#LoadingBox').hide();
                }, 500);
                
                $('#tabsSection').removeClass('steps');
                $('#progressbar').hide();
                $('#tabs-tab').show();
                
            }else{
                $('#LoadingBox').show();
                setTimeout(function() {
                    $('#LoadingBox').hide();
                }, 500);
                $('#tabsSection').addClass('steps');
                $('#progressbar').show();
                $('#tabs-tab').hide();
                $('.btnGroup button').show();
                $('.saveCurrentForm').hide();
            }

            /* var text = $('#unassignedAgentTable').find('tbody tr').prepend();
            $('#assignedAgentTable tbody').prepend(text);
            var last_td = '<a href=\"javascript:void(0)\" class=\"moveAssignAgent\" title=\"Unassign\" aria-label=\"Unassign\" data-pjax=\"0\"><i class=\"fas fa-chevron-left\"></i> Unassign</a>';
            $( '#assignedAgentTable tbody tr td:last-child' ).html(last_td);
            $('#unassignedAgentTable').find('tbody tr').remove();
            var tablebody = $('#unassignedAgentTable tbody');
            if (tablebody.children().length == 0) {
                // tablebody.html(\"<tr id='noData'><td colspan='7' class='text-center'>No Data</td></tr>\");
                $('#fetchLeftToRightAll').attr('disabled', true);
                $('#fetchRightToLeftAll').attr('disabled', false);
            }
            $('#assignedAgentTable #noData').remove(); */
        
        
      
            /* var text = $('#assignedAgentTable').find('tbody tr').prepend();
            $('#unassignedAgentTable tbody').prepend(text);
            var last_td = '<a href=\"javascript:void(0)\" class=\"moveUnassignAgent\" title=\"Assign\" aria-label=\"Assign\" data-pjax=\"0\">Assign <i class=\"fas fa-chevron-right\"></i></a>';
            $( '#unassignedAgentTable tbody tr td:last-child' ).html(last_td);
            $('#assignedAgentTable').find('tbody tr').remove();
            var tablebody = $('#assignedAgentTable tbody');
            if (tablebody.children().length == 0) {
            // tablebody.html(\"<tr id='noData'><td colspan='7' class='text-center'>No Data</td></tr>\");
            }
            $('#unassignedAgentTable #noData').remove(); */
        
            $('#fetchLeftToRightAll').attr('disabled', false);
            $('#fetchRightToLeftAll').attr('disabled', true);
        
        // console.log( "ready!" );
        displayunassinedUser();
        displayAssinedUser();
        pieChart();
        $(".add_agents").click(function() {
            AddAgents();
            $("#tabs-tab #tabs-agent-tab").removeClass('active');
            $("#tabs-tab #tabs-dialer-tab").addClass('active');
            $("#tabs-tabContent #tabs-agent").removeClass('active show');
            $("#tabs-tabContent #tabs-dialer").addClass('active show');
            // alert("Agents data added to localstorage successfully");
        });
        // 
        $('#outbound_criteria').hide();
        $('.add-skill-form').hide();
        $('#TimePreview').hide();
        $(".pacing_ratio").hide();
        $(".abandoned_percent").hide();
        $(".basket_count").hide();
        var campaign_type =  $("#edascampaign-campaign_type").val();
            
            if(campaign_type == 2){
                $('#tabs-skill-tab, #tabs-queues-tab').parent().show();
                $('#progressbar li:nth-child(2)').show();
                $(".generalnext").removeClass("skip-next-next");
                $(".generalnext").addClass("next");
                $("#dispositionPrevious").addClass("previous");
                $("#dispositionPrevious").removeClass("skip-previous-previous");
                // alert(campaign_type);
                $('#edascampaign-audioList').attr('disabled', false);
                $("#CampaignSubType").hide();
                $("#Preview").hide();
                $("#TimePreview").hide();
                $("#outbound_campaign").hide();
                $("#inbound_campaign").parent().addClass('justify-content-center');
                $("#inbound_campaign").show();
                $("#outbound_criteria").hide();
                $("#edascampaign-call_medium").attr('disabled', false);
                $("#edascampaign-campaign_sub_type").prop('required',false);
                
            }else if(campaign_type == 3){
                $('#progressbar li:nth-child(2)').hide();
                $(".generalnext").addClass("skip-next-next");
                $(".generalnext").removeClass("next");
                $("#dispositionPrevious").removeClass("previous");
                $("#dispositionPrevious").addClass("skip-previous-previous");
                $('#edascampaign-audioList').attr('disabled', true);
                $("#outbound_campaign").show();
                $("#inbound_campaign").hide();
                $("#outbound_campaign").parent().addClass('justify-content-center');
                $("#edascampaign-call_medium").attr('disabled', true);
                $("#CampaignSubType").show();
                $("#Preview").show();
                $("#TimePreview").show();
                $("#outbound_criteria").show();
                $("#edascampaign-campaign_sub_type").prop('required',true);
            }else{
                $('#progressbar li:nth-child(2)').hide();
                $(".generalnext").addClass("skip-next-next");
                $(".generalnext").removeClass("next");
                $("#dispositionPrevious").removeClass("previous");
                $("#dispositionPrevious").addClass("skip-previous-previous");
                $('#edascampaign-audioList').attr('disabled',true);
                $("#CampaignSubType").show();
                $("#Preview").show();
                $("#TimePreview").show();
                $("#outbound_campaign").hide();
                $("#inbound_campaign").parent().addClass('justify-content-center');
                $("#inbound_campaign").show();
                $("#edascampaign-campaign_sub_type").prop('required',true);
                $("#edascampaign-call_medium").attr('disabled', true);
            }
        // call medium
        var campaign_type =  $("#edascampaign-call_medium").val();
            if(campaign_type==1){
                // alert('hello');
                $('.add-skill-form').show();
                $('.field-addskillshowhide').hide();
            }else{
                // alert('byee');
                $('.add-skill-form').hide();
                $('.field-addskillshowhide').show();
            }
        // sub type 
        var campaign_sub_type =  $("#edascampaign-campaign_sub_type").val();
            if(campaign_sub_type==2){
                $('#TimePreview').show();
                $('#outbound_criteria').hide();
                $(".pacing_ratio").hide();
                $(".abandoned_percent").hide();
                $(".basket_count").hide();
            }else if(campaign_sub_type==3){
                $('#TimePreview').hide();
                $('#outbound_criteria').show();
                $(".pacing_ratio").show();
                $(".basket_count").show();
            }else if(campaign_sub_type==4){
                $('#outbound_criteria').show();
                $(".pacing_ratio").show();
                $(".abandoned_percent").show();
                $(".basket_count").show();
            }
            // call window 
            var config_id = $('#selected_call_window').val();
            $.ajax({
                url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/call-run-time',
                type: 'get',
                data: {config_id : config_id},
                success: function(response){
                
                    var obj = jQuery.parseJSON( response);
                    var tablerows;
                    $.each(obj, function( index, value ) {
                        // console.log(value);
                        tablerows += '<tr>';
                        tablerows += '<td>'+value['day'] +'</td>';
                        tablerows += '<td>'+ value['start_time'] +'</td>';
                        tablerows += '<td>'+ value['end_time'] +'</td>';
                        tablerows += '</tr>';
                    });
                    $('#callruntimes').html(tablerows);  
                }
            });
            // on chANGR OF HOLD MUSIC 
            $('#edascampaign-select_audio').change( function(){
                var file = $(this).val();
                if(file!==''){
                    // alert('yaha');
                    $('#edascampaign-select_audio').next().children().children('button').prop('disabled',false);
                }else{
                    $('#edascampaign-select_audio').next().children().children('button').prop('disabled',true);
                }
            });
            // disposition
            var plan_id = $('.disposition_plan').val();
            var campaign_id = '<?php echo $campaign_names ?>';
            fetchDispo(plan_id, campaign_id);

            // change skill input field 
             var type_campaignss = '<?= isset($campaign_names) ? $campaign_names : null  ?>';
   
            //  console.log(type_campaignss);
            if(type_campaignss){
                // alert("here");
                $('#edascampaign-add_skill').hide();
                $('.field-addskillshowhide').show();
                $('#addskillshowhide').show();
            }else{
                $('#edascampaign-add_skill').show();
                // console.log($("span.select2").html());
                $('#addskillshowhide').hide();
                $('.field-addskillshowhide').hide();
                
            }
            
        // fetching value of skills on change
        $('#addskillshowhide').on('change', function() {
        var add_skill_id = $(this).val();
        $.ajax({
                url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign-config/get-queue-data',
                type: 'post',
                data: {queue_id : add_skill_id},
                success: function(response){
                    var obj = jQuery.parseJSON( response);
                    // console.log(obj);
                    $('#queue_modal_id').val(obj.id);
                    $('#queue_call_window').val(obj.call_window);
                    $('#call_timeout').val(obj.call_timeout);
                    // console.log(obj.dni_id);
                    $('#queue_dni_id').val(obj.dni_id);
                    // $('#queue_dni_id').addClass('hello');

                    $('#vaanicampaignqueue-criteria').val(obj.criteria);
                }
            });
        });
    });
</script>
<script>
    //jQuery time
    var current_fs, next_fs, previous_fs, skip_next_fs, skip_previous_fs; //fieldsets
    var left, opacity, scale; //fieldset properties which we will animate
    var animating; //flag to prevent quick multi-click glitches
    
    
    $(document).on("click", ".next", function () {
      if (animating) return false;
      animating = true;
      
      current_fs = $(this).parents(".tab-pane");
      next_fs = $(this).parents(".tab-pane").next();
      //activate next step on progressbar using the index of next_fs
      $("#progressbar li").removeClass("active");
      $("#progressbar li").eq($(".tab-pane").index(next_fs)).addClass("active");
      $("#progressbar li").eq($(".tab-pane").index(next_fs)).prev().addClass("finished");

      //show the next fieldset
      next_fs.delay(800).fadeIn(1000);
      //hide the current fieldset with style
      current_fs.animate({ opacity: 0 }, {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale current_fs down to 80%
          scale = 1 - (1 - now) * 0.2;
          //2. bring next_fs from the right(50%)
          left = (now * 50) + "%";
          //3. increase opacity of next_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            'transform': 'scale(' + scale + ')',
            // 'position': 'absolute'
          });
          next_fs.css({ 'left': left, 'opacity': opacity });
        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    });

    $(document).on("click", '.skip-next', function () {
      if (animating) return false;
      animating = true;
      
      current_fs = $(this).parents(".tab-pane");
      skip_next_fs = $(this).parents(".tab-pane").next().next();
      //activate next step on progressbar using the index of next_fs
      $("#progressbar li").removeClass("active");
      $("#progressbar li").eq($(".tab-pane").index(skip_next_fs)).addClass("active");
      $("#progressbar li").eq($(".tab-pane").index(skip_next_fs)).prev().prev().addClass("finished");

      //show the next fieldset
      skip_next_fs.delay(800).fadeIn(1000);
      //hide the current fieldset with style
      current_fs.animate({ opacity: 0 }, {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale current_fs down to 80%
          scale = 1 - (1 - now) * 0.2;
          //2. bring skip_next_fs from the right(50%)
          left = (now * 50) + "%";
          //3. increase opacity of skip_next_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            'transform': 'scale(' + scale + ')',
            // 'position': 'absolute'
          });
          skip_next_fs.css({ 'left': left, 'opacity': opacity });
        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    });
    // queuse tabs hide next 
    $(document).on("click", '.skip-next-next', function () {
      if (animating) return false;
      animating = true;
      
      current_fs = $(this).parents(".tab-pane");
      skip_next_fs = $(this).parents(".tab-pane").next().next().next();
      //activate next step on progressbar using the index of next_fs
      $("#progressbar li").removeClass("active");
      $("#progressbar li").eq($(".tab-pane").index(skip_next_fs)).addClass("active");
      $("#progressbar li").eq($(".tab-pane").index(skip_next_fs)).prev().prev().prev().addClass("finished");

      //show the next fieldset
      skip_next_fs.delay(800).fadeIn(1000);
      //hide the current fieldset with style
      current_fs.animate({ opacity: 0 }, {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale current_fs down to 80%
          scale = 1 - (1 - now) * 0.2;
          //2. bring skip_next_fs from the right(50%)
          left = (now * 50) + "%";
          //3. increase opacity of skip_next_fs to 1 as it moves in
          opacity = 1 - now;
          current_fs.css({
            'transform': 'scale(' + scale + ')',
            // 'position': 'absolute'
          });
          skip_next_fs.css({ 'left': left, 'opacity': opacity });
        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    });
    // previous 
    $(document).on('click', ".previous", function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parents(".tab-pane");
        previous_fs = $(this).parents(".tab-pane").prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($(".tab-pane").index(current_fs)).removeClass("active");
        $("#progressbar li").eq($(".tab-pane").index(previous_fs)).addClass("active");
        $("#progressbar li").eq($(".tab-pane").index(previous_fs)).removeClass("finished");

      //show the previous fieldset
      previous_fs.delay(800).fadeIn(1000);
      //hide the current fieldset with style
      current_fs.animate({ opacity: 0 }, {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale previous_fs from 80% to 100%
          scale = 0.8 + (1 - now) * 0.2;
          //2. take current_fs to the right(50%) - from 0%
          // left = ((1 - now) * 50) + "%";
          //3. increase opacity of previous_fs to 1 as it moves in
          opacity = 1 - now;
          // current_fs.css({ 'left': left });
          previous_fs.css({ 'transform': 'scale(' + scale + ')', 'opacity': opacity });
        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    });

    $(document).on('click', ".skip-previous", function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parents(".tab-pane");
        skip_previous_fs = $(this).parents(".tab-pane").prev().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($(".tab-pane").index(current_fs)).removeClass("active");
        $("#progressbar li").eq($(".tab-pane").index(skip_previous_fs)).addClass("active");
        $("#progressbar li").eq($(".tab-pane").index(skip_previous_fs)).removeClass("finished");

      //show the previous fieldset
      skip_previous_fs.delay(800).fadeIn(1000);
      //hide the current fieldset with style
      current_fs.animate({ opacity: 0 }, {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale skip_previous_fs from 80% to 100%
          scale = 0.8 + (1 - now) * 0.2;
          //2. take current_fs to the right(50%) - from 0%
          // left = ((1 - now) * 50) + "%";
          //3. increase opacity of skip_previous_fs to 1 as it moves in
          opacity = 1 - now;
          // current_fs.css({ 'left': left });
          skip_previous_fs.css({ 'transform': 'scale(' + scale + ')', 'opacity': opacity });
        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    });
    // skip privous privous
    $(document).on('click', ".skip-previous-previous", function () {
        if (animating) return false;
        animating = true;

        current_fs = $(this).parents(".tab-pane");
        skip_previous_fs = $(this).parents(".tab-pane").prev().prev().prev();

        //de-activate current step on progressbar
        $("#progressbar li").eq($(".tab-pane").index(current_fs)).removeClass("active");
        $("#progressbar li").eq($(".tab-pane").index(skip_previous_fs)).addClass("active");
        $("#progressbar li").eq($(".tab-pane").index(skip_previous_fs)).removeClass("finished");

      //show the previous fieldset
      skip_previous_fs.delay(800).fadeIn(1000);
      //hide the current fieldset with style
      current_fs.animate({ opacity: 0 }, {
        step: function (now, mx) {
          //as the opacity of current_fs reduces to 0 - stored in "now"
          //1. scale skip_previous_fs from 80% to 100%
          scale = 0.8 + (1 - now) * 0.2;
          //2. take current_fs to the right(50%) - from 0%
          // left = ((1 - now) * 50) + "%";
          //3. increase opacity of skip_previous_fs to 1 as it moves in
          opacity = 1 - now;
          // current_fs.css({ 'left': left });
          skip_previous_fs.css({ 'transform': 'scale(' + scale + ')', 'opacity': opacity });
        },
        duration: 800,
        complete: function () {
          current_fs.hide();
          animating = false;
        },
        //this comes from the custom easing plugin
        easing: 'easeInOutBack'
      });
    });

    $(".submit").click(function () {
      return false;
    });


    // Campaign Run Time's pop up 
    var text = '<div class=\"modal-backdrop\"></div>';
        $('#viewMore').click(function () {
            setTimeout(function(){
                $('.offcanvas').addClass('show');
                }, 200);
                $('.offcanvas').addClass('showing').delay(1000).queue(function(next){
                $(this).removeClass('showing');
                next();
            });
            $('body').append(text);
        });
        function close() {
            $('.offcanvas').removeClass('show');
            $('.modal-backdrop').remove();
            $('.offcanvas').addClass('showing').delay(1000).queue(function(next){
                $(this).removeClass('showing');
                next();
            });
        }
        $(document).on('click', '.modal-backdrop', function () {
            close();
        });
        $('#closeOffCanvasBtn').click(function () {
            close();
        });

        // Upload Audio File
        $("#uploadAudioFile").on('change', function(){
            var text = $(this).find("#edascampaign-logofile").val().split('\\').pop();
            // console.log(text);
            $('.text-preview').text(text);
        });

        $("#uploadAudiomusicFile").on('change', function(){
            var text = $(this).find("#edascampaign-musicfile").val().split('\\').pop();
            // console.log(text);
            $('.text-preview-audio').text(text);
        });

        $("#uploadsoundmusicFile").on('change', function(){
            var text = $(this).find("#edascampaign-soundfile").val().split('\\').pop();
            // console.log(text);
            $('.text-preview-sound').text(text);
        });
        // alert before page leave 
        // $(window).on('beforeunload', function(){
        //     var c=confirm();
        //     if(c){
        //         
        //         return true;
        //     }else{
        //         return false;
        //     }
        // });
        window.addEventListener('beforeunload', (event) => {
            event.returnValue = 'Are you sure you want to leave?';
            // localStorage.clear();

        });
        //  on change fetch and show knowledge portal tabs 
        $("#edascampaign-kp_id").change(function(){
            var kp_template_id =  $(this).val();
            $.ajax({
                url: '<?php echo $urlManager->baseUrl ?>/index.php/campaign/kp-preview',
                type: 'get',
                data: {id : kp_template_id},
                success: function(response){
                    // console.log(response);
                    // var obj = JSON.parse(response);
                    $('#CampaignpreviewTabs').html(response);
                   
                }
            });
        });
        //  on load  fetch and show knowledge portal tabs
        // $(document).ready(function(){
        //     var kp_template_id =  $("#edascampaign-kp_id").val();
        //     console.log(kp_template_id);
        //     $.ajax({
        //         url: '<?php //echo $urlManager->baseUrl ?>/index.php/campaign/kp-preview',
        //         type: 'post',
        //         data: {id : kp_template_id},
        //         success: function(response){
        //             // console.log(response);
        //             // var obj = JSON.parse(response);
        //             $('#CampaignpreviewTabs').html(response);
                   
        //         }
        //     });
        // });


        // $(".generalnext").click(function(){
        //     var attr = $(this).attr('required');
        //     // For some browsers, `attr` is undefined; for others,
        //     // `attr` is false.  Check for both.
        //     if (typeof attr !== 'undefined' && attr !== false) {
        //         alert('please fill the blank field')
        //     }
        // });

            // let input = document.querySelector("#edascampaign-campaign_name");

        // let camp_name = document.querySelector("#edascampaign-campaign_name");
        // let campaign_type = document.querySelector("#edascampaign-campaign_type");
        // let qms_id = document.querySelector("#edascampaign-qms_id");
        // let call_timeout = document.querySelector("#edascampaign-call_timeout");
        // let selected_call_window = document.querySelector("#selected_call_window");
        // let campaign_operators = document.querySelector("#edascampaign-campaign_operators");

           



        // let camp_name = document.querySelector("#edascampaign-campaign_name");
        // let campaign_type = document.querySelector("#edascampaign-campaign_type");
        // let qms_id = document.querySelector("#edascampaign-qms_id");
        // let call_timeout = document.querySelector("#edascampaign-call_timeout");
        // let selected_call_window = document.querySelector("#selected_call_window");
        // let campaign_operators = document.querySelector("#edascampaign-campaign_operators");
        // let status_btn = document.querySelector("#status-btn");


        //  let button = document.querySelector(".camp_form");

        //  //button.disabled = true; //setting button state to disabled

        //  camp_name.addEventListener("change", stateHandle);
        //  campaign_type.addEventListener("change", stateHandle);
        //  qms_id.addEventListener("change", stateHandle);
        //  call_timeout.addEventListener("change", stateHandle);
        //  selected_call_window.addEventListener("change", stateHandle);
        //  campaign_operators.addEventListener("change", stateHandle);
        //  status_btn.addEventListener("change", stateHandle);
        //  button.addEventListener("change", stateHandle);

        // function stateHandle() {
            
           
        //         if (document.querySelector("#edascampaign-campaign_name").value !== " " && document.querySelector("#edascampaign-campaign_type").value !== " " && document.querySelector("#edascampaign-qms_id").value !== " " && document.querySelector("#edascampaign-call_timeout").value !== " " && document.querySelector("#selected_call_window").value !== " " && document.querySelector("#edascampaign-campaign_operators").value !== " " && document.querySelector("#status-btn").value !== " " ) {
        //             //alert('please fill mandatory fields');
        //             button.disabled = false; //button is enabled
        //         }
        //         else{
        //             button.disabled = true; //button remains disabled
        //         }
         
                
        // }
</script>


<script>

// jQuery.validator.setDefaults({
//   debug: true,
//   success: "valid"
// });
// $( ".camp_form" ).validate({
//   rules: {
//         'EdasCampaign[campaign_name]': "required",
//         'EdasCampaign[campaign_type]': "required",
//         'EdasCampaign[campaign_operators]': "required",
//         'EdasCampaign[qms_id]': "required",
//         'EdasCampaign[call_timeout]': "required",
//         'EdasCampaign[call_window]': "required",
//   }
// });  

$( document ).ready(function() {

                let camp_name = $('#edascampaign-campaign_name');
                let campaign_type = $('#edascampaign-campaign_type');
                let qms_id = $('#edascampaign-qms_id');
                let call_timeout = $('#edascampaign-call_timeout');
                let selected_call_window = $('#selected_call_window');
                let campaign_operators = $('#edascampaign-campaign_operators');
                let button = document.querySelector("#camp_form");
                let button2 = document.querySelector("#dispositionTab");
                let button3 = document.querySelector("#add_dailer");
                let button4 = document.querySelector("#finalsubmit");


                function stateHandle(){
                     if($('#edascampaign-campaign_name').val().length == 0 || $('#edascampaign-campaign_type').val().length == 0 || $('#edascampaign-qms_id').val().length == 0 ||  $('#selected_call_window').val().length == 0  ||  $('#edascampaign-campaign_operators').val().length == 0 || ('#edascampaign-crm_name').val().length == 0 || ('#edascampaign-cli_number').val().length == 0 || ('#edascampaign-cli_number').val().length == 0)
                 {          
                    swal.fire('kindly fill mandatory fields');
                    button.disabled = true;
                        //if($('#edascampaign-campaign_name').val().length == 0){
                        //     alert('Please fill campaign name');
                        //     button.disabled = true;
                        // }
                        // if($('#edascampaign-campaign_type').val().length == 0){
                        //     alert('Please fill campaign type');
                        //     button.disabled = true;
                        // }
                        // if($('#edascampaign-campaign_operators').val().length == 0){
                        //     alert('Please fill operator');
                        //     button.disabled = true;
                        // }
                        // if($('#edascampaign-qms_id').val().length == 0){
                        //     alert('Please fill audit templete');
                        //     button.disabled = true;
                        // }
                        // if($('#selected_call_window').val().length == 0){
                        //     alert('Please fill and runtime');
                        //     button.disabled = true;
                        // }
                        
                
                }
                }
         $('#camp_form').on('click', function() {
                stateHandle();
        });

        function stateHandle2(){
            if($('#edascampaign-disposition_plan_id').val().length == 0){
                swal.fire('Please fill disposition');
                        button2.disabled = true;
            }
        }

        $('#dispositionTab').on('click',function() {

            stateHandle2();
        });


        function stateHandle3(){
            if($('#edascampaign-campaign_sub_type').val().length == 0)
            {
                swal.fire('Please fill dial mode');
                        button3.disabled = true;
                
            }

        }

        $('#add_dailer').on('click',function() {

            stateHandle3();
        });

        function stateHandle4(){
            if($('#edascampaign-kp_id').val().length == 0)
            {
                swal.fire('Please select knowledge portal');
                        button4.disabled = true;
            
                
            }

        }

        $('.finalsubmit').mouseover(function(e) {
    
            stateHandle4();
            e.preventDefault();
        });


        $(document).on('change', function(){

            let button = document.querySelector("#camp_form");
            let button2 = document.querySelector("#dispositionTab");
            let button3 = document.querySelector("#add_dailer");
            let button4 = document.querySelector(".finalsubmit");

            button.disabled = false
            button2.disabled = false
            button3.disabled = false
            button4.disabled = false
        });
});   


//hold music 

        var passedArray = <?php echo json_encode($music); ?>;
        var obj = Object.values(passedArray);
        var optionlist = '<option>Select...</option>\n';
        obj.forEach( o =>  {
        var file = o.split('/').pop();
            optionlist += '<option>'+file+'</option>';
            // alert(file+"heii");
        });
        $('#edascampaign-select_audio').html(optionlist);


        var passedArray1 = <?php echo json_encode($arr); ?>;
        var obj = passedArray1; //Object.entries(passedArray1);

        console.log(passedArray1);
        var optionlist1 = '<option>Select...</option>\n';

        Object.keys(obj).forEach(function(key) {

        console.log(key, obj[key]);
        optionlist1 += '<option value='+key +'>'+obj[key]+'</option>';

        });


    //  });
        // obj.forEach(function(k, value) {
        // //var file = o;
        //     optionlist += '<option value='+field +'>'+value+'</option>';
        //     // alert(file+"heii");
        // });
        $('#edascampaign-crm_name').html(optionlist1);
 


        $('#edascampaign-campaign_type').on('change', function(){
            let button = document.querySelector("#camp_form");
        if($('#edascampaign-campaign_type').val() == 2)
                        {
                            alert('Please fill call medium');
                            button.disabled = true;
                        }
        });
                                                
                                                     
</script>
