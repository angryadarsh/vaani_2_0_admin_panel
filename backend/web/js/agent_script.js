var hideloader          = '';

var inablecalls         = 'f'; // this variable identify agent have active call 
var customer_calleNo    = ''; // caller id
// this variable just check the sip connection stabalish with server. 
var siploginsych        = 0; // sip
var sipregister         = 1;
// this variable user to check CheckCallHangupStatus=hangupintvl,AgentLoginStatus=ALSintvl and reset timeout 
var hangupintvl         = '';
var ALSintvl            = '';

// this variable use to display dashboard and ready to call content
var displayReadytocall  = '';
// js event listener to check internet.
window.addEventListener('offline', () => {
    notification("#msg", "Your internet is disconnected.", "alert-warning"); 
});
 


// window.onbeforeunload = function() { return "Your work will be lost."; };

// Auther 			: 	Ashish Vishwakarma
// Date_created 	: 	02-Nov-2021
// description 		: 	agent releted all js scritps are here.

$(document).ready(function() {
    // hide loader
    hideloader = '1';
    // check user sip register status every second
    AgentLoginStatus();
});


// this function is send data by ajax.
function send_data_by_ajax(urls, datastring, callbackfn)
{
	$.ajax({
		url: urls,
		type: 'POST',	   
		crossDomain: true,
		data: datastring,
		success: function(responsedata) {            
			callbackfn(responsedata);
			
		}
	});
	
} 

// this function user to show vaani loader
function loadershow()
{
	$('#LoadingBox').css('display','flex');
    $('.dashboard-content').addClass('blur'); 
}

// this function user to hide the loader of vaani
function loaderhide()
{
	$('#LoadingBox').css('display','none'); 
	$('.dashboard-content').removeClass('blur'); 
}


// This function check user sip is synch with server
function AgentLoginStatus()
{
    // if its get fail status 10 time than logout the session
    if(siploginsych==10)
    {
        notification("#msg", "Your sip is not synch with server.", "alert-danger");
        setTimeout(function(){
                    window.location.href='../logout.php';
                  },3000);
    }
    
    if(ALSintvl)
    {
        clearInterval(ALSintvl);
    }
    let urls = "../ajax_agent.php";
    var datastring = 'action=agentsiplogin&sipregister='+sipregister;	
    send_data_by_ajax(urls,datastring, message => {
        var data = $.parseJSON(message);
        // console.log('AgentLoginStatus:'+data.result);
        if(data.result == 'success')
        {
            siploginsych = 0;
            sipregister  = 2;
            if(inablecalls == 'f')
            {
                inboundCall();
            }
            else if(inablecalls == 'i')
            {

            }          
        }
        else if(data.result =='empty_campaign')
        {
                
        }
        else
        {            				
            siploginsych++;
        }
        agentActionButtons(data.current_status);
        ALSintvl = setTimeout(function(){ AgentLoginStatus(); }, 1000);
      }
      
    );
       
}

// this function use to check inbound call for agent
function inboundCall()
{  
    let urls = "../ajax_agent.php";
    var datastring = 'action=inboundCall';	
    send_data_by_ajax(urls,datastring, message => {
        // console.log("inboundCall:"+message);
        var data = $.parseJSON(message);
        if(data.result=='incall')
        {            
            inablecalls         = 'i'; // indicate incall
            customer_calleNo    = data.caller_id;
            enableCRM('inbound');  
            CheckCallHangupStatus();          
        }
      }
    );
}

// this function use to check user have dispose this call
function CallHangupByAgent(obj)
{
    let urls = "../ajax_agent.php?CallHangupByAgent";
    var datastring = 'action=AgentHangupCall';	
    send_data_by_ajax(urls,datastring, message => {
        if(message=='hangup')
        {
            inablecalls = 'f'; // indicate incall  
            disableCRM(); 
            notification("#msg", "Call hang up.", "alert-success");    
            agent_activity_update(obj);                
        }
        else
        {
            notification("#msg", "Action not completed("+message+").", "alert-warning");
        }
      }
    );
}

// this function use to check user have dispose this call

function CheckCallHangupStatus(action='')
{
    console.log('CheckCallHangupStatus')
    if(hangupintvl)
    {
        clearInterval(hangupintvl);
    }

    let urls        = "../ajax_agent.php";
    var datastring  = 'action=HangupCallStatus';
    	
    send_data_by_ajax(urls,datastring, message => {
        console.log('CheckCallHangupStatus:'+message);
        if(message=='hangup')
        {
            // call hangup show on mobile view model.
            startTimmer('stop');
            $('.connection-time').html('Call Hangup..');          
        }
        else
        {
            hangupintvl = window.setInterval(function () {
                CheckCallHangupStatus('trigger');
            }, 1500);
        }
      }
    );   
}

// this function is use to Display the calling CRM
function enableCRM(type)
{
    console.log(type);
    $('.customer-info').css('display','');
    $('.hangup_btn').css('display',''); // this button is inside sidebar.
    $('.dashboard-content').css('display','none');
    // console.log('enableCRM:'+type);

    $('.sip-phone-action-btns-container').removeClass('disabled');
    $('.sip-phone').addClass('call-connent');
    $('.keypad-content, #agentDailerImgBox').hide();
    $('.call-connection, #crmFrom').show();

    if(type == 'inbound')
    {
        $('.sip-phone').removeClass('dialer-box');
        $('.dialed-number').html(customer_calleNo);
        $(".connection-time").html('Connected <span class="minute-box">00</span>: <span class="second-box">00</span>'); 
    }
    else if(type == 'manual')
    {  
        $('.sip-phone').removeClass("buzzing");
        $(".connection-time").html('Connected <span class="minute-box">00</span>: <span class="second-box">00</span>'); 
    }

    startTimmer();
    // while take the call reset display dashboard and readyto take call content check variable
    displayReadytocall = '';
}


