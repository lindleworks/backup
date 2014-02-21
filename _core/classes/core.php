<?php
ob_start();
define( 'ROOT', dirname(dirname(dirname(__FILE__))) . '/' );
define( 'VERSION', '1.0.1' );
date_default_timezone_set('US/Eastern');
if (!isset($_SESSION)) {
  session_start();
  $_SESSION["timeout"] = 3600;
}
$newInstall = false;
//check if config file exists
if (file_exists(ROOT . "/_core/config.php"))
{
	require_once ROOT . "/_core/config.php";
}
else
{
	//doesn't exist, let's generate one
	//get the current url
	$baseUrl = $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI'];
	$configOutput = "";
	$tab = "\t";
	$configOutput .= "<?php\n";
	$configOutput .= "//global variables\n";
	$configOutput .= "//system\n";
	$configOutput .= "define( \"SYSTEM_URL\", \"" . $baseUrl . "\" ); // (domain.com/ or domain.com/backup/)\n";
	$configOutput .= "define( \"SYSTEM_NAME\", \"Backup Utility\" );\n";
	$configOutput .= "define( \"SSL\", \"http://\" ); // (http:// or https://)\n";
	$configOutput .= "define( \"FULL_URL\", SSL . SYSTEM_URL );\n";
	$configOutput .= "define( \"CRON_ENABLED\", true ); // ( true or false )\n";
	$configOutput .= "define( \"REQUIRE_AUTHENTICATION\", true ); // ( true or false )\n";
	$configOutput .= "define( \"DB_FILENAME\", \"data.sqlite\" );\n";
	$configOutput .= "//email\n";
	$configOutput .= "define( \"EMAIL_FROM\", \"email@domain.com\" );\n";
	$configOutput .= "define( \"EMAIL_HOST\", \"mail.domain.com\" );\n";
	$configOutput .= "define( \"EMAIL_PORT\", 25 );\n";
	$configOutput .= "define( \"EMAIL_USERNAME\", \"email@domain.com\" );\n";
	$configOutput .= "define( \"EMAIL_PASSWORD\", \"password\" );\n";
	$configOutput .= "//server settings\n";
	$configOutput .= "date_default_timezone_set('America/Indiana/Indianapolis');\n";
	$configOutput .= "ini_set(\"log_errors\" , \"1\");\n";
	$configOutput .= "ini_set(\"display_errors\" , \"0\");\n";
	$configOutput .= "?>";
	//write file
	$filename = ROOT . "/_core/config.php";
	$filenameHandler = fopen($filename, 'w') or die("can't open file");
	fwrite($filenameHandler, $configOutput);
	fclose($filenameHandler);
	$newInstall = true;
	require_once ROOT . "/_core/config.php";
}
//check if htaccess file exists
if (!file_exists(ROOT . "/.htaccess"))
{
	//doesn't exist, let's generate one
	//get the current url
	$virtualPath = "";
	$baseUrl = $_SERVER["SERVER_NAME"] . $_SERVER['REQUEST_URI'];
	$urlArray = explode("/",$baseUrl);
	$i = 0;
	foreach ($urlArray as &$part)
	{
		$i++;
		if ($i > 1 && $i != count($urlArray)) 
		{
			$virtualPath .= $part . "/";
		}
	}
	$output = "";
	$output .= "Options -Indexes\n";
	$output .= "ErrorDocument 403 /" . $virtualPath . "_core/errors/403.php\n";
	$output .= "ErrorDocument 404 /" . $virtualPath . "_core/errors/404.php\n";
	$output .= "ErrorDocument 503 /" . $virtualPath . "_core/errors/503.php\n";
	$output .= "\n";
	$output .= "<FilesMatch \"\\.(sqlite|sdb|s3db|db|zip|sql)$\">\n";
	$output .= "Deny from all\n";
	$output .= "</FilesMatch>\n";
	//write file
	$filename = ROOT . "/.htaccess";
	$filenameHandler = fopen($filename, 'w') or die("can't open file");
	fwrite($filenameHandler, $output);
	fclose($filenameHandler);
}
foreach (glob(ROOT . "/_core/classes/generated/*.php") as $filename)
{
    include $filename;
}
foreach (glob(ROOT . "/_core/classes/*.php") as $filename)
{
	if (strpos($filename, 'core.php') === false)
	{
    	include $filename;
    }
}
//include utility classes
foreach (glob(ROOT . "/_core/classes/utility/*.php") as $filename)
{
	include $filename;
}
foreach (glob(ROOT . "/_core/classes/utility/class-generator/*.php") as $filename)
{
	include $filename;
}
if (!file_exists(ROOT . "_core/database/" . DB_FILENAME))
{
	unlink(ROOT . "_core/database/" . DB_FILENAME);
	//create database
	$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
	try
	{
		$db = new PDO($dsn);
		//now create the database
		$sql = 'CREATE TABLE "Backup" ("BackupId" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "JobId" INTEGER NOT NULL , "DateCreated" DATETIME NOT NULL , "Success" BOOL NOT NULL , "Status" VARCHAR NOT NULL , "BackupType" VARCHAR NOT NULL , "Filename" VARCHAR, "Filepath" VARCHAR, "Filesize" DOUBLE)';
		$db->exec($sql);
		$sql = 'CREATE TABLE "Job" ("JobId" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "Name" VARCHAR NOT NULL , "DbBackup" INTEGER NOT NULL , "FileBackup" INTEGER NOT NULL , "BackupDestinationType" VARCHAR NOT NULL , "Email" VARCHAR, "BackupFolder" VARCHAR, "BackupStorageFolder" VARCHAR, "ScheduleInterval" VARCHAR, "ScheduleDayOfMonth" INTEGER, "ScheduleDayOfWeek" VARCHAR, "ScheduleHour" INTEGER, "ScheduleMinute" INTEGER, "FtpServer" VARCHAR, "FtpUsername" VARCHAR, "FtpPassword" VARCHAR, "FtpFolder" VARCHAR, "MaximumNumberOfBackups" INTEGER NOT NULL , "DbPassword" varchar NULL, "DbUsername" varchar NULL, "DbServer" varchar NULL, "DbName" varchar NULL, "DateCreated" DATETIME NOT NULL, "NextRunDate" DATETIME NULL)';
		$db->exec($sql);
		$sql = 'CREATE TABLE "JobLog" ("JobLogId" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "JobId" INTEGER NOT NULL , "DateRan" DATETIME NOT NULL , "Status" VARCHAR NOT NULL , "DbBackupId" INTEGER,"DbBackupFilename" VARCHAR, "DbBackupFilepath" VARCHAR, "DbBackupFilesize" DOUBLE, "FileBackupId" INTEGER,"FileBackupFilename" VARCHAR, "FileBackupFilepath" VARCHAR,"FileBackupFilesize" DOUBLE, "Success" BOOL NOT NULL )';
		$db->exec($sql);
		$sql = 'CREATE TABLE "LogAuthentication" ("AuthenticationLogId" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "Username" VARCHAR NOT NULL , "IpAddress" VARCHAR NOT NULL , "Successful" INTEGER NOT NULL , "DateCreated" DATETIME NOT NULL )';
		$db->exec($sql);
		$sql = 'CREATE TABLE "LogEmail" ("EmailLogId" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "DateSent" DATETIME NOT NULL , "ToEmails" VARCHAR NOT NULL , "CcEmails" VARCHAR, "BccEmails" VARCHAR, "FromEmail" VARCHAR NOT NULL , "ReplyEmail" VARCHAR, "Subject" VARCHAR NOT NULL , "Message" TEXT NOT NULL , "Successful" INTEGER NOT NULL , "SmtpHost" VARCHAR NOT NULL , "SmtpUsername" VARCHAR NOT NULL )';
		$db->exec($sql);
		$sql = 'CREATE TABLE "User" ("UserId" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "Username" VARCHAR NOT NULL , "Password" VARCHAR NOT NULL , "Email" VARCHAR NOT NULL , "Firstname" VARCHAR NOT NULL , "Lastname" VARCHAR NOT NULL )';
		$db->exec($sql);
		$sql = 'CREATE TABLE "Lock" ("LockId" INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL , "Locked" INTEGER NOT NULL )';
		$db->exec($sql);
	}
	catch (PDOException $e)
	{
		echo 'Connection failed!';// . $e->getMessage();
	}
	$UserFactory = new UserFactory();
	$User = new User();
	//check for an 'admin' user account
	$UserArray = $UserFactory->GetAll(" where Username = 'admin' ");
	if (count($UserArray) > 0)
	{
		$User = $UserArray[0];
	}
	//create a new user
	$User->Username = "admin";
	$User->Password = md5("password");
	$User->Email = "email@domain.com";
	$User->Firstname = "System";
	$User->Lastname = "Administrator";
	$UserFactory->Save($User);
}
if (CRON_ENABLED)
{
	$LockFactory = new LockFactory();
	$LockArray = $LockFactory->GetAll("");
	if (count($LockArray) == 0)
	{
		curl_post_async(FULL_URL . "/cron.php");
	}
}
//utilities
/*
function sortBy($a, $b, $column) {
    return $a[$column] - $b[$column];
}
*/
function getClientIP() {
     $ipaddress = '';
     if (getenv('HTTP_CLIENT_IP'))
         $ipaddress = getenv('HTTP_CLIENT_IP');
     else if(getenv('HTTP_X_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
     else if(getenv('HTTP_X_FORWARDED'))
         $ipaddress = getenv('HTTP_X_FORWARDED');
     else if(getenv('HTTP_FORWARDED_FOR'))
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
     else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
     else if(getenv('REMOTE_ADDR'))
         $ipaddress = getenv('REMOTE_ADDR');
     else
         $ipaddress = 'UNKNOWN';

     return $ipaddress; 
}
function escapeSqliteString($str)
{
	$escaped = sqlite_escape_string($str);
	return $escaped;
}
function sqlite_escape_string( $string ){
    return SQLite3::escapeString($string);
}
function escapeString($str)
{
	if (is_string($str))
	{
		global $dbserver,$database,$dbuser,$dbpassword;
		$conn = new mysqli($dbserver, $dbuser, $dbpassword, $database);
		$escaped = '';
		if ($conn)
		{
			$escaped = $conn->real_escape_string($str);
			mysqli_close($conn); 
		}
		else
		{
			$escaped = $str;
		}
	}
	else
	{
		$escaped = $str;
	}
	return $escaped;
}
function curPageURL() {
	$pageURL = 'http';
	if (isset( $_SERVER["HTTPS"] ) && strtolower( $_SERVER["HTTPS"] ) == "on" )
	{
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") 
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} 
	else 
	{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function utilitySendEmail($to,$subject,$msgText,$msgHtml)
{
    //send email
    require_once "Mail.php";
    require_once "Mail/mime.php";
    $from = EMAIL_FROM;
    $body = "";
    $text = $msgText; 
    $html = $msgHtml;
    $crlf = "\n"; 
    $host = EMAIL_HOST;
    $port = EMAIL_PORT;
    $username = EMAIL_USERNAME;
    $password = EMAIL_PASSWORD;
    $headers = array ('From' => $from,
      'To' => $to,
      'Subject' => $subject);
    $mime = new Mail_mime($crlf);
    $mime->setTXTBody($text);
    $mime->setHTMLBody($html);
    $body = $mime->get();
    $headers = $mime->headers($headers);
    $smtp = Mail::factory('smtp',
      array ('host' => $host,
      	'port' => $port,
        'auth' => true,
        'username' => $username,
        'password' => $password));
    $mail = $smtp->send($to, $headers, $body);
    
    if (PEAR::isError($mail)) 
    {   
        echo(" " . $mail->getMessage() . " ");
        LogEmail($subject,$html,$to,'','','',$from,$host,$username,0);
    } 
    else 
    {   
    	LogEmail($subject,$html,$to,'','','',$from,$host,$username,1);
    }
}
function LogEmail($subject,$message,$to,$cc,$bcc,$reply,$from,$host,$username,$success)
{
	$LogEmailFactory = new LogEmailFactory();
    $LogEmail = new LogEmail();
	$LogEmail->ToEmails = $to;
	$LogEmail->CcEmails = $cc;
	$LogEmail->BccEmails = $bcc;
	$LogEmail->FromEmail = $from;
	$LogEmail->ReplyEmail = $reply;
	$LogEmail->Subject = $subject;
	$LogEmail->Message = $message;
	$LogEmail->Successful = $success;
	$LogEmail->SmtpHost = $host;
	$LogEmail->SmtpUsername = $username;
	$timestamp = time();
	$LogEmail->DateSent = gmdate("Y-m-d H:i:s", $timestamp);
	$LogEmailFactory->Insert($LogEmail);
}
function check_email_address($email) {
	// First, we check that there's one @ symbol, 
	// and that the lengths are right.
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
	{
		// Email invalid because wrong number of characters 
		// in one section or wrong number of @ symbols.
		return false;
	}
	// Split it into sections to make life easier
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++) 
	{
		if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) 
		{
		  return false;
		}
	}
	// Check if domain is IP. If not, 
	// it should be valid domain name
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) 
	{
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2) {
		    return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++) 
		{
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|↪([A-Za-z0-9]+))$",$domain_array[$i])) 
			{
		    	return false;
			}
		}
	}
	return true;
}
function generatePassword($length = 8)
{
    // start with a blank password
    $password = "";
    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";
    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
        $length = $maxlength;
    }
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    // add random characters to $password until $length is reached
    while ($i < $length) { 
        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        // have we already used this character in $password?
        if (!strstr($password, $char)) { 
            // no, so it's OK to add it onto the end of whatever we've already got...
            $password .= $char;
            // ... and increase the counter by one
            $i++;
        }
    }
    return $password;
}
function smartFilesizeDisplay($sizeInBytes) {
	$result = $sizeInBytes;
	$converted = 0;
	if ($sizeInBytes > 1073741824) { //change to gigabytes
		$converted = $sizeInBytes / (1024 * 1024 * 1024);
		$result = round($converted, 1) . " GB";
	}
	else if ($sizeInBytes > 1048576) { //change to megabytes
		$converted = $sizeInBytes / (1024 * 1024);
		$result = round($converted, 1) . " MB";
	}
	else { //change to kilobytes
		$converted = $sizeInBytes / 1024;
		$result = round($converted, 1) . " KB";
	}
	return $result;
}
function returnCronSchedule($minute, $hour, $dayOfMonth, $month, $dayOfWeek)
{
	$cron = "";
	//minute
	if ($minute == "")
	{
		$cron .= "*";
	}
	else 
	{
		$cron .= intval($minute);
	}
	$cron .= " ";
	//hour
	if ($hour == "")
	{
		$cron .= "*";
	}
	else 
	{
		$cron .= intval($hour);
	}
	$cron .= " ";
	//day of month
	if ($dayOfMonth == "")
	{
		$cron .= "*";
	}
	else 
	{
		$cron .= intval($dayOfMonth);
	}
	$cron .= " ";
	//month
	if ($month == "")
	{
		$cron .= "*";
	}
	else 
	{
		$cron .= intval($month);
	}
	$cron .= " ";
	//day of week
	if ($dayOfWeek == "")
	{
		$cron .= "*";
	}
	else 
	{
		if ($dayOfWeek == "Sunday")
		{
			$cron .= "0";
		}
		else if ($dayOfWeek == "Monday")
		{
			$cron .= "1";
		}
		else if ($dayOfWeek == "Tuesday")
		{
			$cron .= "2";
		}
		else if ($dayOfWeek == "Wednesday")
		{
			$cron .= "3";
		}
		else if ($dayOfWeek == "Thursday")
		{
			$cron .= "4";
		}
		else if ($dayOfWeek == "Friday")
		{
			$cron .= "5";
		}
		else if ($dayOfWeek == "Saturday")
		{
			$cron .= "6";
		}
	}
	return $cron;
}
function curl_post_async($url, $params = array())
{
    $post_params = array();
    foreach ($params as $key => &$val) 
    {
		if (is_array($val))
		{ 
			$val = implode(',', $val);
		}
		$post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);
    $parts=parse_url($url);
    $fp = fsockopen($parts['host'],isset($parts['port'])?$parts['port']:80,$errno, $errstr, 30);
    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) 
    {
    	$out.= $post_string;
    }
    fwrite($fp, $out);
    fclose($fp);
}
?>