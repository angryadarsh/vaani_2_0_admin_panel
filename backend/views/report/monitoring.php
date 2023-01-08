<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use fedemotta\datatables\DataTables;
use common\models\User;
use common\models\VaaniActiveStatus;
use common\models\VaaniAgentLiveStatus;
use common\models\VaaniSession;
use common\models\EdasCampaign;

$this->title = 'Monitoring';

$urlManager = Yii::$app->urlManager;
?>


<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
		<!-- <nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item menu-text" aria-current="page">Menu </li>
				<li class="breadcrumb-item active" aria-current="page">Monitoring</li>
			</ol>
		</nav> -->
        <div class="clearfix top-header">
          	<h1 class="float-left">Monitoring</h1>
          	<div class="float-right">
            	<!-- <ul class="nav filter-list">
					<li><b>Filters :</b> &nbsp;  </li>
              		<li class="mr-2">
                		<div class="form-group">
                  			<select name="campaign_type[]" id="campaign_type" class="form-control">
								<?php foreach (EdasCampaign::$campaign_types as $key => $type) { ?>
									<option value="<?= $key ?>"><?= $type ?></option>
								<?php } ?>
                  			</select>
                  			<label class="form-label" for="">Campaign Type</label>
                		</div>
              		</li>
              		<li class="mr-2">
                		<div class="form-group">
                  			<select name="groups[]" id="campaign_list" class="form-control">
                    			<option value="">Campaigns</option>
                  			</select>
                  			<label class="form-label" for="">Campaigns</label>
                		</div>
              		</li>
              		<li class="mr-2">
                		<div class="form-group">
                  			<select name="groups[]" id="queue_list" class="form-control">
                    			<option value="">Queues</option>
                  			</select>
                  			<label class="form-label" for="">Queues</label>
                		</div>
              		</li>
              		<li class="mr-2">
						<div class="form-group">
							<select name="RR" id="refresh_list" class="form-control" required>
								<option value="">Refresh Rate</option>
								<option value="5">5 seconds</option>
								<option value="10">10 seconds</option>
								<option value="20">20 seconds</option>
								<option value="30">30 seconds</option>
							</select>
							<label class="form-label" for="">Refresh Rate</label>
						</div>
					</li>
					<li>
						<div class="refresh">
							<a href="javascript:void(0)" id="refresh_btn"><i class="fas fa-sync-alt"></i></a>
							: <span id="counter_value">30</span>
						</div>
					</li>
            	</ul> -->

				<a href="#" class="view-more btn-top">
					View Details  
					<div class="circle-plus closed">
						<div class="circle">
							<div class="horizontal"></div>
							<div class="vertical"></div>
						</div>
					</div>
				</a>
				<a href="#" class="btn-top ml-2" data-toggle="modal" data-target="#exampleModal"><i class="fas fa-filter"></i> Filters</a>
				<span class="refresh ml-2">
					<a href="javascript:void(0)" id="refresh_btn"><i class="fas fa-sync-alt"></i></a>
					: <span id="counter_value">30</span>
				</span>
				
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Choose Report Display Options</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group mt-3">
									<select name="campaign_type[]" id="campaign_type" class="form-control">
										<?php foreach (EdasCampaign::$campaign_types as $key => $type) { ?>
											<option value="<?= $key ?>"><?= $type ?></option>
										<?php } ?>
									</select>
									<label class="form-label" for="">Campaign Type</label>
								</div>
								<div class="form-group">
									<select name="groups[]" id="campaign_list" class="form-control">
										<option value="">Campaigns</option>
									</select>
									<label class="form-label" for="">Campaigns</label>
								</div>
								<div class="form-group">
									<select name="RR" id="refresh_list" class="form-control">
										<option value="">Refresh Rate</option>
										<option value="5">5 seconds</option>
										<option value="10">10 seconds</option>
										<option value="20">20 seconds</option>
										<option value="30">30 seconds</option>
									</select>
									<label class="form-label" for="">Refresh Rate</label>
								</div>
								<div class="mb-3 text-center">
									<button class="btn btn-outline-primary" type="button">Submit</button>
								</div>
							</div>
						</div>
					</div>
				</div>
          	</div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- <div class="row">
			<div class="col-md-12">
				<div class="card card-primary card-outline card-outline-tabs">
					<div class="card-header p-0 border-bottom-0">
						<ul class="nav nav-tabs" id="tabs-tab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="tabs-view-tab" data-toggle="pill"
									href="#tabs-view" role="tab" aria-controls="tabs-view"
									aria-selected="true">View</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="tabs-stats-tab" data-toggle="pill"
								href="#tabs-stats" role="tab" aria-controls="tabs-stats"
								aria-selected="false">Stats</a>
							</li>
						</ul>
					</div>
					<div class="card-body">
                		<div class="tab-content" id="tabs-tabContent">
                  			<div class="tab-pane fade show active" id="tabs-view" role="tabpanel"
                  				aria-labelledby="tabs-view-tab">
								
             				</div>

							<div class="tab-pane fade" id="tabs-stats" role="tabpanel" aria-labelledby="tabs-stats">
								<div class="row">
									<div class="col-md-6">
										<div class="chartdiv" id="timesection"></div>
									</div>
									<div class="col-md-6">
										<div class="chartdiv" id="weekSection"></div>
									</div>
								</div>
							</div>
                		</div>
              		</div>
            	</div>
            </div>
        </div> -->
        <!-- /.row --> 
		<div class="active_flag_value"></div>
		<div id="monitoring-list"></div>
    </div><!-- /.container-fluid -->
