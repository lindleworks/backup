<?php
//Usage
/*
<?php include 'LogEmail.gen.php' ?>
<?php
$LogEmailFactory = new LogEmailFactory();
$LogEmail = $LogEmailFactory->GetOne(1);
echo $LogEmail->DateSent . "<br />";
unset($LogEmail);
$LogEmailArray = $LogEmailFactory->GetAll(' where EmailLogId = 1 ');
foreach ($LogEmailArray as &$value)
{
	echo $value->DateSent . "<br />";
}
unset($value);
?>
*/
//Core Class
class LogEmail
{
	var $EmailLogId;
	var $DateSent;
	var $ToEmails;
	var $CcEmails;
	var $BccEmails;
	var $FromEmail;
	var $ReplyEmail;
	var $Subject;
	var $Message;
	var $Successful;
	var $SmtpHost;
	var $SmtpUsername;
	function setEmailLogId($EmailLogId)
	{
		$this->EmailLogId = $EmailLogId;
	}
	function getEmailLogId()
	{
		return $this->EmailLogId;
	}
	function setDateSent($DateSent)
	{
		$this->DateSent = $DateSent;
	}
	function getDateSent()
	{
		return $this->DateSent;
	}
	function setToEmails($ToEmails)
	{
		$this->ToEmails = $ToEmails;
	}
	function getToEmails()
	{
		return $this->ToEmails;
	}
	function setCcEmails($CcEmails)
	{
		$this->CcEmails = $CcEmails;
	}
	function getCcEmails()
	{
		return $this->CcEmails;
	}
	function setBccEmails($BccEmails)
	{
		$this->BccEmails = $BccEmails;
	}
	function getBccEmails()
	{
		return $this->BccEmails;
	}
	function setFromEmail($FromEmail)
	{
		$this->FromEmail = $FromEmail;
	}
	function getFromEmail()
	{
		return $this->FromEmail;
	}
	function setReplyEmail($ReplyEmail)
	{
		$this->ReplyEmail = $ReplyEmail;
	}
	function getReplyEmail()
	{
		return $this->ReplyEmail;
	}
	function setSubject($Subject)
	{
		$this->Subject = $Subject;
	}
	function getSubject()
	{
		return $this->Subject;
	}
	function setMessage($Message)
	{
		$this->Message = $Message;
	}
	function getMessage()
	{
		return $this->Message;
	}
	function setSuccessful($Successful)
	{
		$this->Successful = $Successful;
	}
	function getSuccessful()
	{
		return $this->Successful;
	}
	function setSmtpHost($SmtpHost)
	{
		$this->SmtpHost = $SmtpHost;
	}
	function getSmtpHost()
	{
		return $this->SmtpHost;
	}
	function setSmtpUsername($SmtpUsername)
	{
		$this->SmtpUsername = $SmtpUsername;
	}
	function getSmtpUsername()
	{
		return $this->SmtpUsername;
	}
}
//Factory Class
class LogEmailFactory
{
	function GetOne($EmailLogId)
	{
		$LogEmail = new LogEmail();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from LogEmail where EmailLogId = " . escapeSqliteString($EmailLogId);
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$LogEmail->EmailLogId = $row['EmailLogId'];
				$LogEmail->DateSent = $row['DateSent'];
				$LogEmail->ToEmails = $row['ToEmails'];
				$LogEmail->CcEmails = ($row['CcEmails'] === NULL) ? "" : $row['CcEmails'];
				$LogEmail->BccEmails = ($row['BccEmails'] === NULL) ? "" : $row['BccEmails'];
				$LogEmail->FromEmail = $row['FromEmail'];
				$LogEmail->ReplyEmail = ($row['ReplyEmail'] === NULL) ? "" : $row['ReplyEmail'];
				$LogEmail->Subject = $row['Subject'];
				$LogEmail->Message = $row['Message'];
				$LogEmail->Successful = $row['Successful'];
				$LogEmail->SmtpHost = $row['SmtpHost'];
				$LogEmail->SmtpUsername = $row['SmtpUsername'];
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $LogEmail;
	}
	function GetAll($filter)
	{
		$LogEmailArray = Array();
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "select * from LogEmail " . $filter;
			$result = $db->query($sql);
			while($row = $result->fetch(PDO::FETCH_ASSOC))
			{
				$LogEmail = new LogEmail();
				$LogEmail->EmailLogId = $row['EmailLogId'];
				$LogEmail->DateSent = $row['DateSent'];
				$LogEmail->ToEmails = $row['ToEmails'];
				$LogEmail->CcEmails = ($row['CcEmails'] === NULL) ? "" : $row['CcEmails'];
				$LogEmail->BccEmails = ($row['BccEmails'] === NULL) ? "" : $row['BccEmails'];
				$LogEmail->FromEmail = $row['FromEmail'];
				$LogEmail->ReplyEmail = ($row['ReplyEmail'] === NULL) ? "" : $row['ReplyEmail'];
				$LogEmail->Subject = $row['Subject'];
				$LogEmail->Message = $row['Message'];
				$LogEmail->Successful = $row['Successful'];
				$LogEmail->SmtpHost = $row['SmtpHost'];
				$LogEmail->SmtpUsername = $row['SmtpUsername'];
				$LogEmailArray[] = $LogEmail;
			}
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $LogEmailArray;
	}
	function Save($LogEmail)
	{
		$LogEmailFactory = new LogEmailFactory();
		if ($LogEmail->EmailLogId > 0)
		{
			$LogEmailFactory->Update($LogEmail);
		}
		else
		{
			$LogEmailFactory->Insert($LogEmail);
		}
	}
	function Insert($LogEmail)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$insert = "";
			$insert .= "'" . escapeSqliteString($LogEmail->DateSent) . "',";
			$insert .= "'" . escapeSqliteString($LogEmail->ToEmails) . "',";
			$insert .= (escapeSqliteString($LogEmail->CcEmails) === "") ? "NULL," : "'" . escapeSqliteString($LogEmail->CcEmails) . "',";
			$insert .= (escapeSqliteString($LogEmail->BccEmails) === "") ? "NULL," : "'" . escapeSqliteString($LogEmail->BccEmails) . "',";
			$insert .= "'" . escapeSqliteString($LogEmail->FromEmail) . "',";
			$insert .= (escapeSqliteString($LogEmail->ReplyEmail) === "") ? "NULL," : "'" . escapeSqliteString($LogEmail->ReplyEmail) . "',";
			$insert .= "'" . escapeSqliteString($LogEmail->Subject) . "',";
			$insert .= "'" . escapeSqliteString($LogEmail->Message) . "',";
			$insert .= escapeSqliteString($LogEmail->Successful) . ",";
			$insert .= "'" . escapeSqliteString($LogEmail->SmtpHost) . "',";
			$insert .= "'" . escapeSqliteString($LogEmail->SmtpUsername) . "'";
			$sql = "insert into LogEmail (DateSent,ToEmails,CcEmails,BccEmails,FromEmail,ReplyEmail,Subject,Message,Successful,SmtpHost,SmtpUsername) values (" . $insert . ")";
			$db->exec($sql);
			$LogEmail->EmailLogId = $db->lastInsertId();
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
		return $LogEmail;
	}
	function Update($LogEmail)
	{
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$update = "";
			$update .= "DateSent = '" . escapeSqliteString($LogEmail->DateSent) . "',";
			$update .= "ToEmails = '" . escapeSqliteString($LogEmail->ToEmails) . "',";
			$update .= "CcEmails = " . ((escapeSqliteString($LogEmail->CcEmails) === "") ? "NULL" : "'" . escapeSqliteString($LogEmail->CcEmails) . "'") . ",";
			$update .= "BccEmails = " . ((escapeSqliteString($LogEmail->BccEmails) === "") ? "NULL" : "'" . escapeSqliteString($LogEmail->BccEmails) . "'") . ",";
			$update .= "FromEmail = '" . escapeSqliteString($LogEmail->FromEmail) . "',";
			$update .= "ReplyEmail = " . ((escapeSqliteString($LogEmail->ReplyEmail) === "") ? "NULL" : "'" . escapeSqliteString($LogEmail->ReplyEmail) . "'") . ",";
			$update .= "Subject = '" . escapeSqliteString($LogEmail->Subject) . "',";
			$update .= "Message = '" . escapeSqliteString($LogEmail->Message) . "',";
			$update .= "Successful = " . escapeSqliteString($LogEmail->Successful) . ",";
			$update .= "SmtpHost = '" . escapeSqliteString($LogEmail->SmtpHost) . "',";
			$update .= "SmtpUsername = '" . escapeSqliteString($LogEmail->SmtpUsername) . "'";
			$sql = "update LogEmail set " . $update . " where EmailLogId = " . escapeSqliteString($LogEmail->EmailLogId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
	function Delete($LogEmail)
	{
		$EmailLogId = "";
		if (is_numeric($LogEmail))
		{
			$EmailLogId = $LogEmail;
		}
		else
		{
			$EmailLogId = $LogEmail->EmailLogId;
		}
		$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
		try
		{
			$db = new PDO($dsn);
			$sql = "delete from LogEmail where EmailLogId = " . escapeSqliteString($EmailLogId);
			$db->exec($sql);
		}
		catch (PDOException $e)
		{
			echo 'Connection failed!';// . $e->getMessage();
		}
	}
}
?>