// this function is use to Disable the calling CRM
function disableCRM()
{
    $('.sip-phone').addClass('dialer-box')
    $('.sip-phone-action-btns-container').addClass('disabled');
    $('.sip-phone').removeClass('call-connent');
    $('.keypad-content, #agentDailerImgBox').show();
    $('.call-connection, #crmFrom').hide();
    $('.customer-info').css('display','none');
    $('.dashboard-content').css('display','');
    $('.hangup_btn').css('display','none');

    // reset hold value.
    holdLiveCall('true');
}

function outboundCall()
{

}


// this function record all the activity log of user
function agent_activity_update(obj)
{   
    // if ($("#about").hasClass("opened")
    if (!$(obj).hasClass("disabled"))
    {
        $('.vaani_action').removeClass('disabled');
        $(obj).parent('label').addClass("disabled");
        let objval      = $(obj).val()  ;
        let objid       = $(obj).attr('id');
        console.log("agent_activity_update~"+objval+'~'+objid);
        let urls        = "../ajax_agent.php?act";
        var datastring  = 'action=AgentActivityUpdate&attr_val='+objval+'&attr_id='+objid;	
        send_data_by_ajax(urls,datastring, message => {
            // console.log(message);
            if(message=='success')
            {
                
            }
            else
            {
                notification("#msg", "Action not completed.", "alert-warning");
            }
        }
        );
    }    
}


// hide campaignlistmodal.
function viewDashboard() {
    var cams = $("#campaignList").val();
    if (cams == "") {
      notification("#msg", "Please select campaign", "alert-danger");
    }else{
        let urls = "../ajax_agent.php";
        let datastring = "action=AgentCampSelect&camp="+encodeURIComponent(cams);
        send_data_by_ajax(urls,datastring, message => {
                if(message=='success')
                {
                    notification("#msg", "User Campaign login registerd successfully", "alert-success");
                    $("#modalCampaign").remove();
                    $(".dashboard-content").removeClass("blur");
                    setTimeout(function(){
						window.location.reload();
						},1700);
                }
                else
                {
                    notification("#msg", "Invalid Campaign found", "alert-warning");
                    setTimeout(function(){
						window.location.reload();
						},1700);
                }
            }
        );

    }
  }


// as per the agent current status button active disable action trigger here  
function agentActionButtons(data)
{
    // console.log(data, data.id, data.status);

    if(data && data.id != undefined && data.id !='')
    {
        if(hideloader=='1')
        {
            loaderhide();
        }
        // $('.vaani_action').addClass('disabled');
        // user just loged-in in system
        if(data.id=='1' && data.status=='login')
        {
            
            $('.pasueBtn').addClass('active');
            $('.pasueBtn').addClass('disabled');
            // check button is disabled 
            if (!$('.readybtn').hasClass("disabled"))
            {
                 $('#readybtn').attr('onclick','agent_activity_update(this)') 
            }            
        }
        else if(data.id=='3' && data.status=='ready')
        { // user on ready mode.
            
            $('.readybtn').addClass('active');
            $('.readybtn').addClass('disabled');

            $('.pasueBtn').removeClass('disabled');
            $('.pasueBtn').removeClass('active');
            $('#readybtn').removeAttr('onclick');
            // check button is disabled 
            if (!$('.pasueBtn').hasClass("disabled"))
            {                
                $('#pasueBtn').attr('onclick','agent_activity_update(this)') 
            } 
            
            displayready();
        }
        else if(data.id=='4' && data.status=='pause')
        {
            $('.readybtn').removeClass('disabled');
            $('.readybtn').removeClass('active');
            $('#readybtn').removeAttr('onclick');
            $('.pasueBtn').addClass('disabled');
            $('.pasueBtn').addClass('active');

            if (!$('.readybtn').hasClass("disabled"))
            {
                 $('#readybtn').attr('onclick','agent_activity_update(this)') 
            }
        }
        else if(data.id=='5' && data.status=='incall')
        {
            $('.readybtn').addClass('disabled');
            $('.readybtn').removeClass('active');
            $('#readybtn').removeAttr('onclick');
            
            $('.pasueBtn').addClass('disabled');
            $('.pasueBtn').removeClass('active');
            $('#pasueBtn').removeAttr('onclick');
        }
        else
        {

        }
    }
}  

// Not ready 
$('#readybtn').click(function(){
    $(".not-ready-reason-container").hide();
    $(".keypad-content").show();
    $("#call-action-btn").html("<span class='line-1 anim-typewriter font-lg'>I'm <span class='text-success'>ready</span> to take a call.</span>");
    $("#call-action-btn").removeClass("mt-3");
});

$('#pasueBtn').click(function(){
    $(".not-ready-reason-container").show();
    $(".keypad-content").hide();
    $("#call-action-btn").html("I'm <span class='text-danger'>not ready</span> <span class='line-1 anim-typewriter'>  to take a call. Click Ready Button.</span>");
    $("#call-action-btn").addClass("mt-3");
});

// function use to display agent dashboard 
function displaydashboard()
{
    var act = arguments[0];
    if(act == 'back')
    {
        displayReadytocall = 'true';
    }
    $('.dashboard-content').show();
    $('.customer-info').hide();
}

// function used to display agent is ready to take the call and dialer
function displayready()
{
    var act = arguments[0];
    if(act =='true')
    {
        displayReadytocall ='';    
    }

    if(displayReadytocall !='true')
    {
        $('.dashboard-content').hide();
        $('.customer-info').show();
    }    
}