<?php
//Usage
/*
<?php include 'Backup.gen.php' ?>
<?php
$BackupFactory = new BackupFactory();
$Backup = $BackupFactory->GetOne(1);
echo $Backup->JobId . "<br />";
unset($Backup);
$BackupArray = $BackupFactory->GetAll(' where BackupId = 1 ');
foreach ($BackupArray as &$value)
{
	echo $value->JobId . "<br />";
}
unset($value);
?>
*/
//Core Class
class Backup
{
	var $BackupId;
	var $JobId;
	var $DateCreated;
	var $Success;
	var $Status;
	var $BackupType;
	var $Filename;
	var $Filepath;
	var $Filesize;
	function setBackupId($BackupId)
	{
		$this->BackupId = $BackupId;
	}
	function getBackupId()
	{
		return $this->BackupId;
	}
	function setJobId($JobId)
	{
		$this->JobId = $JobId;
	}
	function getJobId()
	{
		return $this->JobId;
	}
	function setDateCreated($DateCreated)
	{
		$this->DateCreated = $DateCreated;
	}
	function getDateCreated()
	{
		return $this->DateCreated;
	}
	function setSuccess($Success)
	{
		$this->Success = $Success;
	}
	function getSuccess()
	{
		return $this->Success;
	}
	function setStatus($Status)
	{
		$this->Status = $Status;
	}
	function getStatus()
	{
		return $this->Status;
	}
	function setBackupType($BackupType)
	{
		$this->BackupType = $BackupType;
	}
	function getBackupType()
	{
		return $this->BackupType;
	}
	function setFilename($Filename)
	{
		$this->Filename = $Filename;
	}
	function getFilename()
	{
		return $this->Filename;
	}
	function setFilepath($Filepath)
	{
		$this->Filepath = $Filepath;
	}
	function getFilepath()
	{
		return $this->Filepath;
	}
	function setFilesize($Filesize)
	{
		$this->Filesize = $Filesize;
	}
	function getFilesize()
	{
		return $this->Filesize;
	}
}
//Factory Class
class BackupFactory
{
	function GetOne($BackupId)
	{
		$Backup = new Backup();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from Backup where BackupId = " . escapeSqliteString($BackupId);
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$Backup->BackupId = $row['BackupId'];
				$Backup->JobId = $row['JobId'];
				$Backup->DateCreated = $row['DateCreated'];
				$Backup->Success = $row['Success'];
				$Backup->Status = $row['Status'];
				$Backup->BackupType = $row['BackupType'];
				$Backup->Filename = ($row['Filename'] === NULL) ? "" : $row['Filename'];
				$Backup->Filepath = ($row['Filepath'] === NULL) ? "" : $row['Filepath'];
				$Backup->Filesize = ($row['Filesize'] === NULL) ? "" : $row['Filesize'];
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $Backup;
	}
	function GetAll($filter)
	{
		$BackupArray = Array();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from Backup " . $filter;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$Backup = new Backup();
				$Backup->BackupId = $row['BackupId'];
				$Backup->JobId = $row['JobId'];
				$Backup->DateCreated = $row['DateCreated'];
				$Backup->Success = $row['Success'];
				$Backup->Status = $row['Status'];
				$Backup->BackupType = $row['BackupType'];
				$Backup->Filename = ($row['Filename'] === NULL) ? "" : $row['Filename'];
				$Backup->Filepath = ($row['Filepath'] === NULL) ? "" : $row['Filepath'];
				$Backup->Filesize = ($row['Filesize'] === NULL) ? "" : $row['Filesize'];
				$BackupArray[] = $Backup;
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $BackupArray;
	}
	function Save($Backup)
	{
		$BackupFactory = new BackupFactory();
		if ($Backup->BackupId > 0)
		{
			$BackupFactory->Update($Backup);
		}
		else
		{
			$BackupFactory->Insert($Backup);
		}
	}
	function Insert($Backup)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$insert = "";
			$insert .= escapeSqliteString($Backup->JobId) . ",";
			$insert .= "'" . escapeSqliteString($Backup->DateCreated) . "',";
			$insert .= escapeSqliteString($Backup->Success) . ",";
			$insert .= "'" . escapeSqliteString($Backup->Status) . "',";
			$insert .= "'" . escapeSqliteString($Backup->BackupType) . "',";
			$insert .= (escapeSqliteString($Backup->Filename) === "") ? "NULL," : "'" . escapeSqliteString($Backup->Filename) . "',";
			$insert .= (escapeSqliteString($Backup->Filepath) === "") ? "NULL," : "'" . escapeSqliteString($Backup->Filepath) . "',";
			$insert .= (escapeSqliteString($Backup->Filesize) === "") ? "NULL" : escapeSqliteString($Backup->Filesize);
			$sql = "insert into Backup (JobId,DateCreated,Success,Status,BackupType,Filename,Filepath,Filesize) values (" . $insert . ")";
			$db->exec($sql);
			$Backup->BackupId = $db->lastInsertId();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $Backup;
	}
	function Update($Backup)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$update = "";
			$update .= "JobId = " . escapeSqliteString($Backup->JobId) . ",";
			$update .= "DateCreated = '" . escapeSqliteString($Backup->DateCreated) . "',";
			$update .= "Success = " . escapeSqliteString($Backup->Success) . ",";
			$update .= "Status = '" . escapeSqliteString($Backup->Status) . "',";
			$update .= "BackupType = '" . escapeSqliteString($Backup->BackupType) . "',";
			$update .= "Filename = " . ((escapeSqliteString($Backup->Filename) === "") ? "NULL" : "'" . escapeSqliteString($Backup->Filename) . "'") . ",";
			$update .= "Filepath = " . ((escapeSqliteString($Backup->Filepath) === "") ? "NULL" : "'" . escapeSqliteString($Backup->Filepath) . "'") . ",";
			$update .= "Filesize = " . ((escapeSqliteString($Backup->Filesize) === "") ? "NULL" : escapeSqliteString($Backup->Filesize));
			$sql = "update Backup set " . $update . " where BackupId = " . escapeSqliteString($Backup->BackupId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
	function Delete($Backup)
	{
		$BackupId = "";
		if (is_numeric($Backup))
		{
			$BackupId = $Backup;
		}
		else
		{
			$BackupId = $Backup->BackupId;
		}
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "delete from Backup where BackupId = " . escapeSqliteString($BackupId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
}
?>