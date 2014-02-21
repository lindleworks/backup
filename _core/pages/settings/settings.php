<!-- Form Begin -->
<?php
$UserFactory = new UserFactory();
$Record = $UserFactory->GetOne($_SESSION['LoggedUserId']);
?>
<div class="container well well-sm">
	<h1>System Setting</h1>
</div>
<div id="frmMain">
	<div class="container well">
		<div class="row">
			<input type="hidden" id="hdnUserId" name="hdnUserId" class="form-control" value="<?php echo $Record->UserId; ?>" />
			<div class="col-md-3">
				<span class="form-label">Username *</span> <img src="<?php echo FULL_URL; ?>_core/img/ajax-loader.gif" alt="Loading" id="loaderUsername" style="vertical-align:middle;display:none;" /><span id="lblResponseUsername"></span>
				<input type="text" id="txtUsername" name="txtUsername" onchange="ajaxCheckUsername();" class="required form-control" value="<?php echo $Record->Username; ?>" />
				<input type="hidden" id="hdnUsernameValid" class="required" requiredby="txtUsername" value="<?php echo $Record->Username; ?>" />
		        <input type="hidden" id="hdnUsername" value="<?php echo $Record->Username; ?>" />
			</div> <!-- end Username column -->
			<div class="col-md-3">
				<span class="form-label">Password *</span>
				<input type="password" id="txtPassword" name="txtPassword" class="form-control" />
			</div> <!-- end Password column -->
			<div class="col-md-3">
				<span class="form-label">Email *</span> <img src="<?php echo FULL_URL; ?>_core/img/ajax-loader.gif" alt="Loading" id="loaderEmail" style="vertical-align:middle;display:none;" /><span id="lblResponseEmail"></span>
				<input type="text" id="txtEmail" name="txtEmail" onchange="ajaxCheckEmailAddress();" class="required form-control" value="<?php echo $Record->Email; ?>" /> 
				<input type="hidden" id="hdnEmailValid" class="required" requiredby="txtEmail" value="<?php echo $Record->Email; ?>" />
		        <input type="hidden" id="hdnEmailAddress" value="<?php echo $Record->Email; ?>" />
			</div> <!-- end Email column -->
		</div> <!-- end row -->
		<div class="row">
			<div class="col-md-3">
				<span class="form-label">Firstname *</span>
				<input type="text" id="txtFirstname" name="txtFirstname" class="required form-control" value="<?php echo $Record->Firstname; ?>" />
			</div> <!-- end Firstname column -->
			<div class="col-md-3">
				<span class="form-label">Lastname *</span>
				<input type="text" id="txtLastname" name="txtLastname" class="required form-control" value="<?php echo $Record->Lastname; ?>" />
			</div> <!-- end Lastname column -->
		</div> <!-- end row -->
		<div class="row">
			<div class="col-md-12">
				<span class="form-label">Generic Cron Job Setup (every 5 minutes)</span>
				<label style="display:block;" id="lblCron" name="lblCron"><?php echo "*/5 * * * * php -q " . ROOT . "cron.php"; ?></label>
			</div> <!-- end column -->
		</div> <!-- end row -->
	</div> <!-- end container well -->
	<div class="container well button-container">
		<div class="row">
			<div class="col-md-12">
				<input type="button" value="Save" class="btn btn-primary default-button" onclick="Save()" /> <img src="<?php echo FULL_URL; ?>_core/img/ajax-loader.gif" alt="Loading" id="loader" style="vertical-align:middle;display:none;" />
				<div id="divOutput"></div>
			</div> <!-- end col-md-12 -->
		</div> <!-- end row -->
	</div> <!-- end container well button-container -->
</div> <!-- end form -->
<!-- Form End -->
<script type="text/javascript">
	$(document).ready(function() {
		$('#frmMain').jvalidate();
	});
	function ClearFields() {
		$('#hdnUserId').val('');
		$('#txtUsername').val('');
		$('#txtPassword').val('');
		$('#txtFirstname').val('');
		$('#txtLastname').val('');
		$('#txtEmail').val('');
	}
	function Save() {
		$('#loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/User.ajax.handler.php', {
			Method: 'save',
			UserId: $('#hdnUserId').val(),
			Username: $('#txtUsername').val(),
			Password: $('#txtPassword').val(),
			Firstname: $('#txtFirstname').val(),
			Lastname: $('#txtLastname').val(),
			Email: $('#txtEmail').val()
		}, function(output) {
			if (parseInt(output) > 0) {
				message = "<div class=\"alert alert-success\">Save successful!</div>";
				$('#divOutput').html(message).slideDown().delay(3000).slideUp();
			}
			else {
				message = "<div class=\"alert alert-danger\">Save failed!</div>";
				$('#divOutput').html(message).slideDown().delay(3000).slideUp();
			}
			$('#loader').hide();
		});
	}
	function Delete(Id) {
		$('#loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/User.ajax.handler.php', {
			Method: 'delete',
			UserId: Id
		}, function(output) {
			$('#divOutput').html(output).show();
			$('#loader').hide();
		});
	}
	function ajaxCheckUsername() {
		if (ValidateUsername()) {
			$('#loaderUsername').show();
			$.post('<?php echo FULL_URL; ?>_core/handlers/User.VerifyUsername.php', { username: $('#txtUsername').val() }, function(output) {
				if (output.indexOf("Not Available!") > -1) {
					$('#hdnUsernameValid').val('');
				}
				else {
					$('#hdnUsernameValid').val('true');
				}
				$('#lblResponseUsername').html(output).show();
				$('#loaderUsername').hide();
			});
		}
	}
    function ValidateUsername() 
	{
		var errors = 0;
        if ($('#txtUsername').val() == "") {
			errors += 1;
		}
        if ($('#txtUsername').val() == $('#hdnUsername').val()) {
            errors += 1;
            $('#hdnUsernameValid').val('true');
            $('#lblResponseUsername').hide();
        }
		if (errors == 0) {
			return true;
		}
		else {
			return false;
		}
	}
    function ajaxCheckEmailAddress() {
		if (ValidateEmailAddress()) {
			$('#loaderEmail').show();
			$.post('<?php echo FULL_URL; ?>_core/handlers/User.VerifyEmail.php', { emailaddress: $('#txtEmailAddress').val() }, function(output) {
				if (output.indexOf("Not Available!") > -1) {
					$('#hdnEmailValid').val('');
				}
				else {
					$('#hdnEmailValid').val('true');
				}
				$('#lblResponseEmail').html(output).show();
				$('#loaderEmail').hide();
			});
		}
	}
    function ValidateEmailAddress() 
	{
		var errors = 0;
        if ($('#txtEmailAddress').val() == "") {
			errors += 1;
		}
        if ($('#txtEmailAddress').val() == $('#hdnEmailAddress').val()) {
            errors += 1;
            $('#hdnEmailValid').val('true');
            $('#lblResponseEmail').hide();
        }
		if (errors == 0) {
			return true;
		}
		else {
			return false;
		}
	}
</script>
