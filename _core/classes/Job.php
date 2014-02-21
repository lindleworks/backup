<?php

function RunJob($JobId)
{
	$JobFactory = new JobFactory();
	$Record = $JobFactory->GetOne($JobId);
	if ($JobId != "" && $JobId > 0)
	{
		$success = true;
		$status = "";
		$timestamp = time();
		$JobLogFactory = new JobLogFactory();
		$BackupFactory = new BackupFactory();
		$JobLog = new JobLog();
		$JobLog->JobId = $JobId;
		$JobLog->DateRan = gmdate("Y-m-d H:i:s", $timestamp);
		$storageDirectory = "";
		if ($Record->BackupDestinationType == "Local")
		{
			$storageDirectory = $Record->BackupStorageFolder;
		}
		else if ($Record->BackupDestinationType == "FTP")
		{
			$storageDirectory = $Record->FtpFolder;
		}
		//check max number of backups
		$backupCount = 0;
		$JobLogArray = $JobLogFactory->GetAll(" where JobId = " . $JobId . " and Success = 1 order by DateRan desc ");
		if (count($JobLogArray) >= $Record->MaximumNumberOfBackups)
		{
			$i = 0;
			foreach ($JobLogArray as &$JobLogInner)
			{
				$i++;
				if ($i >= $Record->MaximumNumberOfBackups)
				{
					if ($Record->BackupDestinationType == "Local")
					{
						//handle local file deletion
						if ($JobLogInner->DbBackupFilepath != "")
						{
							unlink($JobLogInner->DbBackupFilepath);
						}
						if ($JobLogInner->FileBackupFilepath != "")
						{
							unlink($JobLogInner->FileBackupFilepath);
						}
					}
					else if ($Record->BackupDestinationType == "FTP")
					{
						//handle remote file deletion
						if ($JobLogInner->DbBackupFilepath != "")
						{
							FtpDeleteFile($Record->FtpServer, $Record->FtpUsername, $Record->FtpPassword, $JobLogInner->DbBackupFilepath);
						}
						if ($JobLogInner->FileBackupFilepath != "")
						{
							FtpDeleteFile($Record->FtpServer, $Record->FtpUsername, $Record->FtpPassword, $JobLogInner->FileBackupFilepath);
						}
					}
					$BackupFactory->Delete($JobLogInner->DbBackupId);
					$BackupFactory->Delete($JobLogInner->FileBackupId);
					$JobLogFactory->Delete($JobLogInner);
				}
			}
		}
		if ($Record->DbBackup == 1)
		{
			require_once ROOT . "/_core/handlers/DatabaseBackup.php";
			if (backupTables($Record->JobId, $Record->DbServer, $Record->DbUsername, $Record->DbPassword, $Record->DbName, $storageDirectory, $Record->BackupDestinationType, $Record->FtpServer, $Record->FtpUsername, $Record->FtpPassword, $Record->FtpFolder))
			{
				echo "<div class=\"col-md-12\"><div class=\"alert alert-success\">Database backup succeeded!</div></div>";
				$status .= "Database backup succeeded! \n";
				$BackupArray = $BackupFactory->GetAll(" where JobId = " . escapeSqliteString($JobId) . " and BackupType = 'Database' order by DateCreated desc ");
				$i = 0;
				foreach ($BackupArray as &$Backup)
				{
					$i++;
					if ($i == 1)
					{
						$JobLog->DbBackupId = $Backup->BackupId;
						$JobLog->DbBackupFilename = $Backup->Filename;
						$JobLog->DbBackupFilepath = $Backup->Filepath;
						$JobLog->DbBackupFilesize = $Backup->Filesize;
					}
				}
			}
			else
			{
				echo "<div class=\"col-md-12\"><div class=\"alert alert-danger\">Database backup failed!</div></div>";
				$success = false;
				$status .= "Database backup failed! \n";
			}
		}
		if ($Record->FileBackup == 1)
		{
			require_once ROOT . "/_core/handlers/FileBackup.php";
			if (backupDirectory($Record->JobId, $Record->BackupFolder, $storageDirectory, $Record->BackupDestinationType, $Record->FtpServer, $Record->FtpUsername, $Record->FtpPassword, $Record->FtpFolder))
			{
				echo "<div class=\"col-md-12\"><div class=\"alert alert-success\">File backup succeeded!</div></div>";
				$status .= "File backup succeeded! \n";
				$BackupArray = $BackupFactory->GetAll(" where JobId = " . escapeSqliteString($JobId) . " and BackupType = 'File' order by DateCreated desc ");
				$i = 0;
				foreach ($BackupArray as &$Backup)
				{
					$i++;
					if ($i == 1)
					{
						$JobLog->FileBackupId = $Backup->BackupId;
						$JobLog->FileBackupFilename = $Backup->Filename;
						$JobLog->FileBackupFilepath = $Backup->Filepath;
						$JobLog->FileBackupFilesize = $Backup->Filesize;
					}
				}
			}
			else
			{
				echo "<div class=\"col-md-12\"><div class=\"alert alert-danger\">File backup failed!</div></div>";
				$success = false;
				$status .= "File backup failed! \n";
			}
		}
		$JobLog->Success = $success;
		$JobLog->Status = $status;
		$JobLogFactory->Save($JobLog);
		$Record->NextRunDate = gmdate("Y-m-d H:i:s", ReturnNextRun($Record));
		$JobFactory->Save($Record);
		if (check_email_address($Record->Email))
		{
			if ($success)
			{
				//send email
		        $subject =  "Backup Utility - " . $Record->Name . " Job Ran Successfully!";
		        $text = $status; 
		        $html = $status;
		        utilitySendEmail($Record->Email,$subject,$text,$html);
			}
			else
			{
				//send email
		        $subject = "Backup Utility - " . $Record->Name . " Job Failed!";
		        $text = $status; 
		        $html = $status;
		        utilitySendEmail($value->Email,$subject,$text,$html);
			}
		}
	}
}
?>