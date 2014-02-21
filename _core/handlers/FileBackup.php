<?php
/* backup the db OR just a table */
function backupDirectory($JobId, $directory, $outputPath, $BackupDestinationType, $FtpServer, $FtpUsername, $FtpPassword, $FtpFolder)
{
	$timestamp = time();
	// Include the PclZip library
	require_once ROOT . "/_core/classes/utility/pclzip.lib.php";
	try
	{
		$output = "";
		if (substr($outputPath, -1) != "/")
		{
			$outputPath .= "/";
		}
		$excludedBackupDirectory = $outputPath;
		if ($BackupDestinationType == "FTP")
		{
			if (substr($excludedBackupDirectory, 0, 1) == "/")
			{
				$excludedBackupDirectory = substr($excludedBackupDirectory, 1);
			}
			$excludedBackupDirectory = ROOT . $excludedBackupDirectory;
			$outputPath = ROOT . "_core/temp/";
		}
		/*
		//check max number of backups
		$backupCount = 0;
		$FileArray = Array();
		foreach (glob($outputPath . "*.zip") as $filename) {
			$File = new File();
			$File->filename = basename($filename);
			$File->filepath = $filename;
			$File->filesize = filesize($filename);
			$File->extension = pathinfo($filename, PATHINFO_EXTENSION);
			$File->dateModified = filemtime($filename);
			$FileArray[] = $File;
		    $backupCount++;
		}
		if ($backupCount >= $maxBackups) //too many, must delete one or more
		{
			usort($FileArray, 'sortByDateModified');
			$i = 0;
			foreach ($FileArray as &$File)
			{
				$i++;
				if ($i >= $maxBackups)
				{
					unlink($File->filepath);
				}
				/*
				echo $File->filename . "<br />";
				echo $File->filepath . "<br />";
				echo $File->filesize . "<br />";
				echo $File->extension . "<br />";
				echo date ("m-d-Y H:i:s", $File->dateModified) . "<br />";
				echo "<br />";
			}
		}
		*/
		$directoryName = "";
		if (substr($directory, 0, 1) == "/")
		{
			$directoryName = basename($directory);
		}
		$filename = $directoryName . "-" . date('Ymd');
		$baseFilename = $filename;
		$ext = ".zip";
		$i = 0;
		// don't overwrite previous files that were uploaded
        while (file_exists($outputPath . $filename . $ext)) {
        	$i++;
        	if (strpos($filename, "(" . ($i-1) . ")") > -1)
        	{
	        	$filename = str_replace("(" . ($i-1) . ")", "", $filename);
        	}
            $filename .= "(" . $i . ")";
        }
	    // Set the archive filename
	    $archive = new PclZip($outputPath . $filename . $ext);
	 
	    // Set the dir to archive
	    $v_dir = $directory; // or dirname(__FILE__);
	    $v_remove = $v_dir;
	 
		$excludedDirectories = substr($excludedBackupDirectory, 0, -1) . ",";
		$excludedDirectories .= ROOT . "_core/temp,";
		
	    // Create the archive
	    $v_list = $archive->create($v_dir, PCLZIP_OPT_REMOVE_PATH, $v_remove, PCLZIP_OPT_COMMENT, $excludedDirectories);
	    if ($v_list == 0) {
	    	$BackupFactory = new BackupFactory();
			$Backup = new Backup();
			$Backup->JobId = $JobId;
			$Backup->BackupType = "File";
			$Backup->Success = false;
			$Backup->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
			$Backup->Status = "Backup failed! Error : " . $archive->errorInfo(true);
			$BackupFactory->Save($Backup);
			return false;
	    }
	    else
	    {
	    	$filepath = $outputPath . $filename . $ext;
	    	$filesize = filesize($filepath);
			if ($BackupDestinationType == "FTP")
			{
				$distinctFilename = FtpGetUniqueFilename($FtpServer, $FtpUsername, $FtpPassword, $FtpFolder, $baseFilename, $ext);
				if (FtpUploadFile($FtpServer, $FtpUsername, $FtpPassword, $distinctFilename, $filepath, $FtpFolder))
				{
					unlink($filepath);
					$filepath = $FtpFolder . $distinctFilename;
					$filesize = FtpGetFilesize($FtpServer, $FtpUsername, $FtpPassword, $filepath);
					//delete old files
					/** define the directory **/
					$dir = ROOT . "_core/temp/";
					/*** cycle through all files in the directory ***/
					foreach (glob($dir."*") as $file) 
					{
						/*** if file is 24 hours (86400 seconds) old then delete it ***/
						if (filemtime($file) < time() - 86400) 
						{
					    	unlink($file);
					    }
					}
				}
				else
				{
					return false;
				}
				$filename = str_replace(".zip", "", $distinctFilename);
			}
		    $BackupFactory = new BackupFactory();
			$Backup = new Backup();
			$Backup->JobId = $JobId;
			$Backup->Status = "Backup successful!";
			$Backup->BackupType = "File";
			$Backup->Success = true;
			$Backup->Filename = $filename . $ext;
			$Backup->Filepath = $filepath;
			$Backup->Filesize = $filesize;
			$Backup->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
			$BackupFactory->Save($Backup);
			return true;
	    }
	}
	catch (PDOException $e)
	{
		$BackupFactory = new BackupFactory();
		$Backup = new Backup();
		$Backup->JobId = $JobId;
		$Backup->BackupType = "File";
		$Backup->Success = false;
		$Backup->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
		$Backup->Status = "Backup failed! " . $e->getMessage();
		$BackupFactory->Save($Backup);
		return false;// . $e->getMessage();
	}
}
?>