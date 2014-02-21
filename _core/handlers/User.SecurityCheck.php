<?php
	require_once(dirname(dirname(dirname((__FILE__)))) . '/_core/classes/core.php');
	if (curPageURL() !== FULL_URL && curPageURL() !== FULL_URL . 'index.php' && curPageURL() !== FULL_URL . 'forgot-password.php') 
    {
		if (!isset($_SESSION['LoggedUserId']) || $_SESSION["LoggedUserId"] == "")
	    {
	        session_destroy();
	        //redirect to login page
		    header('Location: ' . FULL_URL);
	    }
	    else
	    {
		    header('Location: ' . $_SERVER["HTTP_REFERER"]);
	    }
    }
    else
    {
	    header('Location: ' . $_SERVER["HTTP_REFERER"]);
    }
?>