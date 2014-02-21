<?php 
require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
$username = "";
if (isset($_POST["username"])) 
{
	$username = $_POST["username"];
}
$UserFactory = new UserFactory();
$UserArray = $UserFactory->GetAll(" where Username = '" . escapeSqliteString($username) . "'");
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