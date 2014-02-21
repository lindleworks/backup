<?php
//Usage
/*
<?php include 'Job.gen.php' ?>
<?php
$JobFactory = new JobFactory();
$Job = $JobFactory->GetOne(1);
echo $Job->Name . "<br />";
unset($Job);
$JobArray = $JobFactory->GetAll(' where JobId = 1 ');
foreach ($JobArray as &$value)
{
	echo $value->Name . "<br />";
}
unset($value);
?>
*/
//Core Class
class Job
{
	var $JobId;
	var $Name;
	var $DbBackup;
	var $FileBackup;
	var $BackupDestinationType;
	var $Email;
	var $BackupFolder;
	var $BackupStorageFolder;
	var $ScheduleInterval;
	var $ScheduleDayOfMonth;
	var $ScheduleDayOfWeek;
	var $ScheduleHour;
	var $ScheduleMinute;
	var $FtpServer;
	var $FtpUsername;
	var $FtpPassword;
	var $FtpFolder;
	var $MaximumNumberOfBackups;
	var $DbPassword;
	var $DbUsername;
	var $DbServer;
	var $DbName;
	var $DateCreated;
	var $NextRunDate;
	function setJobId($JobId)
	{
		$this->JobId = $JobId;
	}
	function getJobId()
	{
		return $this->JobId;
	}
	function setName($Name)
	{
		$this->Name = $Name;
	}
	function getName()
	{
		return $this->Name;
	}
	function setDbBackup($DbBackup)
	{
		$this->DbBackup = $DbBackup;
	}
	function getDbBackup()
	{
		return $this->DbBackup;
	}
	function setFileBackup($FileBackup)
	{
		$this->FileBackup = $FileBackup;
	}
	function getFileBackup()
	{
		return $this->FileBackup;
	}
	function setBackupDestinationType($BackupDestinationType)
	{
		$this->BackupDestinationType = $BackupDestinationType;
	}
	function getBackupDestinationType()
	{
		return $this->BackupDestinationType;
	}
	function setEmail($Email)
	{
		$this->Email = $Email;
	}
	function getEmail()
	{
		return $this->Email;
	}
	function setBackupFolder($BackupFolder)
	{
		$this->BackupFolder = $BackupFolder;
	}
	function getBackupFolder()
	{
		return $this->BackupFolder;
	}
	function setBackupStorageFolder($BackupStorageFolder)
	{
		$this->BackupStorageFolder = $BackupStorageFolder;
	}
	function getBackupStorageFolder()
	{
		return $this->BackupStorageFolder;
	}
	function setScheduleInterval($ScheduleInterval)
	{
		$this->ScheduleInterval = $ScheduleInterval;
	}
	function getScheduleInterval()
	{
		return $this->ScheduleInterval;
	}
	function setScheduleDayOfMonth($ScheduleDayOfMonth)
	{
		$this->ScheduleDayOfMonth = $ScheduleDayOfMonth;
	}
	function getScheduleDayOfMonth()
	{
		return $this->ScheduleDayOfMonth;
	}
	function setScheduleDayOfWeek($ScheduleDayOfWeek)
	{
		$this->ScheduleDayOfWeek = $ScheduleDayOfWeek;
	}
	function getScheduleDayOfWeek()
	{
		return $this->ScheduleDayOfWeek;
	}
	function setScheduleHour($ScheduleHour)
	{
		$this->ScheduleHour = $ScheduleHour;
	}
	function getScheduleHour()
	{
		return $this->ScheduleHour;
	}
	function setScheduleMinute($ScheduleMinute)
	{
		$this->ScheduleMinute = $ScheduleMinute;
	}
	function getScheduleMinute()
	{
		return $this->ScheduleMinute;
	}
	function setFtpServer($FtpServer)
	{
		$this->FtpServer = $FtpServer;
	}
	function getFtpServer()
	{
		return $this->FtpServer;
	}
	function setFtpUsername($FtpUsername)
	{
		$this->FtpUsername = $FtpUsername;
	}
	function getFtpUsername()
	{
		return $this->FtpUsername;
	}
	function setFtpPassword($FtpPassword)
	{
		$this->FtpPassword = $FtpPassword;
	}
	function getFtpPassword()
	{
		return $this->FtpPassword;
	}
	function setFtpFolder($FtpFolder)
	{
		$this->FtpFolder = $FtpFolder;
	}
	function getFtpFolder()
	{
		return $this->FtpFolder;
	}
	function setMaximumNumberOfBackups($MaximumNumberOfBackups)
	{
		$this->MaximumNumberOfBackups = $MaximumNumberOfBackups;
	}
	function getMaximumNumberOfBackups()
	{
		return $this->MaximumNumberOfBackups;
	}
	function setDbPassword($DbPassword)
	{
		$this->DbPassword = $DbPassword;
	}
	function getDbPassword()
	{
		return $this->DbPassword;
	}
	function setDbUsername($DbUsername)
	{
		$this->DbUsername = $DbUsername;
	}
	function getDbUsername()
	{
		return $this->DbUsername;
	}
	function setDbServer($DbServer)
	{
		$this->DbServer = $DbServer;
	}
	function getDbServer()
	{
		return $this->DbServer;
	}
	function setDbName($DbName)
	{
		$this->DbName = $DbName;
	}
	function getDbName()
	{
		return $this->DbName;
	}
	function setDateCreated($DateCreated)
	{
		$this->DateCreated = $DateCreated;
	}
	function getDateCreated()
	{
		return $this->DateCreated;
	}
	function setNextRunDate($NextRunDate)
	{
		$this->NextRunDate = $NextRunDate;
	}
	function getNextRunDate()
	{
		return $this->NextRunDate;
	}
}
//Factory Class
class JobFactory
{
	function GetOne($JobId)
	{
		$Job = new Job();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from Job where JobId = " . escapeSqliteString($JobId);
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$Job->JobId = $row['JobId'];
				$Job->Name = $row['Name'];
				$Job->DbBackup = $row['DbBackup'];
				$Job->FileBackup = $row['FileBackup'];
				$Job->BackupDestinationType = $row['BackupDestinationType'];
				$Job->Email = ($row['Email'] === NULL) ? "" : $row['Email'];
				$Job->BackupFolder = ($row['BackupFolder'] === NULL) ? "" : $row['BackupFolder'];
				$Job->BackupStorageFolder = ($row['BackupStorageFolder'] === NULL) ? "" : $row['BackupStorageFolder'];
				$Job->ScheduleInterval = ($row['ScheduleInterval'] === NULL) ? "" : $row['ScheduleInterval'];
				$Job->ScheduleDayOfMonth = ($row['ScheduleDayOfMonth'] === NULL) ? "" : $row['ScheduleDayOfMonth'];
				$Job->ScheduleDayOfWeek = ($row['ScheduleDayOfWeek'] === NULL) ? "" : $row['ScheduleDayOfWeek'];
				$Job->ScheduleHour = ($row['ScheduleHour'] === NULL) ? "" : $row['ScheduleHour'];
				$Job->ScheduleMinute = ($row['ScheduleMinute'] === NULL) ? "" : $row['ScheduleMinute'];
				$Job->FtpServer = ($row['FtpServer'] === NULL) ? "" : $row['FtpServer'];
				$Job->FtpUsername = ($row['FtpUsername'] === NULL) ? "" : $row['FtpUsername'];
				$Job->FtpPassword = ($row['FtpPassword'] === NULL) ? "" : $row['FtpPassword'];
				$Job->FtpFolder = ($row['FtpFolder'] === NULL) ? "" : $row['FtpFolder'];
				$Job->MaximumNumberOfBackups = $row['MaximumNumberOfBackups'];
				$Job->DbPassword = ($row['DbPassword'] === NULL) ? "" : $row['DbPassword'];
				$Job->DbUsername = ($row['DbUsername'] === NULL) ? "" : $row['DbUsername'];
				$Job->DbServer = ($row['DbServer'] === NULL) ? "" : $row['DbServer'];
				$Job->DbName = ($row['DbName'] === NULL) ? "" : $row['DbName'];
				$Job->DateCreated = $row['DateCreated'];
				$Job->NextRunDate = ($row['NextRunDate'] === NULL) ? "" : $row['NextRunDate'];
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $Job;
	}
	function GetAll($filter)
	{
		$JobArray = Array();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from Job " . $filter;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$Job = new Job();
				$Job->JobId = $row['JobId'];
				$Job->Name = $row['Name'];
				$Job->DbBackup = $row['DbBackup'];
				$Job->FileBackup = $row['FileBackup'];
				$Job->BackupDestinationType = $row['BackupDestinationType'];
				$Job->Email = ($row['Email'] === NULL) ? "" : $row['Email'];
				$Job->BackupFolder = ($row['BackupFolder'] === NULL) ? "" : $row['BackupFolder'];
				$Job->BackupStorageFolder = ($row['BackupStorageFolder'] === NULL) ? "" : $row['BackupStorageFolder'];
				$Job->ScheduleInterval = ($row['ScheduleInterval'] === NULL) ? "" : $row['ScheduleInterval'];
				$Job->ScheduleDayOfMonth = ($row['ScheduleDayOfMonth'] === NULL) ? "" : $row['ScheduleDayOfMonth'];
				$Job->ScheduleDayOfWeek = ($row['ScheduleDayOfWeek'] === NULL) ? "" : $row['ScheduleDayOfWeek'];
				$Job->ScheduleHour = ($row['ScheduleHour'] === NULL) ? "" : $row['ScheduleHour'];
				$Job->ScheduleMinute = ($row['ScheduleMinute'] === NULL) ? "" : $row['ScheduleMinute'];
				$Job->FtpServer = ($row['FtpServer'] === NULL) ? "" : $row['FtpServer'];
				$Job->FtpUsername = ($row['FtpUsername'] === NULL) ? "" : $row['FtpUsername'];
				$Job->FtpPassword = ($row['FtpPassword'] === NULL) ? "" : $row['FtpPassword'];
				$Job->FtpFolder = ($row['FtpFolder'] === NULL) ? "" : $row['FtpFolder'];
				$Job->MaximumNumberOfBackups = $row['MaximumNumberOfBackups'];
				$Job->DbPassword = ($row['DbPassword'] === NULL) ? "" : $row['DbPassword'];
				$Job->DbUsername = ($row['DbUsername'] === NULL) ? "" : $row['DbUsername'];
				$Job->DbServer = ($row['DbServer'] === NULL) ? "" : $row['DbServer'];
				$Job->DbName = ($row['DbName'] === NULL) ? "" : $row['DbName'];
				$Job->DateCreated = $row['DateCreated'];
				$Job->NextRunDate = ($row['NextRunDate'] === NULL) ? "" : $row['NextRunDate'];
				$JobArray[] = $Job;
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $JobArray;
	}
	function Save($Job)
	{
		$JobFactory = new JobFactory();
		if ($Job->JobId > 0)
		{
			$JobFactory->Update($Job);
		}
		else
		{
			$JobFactory->Insert($Job);
		}
	}
	function Insert($Job)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$insert = "";
			$insert .= "'" . escapeSqliteString($Job->Name) . "',";
			$insert .= escapeSqliteString($Job->DbBackup) . ",";
			$insert .= escapeSqliteString($Job->FileBackup) . ",";
			$insert .= "'" . escapeSqliteString($Job->BackupDestinationType) . "',";
			$insert .= (escapeSqliteString($Job->Email) === "") ? "NULL," : "'" . escapeSqliteString($Job->Email) . "',";
			$insert .= (escapeSqliteString($Job->BackupFolder) === "") ? "NULL," : "'" . escapeSqliteString($Job->BackupFolder) . "',";
			$insert .= (escapeSqliteString($Job->BackupStorageFolder) === "") ? "NULL," : "'" . escapeSqliteString($Job->BackupStorageFolder) . "',";
			$insert .= (escapeSqliteString($Job->ScheduleInterval) === "") ? "NULL," : "'" . escapeSqliteString($Job->ScheduleInterval) . "',";
			$insert .= (escapeSqliteString($Job->ScheduleDayOfMonth) === "") ? "NULL," : escapeSqliteString($Job->ScheduleDayOfMonth) . ",";
			$insert .= (escapeSqliteString($Job->ScheduleDayOfWeek) === "") ? "NULL," : "'" . escapeSqliteString($Job->ScheduleDayOfWeek) . "',";
			$insert .= (escapeSqliteString($Job->ScheduleHour) === "") ? "NULL," : escapeSqliteString($Job->ScheduleHour) . ",";
			$insert .= (escapeSqliteString($Job->ScheduleMinute) === "") ? "NULL," : escapeSqliteString($Job->ScheduleMinute) . ",";
			$insert .= (escapeSqliteString($Job->FtpServer) === "") ? "NULL," : "'" . escapeSqliteString($Job->FtpServer) . "',";
			$insert .= (escapeSqliteString($Job->FtpUsername) === "") ? "NULL," : "'" . escapeSqliteString($Job->FtpUsername) . "',";
			$insert .= (escapeSqliteString($Job->FtpPassword) === "") ? "NULL," : "'" . escapeSqliteString($Job->FtpPassword) . "',";
			$insert .= (escapeSqliteString($Job->FtpFolder) === "") ? "NULL," : "'" . escapeSqliteString($Job->FtpFolder) . "',";
			$insert .= escapeSqliteString($Job->MaximumNumberOfBackups) . ",";
			$insert .= (escapeSqliteString($Job->DbPassword) === "") ? "NULL," : "'" . escapeSqliteString($Job->DbPassword) . "',";
			$insert .= (escapeSqliteString($Job->DbUsername) === "") ? "NULL," : "'" . escapeSqliteString($Job->DbUsername) . "',";
			$insert .= (escapeSqliteString($Job->DbServer) === "") ? "NULL," : "'" . escapeSqliteString($Job->DbServer) . "',";
			$insert .= (escapeSqliteString($Job->DbName) === "") ? "NULL," : "'" . escapeSqliteString($Job->DbName) . "',";
			$insert .= "'" . escapeSqliteString($Job->DateCreated) . "',";
			$insert .= (escapeSqliteString($Job->NextRunDate) === "") ? "NULL" : "'" . escapeSqliteString($Job->NextRunDate) . "'";
			$sql = "insert into Job (Name,DbBackup,FileBackup,BackupDestinationType,Email,BackupFolder,BackupStorageFolder,ScheduleInterval,ScheduleDayOfMonth,ScheduleDayOfWeek,ScheduleHour,ScheduleMinute,FtpServer,FtpUsername,FtpPassword,FtpFolder,MaximumNumberOfBackups,DbPassword,DbUsername,DbServer,DbName,DateCreated,NextRunDate) values (" . $insert . ")";
			$db->exec($sql);
			$Job->JobId = $db->lastInsertId();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $Job;
	}
	function Update($Job)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$update = "";
			$update .= "Name = '" . escapeSqliteString($Job->Name) . "',";
			$update .= "DbBackup = " . escapeSqliteString($Job->DbBackup) . ",";
			$update .= "FileBackup = " . escapeSqliteString($Job->FileBackup) . ",";
			$update .= "BackupDestinationType = '" . escapeSqliteString($Job->BackupDestinationType) . "',";
			$update .= "Email = " . ((escapeSqliteString($Job->Email) === "") ? "NULL" : "'" . escapeSqliteString($Job->Email) . "'") . ",";
			$update .= "BackupFolder = " . ((escapeSqliteString($Job->BackupFolder) === "") ? "NULL" : "'" . escapeSqliteString($Job->BackupFolder) . "'") . ",";
			$update .= "BackupStorageFolder = " . ((escapeSqliteString($Job->BackupStorageFolder) === "") ? "NULL" : "'" . escapeSqliteString($Job->BackupStorageFolder) . "'") . ",";
			$update .= "ScheduleInterval = " . ((escapeSqliteString($Job->ScheduleInterval) === "") ? "NULL" : "'" . escapeSqliteString($Job->ScheduleInterval) . "'") . ",";
			$update .= "ScheduleDayOfMonth = " . ((escapeSqliteString($Job->ScheduleDayOfMonth) === "") ? "NULL" : escapeSqliteString($Job->ScheduleDayOfMonth)) . ",";
			$update .= "ScheduleDayOfWeek = " . ((escapeSqliteString($Job->ScheduleDayOfWeek) === "") ? "NULL" : "'" . escapeSqliteString($Job->ScheduleDayOfWeek) . "'") . ",";
			$update .= "ScheduleHour = " . ((escapeSqliteString($Job->ScheduleHour) === "") ? "NULL" : escapeSqliteString($Job->ScheduleHour)) . ",";
			$update .= "ScheduleMinute = " . ((escapeSqliteString($Job->ScheduleMinute) === "") ? "NULL" : escapeSqliteString($Job->ScheduleMinute)) . ",";
			$update .= "FtpServer = " . ((escapeSqliteString($Job->FtpServer) === "") ? "NULL" : "'" . escapeSqliteString($Job->FtpServer) . "'") . ",";
			$update .= "FtpUsername = " . ((escapeSqliteString($Job->FtpUsername) === "") ? "NULL" : "'" . escapeSqliteString($Job->FtpUsername) . "'") . ",";
			$update .= "FtpPassword = " . ((escapeSqliteString($Job->FtpPassword) === "") ? "NULL" : "'" . escapeSqliteString($Job->FtpPassword) . "'") . ",";
			$update .= "FtpFolder = " . ((escapeSqliteString($Job->FtpFolder) === "") ? "NULL" : "'" . escapeSqliteString($Job->FtpFolder) . "'") . ",";
			$update .= "MaximumNumberOfBackups = " . escapeSqliteString($Job->MaximumNumberOfBackups) . ",";
			$update .= "DbPassword = " . ((escapeSqliteString($Job->DbPassword) === "") ? "NULL" : "'" . escapeSqliteString($Job->DbPassword) . "'") . ",";
			$update .= "DbUsername = " . ((escapeSqliteString($Job->DbUsername) === "") ? "NULL" : "'" . escapeSqliteString($Job->DbUsername) . "'") . ",";
			$update .= "DbServer = " . ((escapeSqliteString($Job->DbServer) === "") ? "NULL" : "'" . escapeSqliteString($Job->DbServer) . "'") . ",";
			$update .= "DbName = " . ((escapeSqliteString($Job->DbName) === "") ? "NULL" : "'" . escapeSqliteString($Job->DbName) . "'") . ",";
			$update .= "DateCreated = '" . escapeSqliteString($Job->DateCreated) . "',";
			$update .= "NextRunDate = " . ((escapeSqliteString($Job->NextRunDate) === "") ? "NULL" : "'" . escapeSqliteString($Job->NextRunDate)) . "'";
			$sql = "update Job set " . $update . " where JobId = " . escapeSqliteString($Job->JobId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
	function Delete($Job)
	{
		$JobId = "";
		if (is_numeric($Job))
		{
			$JobId = $Job;
		}
		else
		{
			$JobId = $Job->JobId;
		}
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "delete from Job where JobId = " . escapeSqliteString($JobId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
}
?>