</section>

<div id="actionModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal_header">Choose Report Display Options</p>
                <button type="button" class="close modal_close" data-dismiss="modal">&times;</button>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'modal_form']); ?>
            <div class="modal-body">
                <div class="col-md-12">
					<div class="form-group field-monitor_action">
						<select id="monitor_action" class="form-control modal-input" name="monitor_action" placeholder="Monitor" required="true">
							<option value="1">Monitor</option>
							<option value="2">Whisper</option>
							<option value="3">Barge In</option>
						</select>
						<label class="form-label" for="monitor_action">Monitor</label>
						<div class="help-block"></div>
					</div>
                </div>
                <div class="col-md-12">
					<div class="form-group field-agent_id">
						<input type="hidden" id="agent_id" class="form-control modal-input" name="agent_id">
						<input type="hidden" id="unique_id" class="form-control modal-input" name="unique_id">
					</div>
					<div class="form-group field-extension">
						<input type="text" id="extension" class="form-control modal-input" name="supervisor_id" placeholder="Extension" required="true">
						<label class="form-label" for="extension">Extension</label>
						<div class="help-block"></div>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12 form-group text-center">
                    <?= Html::button('Monitor', ['class' => 'btn btn-primary', 'id' => 'modal_submit']) ?>
                    <?= Html::resetButton('Cancel', ['class' => 'btn btn-secondary']) ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$this->registerCss("
	.monitoring-stats{
		margin : 0;
	}
");
?>

<?php
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/amcharts/index.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/amcharts/xy.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/amcharts/percent.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/amcharts/Animated.js');
$this->registerJsFile(Yii::$app->request->baseUrl . '/js/amcharts/piechart.js');
?>
<script>
	var monitor_action = "<?= (isset($_SESSION['monitor_action']) ? $_SESSION['monitor_action'] : null) ?>";
	var monitor_agent_id = "<?= (isset($_SESSION['monitor_agent_id']) ? $_SESSION['monitor_agent_id'] : null) ?>";
	var monitor_extension = "<?= (isset($_SESSION['monitor_extension']) ? $_SESSION['monitor_extension'] : null) ?>";
	var monitor_unique_id = "<?= (isset($_SESSION['monitor_unique_id']) ? $_SESSION['monitor_unique_id'] : null) ?>";
</script>
<!-- fetch ajax data for agent report -->
<?php
$this->registerJs("
	$('table').addClass('table-responsive');

	$('.active_flag_value').val($('.stats_count').data('flag'));
");

$this->registerJs('
// load agent report function

function getReport(flag=null, campaign_id=null, queue_id=null)
{
	var show_details = false;
	// check if details is visible or not
	if($(".campaign-stats").is(":visible")){
		show_details = true;
	}else{
		show_details = false;
	}
	console.log(show_details);

	if(!campaign_id){
		campaign_id = $("#campaign_list").val();
	}
	if(!queue_id){
		queue_id = $("#queue_list").val();
	}
	if(!flag){
		flag = $(".active_flag_value").val();
	}
		
	campaign_type = $("#campaign_type").val();

	$.ajax({
		method: "POST",
        url: "'. $urlManager->baseUrl . "/index.php/report/get-agent-report" .'",
        data: { flag : flag, campaign_id : campaign_id, campaign_type : campaign_type, queue_id : queue_id }
    }).done(function (data) {
        $("#monitoring-list").html(data);

		if(show_details) $(".campaign-stats").show();

		var datatblflt = $("#datatables_w0").dataTable({
			stateSave: true,
			lengthMenu : [
				[10, 20, 50, -1], [10, 20, 50, "All"]
			],
			responsive : true,
			dom : "flrtip",
			buttons : [
				"copy", "excel", "csv", "print",
				{
					text: "PDF",
					extend: "pdfHtml5",
					orientation: "landscape", //portrait
					pageSize: "A4",
					exportOptions: {
						columns: ":visible",
						search: "applied",
						order: "applied"
					},
					customize: function (doc) {
						doc.content[1].table.widths = ["2%",  "20%", "19%", "19%", 
						"19%", "19%"];
						doc.pageMargins = [20,60,20,30];
						doc.defaultStyle.fontSize = 12;
						doc.defaultStyle.alignment = "center";
						doc.styles.tableHeader.fontSize = 12;

						var objLayout = {};
						objLayout["hLineWidth"] = function(i) { return .5; };
						objLayout["vLineWidth"] = function(i) { return .5; };
						objLayout["hLineColor"] = function(i) { return "#aaa"; };
						objLayout["vLineColor"] = function(i) { return "#aaa"; };
						objLayout["paddingLeft"] = function(i) { return 4; };
						objLayout["paddingRight"] = function(i) { return 4; };
						doc.content[0].layout = objLayout;
					}
				}
			]
		});

		// filter apply 06-Apr-2022+++++++++++++++++++++++++++++++
		// Restore state
		if($("#datatables_w0").length > 0)
		{
   		 // 	var state = datatblflt.state.loaded();
		    // if ( state ) {
		    //   datatblflt.columns().eq( 0 ).each( function ( colIdx ) {
		    //     var colSearch = state.columns[colIdx].search;
		        
		    //     if ( colSearch.search ) {
		    //       $( "input", datatblflt.column( colIdx ).footer() ).val( colSearch.search );
		    //     }
		    //   } );
		      
		    //   datatblflt.draw();
		    // }
    
 
		    // // Apply the search
		    // datatblflt.columns().eq( 0 ).each( function ( colIdx ) {
		    //     $( "input", table.column( colIdx ).footer() ).on( "keyup change", function () {
		    //         table
		    //             .column( colIdx )
		    //             .search( this.value )
		    //             .draw();
		    //     } );
		    // } );
		}
		// ++++++++++++++++++++++++++++++++++++++++++++++
		
		// ROW LINK
		$("#datatables_w0 td").css("cursor", "auto");
		
		/* $("td").click(function (e) {
			var id = $(this).closest("tr").data("id");
			if(e.target == this)
				location.href = "' . Url::to(['report/call-register-report']) . '?id=" + id;
		}); */
		
		// STATS FILTER ON REPORT
		$(".stats_count").on("click", function(){
			flag = $(this).data("flag");
			console.log(flag);
			$(".active_flag_value").val(flag);
			getReport(flag);
		});
		
		// open the modal for report display actions
		$(".incall_action").on("click", function(e) {
			var user = $(this).data("user");
			$("#agent_id").val(user);
			var unique_id = $(this).data("unique_id");
			$("#unique_id").val(unique_id);
			
			$("#actionModal").modal("toggle");
		});

		// change submit button text on change of monitor actions
		$("#monitor_action").on("change", function(){
			var action_text = "Monitor";
			var action = $(this).val();
			if(action == 1)
				action_text = "Monitor";
			else if(action == 2)
				action_text = "Whisper";
			else if(action == 3)
				action_text = "Barge In";
			$("#modal_submit").text(action_text);
		});

		// submit modal
		$("#modal_submit").unbind().on("click", function(e) {
			e.preventDefault();
			var form = $("#modal_form");
			var m_action = $("#monitor_action").val();
			var agent_id = $("#agent_id").val();
			var extension = $("#extension").val();
			if(!extension){
				alert("Kindly provide extension!");
			}else{
				$.ajax({
					method: "GET",
					url: "'.$urlManager->baseUrl . '/index.php/site/sip-status' .'",
					data: {supervisor_id : extension}
				}).done( function(result){
					if(result != "OK") {
						alert("Kindly connect to the extension\'s Sip!");
					} else {
						$.ajax({
							method:"POST",
							url: "'.$urlManager->baseUrl . '/index.php/report/display-incall'.'",
							data: form.serialize()
						}).done(function(result){
							$("#actionModal").modal("hide");
							var result = $.parseJSON(result);
							if(result.msg != "success"){
								alert(result.msg);
							}else{
								alert("SUCCESS: You are connected!");
								
								monitor_action = result.monitor_action;
								monitor_agent_id = result.monitor_agent_id;
								monitor_extension = result.monitor_extension;
								monitor_unique_id = result.monitor_unique_id;

								var stop_element = $(".incall_action_stop[data-user=\'" + agent_id +"\']");
								$(".incall_action[data-user=\'" + agent_id +"\']").hide();
								stop_element.show();
								stop_element.attr("data-extension", extension);
		
								if(m_action == 1) {
									$("tr[data-id=\'" + agent_id +"\']").find(".sorting_1").append("<span class=\'blinker monitor_blink\'></span>");
									stop_element.attr("title", "Stop Monitoring");
								} else if(m_action == 2) {
									$("tr[data-id=\'" + agent_id +"\']").find(".sorting_1").append("<span class=\'blinker whisper_blink\'></span>");
									stop_element.attr("title", "Stop Whispering");
								} else if(m_action == 3) {
									$("tr[data-id=\'" + agent_id +"\']").find(".sorting_1").append("<span class=\'blinker barging_blink\'></span>");
									stop_element.attr("title", "Stop Barging");
								}
								getReport();
							}
						});
					}
				});
			}
		});

		// stop action click
		$(".incall_action_stop").on("click", function(){
			var supervisor_id = $(this).data("extension");
			var agent_id = $(this).data("user");
			var unique_id = $(this).data("unique_id");

			$.ajax({
				method:"POST",
				url: "'.$urlManager->baseUrl . '/index.php/report/stop-incall-action'.'",
				data: {supervisor_id : supervisor_id, unique_id : unique_id}
			}).done(function(result){
				if(result == "success") {
					alert("Done");
					monitor_action = null;
					monitor_agent_id = null;
					monitor_extension = null;
					monitor_unique_id = null;
					$(".incall_action[data-user=\'" + agent_id +"\']").show();
					$(".incall_action_stop[data-user=\'" + agent_id +"\']").hide();
					$("tr[data-id=\'" + agent_id +"\']").find(".sorting_1").children("span").remove();
					getReport();
				} else {
					alert(result);
				}
			});
		});
    });
}

// fetch list of campaigns
function getCampaigns()
{
	var campaign_type = $("#campaign_type").val();
	$.ajax({
		method: "GET",
		url: "'.$urlManager->baseUrl . '/index.php/report/get-campaigns' .'",
		data: {campaign_type : campaign_type}
	}).done(function(data){
		var data = $.parseJSON(data);
		if(data){
			var optionlist = "<option value=>Campaigns</option>";
			$.each(data, function(key, value){
				optionlist += "<option value=\'" + key + "\'>" + value + "</option>";
			});
			$("#campaign_list").html(optionlist);
		}
	});
}

// fetch list of Queues
function getQueues()
{
	var campaign_id = $("#campaign_list").val();
	$.ajax({
		method: "GET",
		url: "'.$urlManager->baseUrl . '/index.php/report/get-queues' .'",
		data: {campaign_id : campaign_id}
	}).done(function(data){
		var data = $.parseJSON(data);
		if(data){
			var optionlist = "<option value=>Queues</option>";
			$.each(data, function(key, value){
				optionlist += "<option value=\'" + key + "\'>" + value + "</option>";
			});
			$("#queue_list").html(optionlist);
		}
	});
}

var downloadTimer = "";
var timeleft = 30;
var counter = 30;
function refreshInterval(timeleft){
	var temp = timeleft;
	downloadTimer = setInterval(function(){
		$("#counter_value").text( ("0" + temp).slice(-2) );
		temp--;
		if(temp > 0){
			clearInterval(downloadTimer);
			refreshInterval(temp);
		}else{
			temp = counter;
			getReport();
		}
	}, 1000);
}

$(document).ready(function (e) {
    getCampaigns();
    getReport();

	counter = $("#refresh_list").val();
	if(!counter){
		counter = 30;
	}
	var timeleft = counter;
	refreshInterval(timeleft);

	var checkMonitoringAction = window.setInterval(function(){
		// check if in call action ongoing
		if(monitor_action && monitor_agent_id && monitor_unique_id){
			$.ajax({
				method: "GET",
				url: "'.$urlManager->baseUrl . '/index.php/report/check-call-status' .'",
				data: {unique_id : monitor_unique_id}
			}).done( function(call_status){
				if(call_status){
					var check_status = $("tr[data-id=\'" + monitor_agent_id +"\']").find(".sorting_1").next().next().next().text().toLowerCase();
					
					if(call_status == "INCALL" || call_status == "ANSWERED" || call_status == "PAUSE"){

						$(".incall_action[data-user=\'" + monitor_agent_id + "\']").hide();
						var stop_element = $(".incall_action_stop[data-user=\'" + monitor_agent_id + "\']");
						if(!stop_element.length || !$(".blinker").length){
							stop_element.show();
							stop_element.attr("data-extension", monitor_extension);

							if(monitor_action == 1) {
								$("tr[data-id=\'" + monitor_agent_id +"\']").find(".sorting_1").append("<span class=\'blinker monitor_blink\'></span>");
								stop_element.attr("title", "Stop Monitoring");
							} else if(monitor_action == 2) {
								$("tr[data-id=\'" + monitor_agent_id +"\']").find(".sorting_1").append("<span class=\'blinker whisper_blink\'></span>");
								stop_element.attr("title", "Stop Whispering");
							} else if(monitor_action == 3) {
								$("tr[data-id=\'" + monitor_agent_id +"\']").find(".sorting_1").append("<span class=\'blinker barging_blink\'></span>");
								stop_element.attr("title", "Stop Barging");
							}
						}
					}else{
						if(check_status.indexOf("in call") == -1){
							getReport();
						}
						
						$.ajax({
							method:"POST",
							url: "'.$urlManager->baseUrl . '/index.php/report/stop-incall-action'.'",
							data: {supervisor_id : monitor_extension, unique_id : monitor_unique_id}
						}).done(function(result){
							if(result == "success") {
								monitor_action = null;
								monitor_agent_id = null;
								monitor_extension = null;
								monitor_unique_id = null;
								$(".incall_action[data-user=\'" + monitor_agent_id +"\']").show();
								$(".incall_action_stop[data-user=\'" + monitor_agent_id +"\']").hide();
								$("tr[data-id=\'" + monitor_agent_id +"\']").find(".sorting_1").children("span").remove();
								alert("Done. Agent\'s call disconnected.");
								clearInterval(checkMonitoringAction);
								getReport();
							} else {
								alert(result);
								clearInterval(checkMonitoringAction);
							}
						});
					}
				}
			});
		}
	}, 1000);
});

$("#refresh_btn").on("click", function(e) {
	if(downloadTimer)
		clearInterval(downloadTimer);
	getReport();
	refreshInterval(counter);
});

$("#refresh_list").on("change", function(e) {
	counter = $(this).val();
	if(downloadTimer)
		clearInterval(downloadTimer);
	refreshInterval(counter);
});

$("#queue_list").on("change", function(e) {
	campaign_id = $("#campaign_list").val();
	queue_id = $(this).val();
	getReport(null, campaign_id, queue_id);
});

$("#campaign_list").on("change", function(e) {
	campaign_id = $(this).val();
	getQueues();
	getReport(null, campaign_id);
});

$("#campaign_type").on("change", function(e) {
	getCampaigns();
	getReport();
});

$(".view-more").on("click", function(){
	$(".circle-plus").toggleClass("opened");
	$(".campaign-stats").slideToggle();
});
  

');

?>
