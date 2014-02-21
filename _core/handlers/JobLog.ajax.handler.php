<?php
//the following line use may vary depending on the location this handler utility is placed
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
//get the id for handling the record
$JobLogId = "";
if (isset($_POST["JobLogId"]))
{
	$JobLogId = $_POST["JobLogId"];
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
	$DateRan = "";
	$Status = "";
	$DbBackupId = "";
	$DbBackupFilename = "";
	$DbBackupFilepath = "";
	$DbBackupFilesize = "";
	$FileBackupId = "";
	$FileBackupFilename = "";
	$FileBackupFilepath = "";
	$FileBackupFilesize = "";
	$Success = "";
	if (isset($_POST["JobId"]))
	{
		$JobId = $_POST["JobId"];
	}
	if (isset($_POST["DateRan"]))
	{
		$DateRan = $_POST["DateRan"];
	}
	if (isset($_POST["Status"]))
	{
		$Status = $_POST["Status"];
	}
	if (isset($_POST["DbBackupId"]))
	{
		$DbBackupId = $_POST["DbBackupId"];
	}
	if (isset($_POST["DbBackupFilename"]))
	{
		$DbBackupFilename = $_POST["DbBackupFilename"];
	}
	if (isset($_POST["DbBackupFilepath"]))
	{
		$DbBackupFilepath = $_POST["DbBackupFilepath"];
	}
	if (isset($_POST["DbBackupFilesize"]))
	{
		$DbBackupFilesize = $_POST["DbBackupFilesize"];
	}
	if (isset($_POST["FileBackupId"]))
	{
		$FileBackupId = $_POST["FileBackupId"];
	}
	if (isset($_POST["FileBackupFilename"]))
	{
		$FileBackupFilename = $_POST["FileBackupFilename"];
	}
	if (isset($_POST["FileBackupFilepath"]))
	{
		$FileBackupFilepath = $_POST["FileBackupFilepath"];
	}
	if (isset($_POST["FileBackupFilesize"]))
	{
		$FileBackupFilesize = $_POST["FileBackupFilesize"];
	}
	if (isset($_POST["Success"]))
	{
		$Success = $_POST["Success"];
	}

	$JobLogFactory = new JobLogFactory();
	$newRecord = true;
	$timestamp = time();
	if ($JobLogId != "" && $JobLogId > 0)
	{
		$JobLog = $JobLogFactory->GetOne($JobLogId);
		$newRecord = false;
	}
	else
	{
		$JobLog = new JobLog();
		$newRecord = true;
	}
	$JobLog->JobId = $JobId;
	$JobLog->DateRan = $DateRan;
	$JobLog->Status = $Status;
	$JobLog->DbBackupId = $DbBackupId;
	$JobLog->DbBackupFilename = $DbBackupFilename;
	$JobLog->DbBackupFilepath = $DbBackupFilepath;
	$JobLog->DbBackupFilesize = $DbBackupFilesize;
	$JobLog->FileBackupId = $FileBackupId;
	$JobLog->FileBackupFilename = $FileBackupFilename;
	$JobLog->FileBackupFilepath = $FileBackupFilepath;
	$JobLog->FileBackupFilesize = $FileBackupFilesize;
	$JobLog->Success = $Success;
	$JobLogFactory->Save($JobLog);
	echo $JobLog->JobLogId;
	exit();

}
else if ($Method == "delete")
{
	$JobLogFactory = new JobLogFactory();
	if ($JobLogId != "" && $JobLogId > 0)
	{
		$JobLog = $JobLogFactory->GetOne($JobLogId);
	}
	if ($JobLog->JobLogId > 0)
	{
		$JobLogFactory->Delete($JobLog->JobLogId);
	}
	exit();

}
else if ($Method == "softDelete")
{

}
else if ($Method == "load")
{
	$JobLogFactory = new JobLogFactory();
	$Record = $JobLogFactory->GetOne($JobLogId);
	if ($JobLogId != "" && $JobLogId > 0)
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">JobLogId</span>
						<input type="text" id="txtJobLogId" name="txtJobLogId" class="form-control" value="<?php echo $Record->JobLogId; ?>" />
					</div> <!-- end JobLogId column -->
					<div class="col-md-3">
						<span class="form-label">JobId</span>
						<input type="text" id="txtJobId" name="txtJobId" class="form-control" value="<?php echo $Record->JobId; ?>" />
					</div> <!-- end JobId column -->
					<div class="col-md-3">
						<span class="form-label">DateRan</span>
						<input type="text" id="txtDateRan" name="txtDateRan" value="<?php $date = new DateTime($Record->DateRan); echo $date->format('m/d/Y'); ?>" class="form-control date" />
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Status</span>
						<input type="text" id="txtStatus" name="txtStatus" class="form-control" value="<?php echo $Record->Status; ?>" />
					</div> <!-- end Status column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">DbBackupId</span>
						<input type="text" id="txtDbBackupId" name="txtDbBackupId" class="form-control" value="<?php echo $Record->DbBackupId; ?>" />
					</div> <!-- end DbBackupId column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">DbBackupFilename</span>
						<input type="text" id="txtDbBackupFilename" name="txtDbBackupFilename" class="form-control" value="<?php echo $Record->DbBackupFilename; ?>" />
					</div> <!-- end DbBackupFilename column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">DbBackupFilepath</span>
						<input type="text" id="txtDbBackupFilepath" name="txtDbBackupFilepath" class="form-control" value="<?php echo $Record->DbBackupFilepath; ?>" />
					</div> <!-- end DbBackupFilepath column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-4">
						<span class="form-label">DbBackupFilesize</span>
						<input type="text" id="txtDbBackupFilesize" name="txtDbBackupFilesize" class="form-control" value="<?php echo $Record->DbBackupFilesize; ?>" />
					</div> <!-- end DbBackupFilesize column -->
					<div class="col-md-3">
						<span class="form-label">FileBackupId</span>
						<input type="text" id="txtFileBackupId" name="txtFileBackupId" class="form-control" value="<?php echo $Record->FileBackupId; ?>" />
					</div> <!-- end FileBackupId column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">FileBackupFilename</span>
						<input type="text" id="txtFileBackupFilename" name="txtFileBackupFilename" class="form-control" value="<?php echo $Record->FileBackupFilename; ?>" />
					</div> <!-- end FileBackupFilename column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">FileBackupFilepath</span>
						<input type="text" id="txtFileBackupFilepath" name="txtFileBackupFilepath" class="form-control" value="<?php echo $Record->FileBackupFilepath; ?>" />
					</div> <!-- end FileBackupFilepath column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">FileBackupFilesize</span>
						<input type="text" id="txtFileBackupFilesize" name="txtFileBackupFilesize" class="form-control" value="<?php echo $Record->FileBackupFilesize; ?>" />
					</div> <!-- end FileBackupFilesize column -->
					<div class="col-md-3">
						<span class="form-label">Success</span>
						<input type="text" id="txtSuccess" name="txtSuccess" class="form-control" value="<?php echo $Record->Success; ?>" />
					</div> <!-- end Success column -->
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
						<span class="form-label">JobLogId</span>
						<input type="text" id="txtJobLogId" name="txtJobLogId" class="form-control" />
					</div> <!-- end JobLogId column -->
					<div class="col-md-3">
						<span class="form-label">JobId</span>
						<input type="text" id="txtJobId" name="txtJobId" class="form-control" />
					</div> <!-- end JobId column -->
					<div class="col-md-3">
						<span class="form-label">DateRan</span>
						<input type="text" id="txtDateRan" name="txtDateRan" class="form-control date" />
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">Status</span>
						<input type="text" id="txtStatus" name="txtStatus" class="form-control" />
					</div> <!-- end Status column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">DbBackupId</span>
						<input type="text" id="txtDbBackupId" name="txtDbBackupId" class="form-control" />
					</div> <!-- end DbBackupId column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">DbBackupFilename</span>
						<input type="text" id="txtDbBackupFilename" name="txtDbBackupFilename" class="form-control" />
					</div> <!-- end DbBackupFilename column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">DbBackupFilepath</span>
						<input type="text" id="txtDbBackupFilepath" name="txtDbBackupFilepath" class="form-control" />
					</div> <!-- end DbBackupFilepath column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-4">
						<span class="form-label">DbBackupFilesize</span>
						<input type="text" id="txtDbBackupFilesize" name="txtDbBackupFilesize" class="form-control" />
					</div> <!-- end DbBackupFilesize column -->
					<div class="col-md-3">
						<span class="form-label">FileBackupId</span>
						<input type="text" id="txtFileBackupId" name="txtFileBackupId" class="form-control" />
					</div> <!-- end FileBackupId column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">FileBackupFilename</span>
						<input type="text" id="txtFileBackupFilename" name="txtFileBackupFilename" class="form-control" />
					</div> <!-- end FileBackupFilename column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-12">
						<span class="form-label">FileBackupFilepath</span>
						<input type="text" id="txtFileBackupFilepath" name="txtFileBackupFilepath" class="form-control" />
					</div> <!-- end FileBackupFilepath column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">FileBackupFilesize</span>
						<input type="text" id="txtFileBackupFilesize" name="txtFileBackupFilesize" class="form-control" />
					</div> <!-- end FileBackupFilesize column -->
					<div class="col-md-3">
						<span class="form-label">Success</span>
						<input type="text" id="txtSuccess" name="txtSuccess" class="form-control" />
					</div> <!-- end Success column -->
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
else if ($Method == "view")
{
	$JobLogFactory = new JobLogFactory();
	$Record = $JobLogFactory->GetOne($JobLogId);
	$JobFactory = new JobFactory();
	$Job = $JobFactory->GetOne($Record->JobId);
	if ($JobLogId != "" && $JobLogId > 0)
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Job Name: </span>
					</div> <!-- end DateRan column -->
					<div class="col-md-9">
						<label id="txtJobName" name="txtJobName"><?php echo $Job->Name; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Type: </span>
					</div> <!-- end DbBackupFilename column -->
					<div class="col-md-9">
						<label id="txtJobType" name="txtJobType"><?php echo $Job->BackupDestinationType; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Date Ran: </span>
					</div> <!-- end DbBackupFilename column -->
					<div class="col-md-9">
						<label id="txtDateRan" name="txtDateRan"><?php $date = new DateTime($Record->DateRan); echo $date->format('m/d/Y H:i:s'); ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Success: </span>
					</div> <!-- end Success column -->
					<div class="col-md-9">
						<label id="txtSuccess" name="txtSuccess"><?php echo ($Record->Success == 1) ? "True" : "False"; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Status: </span>
					</div> <!-- end Status column -->
					<div class="col-md-9">
						<label id="txtStatus" name="txtStatus"><?php echo $Record->Status; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<?php
				if ($Record->DbBackupFilepath != "")
				{
				?>
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Db Backup Filename: </span>
					</div> <!-- end DbBackupFilename column -->
					<div class="col-md-9">
						<label id="txtDbBackupFilename" name="txtDbBackupFilename"><?php echo $Record->DbBackupFilename; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Db Backup Filepath: </span>
					</div> <!-- end DbBackupFilepath column -->
					<div class="col-md-9">
						<label id="txtDbBackupFilepath" name="txtDbBackupFilepath"><?php echo $Record->DbBackupFilepath; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">Db Backup Filesize: </span>
					</div> <!-- end DbBackupFilesize column -->
					<div class="col-md-9">
						<label id="txtDbBackupFilesize" name="txtDbBackupFilesize"><?php echo smartFilesizeDisplay($Record->DbBackupFilesize); ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<?php
				}
				if ($Record->FileBackupFilepath != "")
				{
				?>
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">File Backup Filename: </span>
					</div> <!-- end FileBackupFilename column -->
					<div class="col-md-9">
						<label id="txtFileBackupFilename" name="txtFileBackupFilename"><?php echo $Record->FileBackupFilename; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">File Backup Filepath: </span>
					</div> <!-- end FileBackupFilepath column -->
					<div class="col-md-9">
						<label id="txtFileBackupFilepath" name="txtFileBackupFilepath"><?php echo $Record->FileBackupFilepath; ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-2" style="text-align:right;">
						<span class="form-label">File Backup Filesize: </span>
					</div> <!-- end FileBackupFilesize column -->
					<div class="col-md-9">
						<label id="txtFileBackupFilesize" name="txtFileBackupFilesize"><?php echo smartFilesizeDisplay($Record->FileBackupFilesize); ?></label>
					</div> <!-- end DateRan column -->
				</div> <!-- end row -->
				<?php
					}
				?>
			</div> <!-- end container well -->
			<div class="container well button-container">
				<div class="row">
					<div class="col-md-12">
						<input type="button" class="btn btn-primary" onclick="HideEdit();" value="Close" />
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