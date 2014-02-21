<div class="container">
	<form id="form-login" class="form-signin">
		<h1 style="text-align:center;">Sign In</h1>
		<span id="lblResponse"></span>
		<div class="form-group">
			<input type="text" id="txtUsername" class="form-control required" placeholder="Username" autofocus>
		</div>
		<div class="form-group">
			<input type="password" id="txtPassword" class="form-control required" placeholder="Password">
		</div>
		<button class="btn btn-lg btn-grey btn-grey-lg btn-block default-button" onclick="ajaxAuthenticate()" type="button">LOGIN</button>
		<a class="btn btn-lg btn-grey btn-grey-lg btn-block" href="<?php echo FULL_URL; ?>forgot-password.php">FORGOT PASSWORD?</a>
	</form>
</div> <!-- /container -->
<script type="text/javascript">
$(document).ready(function() {
	$('#form-login').jvalidate();
});
function ajaxAuthenticate() {
	$.post('<?php echo FULL_URL; ?>_core/handlers/User.Authenticate.php', { username: $('#txtUsername').val(), password: $('#txtPassword').val()  }, function(output) {
		$('#lblResponse').html(output).show();
	});
}
</script>