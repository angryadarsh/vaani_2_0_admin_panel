<?php

use common\models\VaaniAgentCallReport;
use common\models\VaaniAgentAuditMarkings;
use common\models\VaaniQmsSheet;

$urlManager = Yii::$app->urlManager;

$path = ($callModel ? $callModel->recording_path : null);

if($path){
    $path = str_replace('/var/www/html', '', $path);
}else{
    $path = $urlManager->baseUrl . '/test_recording';
}

$startDate = date("Y-m-d H:i:s");
?>

<!-- audio section -->
<div class="audio_section">
    <?php //if($sheetModel->type == VaaniQmsSheet::TYPE_TRANSACTIONAL){ ?>
        <audio id="current_audio"  preload="auto" controls>
            <?php if($path){ ?>
                <source src="<?= ($path ? Yii::$app->urlManager->hostInfo . trim($path) . '.wav' : null) ?>" type="audio/wav">
                <source src="<?= ($path ? Yii::$app->urlManager->hostInfo . trim($path) . '.mpeg' : null) ?>" type="audio/mpeg">
            <?php } ?>
        </audio>

        <?php if($evaluated_sheet && $evaluated_sheet->rec_markers){
                $markers = explode(',', $evaluated_sheet->rec_markers);
                $m_cnt = 1;
                if($markers){
        ?>

                <div id="marker-div" style="margin:5px 0;" data-markercount="0">
        
                <?php foreach ($markers as $m_key => $marker) {
                    $marker_time = explode(':', $marker);
                    if($marker_time){
                        $marker_min = $marker_time[0];
                        $marker_sec = $marker_time[1];
                        ?>
                        <span class="added-marker-span" id="add_marker_span_<?=$m_cnt?>"> 
                            <span class="time-marked"><?=$marker?> </span>
                            <input type="hidden" class="marker_minute_input" id="marker_minute_<?=$m_cnt?>" name="audio_marker_minute[<?=$m_cnt?>]" value="<?=$marker_min?>">
                            <input type="hidden" id="marker_seconds_<?=$m_cnt?>" class="marker_seconds_input" name="audio_marker_seconds[<?=$m_cnt?>]" value="<?=$marker_sec?>">
                            <span class="mark-count">Mark<?=$m_cnt?></span> 
                            <button onclick="remove_this('add_marker_span_<?=$m_cnt?>','<?=$m_cnt?>');" class="btn btn-danger">X</button>
                            <span class="marker_content" value="<?= $marker_min ?>.<?= $marker_sec ?>" data-count="<?= $m_cnt ?>"></span>
                        </span>
                        
                        <?php $m_cnt++; 
                        } ?>
                <?php } ?>
                </div>
            <?php } ?>
        <?php } ?>
    <?php //} ?>
    
    <div id="starttime" style="display: none;">
        Start Time: <?=  $startDate; ?> Count: <?= date("Y-m-d"); ?>
        <span id="audit_time"></span>
        <input type="hidden" id="evaluate-audit_start_time" name="audit_start_time" value="<?=  $startDate; ?>">
    </div>
</div>
<ul class="nav nav-tabs mb-3 tabs-list" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="tabs-quality-tab" data-toggle="pill" href="#tabs-quality" role="tab" aria-controls="tabs-quality" aria-selected="true">Quality Feedback</a>
    </li>
    <?php if($sheetModel->parameters){
        foreach ($sheetModel->parameters as $key => $parameter) { ?>
            <li class="nav-item">
                <a class="nav-link" id="tabs-<?=$key?>-tab" data-toggle="pill" href="#tabs-<?=$key?>" role="tab" aria-controls="tabs-<?=$key?>" aria-selected="false"><?= $parameter->name ?></a>
            </li>
        <?php } ?>
    <?php } ?>
