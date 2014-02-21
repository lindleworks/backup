<?php 
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
$emailaddress = "";
if (isset($_POST["emailaddress"])) 
{
	$emailaddress = $_POST["emailaddress"];
}
$UserFactory = new UserFactory();
$UserArray = $UserFactory->GetAll(" where Email = '" . escapeSqliteString($emailaddress) . "'");
if (count($UserArray) > 0)
{
    echo "<span style=\"color:#ff0000;\">Not Available!</span>";
    exit();
}
else
{
    echo "<span style=\"color:#21B203;\">Available!</span>";
}
?>