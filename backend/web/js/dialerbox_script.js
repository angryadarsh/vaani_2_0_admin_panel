// variable user to check call hold and unhold
var vaani_hold_check = '';
// Timer
var intervalId=''; 
var counter = 1;
var seconds = "00";
var minutes = "00";
const getTimerMinutes = (counter) => {
  let minuteCounter = Math.floor(counter / 60);
  return minuteCounter < 10 ? `0${minuteCounter}` : `${minuteCounter}`;
};

const getTimerSeconds = (counter) => {
  let secondCounter = counter % 60;
  $(".second-box").html(secondCounter < 10 ? `0${secondCounter}` : `${secondCounter}`);
  $(".second-box").html(1);
  return secondCounter < 10 ? `0${secondCounter}` : `${secondCounter}`;
};
const setCount = () => {
  console.log("here");
  seconds = getTimerSeconds(counter);
  minutes = getTimerMinutes(counter);

  $(".minute-box").html(seconds);
  counter = counter + 1;
};

// this function use to show timmer count inside mobiel view
function startTimmer(){
  let action  = arguments[0];
  counter     = 1;
  seconds     = "00";
  minutes     = "00";  
  if(intervalId)
  {
    clearInterval(intervalId);
  }
  if(action!='stop')
  {
    intervalId  = window.setInterval(function () {
    seconds     = getTimerSeconds(counter);
    minutes     = getTimerMinutes(counter);
    $(".minute-box").html(minutes);
    $(".second-box").html(seconds);
    counter     = counter + 1;
  }, 1000);
  }
}


// this js script use to call hold unhold action.
$(".sip-phoneBtn").click(function()
{ 
  $(this).toggleClass("active");

  let textid  = $(this).siblings(".sip-phone-text");
  let text    = textid.text();

  console.log('sip-btn:'+text);
// for mute
  if(text === "Mute"){
    textid.text("Unmute");   
  }
  else if(text === "Unmute"){
    textid.text("Mute");    
  }

  // for hold
  if(text === "Hold"){
    textid.text("Unhold");
     // call hold function and chenge checker variable value
     vaani_hold_check = 'true';
     holdLiveCall();
  }
  else if(text === "Unhold"){
    textid.text("Hold");
    // call unhold function and chenge checker variable value
    vaani_hold_check = 'false';
    unholdLiveCall();
  }

  // for consult
  if(text === "Consult"){
    textid.text("End Consult");
  }
  else if(text === "End Consult"){
    textid.text("Consult");
  }

  // for transfer
  if(text === "Transfer"){
    textid.text("End Transfer");
    transfer_list();
  }
  else if(text === "End Transfer"){
    textid.text("Transfer");
  }
});

//function to hold the live call
function holdLiveCall(){
  var chk = arguments[0];
  console.log('holdLiveCall:'+chk);
  if(chk=='true')
  {
    vaani_hold_check = 'false';
  }
  
  if(vaani_hold_check == 'true')
  {
    let urls = "../ajax_agent.php?hold";
    var datastring = 'action=holdcall';
    send_data_by_ajax(urls,datastring, message => {
      if(message == 'success')
      {
        setTimeout(function(){ holdLiveCall(); }, 15500);
        notification("#msg", "Call on hold.", "alert-warning");
      }
    });
  }
}

// function to unhold the live call
function unholdLiveCall()
{
  vaani_hold_check  = 'false';
  let urls          = "../ajax_agent.php?unhold";
    var datastring  = 'action=unholdcall';
    send_data_by_ajax(urls,datastring, message => {
      if(message == 'success')
      {        
        notification("#msg", "Call unhold.", "alert-success");
      }
    });
}


// this function only change transfer css effect after click on transfer
function transfercss(obj){
  let chk = $(obj).siblings(".sip-phone-text").text();
  console.log("transfercss:"+chk);
  if(chk == 'Transfer')
  {
    $(".call-connection").hide();
    $(".add-contact-box, .connected-client-box").show();
    $(".sip-phone").addClass("dialer-box");
    $(".sip-phone").removeClass("call-connent");
  }
}


// function to display the all the list of agent to transfer the call
function transfer_list()
{
  
}


// ============================================================= UI PART krunanl  =========================================================

