<?php 
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
$username = "";
$userpassword = "";
if (isset($_POST["username"])) 
{
	$username = $_POST["username"];
}
$UserFactory = new UserFactory();
$UserArray = $UserFactory->GetAll(" where (username = '" . escapeSqliteString($username) . "' or email = '" . escapeSqliteString($username) . "')");
if (count($UserArray) > 0)
{ 
	foreach ($UserArray as &$value)
	{
		$newPassword = generatePassword(8);
        $value->Password = md5($newPassword);
        $UserFactory->Update($value);
        //send email
        $subject = SYSTEM_NAME . " - Password Reset";
        $text = "Sorry you lost your password. Your new password is '$newPassword'. (In case you forgot, your username is '$value->Username')"; 
        $html = "<p>Sorry you lost your password. Your new password is '$newPassword'. (In case you forgot, your username is '$value->Username')</p>";
        utilitySendEmail($value->Email,$subject,$text,$html);
        echo "<div class=\"alert alert-success\">Password emailed!</div>";
	}
}
else
{
	echo "<div class=\"alert alert-danger\">Username/Email not found!</div>";
}
unset($value);
?>