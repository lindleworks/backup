<?php
/* backup the db OR just a table */
function backupTables($JobId, $dbserver, $dbuser, $dbpassword, $database, $outputPath, $BackupDestinationType, $FtpServer, $FtpUsername, $FtpPassword, $FtpFolder)
{
	$dsn = "mysql:dbname=$database;host=$dbserver";
	$timestamp = time();
	try
	{
		$db = new PDO($dsn, $dbuser, $dbpassword);
		$output = "";
		if (substr($outputPath, -1) != "/")
		{
			$outputPath .= "/";
		}
		if ($BackupDestinationType == "FTP")
		{
			$outputPath = ROOT . "_core/temp/";
		}
		//EXPORT TABLES
		//get all of the tables
		$tables = array();
		$sql = "select * from INFORMATION_SCHEMA.tables where table_type = 'base table' and table_schema = '" . $database . "' order by table_name";
		$result = $db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$tables[] = $row["TABLE_NAME"];
		}
		//cycle through each table
		foreach($tables as $table)
		{
			$columnCount = 0;
			$sql = " select column_name, is_nullable, data_type,character_maximum_length,extra from INFORMATION_SCHEMA.columns where table_name = '" . $table . "' and table_schema = '" . $database . "'";
			$result = $db->query($sql);
			$TableStructureArray = Array();
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$TableStructure = new MySqlTableStructure();
				$TableStructure->ColumnName = $row['column_name'];
				$TableStructure->TypeName = $row['data_type'];
				$TableStructure->MaxLength = $row['character_maximum_length'];
				$TableStructure->IsNullable = $row['is_nullable'];
				$TableStructure->IsIdentity = $row['extra'];
				$TableStructureArray[] = $TableStructure;
				$columnCount++;
			}
			$sql = " SHOW CREATE TABLE " . $table;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$output .= "DROP TABLE IF EXISTS `" . $table . "`;\n";
				$output .= $row["Create Table"] . ";\n\n";
			}
			$sql = "SELECT * FROM `" . $table . "`";
			$result = $db->query($sql);
			
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$output.= 'INSERT INTO `'.$table.'` VALUES(';
				$j = 0;
				foreach ($TableStructureArray as &$value)
				{
					$row[$value->ColumnName] = addslashes($row[$value->ColumnName]);
					$row[$value->ColumnName] = ereg_replace("\n","\\n",$row[$value->ColumnName]);
					if (isset($row[$value->ColumnName])) { $output .= '"' . $row[$value->ColumnName] . '"' ; } else { $output .= '""'; }
					if ($j<($columnCount-1)) { $output .= ','; }
					$j++;
				}
				$output .= ");\n";
			}
			$output .="\n";
		}
		//EXPORT VIEWS
		$views = array();
		$sql = "select * from INFORMATION_SCHEMA.tables where table_type = 'view' and table_schema = '" . $database . "' order by table_name";
		$result = $db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$views[] = $row["TABLE_NAME"];
		}
		//cycle through each view
		foreach($views as $view)
		{
			$sql = " SHOW CREATE TABLE " . $view;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$output .= "DROP VIEW IF EXISTS `" . $view . "`;\n";
				$viewCreate = "CREATE " . substr($row["Create View"], strpos($row["Create View"], "VIEW"));
				$output .= $viewCreate . ";\n\n";
			}
		}
		//EXPORT FUNCTIONS
		
		//EXPORT TRIGGERS
		$triggers = array();
		$sql = "select * from INFORMATION_SCHEMA.triggers where trigger_schema = '" . $database . "' order by trigger_name";
		$result = $db->query($sql);
		while($row = $result->fetch(PDO::FETCH_ASSOC))
		{
			$triggers[] = $row["TRIGGER_NAME"];
		}
		//cycle through each trigger
		foreach($triggers as $trigger)
		{
			$sql = " SHOW CREATE TRIGGER " . $trigger;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$output .= "DROP TRIGGER IF EXISTS `" . $trigger . "`;\n";
				$triggerCreate = "CREATE " . substr($row["SQL Original Statement"], strpos($row["SQL Original Statement"], "TRIGGER"));
				$output .= $triggerCreate . ";\n\n";
			}
		}
		//save file
		/*
		//check max number of backups
		$backupCount = 0;
		$FileArray = Array();
		foreach (glob($outputPath . "*.sql") as $filename) {
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
		$filename = $database . "-" . date('Ymd');
		$baseFilename = $filename;
		$ext = ".sql";
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
		$handle = fopen($outputPath . $filename . $ext,'w+');
		fwrite($handle,$output);
		fclose($handle);
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
			$filename = str_replace(".sql", "", $distinctFilename);
		}
		$BackupFactory = new BackupFactory();
		$Backup = new Backup();
		$Backup->JobId = $JobId;
		$Backup->Status = "Backup successful!";
		$Backup->BackupType = "Database";
		$Backup->Success = true;
		$Backup->Filename = $filename . $ext;
		$Backup->Filepath = $filepath;
		$Backup->Filesize = $filesize;
		$Backup->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
		$BackupFactory->Save($Backup);
		return true;
	}
	catch (PDOException $e)
	{
		$BackupFactory = new BackupFactory();
		$Backup = new Backup();
		$Backup->JobId = $JobId;
		$Backup->BackupType = "Database";
		$Backup->Success = false;
		$Backup->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
		$Backup->Status = "Backup failed! " . $e->getMessage();
		$BackupFactory->Save($Backup);
		return false;// . $e->getMessage();
	}
}
?>