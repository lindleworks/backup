<?php
//the following line use may vary depending on the location this handler utility is placed
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
//get the id for handling the record
$EmailLogId = "";
if (isset($_POST["EmailLogId"]))
{
	$EmailLogId = $_POST["EmailLogId"];
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
	$DateSent = "";
	$ToEmails = "";
	$CcEmails = "";
	$BccEmails = "";
	$FromEmail = "";
	$ReplyEmail = "";
	$Subject = "";
	$Message = "";
	$Successful = "";
	$SmtpHost = "";
	$SmtpUsername = "";
	if (isset($_POST["DateSent"]))
	{
		$DateSent = $_POST["DateSent"];
	}
	if (isset($_POST["ToEmails"]))
	{
		$ToEmails = $_POST["ToEmails"];
	}
	if (isset($_POST["CcEmails"]))
	{
		$CcEmails = $_POST["CcEmails"];
	}
	if (isset($_POST["BccEmails"]))
	{
		$BccEmails = $_POST["BccEmails"];
	}
	if (isset($_POST["FromEmail"]))
	{
		$FromEmail = $_POST["FromEmail"];
	}
	if (isset($_POST["ReplyEmail"]))
	{
		$ReplyEmail = $_POST["ReplyEmail"];
	}
	if (isset($_POST["Subject"]))
	{
		$Subject = $_POST["Subject"];
	}
	if (isset($_POST["Message"]))
	{
		$Message = $_POST["Message"];
	}
	if (isset($_POST["Successful"]))
	{
		$Successful = $_POST["Successful"];
	}
	if (isset($_POST["SmtpHost"]))
	{
		$SmtpHost = $_POST["SmtpHost"];
	}
	if (isset($_POST["SmtpUsername"]))
	{
		$SmtpUsername = $_POST["SmtpUsername"];
	}

	$LogEmailFactory = new LogEmailFactory();
	$newRecord = true;
	$timestamp = time();
	if ($EmailLogId != "" && $EmailLogId > 0)
	{
		$LogEmail = $LogEmailFactory->GetOne($EmailLogId);
		$newRecord = false;
	}
	else
	{
		$LogEmail = new LogEmail();
		$newRecord = true;
	}
	$LogEmail->DateSent = $DateSent;
	$LogEmail->ToEmails = $ToEmails;
	$LogEmail->CcEmails = $CcEmails;
	$LogEmail->BccEmails = $BccEmails;
	$LogEmail->FromEmail = $FromEmail;
	$LogEmail->ReplyEmail = $ReplyEmail;
	$LogEmail->Subject = $Subject;
	$LogEmail->Message = $Message;
	$LogEmail->Successful = $Successful;
	$LogEmail->SmtpHost = $SmtpHost;
	$LogEmail->SmtpUsername = $SmtpUsername;
	$LogEmailFactory->Save($LogEmail);
	echo $LogEmail->EmailLogId;
	exit();

}
else if ($Method == "delete")
{
	$LogEmailFactory = new LogEmailFactory();
	if ($EmailLogId != "" && $EmailLogId > 0)
	{
		$LogEmail = $LogEmailFactory->GetOne($EmailLogId);
	}
	if ($LogEmail->EmailLogId > 0)
	{
		$LogEmailFactory->Delete($LogEmail->EmailLogId);
	}
	exit();

}
else if ($Method == "softDelete")
{

}
else if ($Method == "load")
{
	$LogEmailFactory = new LogEmailFactory();
	$Record = $LogEmailFactory->GetOne($EmailLogId);
	if ($EmailLogId != "" && $EmailLogId > 0)
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">EmailLogId</span>
						<input type="text" id="txtEmailLogId" name="txtEmailLogId" class="form-control" value="<?php echo $Record->EmailLogId; ?>" />
					</div> <!-- end EmailLogId column -->
					<div class="col-md-3">
						<span class="form-label">DateSent</span>
						<input type="text" id="txtDateSent" name="txtDateSent" value="<?php $date = new DateTime($Record->DateSent); echo $date->format('m/d/Y'); ?>" class="form-control date" />
					</div> <!-- end DateSent column -->
					<div class="col-md-3">
						<span class="form-label">ToEmails</span>
						<input type="text" id="txtToEmails" name="txtToEmails" class="form-control" value="<?php echo $Record->ToEmails; ?>" />
					</div> <!-- end ToEmails column -->
					<div class="col-md-3">
						<span class="form-label">CcEmails</span>
						<input type="text" id="txtCcEmails" name="txtCcEmails" class="form-control" value="<?php echo $Record->CcEmails; ?>" />
					</div> <!-- end CcEmails column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">BccEmails</span>
						<input type="text" id="txtBccEmails" name="txtBccEmails" class="form-control" value="<?php echo $Record->BccEmails; ?>" />
					</div> <!-- end BccEmails column -->
					<div class="col-md-3">
						<span class="form-label">FromEmail</span>
						<input type="text" id="txtFromEmail" name="txtFromEmail" class="form-control" value="<?php echo $Record->FromEmail; ?>" />
					</div> <!-- end FromEmail column -->
					<div class="col-md-3">
						<span class="form-label">ReplyEmail</span>
						<input type="text" id="txtReplyEmail" name="txtReplyEmail" class="form-control" value="<?php echo $Record->ReplyEmail; ?>" />
					</div> <!-- end ReplyEmail column -->
					<div class="col-md-3">
						<span class="form-label">Subject</span>
						<input type="text" id="txtSubject" name="txtSubject" class="form-control" value="<?php echo $Record->Subject; ?>" />
					</div> <!-- end Subject column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Message</span>
						<textarea id="txtMessage" name="txtMessage" class="form-control" placeholder="Enter Message" style="height:160px;"><?php echo $Record->Message; ?></textarea>
					</div> <!-- end Message column -->
					<div class="col-md-3">
						<span class="form-label">Successful</span>
						<input type="text" id="txtSuccessful" name="txtSuccessful" class="form-control" value="<?php echo $Record->Successful; ?>" />
					</div> <!-- end Successful column -->
					<div class="col-md-3">
						<span class="form-label">SmtpHost</span>
						<input type="text" id="txtSmtpHost" name="txtSmtpHost" class="form-control" value="<?php echo $Record->SmtpHost; ?>" />
					</div> <!-- end SmtpHost column -->
					<div class="col-md-3">
						<span class="form-label">SmtpUsername</span>
						<input type="text" id="txtSmtpUsername" name="txtSmtpUsername" class="form-control" value="<?php echo $Record->SmtpUsername; ?>" />
					</div> <!-- end SmtpUsername column -->
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
						<span class="form-label">EmailLogId</span>
						<input type="text" id="txtEmailLogId" name="txtEmailLogId" class="form-control" />
					</div> <!-- end EmailLogId column -->
					<div class="col-md-3">
						<span class="form-label">DateSent</span>
						<input type="text" id="txtDateSent" name="txtDateSent" class="form-control date" />
					</div> <!-- end DateSent column -->
					<div class="col-md-3">
						<span class="form-label">ToEmails</span>
						<input type="text" id="txtToEmails" name="txtToEmails" class="form-control" />
					</div> <!-- end ToEmails column -->
					<div class="col-md-3">
						<span class="form-label">CcEmails</span>
						<input type="text" id="txtCcEmails" name="txtCcEmails" class="form-control" />
					</div> <!-- end CcEmails column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">BccEmails</span>
						<input type="text" id="txtBccEmails" name="txtBccEmails" class="form-control" />
					</div> <!-- end BccEmails column -->
					<div class="col-md-3">
						<span class="form-label">FromEmail</span>
						<input type="text" id="txtFromEmail" name="txtFromEmail" class="form-control" />
					</div> <!-- end FromEmail column -->
					<div class="col-md-3">
						<span class="form-label">ReplyEmail</span>
						<input type="text" id="txtReplyEmail" name="txtReplyEmail" class="form-control" />
					</div> <!-- end ReplyEmail column -->
					<div class="col-md-3">
						<span class="form-label">Subject</span>
						<input type="text" id="txtSubject" name="txtSubject" class="form-control" />
					</div> <!-- end Subject column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Message</span>
						<textarea id="txtMessage" name="txtMessage" class="form-control" placeholder="Enter Message" style="height:160px;"></textarea>
					</div> <!-- end Message column -->
					<div class="col-md-3">
						<span class="form-label">Successful</span>
						<input type="text" id="txtSuccessful" name="txtSuccessful" class="form-control" />
					</div> <!-- end Successful column -->
					<div class="col-md-3">
						<span class="form-label">SmtpHost</span>
						<input type="text" id="txtSmtpHost" name="txtSmtpHost" class="form-control" />
					</div> <!-- end SmtpHost column -->
					<div class="col-md-3">
						<span class="form-label">SmtpUsername</span>
						<input type="text" id="txtSmtpUsername" name="txtSmtpUsername" class="form-control" />
					</div> <!-- end SmtpUsername column -->
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