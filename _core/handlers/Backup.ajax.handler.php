<?php
//the following line use may vary depending on the location this handler utility is placed
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
//get the id for handling the record
$BackupId = "";
if (isset($_POST["BackupId"]))
{
	$BackupId = $_POST["BackupId"];
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
	$JobId = "";
	$DateCreated = "";
	$Success = "";
	$Status = "";
	$BackupType = "";
	$Filename = "";
	$Filepath = "";
	$Filesize = "";
	if (isset($_POST["JobId"]))
	{
		$JobId = $_POST["JobId"];
	}
	if (isset($_POST["DateCreated"]))
	{
		$DateCreated = $_POST["DateCreated"];
	}
	if (isset($_POST["Success"]))
	{
		$Success = $_POST["Success"];
	}
	if (isset($_POST["Status"]))
	{
		$Status = $_POST["Status"];
	}
	if (isset($_POST["BackupType"]))
	{
		$BackupType = $_POST["BackupType"];
	}
	if (isset($_POST["Filename"]))
	{
		$Filename = $_POST["Filename"];
	}
	if (isset($_POST["Filepath"]))
	{
		$Filepath = $_POST["Filepath"];
	}
	if (isset($_POST["Filesize"]))
	{
		$Filesize = $_POST["Filesize"];
	}

	$BackupFactory = new BackupFactory();
	$newRecord = true;
	$timestamp = time();
	if ($BackupId != "" && $BackupId > 0)
	{
		$Backup = $BackupFactory->GetOne($BackupId);
		$newRecord = false;
	}
	else
	{
		$Backup = new Backup();
		$Backup->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
		$newRecord = true;
	}
	$Backup->JobId = $JobId;
	$Backup->Success = $Success;
	$Backup->Status = $Status;
	$Backup->BackupType = $BackupType;
	$Backup->Filename = $Filename;
	$Backup->Filepath = $Filepath;
	$Backup->Filesize = $Filesize;
	$BackupFactory->Save($Backup);
	echo $Backup->BackupId;
	exit();

}
else if ($Method == "delete")
{
	$BackupFactory = new BackupFactory();
	if ($BackupId != "" && $BackupId > 0)
	{
		$Backup = $BackupFactory->GetOne($BackupId);
	}
	if ($Backup->BackupId > 0)
	{
		$BackupFactory->Delete($Backup->BackupId);
	}
	exit();

}
else if ($Method == "softDelete")
{

}
else if ($Method == "load")
{
	$BackupFactory = new BackupFactory();
	$Record = $BackupFactory->GetOne($BackupId);
	if ($BackupId != "" && $BackupId > 0)
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">DateCreated</span>
						<input type="text" id="txtDateCreated" name="txtDateCreated" value="<?php $date = new DateTime($Record->DateCreated); echo $date->format('m/d/Y'); ?>" class="form-control date" />
					</div> <!-- end DateCreated column -->
					<div class="col-md-3">
						<span class="form-label">Success</span>
						<input type="text" id="txtSuccess" name="txtSuccess" class="form-control" value="<?php echo $Record->Success; ?>" />
					</div> <!-- end Success column -->
					<div class="col-md-3">
						<span class="form-label">BackupType</span>
						<input type="text" id="txtBackupType" name="txtBackupType" class="form-control" value="<?php echo $Record->BackupType; ?>" />
					</div> <!-- end BackupType column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Status</span>
						<input type="text" id="txtStatus" name="txtStatus" class="form-control" value="<?php echo $Record->Status; ?>" />
					</div> <!-- end Status column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Filename</span>
						<input type="text" id="txtFilename" name="txtFilename" class="form-control" value="<?php echo $Record->Filename; ?>" />
					</div> <!-- end Filename column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Filepath</span>
						<input type="text" id="txtFilepath" name="txtFilepath" class="form-control" value="<?php echo $Record->Filepath; ?>" />
					</div> <!-- end Filepath column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Filesize</span>
						<input type="text" id="txtFilesize" name="txtFilesize" class="form-control" value="<?php echo $Record->Filesize; ?>" />
					</div> <!-- end Filesize column -->
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
						<span class="form-label">DateCreated</span>
						<input type="text" id="txtDateCreated" name="txtDateCreated" class="form-control date" />
					</div> <!-- end DateCreated column -->
					<div class="col-md-3">
						<span class="form-label">Success</span>
						<input type="text" id="txtSuccess" name="txtSuccess" class="form-control" />
					</div> <!-- end Success column -->
					<div class="col-md-3">
						<span class="form-label">BackupType</span>
						<input type="text" id="txtBackupType" name="txtBackupType" class="form-control" />
					</div> <!-- end BackupType column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Status</span>
						<input type="text" id="txtStatus" name="txtStatus" class="form-control" />
					</div> <!-- end Status column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Filename</span>
						<input type="text" id="txtFilename" name="txtFilename" class="form-control" />
					</div> <!-- end Filename column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Filepath</span>
						<input type="text" id="txtFilepath" name="txtFilepath" class="form-control" />
					</div> <!-- end Filepath column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Filesize</span>
						<input type="text" id="txtFilesize" name="txtFilesize" class="form-control" />
					</div> <!-- end Filesize column -->
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