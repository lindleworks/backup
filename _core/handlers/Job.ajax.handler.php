<?php
//the following line use may vary depending on the location this handler utility is placed
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
//get the id for handling the record
$JobId = "";
if (isset($_POST["JobId"]))
{
	$JobId = $_POST["JobId"];
}

function sortByDateModified($a, $b) {
    return $b->dateModified - $a->dateModified;
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
	$Name = "";
	$DbBackup = "";
	$FileBackup = "";
	$BackupDestinationType = "";
	$Email = "";
	$BackupFolder = "";
	$BackupStorageFolder = "";
	$ScheduleInterval = "";
	$ScheduleDayOfMonth = "";
	$ScheduleDayOfWeek = "";
	$ScheduleHour = "";
	$ScheduleMinute = "";
	$FtpServer = "";
	$FtpUsername = "";
	$FtpPassword = "";
	$FtpFolder = "";
	$MaximumNumberOfBackups = "";
	$DbPassword = "";
	$DbUsername = "";
	$DbServer = "";
	$DbName = "";
	$DateCreated = "";
	$NextRunDate = "";
	if (isset($_POST["Name"]))
	{
		$Name = $_POST["Name"];
	}
	if (isset($_POST["DbBackup"]))
	{
		$DbBackup = $_POST["DbBackup"];
	}
	if (isset($_POST["FileBackup"]))
	{
		$FileBackup = $_POST["FileBackup"];
	}
	if (isset($_POST["BackupDestinationType"]))
	{
		$BackupDestinationType = $_POST["BackupDestinationType"];
	}
	if (isset($_POST["Email"]))
	{
		$Email = $_POST["Email"];
	}
	if (isset($_POST["BackupFolder"]))
	{
		$BackupFolder = $_POST["BackupFolder"];
	}
	if (isset($_POST["BackupStorageFolder"]))
	{
		$BackupStorageFolder = $_POST["BackupStorageFolder"];
	}
	if (isset($_POST["ScheduleInterval"]))
	{
		$ScheduleInterval = $_POST["ScheduleInterval"];
	}
	if (isset($_POST["ScheduleDayOfMonth"]))
	{
		$ScheduleDayOfMonth = $_POST["ScheduleDayOfMonth"];
	}
	if (isset($_POST["ScheduleDayOfWeek"]))
	{
		$ScheduleDayOfWeek = $_POST["ScheduleDayOfWeek"];
	}
	if (isset($_POST["ScheduleHour"]))
	{
		$ScheduleHour = $_POST["ScheduleHour"];
	}
	if (isset($_POST["ScheduleMinute"]))
	{
		$ScheduleMinute = $_POST["ScheduleMinute"];
	}
	if (isset($_POST["FtpServer"]))
	{
		$FtpServer = $_POST["FtpServer"];
	}
	if (isset($_POST["FtpUsername"]))
	{
		$FtpUsername = $_POST["FtpUsername"];
	}
	if (isset($_POST["FtpPassword"]))
	{
		$FtpPassword = $_POST["FtpPassword"];
	}
	if (isset($_POST["FtpFolder"]))
	{
		$FtpFolder = $_POST["FtpFolder"];
	}
	if (isset($_POST["MaximumNumberOfBackups"]))
	{
		$MaximumNumberOfBackups = $_POST["MaximumNumberOfBackups"];
	}
	if (isset($_POST["DbPassword"]))
	{
		$DbPassword = $_POST["DbPassword"];
	}
	if (isset($_POST["DbUsername"]))
	{
		$DbUsername = $_POST["DbUsername"];
	}
	if (isset($_POST["DbServer"]))
	{
		$DbServer = $_POST["DbServer"];
	}
	if (isset($_POST["DbName"]))
	{
		$DbName = $_POST["DbName"];
	}
	if (isset($_POST["DateCreated"]))
	{
		$DateCreated = $_POST["DateCreated"];
	}
	if (isset($_POST["NextRunDate"]))
	{
		$NextRunDate = $_POST["NextRunDate"];
	}

	$JobFactory = new JobFactory();
	$newRecord = true;
	$timestamp = time();
	if ($JobId != "" && $JobId > 0)
	{
		$Job = $JobFactory->GetOne($JobId);
		$newRecord = false;
	}
	else
	{
		$Job = new Job();
		$Job->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
		$newRecord = true;
	}
	$Job->Name = $Name;
	$Job->DbBackup = $DbBackup;
	$Job->FileBackup = $FileBackup;
	$Job->BackupDestinationType = $BackupDestinationType;
	$Job->Email = $Email;
	$Job->BackupFolder = $BackupFolder;
	$Job->BackupStorageFolder = $BackupStorageFolder;
	$Job->ScheduleInterval = $ScheduleInterval;
	$Job->ScheduleDayOfMonth = $ScheduleDayOfMonth;
	$Job->ScheduleDayOfWeek = $ScheduleDayOfWeek;
	$Job->ScheduleHour = $ScheduleHour;
	$Job->ScheduleMinute = $ScheduleMinute;
	$Job->FtpServer = $FtpServer;
	if ($FtpPassword != "")
	{
		$Job->FtpPassword = $FtpPassword;
	}
	$Job->FtpUsername = $FtpUsername;
	$Job->FtpFolder = $FtpFolder;
	$Job->MaximumNumberOfBackups = $MaximumNumberOfBackups;
	if ($DbPassword != "")
	{
		$Job->DbPassword = $DbPassword;
	}
	$Job->DbUsername = $DbUsername;
	$Job->DbServer = $DbServer;
	$Job->DbName = $DbName;
	$Job->NextRunDate = gmdate("Y-m-d H:i:s", ReturnNextRun($Job));
	$JobFactory->Save($Job);
	echo $Job->JobId;
	exit();

}
else if ($Method == "delete")
{
	$JobFactory = new JobFactory();
	if ($JobId != "" && $JobId > 0)
	{
		$Job = $JobFactory->GetOne($JobId);
		//delete all logs
		$JobLogFactory = new JobLogFactory();
		$BackupFactory = new BackupFactory();
		$JobLogArray = $JobLogFactory->GetAll(" where JobId = " . $Job->JobId);
		foreach ($JobLogArray as &$JobLog)
		{
			if ($Job->BackupDestinationType == "Local")
			{
				//handle local file deletion
				if ($JobLog->DbBackupFilepath != "")
				{
					unlink($JobLog->DbBackupFilepath);
				}
				if ($JobLog->FileBackupFilepath != "")
				{
					unlink($JobLog->FileBackupFilepath);
				}
			}
			else if ($Job->BackupDestinationType == "FTP")
			{
				//handle remote file deletion
				if ($JobLog->DbBackupFilepath != "")
				{
					FtpDeleteFile($Job->FtpServer, $Job->FtpUsername, $Job->FtpPassword, $JobLog->DbBackupFilepath);
				}
				if ($JobLog->FileBackupFilepath != "")
				{
					FtpDeleteFile($Job->FtpServer, $Job->FtpUsername, $Job->FtpPassword, $JobLog->FileBackupFilepath);
				}
			}
			$BackupFactory->Delete($JobLog->DbBackupId);
			$BackupFactory->Delete($JobLog->FileBackupId);
			$JobLogFactory->Delete($JobLog);
		}
	}
	if ($Job->JobId > 0)
	{
		$JobFactory->Delete($Job->JobId);
	}
	exit();

}
else if ($Method == "softDelete")
{

}
else if ($Method == "load")
{
	$JobFactory = new JobFactory();
	$Record = $JobFactory->GetOne($JobId);
	if ($JobId != "" && $JobId > 0)
	{
		?>
		<!-- Form End -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<input type="hidden" id="hdnJobId" name="hdnJobId" class="form-control" value="<?php echo $Record->JobId; ?>" />
					<div class="col-md-3">
						<span class="form-label">Name *</span>
						<input type="text" id="txtName" name="txtName" class="required form-control" value="<?php echo $Record->Name; ?>" />
					</div> <!-- end Name column -->
					<div class="col-md-4">
						<span class="form-label">Email</span>
						<input type="text" id="txtEmail" name="txtEmail" class="form-control email" value="<?php echo $Record->Email; ?>" />
					</div> <!-- end Email column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Backup Destination Type *</span>
						<select id="ddlBackupDestinationType" name="ddlBackupDestinationType" class="required form-control" onchange="HideShowFTP();">
							<option value="Local"<?php if ($Record->BackupDestinationType == 'Local') echo ' selected="selected"'; ?>>Local</option>
							<option value="FTP"<?php if ($Record->BackupDestinationType == 'FTP') echo ' selected="selected"'; ?>>FTP</option>
						</select>
					</div> <!-- end BackupDestinationType column -->
					<div class="col-md-2">
						<br />
						<input type="checkbox" id="chbDbBackup" name="chbDbBackup" class="" onclick="HideShowDb();" <?php if ($Record->DbBackup == 1) { echo 'checked="checked"'; } ?> /> <label for="chbDbBackup">Backup Database</label>
					</div> <!-- end DbBackup column -->
					<div class="col-md-2">
						<br />
						<input type="checkbox" id="chbFileBackup" name="chbFileBackup" class="" onclick="HideShowFile();" <?php if ($Record->FileBackup == 1) { echo 'checked="checked"'; } ?> /> <label for="chbFileBackup">Backup Files</label>
					</div> <!-- end FileBackup column -->
					<div class="col-md-3">
						<span class="form-label">Maximum Backups *</span> (0 = unlimited)
						<input type="text" id="txtMaximumNumberOfBackups" name="txtMaximumNumberOfBackups" class="required form-control positive-integer" value="<?php echo $Record->MaximumNumberOfBackups; ?>" />
					</div> <!-- end MaximumNumberOfBackups column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-6 files-backup">
						<span class="form-label">Folder To Backup *</span>
						<input type="text" id="txtBackupFolder" name="txtBackupFolder" class="form-control" value="<?php echo $Record->BackupFolder; ?>" />
					</div> <!-- end BackupFolder column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Server *</span>
						<input type="text" id="txtDbServer" name="txtDbServer" class="form-control" value="<?php echo $Record->DbServer; ?>" />
					</div> <!-- end DbServer column -->
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Name *</span>
						<input type="text" id="txtDbName" name="txtDbName" class="form-control" value="<?php echo $Record->DbName; ?>" />
					</div> <!-- end DbName column -->
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Username *</span>
						<input type="text" id="txtDbUsername" name="txtDbUsername" class="form-control" value="<?php echo $Record->DbUsername; ?>" />
					</div> <!-- end DbUsername column -->
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Password *</span>
						<input type="password" id="txtDbPassword" name="txtDbPassword" class="form-control" />
					</div> <!-- end DbPassword column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-6 local-backup">
						<span class="form-label">Backup Storage Folder *</span>
						<input type="text" id="txtBackupStorageFolder" name="txtBackupStorageFolder" class="form-control required" value="<?php echo $Record->BackupStorageFolder; ?>" />
					</div> <!-- end BackupStorageFolder column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Schedule Interval *</span>
						<select id="ddlScheduleInterval" name="ddlScheduleInterval" class="form-control required" onchange="HideShowInterval();">
							<option value="Manual"<?php if ($Record->ScheduleInterval == 'Manual') echo ' selected="selected"'; ?>>Manual</option>
							<option value="Hourly"<?php if ($Record->ScheduleInterval == 'Hourly') echo ' selected="selected"'; ?>>Hourly</option>
							<option value="Daily"<?php if ($Record->ScheduleInterval == 'Daily') echo ' selected="selected"'; ?>>Daily</option>
							<option value="Weekly"<?php if ($Record->ScheduleInterval == 'Weekly') echo ' selected="selected"'; ?>>Weekly</option>
							<option value="Monthly"<?php if ($Record->ScheduleInterval == 'Monthly') echo ' selected="selected"'; ?>>Monthly</option>
						</select>
					</div> <!-- end ScheduleInterval column -->
					<div class="col-md-3 monthly-interval">
						<span class="form-label">Day Of Month</span>
						<select id="ddlScheduleDayOfMonth" name="ddlScheduleDayOfMonth" class="form-control">
							<option value="1"<?php if ($Record->ScheduleDayOfMonth == '1') echo ' selected="selected"'; ?>>1</option>
							<option value="2"<?php if ($Record->ScheduleDayOfMonth == '2') echo ' selected="selected"'; ?>>2</option>
							<option value="3"<?php if ($Record->ScheduleDayOfMonth == '3') echo ' selected="selected"'; ?>>3</option>
							<option value="4"<?php if ($Record->ScheduleDayOfMonth == '4') echo ' selected="selected"'; ?>>4</option>
							<option value="5"<?php if ($Record->ScheduleDayOfMonth == '5') echo ' selected="selected"'; ?>>5</option>
							<option value="6"<?php if ($Record->ScheduleDayOfMonth == '6') echo ' selected="selected"'; ?>>6</option>
							<option value="7"<?php if ($Record->ScheduleDayOfMonth == '7') echo ' selected="selected"'; ?>>7</option>
							<option value="8"<?php if ($Record->ScheduleDayOfMonth == '8') echo ' selected="selected"'; ?>>8</option>
							<option value="9"<?php if ($Record->ScheduleDayOfMonth == '9') echo ' selected="selected"'; ?>>9</option>
							<option value="10"<?php if ($Record->ScheduleDayOfMonth == '10') echo ' selected="selected"'; ?>>10</option>
							<option value="11"<?php if ($Record->ScheduleDayOfMonth == '11') echo ' selected="selected"'; ?>>11</option>
							<option value="12"<?php if ($Record->ScheduleDayOfMonth == '12') echo ' selected="selected"'; ?>>12</option>
							<option value="13"<?php if ($Record->ScheduleDayOfMonth == '13') echo ' selected="selected"'; ?>>13</option>
							<option value="14"<?php if ($Record->ScheduleDayOfMonth == '14') echo ' selected="selected"'; ?>>14</option>
							<option value="15"<?php if ($Record->ScheduleDayOfMonth == '15') echo ' selected="selected"'; ?>>15</option>
							<option value="16"<?php if ($Record->ScheduleDayOfMonth == '16') echo ' selected="selected"'; ?>>16</option>
							<option value="17"<?php if ($Record->ScheduleDayOfMonth == '17') echo ' selected="selected"'; ?>>17</option>
							<option value="18"<?php if ($Record->ScheduleDayOfMonth == '18') echo ' selected="selected"'; ?>>18</option>
							<option value="19"<?php if ($Record->ScheduleDayOfMonth == '19') echo ' selected="selected"'; ?>>19</option>
							<option value="20"<?php if ($Record->ScheduleDayOfMonth == '20') echo ' selected="selected"'; ?>>20</option>
							<option value="21"<?php if ($Record->ScheduleDayOfMonth == '21') echo ' selected="selected"'; ?>>21</option>
							<option value="22"<?php if ($Record->ScheduleDayOfMonth == '22') echo ' selected="selected"'; ?>>22</option>
							<option value="23"<?php if ($Record->ScheduleDayOfMonth == '23') echo ' selected="selected"'; ?>>23</option>
							<option value="24"<?php if ($Record->ScheduleDayOfMonth == '24') echo ' selected="selected"'; ?>>24</option>
							<option value="25"<?php if ($Record->ScheduleDayOfMonth == '25') echo ' selected="selected"'; ?>>25</option>
							<option value="26"<?php if ($Record->ScheduleDayOfMonth == '26') echo ' selected="selected"'; ?>>26</option>
							<option value="27"<?php if ($Record->ScheduleDayOfMonth == '27') echo ' selected="selected"'; ?>>27</option>
							<option value="28"<?php if ($Record->ScheduleDayOfMonth == '28') echo ' selected="selected"'; ?>>28</option>
							<option value="29"<?php if ($Record->ScheduleDayOfMonth == '29') echo ' selected="selected"'; ?>>29</option>
							<option value="30"<?php if ($Record->ScheduleDayOfMonth == '30') echo ' selected="selected"'; ?>>30</option>
							<option value="31"<?php if ($Record->ScheduleDayOfMonth == '31') echo ' selected="selected"'; ?>>31</option>
						</select>
					</div> <!-- end ScheduleDayOfMonth column -->
					<div class="col-md-3 weekly-interval">
						<span class="form-label">Day Of Week</span>
						<select id="ddlScheduleDayOfWeek" name="ddlScheduleDayOfWeek" class="form-control">
							<option value="Monday"<?php if ($Record->ScheduleDayOfWeek == 'Monday') echo ' selected="selected"'; ?>>Monday</option>
							<option value="Tuesday"<?php if ($Record->ScheduleDayOfWeek == 'Tuesday') echo ' selected="selected"'; ?>>Tuesday</option>
							<option value="Wednesday"<?php if ($Record->ScheduleDayOfWeek == 'Wednesday') echo ' selected="selected"'; ?>>Wednesday</option>
							<option value="Thursday"<?php if ($Record->ScheduleDayOfWeek == 'Thursday') echo ' selected="selected"'; ?>>Thursday</option>
							<option value="Friday"<?php if ($Record->ScheduleDayOfWeek == 'Friday') echo ' selected="selected"'; ?>>Friday</option>
							<option value="Saturday"<?php if ($Record->ScheduleDayOfWeek == 'Saturday') echo ' selected="selected"'; ?>>Saturday</option>
							<option value="Sunday"<?php if ($Record->ScheduleDayOfWeek == 'Sunday') echo ' selected="selected"'; ?>>Sunday</option>
						</select>
					</div> <!-- end ScheduleDayOfWeek column -->
					<div class="col-md-3 daily-interval">
						<span class="form-label">Hour</span>
						<select id="ddlScheduleHour" name="ddlScheduleHour" class="form-control">
							<option value="00"<?php if ($Record->ScheduleHour == '00') echo ' selected="selected"'; ?>>00</option>
							<option value="01"<?php if ($Record->ScheduleHour == '01') echo ' selected="selected"'; ?>>01</option>
							<option value="02"<?php if ($Record->ScheduleHour == '02') echo ' selected="selected"'; ?>>02</option>
							<option value="03"<?php if ($Record->ScheduleHour == '03') echo ' selected="selected"'; ?>>03</option>
							<option value="04"<?php if ($Record->ScheduleHour == '04') echo ' selected="selected"'; ?>>04</option>
							<option value="05"<?php if ($Record->ScheduleHour == '05') echo ' selected="selected"'; ?>>05</option>
							<option value="06"<?php if ($Record->ScheduleHour == '06') echo ' selected="selected"'; ?>>06</option>
							<option value="07"<?php if ($Record->ScheduleHour == '07') echo ' selected="selected"'; ?>>07</option>
							<option value="08"<?php if ($Record->ScheduleHour == '08') echo ' selected="selected"'; ?>>08</option>
							<option value="09"<?php if ($Record->ScheduleHour == '09') echo ' selected="selected"'; ?>>09</option>
							<option value="10"<?php if ($Record->ScheduleHour == '10') echo ' selected="selected"'; ?>>10</option>
							<option value="11"<?php if ($Record->ScheduleHour == '11') echo ' selected="selected"'; ?>>11</option>
							<option value="12"<?php if ($Record->ScheduleHour == '12') echo ' selected="selected"'; ?>>12</option>
							<option value="13"<?php if ($Record->ScheduleHour == '13') echo ' selected="selected"'; ?>>13</option>
							<option value="14"<?php if ($Record->ScheduleHour == '14') echo ' selected="selected"'; ?>>14</option>
							<option value="15"<?php if ($Record->ScheduleHour == '15') echo ' selected="selected"'; ?>>15</option>
							<option value="16"<?php if ($Record->ScheduleHour == '16') echo ' selected="selected"'; ?>>16</option>
							<option value="17"<?php if ($Record->ScheduleHour == '17') echo ' selected="selected"'; ?>>17</option>
							<option value="18"<?php if ($Record->ScheduleHour == '18') echo ' selected="selected"'; ?>>18</option>
							<option value="19"<?php if ($Record->ScheduleHour == '19') echo ' selected="selected"'; ?>>19</option>
							<option value="20"<?php if ($Record->ScheduleHour == '20') echo ' selected="selected"'; ?>>20</option>
							<option value="21"<?php if ($Record->ScheduleHour == '21') echo ' selected="selected"'; ?>>21</option>
							<option value="22"<?php if ($Record->ScheduleHour == '22') echo ' selected="selected"'; ?>>22</option>
							<option value="23"<?php if ($Record->ScheduleHour == '23') echo ' selected="selected"'; ?>>23</option>
						</select>
					</div> <!-- end ScheduleHour column -->
					<div class="col-md-3 hourly-interval">
						<span class="form-label">Minute</span>
						<select id="ddlScheduleMinute" name="ddlScheduleMinute" class="form-control">
							<option value="00"<?php if ($Record->ScheduleMinute == '00') echo ' selected="selected"'; ?>>00</option>
							<option value="05"<?php if ($Record->ScheduleMinute == '05') echo ' selected="selected"'; ?>>05</option>
							<option value="10"<?php if ($Record->ScheduleMinute == '10') echo ' selected="selected"'; ?>>10</option>
							<option value="15"<?php if ($Record->ScheduleMinute == '15') echo ' selected="selected"'; ?>>15</option>
							<option value="20"<?php if ($Record->ScheduleMinute == '20') echo ' selected="selected"'; ?>>20</option>
							<option value="25"<?php if ($Record->ScheduleMinute == '25') echo ' selected="selected"'; ?>>25</option>
							<option value="30"<?php if ($Record->ScheduleMinute == '30') echo ' selected="selected"'; ?>>30</option>
							<option value="35"<?php if ($Record->ScheduleMinute == '35') echo ' selected="selected"'; ?>>35</option>
							<option value="40"<?php if ($Record->ScheduleMinute == '40') echo ' selected="selected"'; ?>>40</option>
							<option value="45"<?php if ($Record->ScheduleMinute == '45') echo ' selected="selected"'; ?>>45</option>
							<option value="50"<?php if ($Record->ScheduleMinute == '50') echo ' selected="selected"'; ?>>50</option>
							<option value="55"<?php if ($Record->ScheduleMinute == '55') echo ' selected="selected"'; ?>>55</option>
						</select>
					</div> <!-- end ScheduleMinute column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Server *</span>
						<input type="text" id="txtFtpServer" name="txtFtpServer" class="form-control" value="<?php echo $Record->FtpServer; ?>" />
					</div> <!-- end FtpServer column -->
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Username *</span>
						<input type="text" id="txtFtpUsername" name="txtFtpUsername" class="form-control" value="<?php echo $Record->FtpUsername; ?>" />
					</div> <!-- end FtpUsername column -->
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Password *</span>
						<input type="password" id="txtFtpPassword" name="txtFtpPassword" class="form-control" />
					</div> <!-- end FtpPassword column -->
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Folder *</span>
						<input type="text" id="txtFtpFolder" name="txtFtpFolder" class="form-control" value="<?php echo $Record->FtpFolder; ?>" />
					</div> <!-- end FtpFolder column -->
				</div> <!-- end row -->
				<?php
				if ($Record->ScheduleInterval != "Manual")
				{
				?>
				<div class="row">
					<div class="col-md-8">
						<span class="form-label">Cron Job Setup</span>
						<label style="display:block;" id="lblCron" name="lblCron"><?php 
						if ($Record->ScheduleInterval == "Daily")
						{
							echo returnCronSchedule($Record->ScheduleMinute, $Record->ScheduleHour, "", "", "");
						}
						else if ($Record->ScheduleInterval == "Hourly")
						{
							echo returnCronSchedule($Record->ScheduleMinute, "", "", "", "");
						}
						else if ($Record->ScheduleInterval == "Weekly")
						{
							echo returnCronSchedule($Record->ScheduleMinute, $Record->ScheduleHour, "", "", $Record->ScheduleDayOfWeek);
						}
						else if ($Record->ScheduleInterval == "Monthly")
						{
							echo returnCronSchedule($Record->ScheduleMinute, $Record->ScheduleHour, $Record->ScheduleDayOfMonth, "", "");
						}
						echo " php -q " . ROOT . "cron.php JobId=" . $JobId;
						?></label>
					</div> <!-- end FtpServer column -->
					<div class="col-md-4">
						<span class="form-label">Next run</span>
						<label style="display:block;" id="lblNextRun" name="lblNextRun"><?php $date = new DateTime($Record->NextRunDate); echo $date->format('m/d/Y h:i:s a'); ?></label>
					</div> <!-- end FtpServer column -->
				</div> <!-- end row -->
				<?php
				}
				?>
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
		<?php
	}
	else
	{
		?>
		<!-- Form Begin -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<input type="hidden" id="hdnJobId" name="hdnJobId" class="form-control" />
					<div class="col-md-3">
						<span class="form-label">Name *</span>
						<input type="text" id="txtName" name="txtName" class="required form-control" />
					</div> <!-- end Name column -->
					<div class="col-md-4">
						<span class="form-label">Email</span>
						<input type="text" id="txtEmail" name="txtEmail" class="form-control email" />
					</div> <!-- end Email column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Backup Destination Type *</span>
						<select id="ddlBackupDestinationType" name="ddlBackupDestinationType" class="required form-control" onchange="HideShowFTP();">
							<option value="Local">Local</option>
							<option value="FTP">FTP</option>
						</select>
					</div> <!-- end BackupDestinationType column -->
					<div class="col-md-2">
						<br />
						<input type="checkbox" id="chbDbBackup" name="chbDbBackup" onclick="HideShowDb();" class="" checked="checked" /> <label for="chbDbBackup">Backup Database</label>
					</div> <!-- end DbBackup column -->
					<div class="col-md-2">
						<br />
						<input type="checkbox" id="chbFileBackup" name="chbFileBackup" onclick="HideShowFile();" class="" checked="checked" /> <label for="chbFileBackup">Backup Files</label>
					</div> <!-- end FileBackup column -->
					<div class="col-md-3">
						<span class="form-label">Maximum Backups *</span> (0 = unlimited)
						<input type="text" id="txtMaximumNumberOfBackups" name="txtMaximumNumberOfBackups" class="required form-control positive-integer" value="0" />
					</div> <!-- end MaximumNumberOfBackups column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-6 files-backup">
						<span class="form-label">Folder To Backup *</span>
						<input type="text" id="txtBackupFolder" name="txtBackupFolder" class="form-control" value="<?php echo ROOT; ?>" />
					</div> <!-- end BackupFolder column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Server *</span>
						<input type="text" id="txtDbServer" name="txtDbServer" class="form-control" />
					</div> <!-- end DbServer column -->
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Name *</span>
						<input type="text" id="txtDbName" name="txtDbName" class="form-control" />
					</div> <!-- end DbName column -->
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Username *</span>
						<input type="text" id="txtDbUsername" name="txtDbUsername" class="form-control" />
					</div> <!-- end DbUsername column -->
					<div class="col-md-3 database-backup">
						<span class="form-label">Database Password *</span>
						<input type="password" id="txtDbPassword" name="txtDbPassword" class="form-control" />
					</div> <!-- end DbPassword column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-6 local-backup">
						<span class="form-label">Backup Storage Folder *</span>
						<input type="text" id="txtBackupStorageFolder" name="txtBackupStorageFolder" class="form-control required" value="<?php echo ROOT . "backups/"; ?>" />
					</div> <!-- end BackupStorageFolder column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3">
						<span class="form-label">Schedule Interval *</span>
						<select id="ddlScheduleInterval" name="ddlScheduleInterval" class="form-control required" onchange="HideShowInterval();">
							<option value="Manual">Manual</option>
							<option value="Hourly">Hourly</option>
							<option value="Daily" selected="selected">Daily</option>
							<option value="Weekly">Weekly</option>
							<option value="Monthly">Monthly</option>
						</select>
					</div> <!-- end ScheduleInterval column -->
					<div class="col-md-3 monthly-interval">
						<span class="form-label">Day Of Month</span>
						<select id="ddlScheduleDayOfMonth" name="ddlScheduleDayOfMonth" class="form-control">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
							<option value="24">24</option>
							<option value="25">25</option>
							<option value="26">26</option>
							<option value="27">27</option>
							<option value="28">28</option>
							<option value="29">29</option>
							<option value="30">30</option>
							<option value="31">31</option>
						</select>
					</div> <!-- end ScheduleDayOfMonth column -->
					<div class="col-md-3 weekly-interval">
						<span class="form-label">Day Of Week</span>
						<select id="ddlScheduleDayOfWeek" name="ddlScheduleDayOfWeek" class="form-control">
							<option value="Monday">Monday</option>
							<option value="Tuesday">Tuesday</option>
							<option value="Wednesday">Wednesday</option>
							<option value="Thursday">Thursday</option>
							<option value="Friday">Friday</option>
							<option value="Saturday">Saturday</option>
							<option value="Sunday">Sunday</option>
						</select>
					</div> <!-- end ScheduleDayOfWeek column -->
					<div class="col-md-3 daily-interval">
						<span class="form-label">Hour</span>
						<select id="ddlScheduleHour" name="ddlScheduleHour" class="form-control">
							<option value="00">00</option>
							<option value="01">01</option>
							<option value="02">02</option>
							<option value="03">03</option>
							<option value="04">04</option>
							<option value="05">05</option>
							<option value="06">06</option>
							<option value="07">07</option>
							<option value="08">08</option>
							<option value="09">09</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
							<option value="13">13</option>
							<option value="14">14</option>
							<option value="15">15</option>
							<option value="16">16</option>
							<option value="17">17</option>
							<option value="18">18</option>
							<option value="19">19</option>
							<option value="20">20</option>
							<option value="21">21</option>
							<option value="22">22</option>
							<option value="23">23</option>
						</select>
					</div> <!-- end ScheduleHour column -->
					<div class="col-md-3 hourly-interval">
						<span class="form-label">Minute</span>
						<select id="ddlScheduleMinute" name="ddlScheduleMinute" class="form-control">
							<option value="00">00</option>
							<option value="05">05</option>
							<option value="10">10</option>
							<option value="15">15</option>
							<option value="20">20</option>
							<option value="25">25</option>
							<option value="30">30</option>
							<option value="35">35</option>
							<option value="40">40</option>
							<option value="45">45</option>
							<option value="50">50</option>
							<option value="55">55</option>
						</select>
					</div> <!-- end ScheduleMinute column -->
				</div> <!-- end row -->
				<div class="row">
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Server *</span>
						<input type="text" id="txtFtpServer" name="txtFtpServer" class="form-control" />
					</div> <!-- end FtpServer column -->
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Username *</span>
						<input type="text" id="txtFtpUsername" name="txtFtpUsername" class="form-control" />
					</div> <!-- end FtpUsername column -->
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Password *</span>
						<input type="password" id="txtFtpPassword" name="txtFtpPassword" class="form-control" />
					</div> <!-- end FtpPassword column -->
					<div class="col-md-3 ftp-backup">
						<span class="form-label">FTP Folder *</span>
						<input type="text" id="txtFtpFolder" name="txtFtpFolder" class="form-control" />
					</div> <!-- end FtpFolder column -->
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
else if ($Method == "load-run")
{
	$JobFactory = new JobFactory();
	$Record = $JobFactory->GetOne($JobId);
	if ($JobId != "" && $JobId > 0)
	{
		?>
		<!-- Form End -->
		<div id="frmMain">
			<div class="container well">
				<div class="row">
					<input type="hidden" id="hdnJobId" name="hdnJobId" class="form-control" value="<?php echo $Record->JobId; ?>" />
					<div class="col-md-3">
						<strong>Job Name: </strong><?php echo $Record->Name; ?>
					</div> <!-- end Name column -->
				</div> <!-- end row -->
				<div class="row" id="job-output">
				
				</div> <!-- end row -->
			</div> <!-- end container well -->
			<div class="container well button-container">
				<div class="row">
					<div class="col-md-12">
						<input type="button" value="Run" class="btn btn-primary default-button" onclick="RunJob(<?php echo $Record->JobId; ?>)" /> <img src="<?php echo FULL_URL; ?>_core/img/ajax-loader.gif" alt="Loading" id="loader" style="vertical-align:middle;display:none;" /> <input type="button" class="btn btn-primary" onclick="HideRun();" value="Cancel" />
						<div id="divOutput"></div>
					</div> <!-- end col-md-12 -->
				</div> <!-- end row -->
			</div> <!-- end container well button-container -->
		</div> <!-- end form -->
		<?php
	}
}
else if ($Method == "run-job")
{
	RunJob($JobId);
}
?>