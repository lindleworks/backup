<?php
//the following line use may vary depending on the location this handler utility is placed
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
//get the id for handling the record
$AuthenticationLogId = "";
if (isset($_POST["AuthenticationLogId"]))
{
	$AuthenticationLogId = $_POST["AuthenticationLogId"];
}

//get the method type
$Method = "";
if (isset($_POST["Method"])) 
{
	$Method = $_POST["Method"];
}
if ($Method == "save")
{
	//setup variables
	$Username = "";
	$IpAddress = "";
	$Successful = "";
	$DateCreated = "";
	if (isset($_POST["Username"]))
	{
		$Username = $_POST["Username"];
	}
	if (isset($_POST["IpAddress"]))
	{
		$IpAddress = $_POST["IpAddress"];
	}
	if (isset($_POST["Successful"]))
	{
		$Successful = $_POST["Successful"];
	}
	if (isset($_POST["DateCreated"]))
	{
		$DateCreated = $_POST["DateCreated"];
	}

	$LogAuthenticationFactory = new LogAuthenticationFactory();
	$newRecord = true;
	$timestamp = time();
	if ($AuthenticationLogId != "" && $AuthenticationLogId > 0)
	{
		$LogAuthentication = $LogAuthenticationFactory->GetOne($AuthenticationLogId);
		$newRecord = false;
	}
	else
	{
		$LogAuthentication = new LogAuthentication();
		$LogAuthentication->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
		$newRecord = true;
	}
	$LogAuthentication->Username = $Username;
	$LogAuthentication->IpAddress = $IpAddress;
	$LogAuthentication->Successful = $Successful;
	$LogAuthenticationFactory->Save($LogAuthentication);
	echo $LogAuthentication->AuthenticationLogId;
	exit();

}
else if ($Method == "delete")
{
	$LogAuthenticationFactory = new LogAuthenticationFactory();
	if ($AuthenticationLogId != "" && $AuthenticationLogId > 0)
	{
		$LogAuthentication = $LogAuthenticationFactory->GetOne($AuthenticationLogId);
	}
	if ($LogAuthentication->AuthenticationLogId > 0)
	{
		$LogAuthenticationFactory->Delete($LogAuthentication->AuthenticationLogId);
	}
	exit();

}
else if ($Method == "softDelete")
{

}
else if ($Method == "load")
{
	$LogAuthenticationFactory = new LogAuthenticationFactory();
	$Record = $LogAuthenticationFactory->GetOne($AuthenticationLogId);
	if ($AuthenticationLogId != "" && $AuthenticationLogId > 0)
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">AuthenticationLogId</span>
						<input type="text" id="txtAuthenticationLogId" name="txtAuthenticationLogId" class="form-control" value="<?php echo $Record->AuthenticationLogId; ?>" />
					</div> <!-- end AuthenticationLogId column -->
					<div class="col-md-3">
						<span class="form-label">Username</span>
						<input type="text" id="txtUsername" name="txtUsername" class="form-control" value="<?php echo $Record->Username; ?>" />
					</div> <!-- end Username column -->
					<div class="col-md-3">
						<span class="form-label">IpAddress</span>
						<input type="text" id="txtIpAddress" name="txtIpAddress" class="form-control" value="<?php echo $Record->IpAddress; ?>" />
					</div> <!-- end IpAddress column -->
					<div class="col-md-3">
						<span class="form-label">Successful</span>
						<input type="text" id="txtSuccessful" name="txtSuccessful" class="form-control" value="<?php echo $Record->Successful; ?>" />
					</div> <!-- end Successful column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">DateCreated</span>
						<input type="text" id="txtDateCreated" name="txtDateCreated" value="<?php $date = new DateTime($Record->DateCreated); echo $date->format('m/d/Y'); ?>" class="form-control date" />
					</div> <!-- end DateCreated column -->
				</div> <!-- end row -->
			</div> <!-- end container well -->
			<div class="container well button-container">
				<div class="row">
					<div class="col-md-12">
						<input type="button" value="Save" class="btn btn-primary default-button" onclick="Save()" /> <img src="<?php echo FULL_URL; ?>_core/img/ajax-loader.gif" alt="Loading" id="loader" style="vertical-align:middle;display:none;" /> <input type="button" class="btn btn-primary" onclick="HideEdit();" value="Cancel" />
						<div id="divOutput"></div>
					</div> <!-- end col-md-12 -->
				</div> <!-- end row -->
			</div> <!-- end container well button-container -->
		</div> <!-- end form -->
		<!-- Form End -->
		<?php
	}
	else
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">AuthenticationLogId</span>
						<input type="text" id="txtAuthenticationLogId" name="txtAuthenticationLogId" class="form-control" />
					</div> <!-- end AuthenticationLogId column -->
					<div class="col-md-3">
						<span class="form-label">Username</span>
						<input type="text" id="txtUsername" name="txtUsername" class="form-control" />
					</div> <!-- end Username column -->
					<div class="col-md-3">
						<span class="form-label">IpAddress</span>
						<input type="text" id="txtIpAddress" name="txtIpAddress" class="form-control" />
					</div> <!-- end IpAddress column -->
					<div class="col-md-3">
						<span class="form-label">Successful</span>
						<input type="text" id="txtSuccessful" name="txtSuccessful" class="form-control" />
					</div> <!-- end Successful column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">DateCreated</span>
						<input type="text" id="txtDateCreated" name="txtDateCreated" class="form-control date" />
					</div> <!-- end DateCreated column -->
				</div> <!-- end row -->
			</div> <!-- end container well -->
			<div class="container well button-container">
				<div class="row">
					<div class="col-md-12">
						<input type="button" value="Save" class="btn btn-primary default-button" onclick="Save()" /> <img src="<?php echo FULL_URL; ?>_core/img/ajax-loader.gif" alt="Loading" id="loader" style="vertical-align:middle;display:none;" /> <input type="button" class="btn btn-primary" onclick="HideEdit();" value="Cancel" />
						<div id="divOutput"></div>
					</div> <!-- end col-md-12 -->
				</div> <!-- end row -->
			</div> <!-- end container well button-container -->
		</div> <!-- end form -->
		<!-- Form End -->
		<?php
	}

}

?>