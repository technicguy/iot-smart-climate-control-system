$(document).ready(function(){
	  $('#result').html("<div class='preloader'></div>");
	 if(localStorage.getItem("login")=="true" ){
		getURL("include/iot.php","#result");  	
	 }else{
		getURL("include/login.php","#result");	
	 }	 
				  
});	

	function getStatus(id){
			$.ajax({
					url: 'include/get_sw_info.php?id='+id+'_sw',
					 type: 'GET',
					 dataType: 'json',					 
					success: function (response) {											
					$('#'+id+'-mn input').bootstrapToggle(response.value, true);					
					}
				});			
		
	}			
	function getURL(url, result){		
		$(result).load( url, function( response, status, xhr ) {
		  if ( status == "error" ) {
			var msg = "Sorry but there was an error: ";
			$( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
		  }
		  
		});				
	}	
	function notify(types, titles, msg){
		$.notify({
			icon: 'fa fa-bell',
			title: '<strong>'+ titles +'</strong>',
			message: msg,

		},{
			type: types,
			animate: {
				enter: 'animated fadeInRight',
				exit: 'animated fadeOutRight'
			},
			delay: 5000,
			time: 1000,
		});		
		
	}	
	function notifyUp(types, titles, msg){		
		var notify = $.notify('<i class="fa fa-bell"></i> <strong>Please wait for </strong>Saving...', {
			allow_dismiss: false,
			showProgressbar: true,
			time: 500,
		});

		setTimeout(function() {
			notify.update({'type': types, 'message': '<i class="fa fa-bell"></i>  <strong>'+ titles +':</strong>' +msg, 'progress': 25});
		}, 500);	
		
	}