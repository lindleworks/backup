<?php
//Usage
/*
<?php include 'LogAuthentication.gen.php' ?>
<?php
$LogAuthenticationFactory = new LogAuthenticationFactory();
$LogAuthentication = $LogAuthenticationFactory->GetOne(1);
echo $LogAuthentication->Username . "<br />";
unset($LogAuthentication);
$LogAuthenticationArray = $LogAuthenticationFactory->GetAll(' where AuthenticationLogId = 1 ');
foreach ($LogAuthenticationArray as &$value)
{
	echo $value->Username . "<br />";
}
unset($value);
?>
*/
//Core Class
class LogAuthentication
{
	var $AuthenticationLogId;
	var $Username;
	var $IpAddress;
	var $Successful;
	var $DateCreated;
	function setAuthenticationLogId($AuthenticationLogId)
	{
		$this->AuthenticationLogId = $AuthenticationLogId;
	}
	function getAuthenticationLogId()
	{
		return $this->AuthenticationLogId;
	}
	function setUsername($Username)
	{
		$this->Username = $Username;
	}
	function getUsername()
	{
		return $this->Username;
	}
	function setIpAddress($IpAddress)
	{
		$this->IpAddress = $IpAddress;
	}
	function getIpAddress()
	{
		return $this->IpAddress;
	}
	function setSuccessful($Successful)
	{
		$this->Successful = $Successful;
	}
	function getSuccessful()
	{
		return $this->Successful;
	}
	function setDateCreated($DateCreated)
	{
		$this->DateCreated = $DateCreated;
	}
	function getDateCreated()
	{
		return $this->DateCreated;
	}
}
//Factory Class
class LogAuthenticationFactory
{
	function GetOne($AuthenticationLogId)
	{
		$LogAuthentication = new LogAuthentication();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from LogAuthentication where AuthenticationLogId = " . escapeSqliteString($AuthenticationLogId);
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$LogAuthentication->AuthenticationLogId = $row['AuthenticationLogId'];
				$LogAuthentication->Username = $row['Username'];
				$LogAuthentication->IpAddress = $row['IpAddress'];
				$LogAuthentication->Successful = $row['Successful'];
				$LogAuthentication->DateCreated = $row['DateCreated'];
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $LogAuthentication;
	}
	function GetAll($filter)
	{
		$LogAuthenticationArray = Array();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from LogAuthentication " . $filter;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$LogAuthentication = new LogAuthentication();
				$LogAuthentication->AuthenticationLogId = $row['AuthenticationLogId'];
				$LogAuthentication->Username = $row['Username'];
				$LogAuthentication->IpAddress = $row['IpAddress'];
				$LogAuthentication->Successful = $row['Successful'];
				$LogAuthentication->DateCreated = $row['DateCreated'];
				$LogAuthenticationArray[] = $LogAuthentication;
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $LogAuthenticationArray;
	}
	function Save($LogAuthentication)
	{
		$LogAuthenticationFactory = new LogAuthenticationFactory();
		if ($LogAuthentication->AuthenticationLogId > 0)
		{
			$LogAuthenticationFactory->Update($LogAuthentication);
		}
		else
		{
			$LogAuthenticationFactory->Insert($LogAuthentication);
		}
	}
	function Insert($LogAuthentication)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$insert = "";
			$insert .= "'" . escapeSqliteString($LogAuthentication->Username) . "',";
			$insert .= "'" . escapeSqliteString($LogAuthentication->IpAddress) . "',";
			$insert .= escapeSqliteString($LogAuthentication->Successful) . ",";
			$insert .= "'" . escapeSqliteString($LogAuthentication->DateCreated) . "'";
			$sql = "insert into LogAuthentication (Username,IpAddress,Successful,DateCreated) values (" . $insert . ")";
			$db->exec($sql);
			$LogAuthentication->AuthenticationLogId = $db->lastInsertId();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $LogAuthentication;
	}
	function Update($LogAuthentication)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$update = "";
			$update .= "Username = '" . escapeSqliteString($LogAuthentication->Username) . "',";
			$update .= "IpAddress = '" . escapeSqliteString($LogAuthentication->IpAddress) . "',";
			$update .= "Successful = " . escapeSqliteString($LogAuthentication->Successful) . ",";
			$update .= "DateCreated = '" . escapeSqliteString($LogAuthentication->DateCreated) . "'";
			$sql = "update LogAuthentication set " . $update . " where AuthenticationLogId = " . escapeSqliteString($LogAuthentication->AuthenticationLogId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
	function Delete($LogAuthentication)
	{
		$AuthenticationLogId = "";
		if (is_numeric($LogAuthentication))
		{
			$AuthenticationLogId = $LogAuthentication;
		}
		else
		{
			$AuthenticationLogId = $LogAuthentication->AuthenticationLogId;
		}
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "delete from LogAuthentication where AuthenticationLogId = " . escapeSqliteString($AuthenticationLogId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
}
?>