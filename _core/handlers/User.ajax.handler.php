<?php
//the following line use may vary depending on the location this handler utility is placed
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
//get the id for handling the record
$UserId = "";
if (isset($_POST["UserId"]))
{
	$UserId = $_POST["UserId"];
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
	$Password = "";
	$Email = "";
	$Firstname = "";
	$Lastname = "";
	if (isset($_POST["Username"]))
	{
		$Username = $_POST["Username"];
	}
	if (isset($_POST["Password"]))
	{
		$Password = $_POST["Password"];
	}
	if (isset($_POST["Email"]))
	{
		$Email = $_POST["Email"];
	}
	if (isset($_POST["Firstname"]))
	{
		$Firstname = $_POST["Firstname"];
	}
	if (isset($_POST["Lastname"]))
	{
		$Lastname = $_POST["Lastname"];
	}

	$UserFactory = new UserFactory();
	$newRecord = true;
	$timestamp = time();
	if ($UserId != "" && $UserId > 0)
	{
		$User = $UserFactory->GetOne($UserId);
		$newRecord = false;
	}
	else
	{
		$User = new User();
		$newRecord = true;
	}
	$User->Username = $Username;
	if ($Password != "")
	{
		$User->Password = md5($Password);
	}
	$User->Email = $Email;
	$User->Firstname = $Firstname;
	$User->Lastname = $Lastname;
	$UserFactory->Save($User);
	$_SESSION['LoggedUserId'] = $User->UserId;
    $_SESSION['LoggedUsername'] = $User->Username;
    $_SESSION['LoggedFirstname'] = $User->Firstname;
    $_SESSION['LoggedLastname'] = $User->Lastname;
    $_SESSION['LoggedEmail'] = $User->Email;
	echo $User->UserId;
	exit();

}
else if ($Method == "delete")
{
	$UserFactory = new UserFactory();
	if ($UserId != "" && $UserId > 0)
	{
		$User = $UserFactory->GetOne($UserId);
	}
	if ($User->UserId > 0)
	{
		$UserFactory->Delete($User->UserId);
	}
	exit();

}
else if ($Method == "softDelete")
{

}
else if ($Method == "load")
{
	$UserFactory = new UserFactory();
	$Record = $UserFactory->GetOne($UserId);
	if ($UserId != "" && $UserId > 0)
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">UserId</span>
						<input type="text" id="txtUserId" name="txtUserId" class="form-control" value="<?php echo $Record->UserId; ?>" />
					</div> <!-- end UserId column -->
					<div class="col-md-3">
						<span class="form-label">Username</span>
						<input type="text" id="txtUsername" name="txtUsername" class="form-control" value="<?php echo $Record->Username; ?>" />
					</div> <!-- end Username column -->
					<div class="col-md-3">
						<span class="form-label">Password</span>
						<input type="text" id="txtPassword" name="txtPassword" class="form-control" value="<?php echo $Record->Password; ?>" />
					</div> <!-- end Password column -->
					<div class="col-md-3">
						<span class="form-label">Email</span>
						<input type="text" id="txtEmail" name="txtEmail" class="form-control" value="<?php echo $Record->Email; ?>" />
					</div> <!-- end Email column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Firstname</span>
						<input type="text" id="txtFirstname" name="txtFirstname" class="form-control" value="<?php echo $Record->Firstname; ?>" />
					</div> <!-- end Firstname column -->
					<div class="col-md-3">
						<span class="form-label">Lastname</span>
						<input type="text" id="txtLastname" name="txtLastname" class="form-control" value="<?php echo $Record->Lastname; ?>" />
					</div> <!-- end Lastname column -->
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
						<span class="form-label">UserId</span>
						<input type="text" id="txtUserId" name="txtUserId" class="form-control" />
					</div> <!-- end UserId column -->
					<div class="col-md-3">
						<span class="form-label">Username</span>
						<input type="text" id="txtUsername" name="txtUsername" class="form-control" />
					</div> <!-- end Username column -->
					<div class="col-md-3">
						<span class="form-label">Password</span>
						<input type="text" id="txtPassword" name="txtPassword" class="form-control" />
					</div> <!-- end Password column -->
					<div class="col-md-3">
						<span class="form-label">Email</span>
						<input type="text" id="txtEmail" name="txtEmail" class="form-control" />
					</div> <!-- end Email column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Firstname</span>
						<input type="text" id="txtFirstname" name="txtFirstname" class="form-control" />
					</div> <!-- end Firstname column -->
					<div class="col-md-3">
						<span class="form-label">Lastname</span>
						<input type="text" id="txtLastname" name="txtLastname" class="form-control" />
					</div> <!-- end Lastname column -->
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