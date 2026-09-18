<?php 
//echo hash('sha256', 'esanshar');
?>
<div class="container p-5 login-pannel">
<div class="card">
  <div class="card-body">
<form action="" method="POST" id="login">
  <div class="form-group">
    <label for="user">User ID:</label>

	 <div class="input-group mb-3">
		<div class="input-group-prepend">
		  <span class="input-group-text">🛡️ </span>
		</div>
		<input type="text" class="form-control" placeholder="Enter user" name="user" id="user">
	</div>
	
	
  </div>
  <div class="form-group">
    <label for="pwd">Password:</label>
    
 <div class="input-group mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">🔑</span>
    </div>
   <input type="password" class="form-control" placeholder="Enter password" name="pwd" id="pwd">
</div>	
	
	
	
  </div>

  <input type="submit" class="btn btn-primary" value="Submit" id="loginbtn">
</form>
 </div> </div> 
 </div>
<script type="text/javascript">
	$(document).ready(function(){
		$('form#login').on('submit', function (e) {
			$("#loginbtn").attr("disabled", true);
			
			e.preventDefault();
			var user = $("#user").val();
			var pwd = $("#pwd").val();
			$.ajax({
					url: 'include/check_login.php',
					type: 'POST',
					data: $('form').serialize(),
					beforeSend: function () {
						$('.loading-overlay').show();						
					},
					complete: function() {
						$('.loading-overlay').hide();
						$("#loginbtn").attr("disabled", false);
					},					
					success: function (response) {
						
						var obj = JSON.parse(response);
						//console.log(obj);
								if(obj.type=="success"){
									localStorage.setItem("login", "true");
									localStorage.setItem("user", user);
									localStorage.setItem("pwd", pwd);
									notify('success', 'Login Process', obj.msg);
									location.reload();									
									getURL("iot.php","#result");									
								}else{
									localStorage.setItem("login", "false");
									localStorage.removeItem("user");
									localStorage.removeItem("pwd");
									notify('danger', 'ERROR', obj.msg);
								}
									
					}

				});	
		});

	});

</script>