//-------------
  //- DONUT CHART -
  //-------------
  // Get context with jQuery - using jQuery's .get() method.
  var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
  var donutData        = {
    labels: [
        'Series-1',
        'Series-2',
        'Series-3',
        'Series-4',
    ],
    datasets: [
      {
        data: [700,500,400,600],
        backgroundColor : ['#008ffb', '#00e396', '#feb019', '#ff4560', '#3c8dbc', '#d2d6de'],
      }
    ]
  }
  var donutOptions     = {
    maintainAspectRatio : false,
    responsive : true,
  }
  //Create pie or douhnut chart
  // You can switch between pie and douhnut using the method below.
  new Chart(donutChartCanvas, {
    type: 'doughnut',
    data: donutData,
    options: donutOptions
  })

  //-------------
  //- LINE CHART -
  //--------------
  var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
  // var lineChartOptions = $.extend(true, {}, areaChartOptions)
  // var lineChartData = $.extend(true, {}, areaChartData)
  // lineChartData.datasets[0].fill = false;
  // lineChartData.datasets[1].fill = false;
  // lineChartOptions.datasetFill = false

  var lineChart = new Chart(lineChartCanvas, {
    type: 'line',
    // data: lineChartData,
    // options: lineChartOptions
  })
// ---------------------------------------------------------------------------------------------------------------

$("#dialerBtn").click(function () {
  $(".dashboard-content").hide();
  $(".customer-info").slideDown();
  $("#dialerInput").focus();
});
$("#dialerInput").keypress(function (e) {
  // alert("Hi");
  var charCode = (e.which) ? e.which : event.keyCode    
  if (String.fromCharCode(charCode).match(/[^0-9]/g))    
  return false;
});

$(".number").click(function () {
  let text = $(this).text();
  var val = $('#dialerInput').val();
  if (val.length < 10) {
    $("#dialerInput").val(val+text).focus();
  }
});

$(".remove-btn").click(function () {
  var val = $('#dialerInput').val();
  var tmp= val.slice(0,-1);
  $("#dialerInput").val(tmp).focus();
});


$("#dialerInput").keypress(function(event){
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if(keycode == '13'){ 
    phoneValidation();
  }
});
function callDialedNumber() {
  startTimmer('stop');
  phoneValidation() 
}

// trigger manual dial ajax in this function
function phoneValidation() {
  var val = $('#dialerInput').val();
  $('#dialerInput').val('');
  if (val.length < 10) 
  {
    notification("#msg", "Please enter valid number", "alert-danger");
  }
  else
  {
    notification("#msg", "Connenting...", "alert-warning");
    $(".keypad-content, #agentDailerImgBox").hide();
    $(".call-connection").show();
    $(".sip-phone").removeClass("dialer-box");
    $(".sip-phone").addClass("call-connent buzzing");
    $(".dialed-number").html(val);
    $("#crmFrom").slideDown();

    // setTimeout(function(){ 
      $(".connection-time").html('Connenting...');
    //   $('.sip-phone').removeClass("buzzing");
    //   $('.sip-phone-action-btns-container').removeClass("disabled");
    //   notification("#msg", "Call connected", "alert-success");
    // }, 5000);

    // call ajax for manual dial trigger.
    let urls = "../ajax_agent.php?manual";
    var datastring = 'action=manual_dial&ID='+val;
    send_data_by_ajax(urls,datastring, message => {
      if(message == 'success')
      {
        // check manual dial is connected.
        CheckManualDial_connect();
      }
      else
      {
        notification("#msg", message, "alert-warning");
      }
    });

  } 
}

// show contact div 
function addContact(){
  $(".call-connection").hide();
  $(".add-contact-box, .connected-client-box").show();
  $(".sip-phone").addClass("dialer-box");
  $(".sip-phone").removeClass("call-connent");
}

$(".connected-client-box").click(function(){
  $(".call-connection").show();
  $(".sip-phone").removeClass("dialer-box");
  $(".sip-phone").addClass("call-connent");
  $(".add-contact-box, .connected-client-box").hide();
});

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ END UI krunal ++++++++++++++++++++++++++++++++++++++++++++++

function CheckManualDial_connect()
{  
  console.log('CheckManualDial_connect');
  let urls = "../ajax_agent.php?manual_check";
  var datastring = 'action=manualDial_check';
  send_data_by_ajax(urls,datastring, message => {
    var data = $.parseJSON(message);
    if(data.result=='incall')
    {  
      customer_calleNo    = data.caller_id;
      enableCRM('manual');  
      CheckCallHangupStatus();          
    }
    else
    {
      setTimeout(function(){CheckManualDial_connect('action');},1000);
    }
  });
}