</ul>
<div class="tab-content sub_tabs_content">
    <div class="tab-pane active" id="tabs-quality" role="tabpanel" aria-labelledby="tabs-quality-tab">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Agent Name</label>
                        <div class=" col-sm-8">
                            <select readonly name="agent_id" class="form-control" required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <!-- <option value="">Select</option> -->
                                <?php if($callModel){ ?>
                                    <option value="<?=$callModel->user->user_id?>" selected><?=$callModel->user->user_name?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Campaign</label>
                        <div class=" col-sm-8">
                            <select readonly name="campaign_name" class="form-control" required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <!-- <option value="">Select</option> -->
                                <?php if($callModel){ ?>
                                    <option value="<?=$callModel->campaign?>" selected><?=$callModel->campaign?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Language</label>
                        <div class=" col-sm-8">
                            <select name="language" class="form-control" id="" <?= ($evaluated_sheet && $evaluated_sheet->location ? 'readonly' : '') ?> required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <option value="">---Select Language---</option>
                                <option value="English" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'English' ? 'selected' : 'disabled') : '') ?> >English</option>
                                <option value="Hindi" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Hindi' ? 'selected' : 'disabled') : '') ?> >Hindi</option>
                                <option value="Marathi" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Marathi' ? 'selected' : 'disabled') : '') ?> >Marathi</option>
                                <option value="Tamil" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Tamil' ? 'selected' : 'disabled') : '') ?> >Tamil</option>
                                <option value="Telugu" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Telugu' ? 'selected' : 'disabled') : '') ?> >Telugu</option>
                                <option value="Kannada" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Kannada' ? 'selected' : 'disabled') : '') ?> >Kannada</option>
                                <option value="Malyalam" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Malyalam' ? 'selected' : 'disabled') : '') ?> >Malyalam</option>
                                <option value="Gujarathi" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Gujarathi' ? 'selected' : 'disabled') : '') ?> >Gujarathi</option>
                                <option value="Bengali" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Bengali' ? 'selected' : 'disabled') : '') ?> >Bengali</option>
                                <option value="Oriya" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Oriya' ? 'selected' : 'disabled') : '') ?> >Oriya</option>
                                <option value="Aasami" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Aasami' ? 'selected' : 'disabled') : '') ?> >Aasami</option>
                                <option value="Punjabi" <?= ($evaluated_sheet ? ($evaluated_sheet->language == 'Punjabi' ? 'selected' : 'disabled') : '') ?> >Punjabi</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Audit Type</label>
                        <div class=" col-sm-8">
                            <select name="audit_type" class="form-control" required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <option value="">Select</option>
                                <option value="Recorded Calls" <?= ($evaluated_sheet ? ($evaluated_sheet->audit_type == 'Recorded Calls' ? 'selected' : 'disabled') : 'selected') ?>>Recorded Calls</option>
                                <option value="Live Call" <?= ($evaluated_sheet ? ($evaluated_sheet->audit_type == 'Live Call' ? 'selected' : 'disabled') : '') ?>>Live Call</option>
                                <option value="SBS" <?= ($evaluated_sheet ? ($evaluated_sheet->audit_type == 'SBS' ? 'selected' : 'disabled') : '') ?>>SBS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Location</label>
                        <div class=" col-sm-8">
                            <select name="location" class="form-control" readonly>
                                <!-- <option value="">Select</option> -->
                                <option value="mumbai" selected>Mumbai</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Call Duration</label>
                        <div class=" col-sm-8">
                            <input type="text" readonly name="call_duration" class="form-control" value="<?=($callModel ? $callModel->duration : null)?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Call Date</label>
                        <div class=" col-sm-8">
                            <input type="text" readonly name="call_date" class="form-control" value="<?= (($callModel ? $callModel->start_time : null)) ?>">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Week</label>
                        <div class=" col-sm-8">
                            <?php $week = null;
                            // fetch week based on call date
                            if(!($evaluated_sheet && $evaluated_sheet->week) && $callModel && $callModel->start_time){
                                $firstOfMonth = strtotime(date("Y-m-01", strtotime($callModel->start_time)));
                                
                                $week = intval(date("W", strtotime($callModel->start_time))) - intval(date("W", $firstOfMonth)) + 1;

                                if($week) $week = 'Week'.$week;
                            } ?>
                            <select class="form-control" name="week" required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <option value="<?=$week?>">Select</option>
                                <option value="Week1" <?= ($evaluated_sheet && $evaluated_sheet->week == 'Week1' ? 'selected' : ($week == 'Week1' ? 'selected' : '')) ?>>Week1</option>
                                <option value="Week2" <?= ($evaluated_sheet && $evaluated_sheet->week == 'Week2' ? 'selected' : ($week == 'Week2' ? 'selected' : '')) ?>>Week2</option>
                                <option value="Week3" <?= ($evaluated_sheet && $evaluated_sheet->week == 'Week3' ? 'selected' : ($week == 'Week3' ? 'selected' : '')) ?>>Week3</option>
                                <option value="Week4" <?= ($evaluated_sheet && $evaluated_sheet->week == 'Week4' ? 'selected' : ($week == 'Week4' ? 'selected' : '')) ?>>Week4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Month</label>
                        <div class=" col-sm-8">
                            <!-- fetch month from call data -->
                            <?php
                            if($evaluated_sheet && $evaluated_sheet->month){
                                $month = $evaluated_sheet->month;
                            }else{
                                $month = ($callModel ? date('F', strtotime($callModel->start_time)) : null);
                            }
                            ?>
                            <select class="form-control" name="month" required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <option value="">Select</option>
                                <option value="January" <?= ($month == 'January' ? 'selected' : '') ?> >January</option>
                                <option value="February" <?= ($month == 'February' ? 'selected' : '') ?> >February</option>
                                <option value="March" <?= ($month == 'March' ? 'selected' : '') ?> >March</option>
                                <option value="April" <?= ($month == 'April' ? 'selected' : '') ?> >April</option>
                                <option value="May" <?= ($month == 'May' ? 'selected' : '') ?> >May</option>
                                <option value="June" <?= ($month == 'June' ? 'selected' : '') ?> >June</option>
                                <option value="July" <?= ($month == 'July' ? 'selected' : '') ?> >July</option>
                                <option value="August" <?= ($month == 'August' ? 'selected' : '') ?> >August</option>
                                <option value="September" <?= ($month == 'September' ? 'selected' : '') ?> >September</option>
                                <option value="October" <?= ($month == 'October' ? 'selected' : '') ?> >October</option>
                                <option value="November" <?= ($month == 'November' ? 'selected' : '') ?> >November</option>
                                <option value="December" <?= ($month == 'December' ? 'selected' : '') ?> >December</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Call ID</label>
                        <div class=" col-sm-8">
                            <input type="text" name="call_id" class="form-control" value="<?php // ($evaluated_sheet && $evaluated_sheet->call_id ? $evaluated_sheet->call_id : '') ?>" placeholder="Call ID">
                        </div>
                    </div>
                </div> -->
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Analysis Finding</label>
                        <div class=" col-sm-8">
                            <select class="form-control" name="analysis_finding" required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <option value="">Select</option>
                                <option value="Agent" <?= ($evaluated_sheet && $evaluated_sheet->analysis_finding == 'Agent' ? 'selected' : '') ?>>Agent</option>
                                <option value="Customer" <?= ($evaluated_sheet && $evaluated_sheet->analysis_finding == 'Customer' ? 'selected' : '') ?>>Customer</option>
                                <option value="Process" <?= ($evaluated_sheet && $evaluated_sheet->analysis_finding == 'Process' ? 'selected' : '') ?>>Process</option>
                                <option value="Technical" <?= ($evaluated_sheet && $evaluated_sheet->analysis_finding == 'Technical' ? 'selected' : '') ?>>Technical</option>
                                <option value="NA" <?= ($evaluated_sheet && $evaluated_sheet->analysis_finding == 'NA' ? 'selected' : '') ?>>NA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Agent Type</label>
                        <div class=" col-sm-8">
                            <select class="form-control" name="agent_type">
                                <!-- <option value="">Select</option> -->
                                <option value="NON OJT" selected>NON OJT</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Unique ID</label>
                        <div class=" col-sm-8">
                            <input type="text" readonly name="unique_id" required class="form-control" value="<?=($callModel ? $callModel->unique_id : null)?>" oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Disposition</label>
                        <div class=" col-sm-8">
                            <?php $dispo_name = ($callModel && $callModel->history ? $callModel->history->disposition : null); ?>
                            <select readonly class="form-control" name="disposition">
                                <!-- <option value="">Select</option> -->
                                <?php if($dispo_name){ ?>
                                    <option value="<?= $dispo_name ?>" selected><?= $dispo_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Sub Disposition</label>
                        <div class=" col-sm-8">
                            <?php $sub_dispo_name = ($callModel && $callModel->history ? $callModel->history->sub_disposition : null); ?>
                            <select readonly class="form-control" name="sub_disposition">
                                <!-- <option value="">Select</option> -->
                                <?php if($sub_dispo_name){ ?>
                                    <option value="<?= $sub_dispo_name ?>" selected><?= $sub_dispo_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">PIP Status</label>
                        <div class=" col-sm-8">
                            <select class="form-control" name="pip_status" required oninvalid="$(this).parent().closest('.tab-pane').addClass('active');">
                                <!-- <option value="">Select</option> -->
                                <?php $pip_status = $sheetModel->template->pip_status;
                                $pip_status = explode(',', $pip_status);
                                foreach($pip_status as $k => $stat){ ?>
                                    <option value="<?=$stat?>"><?=$stat?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Categorization</label>
                        <div class=" col-sm-8">
                            <select class="form-control" name="categorization">
                                <!-- <option value="">Select</option> -->
                                <option value="NA" selected>NA</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="row form-group">
                        <label class="form-label col-sm-4">Action Status</label>
                        <div class=" col-sm-8">
                            <select class="form-control" name="action_status">
                                <!-- <option value="">Select</option> -->
                                <option value="NA" selected>NA</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <?php if($sheetModel->type == VaaniQmsSheet::TYPE_TRANSACTIONAL){ ?>
                    <div class="col-lg-12">
                        <div class="row form-group">
                            <label class="form-label col-sm-2">Gist of Case</label>
                            <div class=" col-sm-10">
                                <textarea rows="2" name="gist_of_case" class="form-control" placeholder="Gist of Case"  oninvalid="$(this).parent().closest('.tab-pane').addClass('active');"><?= ($evaluated_sheet && $evaluated_sheet->gist_of_case ? $evaluated_sheet->gist_of_case : '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row form-group">
                            <label class="form-label col-sm-2">Analysis Finding</label>
                            <div class=" col-sm-10">
                                <textarea rows="2" name="resolution_provided" class="form-control" placeholder="Analysis Finding"  oninvalid="$(this).parent().closest('.tab-pane').addClass('active');"><?= ($evaluated_sheet && $evaluated_sheet->resolution_provided ? $evaluated_sheet->resolution_provided : '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row form-group">
                            <label class="form-label col-sm-2">Areas Of Improvement</label>
                            <div class=" col-sm-10">
                                <textarea rows="2" name="areas_of_improvement" class="form-control" placeholder="Areas Of Improvement"  oninvalid="$(this).parent().closest('.tab-pane').addClass('active');"><?= ($evaluated_sheet && $evaluated_sheet->areas_of_improvement ? $evaluated_sheet->areas_of_improvement : '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="row form-group">
                            <label class="form-label col-sm-2">Reason For Error Call</label>
                            <div class=" col-sm-10">
                                <textarea rows="2" name="reason_for_fatal_call" class="form-control" placeholder="Reason For Error Call"  oninvalid="$(this).parent().closest('.tab-pane').addClass('active');"><?= ($evaluated_sheet && $evaluated_sheet->reason_for_fatal_call ? $evaluated_sheet->reason_for_fatal_call : '') ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <!-- Sub parameters markings -->
    <?php if($sheetModel->parameters){
        foreach ($sheetModel->parameters as $key => $parameter) { ?>
            <div class="tab-pane" id="tabs-<?=$key?>" role="tabpanel" aria-labelledby="tabs-<?=$key?>-tab">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Parameter</th>
                            <th>Sub Parameter</th>
                            <th>Marking</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if($parameter->subParameters){
                        foreach ($parameter->subParameters as $sub_key => $sub_parameter) {
                            $scores = explode(",", $sub_parameter->score);
                            $evaluated_marking = ($evaluated_sheet ? (VaaniAgentAuditMarkings::find()->where(['audit_id' => $evaluated_sheet->audit_id])->andWhere(['sub_parameter_id' => $sub_parameter->id])->one()) : null);
                        ?>
                            <tr>
                                <td><?= $parameter->name ?></td>
                                <td><?= $sub_parameter->name ?></td>
                                <td>
                                    <?php if($sheetModel->type == VaaniQmsSheet::TYPE_TRANSACTIONAL){
                                        $marking_class = 'marking';
                                    }else{
                                        $marking_class = 'analytical_marking';
                                    } ?>

                                    <select name="markings[<?=$sub_parameter->id?>]" class="form-control <?=$marking_class?>" required >
                                        <option value="">Select</option>
                                        <?php foreach ($scores as $k => $score) { ?>
                                            <option value="<?= $score ?>" <?= ($evaluated_marking && $evaluated_marking->marking == $score ? 'selected' : '') ?>><?= $score ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="remarks[<?=$sub_parameter->id?>]" class="form-control" value="<?= ($evaluated_marking && $evaluated_marking->remarks ? $evaluated_marking->remarks : '') ?>">
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<!-- fixed fields -->
<div class="container-fluid">
    <hr>
    <div class="row">
        <div class="col-lg-4">
            <?php if($sheetModel->type == VaaniQmsSheet::TYPE_TRANSACTIONAL){ ?>
                <div class="row form-group">
                    <label class="form-label col-sm-4">Quality Score</label>
                    <div class=" col-sm-8">
                        <input type="text" name="quality_score" id="quality_score" class="form-control" readonly value="<?= ($evaluated_sheet && $evaluated_sheet->quality_score ? $evaluated_sheet->quality_score : 0) ?>">
                    </div>
                </div>
            <?php }else{ ?>
                <div class="row form-group">
                    <label class="form-label col-sm-4">Total Yes</label>
                    <div class=" col-sm-8">
                        <input type="text" name="yes_count" id="yes_count" class="form-control" readonly value="<?= ($evaluated_sheet && $evaluated_sheet->yes_count ? $evaluated_sheet->yes_count : 0) ?>">
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if($sheetModel->type == VaaniQmsSheet::TYPE_TRANSACTIONAL){ ?>
            <div class="col-lg-4">
                <div class="row form-group">
                    <label class="form-label col-sm-4">Out Of</label>
                    <div class=" col-sm-8">
                        <input type="text" name="out_of" id="out_of_score" class="form-control" value="<?= $sheetModel->out_of ?>" readonly>
                        <span class="hidden hidden_out_of" value="<?= ($evaluated_sheet && $evaluated_sheet->out_of ? $evaluated_sheet->out_of : $sheetModel->out_of) ?>"></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="row form-group">
                    <label class="form-label col-sm-4">Final Score</label>
                    <div class=" col-sm-8">
                        <input type="text" name="final_score" id="final_score" class="form-control" readonly value="<?= ($evaluated_sheet && $evaluated_sheet->final_score ? $evaluated_sheet->final_score : 0) ?>">
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-lg-4">
            <div class="row form-group">
                <label class="form-label col-sm-4">Percentage (%)</label>
                <div class=" col-sm-8">
                    <input type="text" name="total_percent" id="total_percent" class="form-control" readonly value="<?= ($evaluated_sheet && $evaluated_sheet->total_percent ? $evaluated_sheet->total_percent : 0) ?>">
                </div>
            </div>
        </div>
    </div>
    <?php if(!$is_preview){ ?>
        <div class="text-center">
            <button type="reset" class="btn btn-outline-secondary" style="">Cancel</button>
            <button type="submit" class="btn btn-outline-primary" style="">Save</button>
        </div>
    <?php } ?>
</div>