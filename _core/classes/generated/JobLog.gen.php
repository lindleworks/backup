<?php
//Usage
/*
<?php include 'JobLog.gen.php' ?>
<?php
$JobLogFactory = new JobLogFactory();
$JobLog = $JobLogFactory->GetOne(1);
echo $JobLog->JobId . "<br />";
unset($JobLog);
$JobLogArray = $JobLogFactory->GetAll(' where JobLogId = 1 ');
foreach ($JobLogArray as &$value)
{
	echo $value->JobId . "<br />";
}
unset($value);
?>
*/
//Core Class
class JobLog
{
	var $JobLogId;
	var $JobId;
	var $DateRan;
	var $Status;
	var $DbBackupId;
	var $DbBackupFilename;
	var $DbBackupFilepath;
	var $DbBackupFilesize;
	var $FileBackupId;
	var $FileBackupFilename;
	var $FileBackupFilepath;
	var $FileBackupFilesize;
	var $Success;
	function setJobLogId($JobLogId)
	{
		$this->JobLogId = $JobLogId;
	}
	function getJobLogId()
	{
		return $this->JobLogId;
	}
	function setJobId($JobId)
	{
		$this->JobId = $JobId;
	}
	function getJobId()
	{
		return $this->JobId;
	}
	function setDateRan($DateRan)
	{
		$this->DateRan = $DateRan;
	}
	function getDateRan()
	{
		return $this->DateRan;
	}
	function setStatus($Status)
	{
		$this->Status = $Status;
	}
	function getStatus()
	{
		return $this->Status;
	}
	function setDbBackupId($DbBackupId)
	{
		$this->DbBackupId = $DbBackupId;
	}
	function getDbBackupId()
	{
		return $this->DbBackupId;
	}
	function setDbBackupFilename($DbBackupFilename)
	{
		$this->DbBackupFilename = $DbBackupFilename;
	}
	function getDbBackupFilename()
	{
		return $this->DbBackupFilename;
	}
	function setDbBackupFilepath($DbBackupFilepath)
	{
		$this->DbBackupFilepath = $DbBackupFilepath;
	}
	function getDbBackupFilepath()
	{
		return $this->DbBackupFilepath;
	}
	function setDbBackupFilesize($DbBackupFilesize)
	{
		$this->DbBackupFilesize = $DbBackupFilesize;
	}
	function getDbBackupFilesize()
	{
		return $this->DbBackupFilesize;
	}
	function setFileBackupId($FileBackupId)
	{
		$this->FileBackupId = $FileBackupId;
	}
	function getFileBackupId()
	{
		return $this->FileBackupId;
	}
	function setFileBackupFilename($FileBackupFilename)
	{
		$this->FileBackupFilename = $FileBackupFilename;
	}
	function getFileBackupFilename()
	{
		return $this->FileBackupFilename;
	}
	function setFileBackupFilepath($FileBackupFilepath)
	{
		$this->FileBackupFilepath = $FileBackupFilepath;
	}
	function getFileBackupFilepath()
	{
		return $this->FileBackupFilepath;
	}
	function setFileBackupFilesize($FileBackupFilesize)
	{
		$this->FileBackupFilesize = $FileBackupFilesize;
	}
	function getFileBackupFilesize()
	{
		return $this->FileBackupFilesize;
	}
	function setSuccess($Success)
	{
		$this->Success = $Success;
	}
	function getSuccess()
	{
		return $this->Success;
	}
}
//Factory Class
class JobLogFactory
{
	function GetOne($JobLogId)
	{
		$JobLog = new JobLog();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from JobLog where JobLogId = " . escapeSqliteString($JobLogId);
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$JobLog->JobLogId = $row['JobLogId'];
				$JobLog->JobId = $row['JobId'];
				$JobLog->DateRan = $row['DateRan'];
				$JobLog->Status = $row['Status'];
				$JobLog->DbBackupId = ($row['DbBackupId'] === NULL) ? "" : $row['DbBackupId'];
				$JobLog->DbBackupFilename = ($row['DbBackupFilename'] === NULL) ? "" : $row['DbBackupFilename'];
				$JobLog->DbBackupFilepath = ($row['DbBackupFilepath'] === NULL) ? "" : $row['DbBackupFilepath'];
				$JobLog->DbBackupFilesize = ($row['DbBackupFilesize'] === NULL) ? "" : $row['DbBackupFilesize'];
				$JobLog->FileBackupId = ($row['FileBackupId'] === NULL) ? "" : $row['FileBackupId'];
				$JobLog->FileBackupFilename = ($row['FileBackupFilename'] === NULL) ? "" : $row['FileBackupFilename'];
				$JobLog->FileBackupFilepath = ($row['FileBackupFilepath'] === NULL) ? "" : $row['FileBackupFilepath'];
				$JobLog->FileBackupFilesize = ($row['FileBackupFilesize'] === NULL) ? "" : $row['FileBackupFilesize'];
				$JobLog->Success = $row['Success'];
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $JobLog;
	}
	function GetAll($filter)
	{
		$JobLogArray = Array();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from JobLog " . $filter;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$JobLog = new JobLog();
				$JobLog->JobLogId = $row['JobLogId'];
				$JobLog->JobId = $row['JobId'];
				$JobLog->DateRan = $row['DateRan'];
				$JobLog->Status = $row['Status'];
				$JobLog->DbBackupId = ($row['DbBackupId'] === NULL) ? "" : $row['DbBackupId'];
				$JobLog->DbBackupFilename = ($row['DbBackupFilename'] === NULL) ? "" : $row['DbBackupFilename'];
				$JobLog->DbBackupFilepath = ($row['DbBackupFilepath'] === NULL) ? "" : $row['DbBackupFilepath'];
				$JobLog->DbBackupFilesize = ($row['DbBackupFilesize'] === NULL) ? "" : $row['DbBackupFilesize'];
				$JobLog->FileBackupId = ($row['FileBackupId'] === NULL) ? "" : $row['FileBackupId'];
				$JobLog->FileBackupFilename = ($row['FileBackupFilename'] === NULL) ? "" : $row['FileBackupFilename'];
				$JobLog->FileBackupFilepath = ($row['FileBackupFilepath'] === NULL) ? "" : $row['FileBackupFilepath'];
				$JobLog->FileBackupFilesize = ($row['FileBackupFilesize'] === NULL) ? "" : $row['FileBackupFilesize'];
				$JobLog->Success = $row['Success'];
				$JobLogArray[] = $JobLog;
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $JobLogArray;
	}
	function Save($JobLog)
	{
		$JobLogFactory = new JobLogFactory();
		if ($JobLog->JobLogId > 0)
		{
			$JobLogFactory->Update($JobLog);
		}
		else
		{
			$JobLogFactory->Insert($JobLog);
		}
	}
	function Insert($JobLog)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$insert = "";
			$insert .= escapeSqliteString($JobLog->JobId) . ",";
			$insert .= "'" . escapeSqliteString($JobLog->DateRan) . "',";
			$insert .= "'" . escapeSqliteString($JobLog->Status) . "',";
			$insert .= (escapeSqliteString($JobLog->DbBackupId) === "") ? "NULL," : escapeSqliteString($JobLog->DbBackupId) . ",";
			$insert .= (escapeSqliteString($JobLog->DbBackupFilename) === "") ? "NULL," : "'" . escapeSqliteString($JobLog->DbBackupFilename) . "',";
			$insert .= (escapeSqliteString($JobLog->DbBackupFilepath) === "") ? "NULL," : "'" . escapeSqliteString($JobLog->DbBackupFilepath) . "',";
			$insert .= (escapeSqliteString($JobLog->DbBackupFilesize) === "") ? "NULL," : escapeSqliteString($JobLog->DbBackupFilesize) . ",";
			$insert .= (escapeSqliteString($JobLog->FileBackupId) === "") ? "NULL," : escapeSqliteString($JobLog->FileBackupId) . ",";
			$insert .= (escapeSqliteString($JobLog->FileBackupFilename) === "") ? "NULL," : "'" . escapeSqliteString($JobLog->FileBackupFilename) . "',";
			$insert .= (escapeSqliteString($JobLog->FileBackupFilepath) === "") ? "NULL," : "'" . escapeSqliteString($JobLog->FileBackupFilepath) . "',";
			$insert .= (escapeSqliteString($JobLog->FileBackupFilesize) === "") ? "NULL," : escapeSqliteString($JobLog->FileBackupFilesize) . ",";
			$insert .= escapeSqliteString($JobLog->Success);
			$sql = "insert into JobLog (JobId,DateRan,Status,DbBackupId,DbBackupFilename,DbBackupFilepath,DbBackupFilesize,FileBackupId,FileBackupFilename,FileBackupFilepath,FileBackupFilesize,Success) values (" . $insert . ")";
			$db->exec($sql);
			$JobLog->JobLogId = $db->lastInsertId();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $JobLog;
	}
	function Update($JobLog)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$update = "";
			$update .= "JobId = " . escapeSqliteString($JobLog->JobId) . ",";
			$update .= "DateRan = '" . escapeSqliteString($JobLog->DateRan) . "',";
			$update .= "Status = '" . escapeSqliteString($JobLog->Status) . "',";
			$update .= "DbBackupId = " . ((escapeSqliteString($JobLog->DbBackupId) === "") ? "NULL" : escapeSqliteString($JobLog->DbBackupId)) . ",";
			$update .= "DbBackupFilename = " . ((escapeSqliteString($JobLog->DbBackupFilename) === "") ? "NULL" : "'" . escapeSqliteString($JobLog->DbBackupFilename) . "'") . ",";
			$update .= "DbBackupFilepath = " . ((escapeSqliteString($JobLog->DbBackupFilepath) === "") ? "NULL" : "'" . escapeSqliteString($JobLog->DbBackupFilepath) . "'") . ",";
			$update .= "DbBackupFilesize = " . ((escapeSqliteString($JobLog->DbBackupFilesize) === "") ? "NULL" : escapeSqliteString($JobLog->DbBackupFilesize)) . ",";
			$update .= "FileBackupId = " . ((escapeSqliteString($JobLog->FileBackupId) === "") ? "NULL" : escapeSqliteString($JobLog->FileBackupId)) . ",";
			$update .= "FileBackupFilename = " . ((escapeSqliteString($JobLog->FileBackupFilename) === "") ? "NULL" : "'" . escapeSqliteString($JobLog->FileBackupFilename) . "'") . ",";
			$update .= "FileBackupFilepath = " . ((escapeSqliteString($JobLog->FileBackupFilepath) === "") ? "NULL" : "'" . escapeSqliteString($JobLog->FileBackupFilepath) . "'") . ",";
			$update .= "FileBackupFilesize = " . ((escapeSqliteString($JobLog->FileBackupFilesize) === "") ? "NULL" : escapeSqliteString($JobLog->FileBackupFilesize)) . ",";
			$update .= "Success = " . escapeSqliteString($JobLog->Success);
			$sql = "update JobLog set " . $update . " where JobLogId = " . escapeSqliteString($JobLog->JobLogId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
	function Delete($JobLog)
	{
		$JobLogId = "";
		if (is_numeric($JobLog))
		{
			$JobLogId = $JobLog;
		}
		else
		{
			$JobLogId = $JobLog->JobLogId;
		}
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "delete from JobLog where JobLogId = " . escapeSqliteString($JobLogId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
}
?>