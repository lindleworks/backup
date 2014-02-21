<?php
//Usage
/*
<?php include 'User.gen.php' ?>
<?php
$UserFactory = new UserFactory();
$User = $UserFactory->GetOne(1);
echo $User->Username . "<br />";
unset($User);
$UserArray = $UserFactory->GetAll(' where UserId = 1 ');
foreach ($UserArray as &$value)
{
	echo $value->Username . "<br />";
}
unset($value);
?>
*/
//Core Class
class User
{
	var $UserId;
	var $Username;
	var $Password;
	var $Email;
	var $Firstname;
	var $Lastname;
	function setUserId($UserId)
	{
		$this->UserId = $UserId;
	}
	function getUserId()
	{
		return $this->UserId;
	}
	function setUsername($Username)
	{
		$this->Username = $Username;
	}
	function getUsername()
	{
		return $this->Username;
	}
	function setPassword($Password)
	{
		$this->Password = $Password;
	}
	function getPassword()
	{
		return $this->Password;
	}
	function setEmail($Email)
	{
		$this->Email = $Email;
	}
	function getEmail()
	{
		return $this->Email;
	}
	function setFirstname($Firstname)
	{
		$this->Firstname = $Firstname;
	}
	function getFirstname()
	{
		return $this->Firstname;
	}
	function setLastname($Lastname)
	{
		$this->Lastname = $Lastname;
	}
	function getLastname()
	{
		return $this->Lastname;
	}
}
//Factory Class
class UserFactory
{
	function GetOne($UserId)
	{
		$User = new User();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from User where UserId = " . escapeSqliteString($UserId);
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$User->UserId = $row['UserId'];
				$User->Username = $row['Username'];
				$User->Password = $row['Password'];
				$User->Email = $row['Email'];
				$User->Firstname = $row['Firstname'];
				$User->Lastname = $row['Lastname'];
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $User;
	}
	function GetAll($filter)
	{
		$UserArray = Array();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from User " . $filter;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$User = new User();
				$User->UserId = $row['UserId'];
				$User->Username = $row['Username'];
				$User->Password = $row['Password'];
				$User->Email = $row['Email'];
				$User->Firstname = $row['Firstname'];
				$User->Lastname = $row['Lastname'];
				$UserArray[] = $User;
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $UserArray;
	}
	function Save($User)
	{
		$UserFactory = new UserFactory();
		if ($User->UserId > 0)
		{
			$UserFactory->Update($User);
		}
		else
		{
			$UserFactory->Insert($User);
		}
	}
	function Insert($User)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$insert = "";
			$insert .= "'" . escapeSqliteString($User->Username) . "',";
			$insert .= "'" . escapeSqliteString($User->Password) . "',";
			$insert .= "'" . escapeSqliteString($User->Email) . "',";
			$insert .= "'" . escapeSqliteString($User->Firstname) . "',";
			$insert .= "'" . escapeSqliteString($User->Lastname) . "'";
			$sql = "insert into User (Username,Password,Email,Firstname,Lastname) values (" . $insert . ")";
			$db->exec($sql);
			$User->UserId = $db->lastInsertId();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $User;
	}
	function Update($User)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$update = "";
			$update .= "Username = '" . escapeSqliteString($User->Username) . "',";
			$update .= "Password = '" . escapeSqliteString($User->Password) . "',";
			$update .= "Email = '" . escapeSqliteString($User->Email) . "',";
			$update .= "Firstname = '" . escapeSqliteString($User->Firstname) . "',";
			$update .= "Lastname = '" . escapeSqliteString($User->Lastname) . "'";
			$sql = "update User set " . $update . " where UserId = " . escapeSqliteString($User->UserId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
	function Delete($User)
	{
		$UserId = "";
		if (is_numeric($User))
		{
			$UserId = $User;
		}
		else
		{
			$UserId = $User->UserId;
		}
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "delete from User where UserId = " . escapeSqliteString($UserId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
}
?>