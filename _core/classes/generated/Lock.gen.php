<?php
//Usage
/*
<?php include 'Lock.gen.php' ?>
<?php
$LockFactory = new LockFactory();
$Lock = $LockFactory->GetOne(1);
echo $Lock->Locked . "<br />";
unset($Lock);
$LockArray = $LockFactory->GetAll(' where LockId = 1 ');
foreach ($LockArray as &$value)
{
	echo $value->Locked . "<br />";
}
unset($value);
?>
*/
//Core Class
class Lock
{
	var $LockId;
	var $Locked;
	function setLockId($LockId)
	{
		$this->LockId = $LockId;
	}
	function getLockId()
	{
		return $this->LockId;
	}
	function setLocked($Locked)
	{
		$this->Locked = $Locked;
	}
	function getLocked()
	{
		return $this->Locked;
	}
}
//Factory Class
class LockFactory
{
	function GetOne($LockId)
	{
		$Lock = new Lock();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from Lock where LockId = " . escapeSqliteString($LockId);
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$Lock->LockId = $row['LockId'];
				$Lock->Locked = $row['Locked'];
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $Lock;
	}
	function GetAll($filter)
	{
		$LockArray = Array();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from Lock " . $filter;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$Lock = new Lock();
				$Lock->LockId = $row['LockId'];
				$Lock->Locked = $row['Locked'];
				$LockArray[] = $Lock;
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $LockArray;
	}
	function Save($Lock)
	{
		$LockFactory = new LockFactory();
		if ($Lock->LockId > 0)
		{
			$LockFactory->Update($Lock);
		}
		else
		{
			$LockFactory->Insert($Lock);
		}
	}
	function Insert($Lock)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$insert = "";
			$insert .= escapeSqliteString($Lock->Locked);
			$sql = "insert into Lock (Locked) values (" . $insert . ")";
			$db->exec($sql);
			$Lock->LockId = $db->lastInsertId();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $Lock;
	}
	function Update($Lock)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$update = "";
			$update .= "Locked = " . escapeSqliteString($Lock->Locked);
			$sql = "update Lock set " . $update . " where LockId = " . escapeSqliteString($Lock->LockId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
	function Delete($Lock)
	{
		$LockId = "";
		if (is_numeric($Lock))
		{
			$LockId = $Lock;
		}
		else
		{
			$LockId = $Lock->LockId;
		}
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "delete from Lock where LockId = " . escapeSqliteString($LockId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
}
?>