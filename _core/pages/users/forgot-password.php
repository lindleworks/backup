<div class="container">
  <form id="form-login" class="form-signin">
  	<h1 style="text-align:center;">Reset Password</h1>
  	<span id="lblResponse"></span>
  	<div class="form-group">
    	<input type="text" id="txtUsername" class="form-control required" placeholder="Username/Email" autofocus>
  	</div>
    <button class="btn btn-lg btn-grey btn-grey-lg btn-block default-button" onclick="ajaxSendPassword()" type="button">RESET PASSWORD</button>
    <a class="btn btn-lg btn-grey btn-grey-lg btn-block" href="<?php echo FULL_URL; ?>">BACK TO LOGIN</a>
  </form>
  <script type="text/javascript">
	$(document).ready(function() {
		$('#form-login').jvalidate({
			
		});
	});
	function ajaxSendPassword() {
		$.post('<?php echo FULL_URL; ?>_core/handlers/User.ResetPassword.php', { username: $('#txtUsername').val()  }, function(output) {
			$('#lblResponse').html(output).show();
		});
	}
</script>
</div> <!-- /container -->