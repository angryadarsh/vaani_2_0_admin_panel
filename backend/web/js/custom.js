
$(document).ready(function() {
	$("#submitForm").submit(function(e) {

	    /*e.preventDefault(); // avoid to execute the actual submit of the form.

	    var form = $(this);
	    var actionUrl = form.attr('action');
	    
	    $.ajax({
	        type: "POST",
	        url: actionUrl,
	        data: form.serialize(), // serializes the form's elements.
	        beforeSend: function(data)
	        {
	          $("#LoadingBox").show(); // show response from the php script.
	        },
	        success :function(data){
	        	location.reload();
	        },
	        complete :function(){
	        	$("#LoadingBox").hide();
	        }
	    });*/
	});
});