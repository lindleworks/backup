<?php 
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
$username = "";
$userpassword = "";
if (isset($_POST["username"])) 
{
	$username = $_POST["username"];
}
if (isset($_POST["password"])) 
{
	$userpassword = $_POST["password"];
}
$UserFactory = new UserFactory();
$UserArray = $UserFactory->GetAll(" where username = '" . escapeSqliteString($username) . "' and password = '" . md5(utf8_encode($userpassword)) . "'");
if (count($UserArray) > 0)
{ 
	foreach ($UserArray as &$value)
	{
		$_SESSION['LoggedUserId'] = $value->UserId;
        $_SESSION['LoggedUsername'] = $value->Username;
        $_SESSION['LoggedFirstname'] = $value->Firstname;
        $_SESSION['LoggedLastname'] = $value->Lastname;
        $_SESSION['LoggedEmail'] = $value->Email;
        //log user authentication
        $LogAuthenticationFactory = new LogAuthenticationFactory();
        $LogAuthentication = new LogAuthentication();
		$LogAuthentication->Username = $value->Username;
		$LogAuthentication->IpAddress = getClientIP();
		$LogAuthentication->Successful = 1;
		$timestamp = time();
		$LogAuthentication->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
		$LogAuthenticationFactory->Insert($LogAuthentication);	
		echo "<script type=\"text/javascript]\">window.location='".FULL_URL."secure/';</script>";
	}
}
else
{
	$LogAuthenticationFactory = new LogAuthenticationFactory();
    $LogAuthentication = new LogAuthentication();
	$LogAuthentication->Username = $username;
	$LogAuthentication->IpAddress = getClientIP();
	$LogAuthentication->Successful = 0;
	$timestamp = time();
	$LogAuthentication->DateCreated = gmdate("Y-m-d H:i:s", $timestamp);
	$LogAuthenticationFactory->Insert($LogAuthentication);
	echo "<div class=\"alert alert-danger\">Username/Password incorrect!</div>";
}
unset($value);
?>