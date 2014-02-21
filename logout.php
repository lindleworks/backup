<?php
	require_once(dirname((__FILE__)) . '/_core/classes/core.php');
    session_start();
	// set the expiration date to one hour ago
	setcookie($cookieName, '', time()-3600, '/', SYSTEM_URL);
	session_destroy();
	//send back to login page
	header('Location: ' . FULL_URL);
?>