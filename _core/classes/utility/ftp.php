<?php
function FtpTestConnection($ftpServer, $ftpUsername, $ftpPassword)
{
	$connectionValid = true;
	// set up basic connection
	$conn = ftp_connect($ftpServer) or $connectionValid = false;
	// login with username and password
	// try to login
	if (@ftp_login($conn, $ftpUsername, $ftpPassword)) {
	    //echo "Connected as $ftpUsername@$ftpServer\n";
	    $connectionValid = true;
	} else {
		$connectionValid = false;
	    //echo "Couldn't connect as $ftpUsername\n";
	}
	if ($connectionValid)
	{
		// close the connection
		ftp_close($conn);
	}
	return $connectionValid;
}
function FtpUploadFile($ftpServer, $ftpUsername, $ftpPassword, $filename, $filepath, $destination)
{
	$response = false;
	$connectionValid = true;
	// set up basic connection
	$conn = ftp_connect($ftpServer) or $connectionValid = false;
	// login with username and password
	// try to login
	if (@ftp_login($conn, $ftpUsername, $ftpPassword)) {
	    $connectionValid = true;
	    if (file_exists($filepath))
	    {
		    $fp = fopen($filepath, 'r');
			// try to upload $file
			if (ftp_put($conn, $destination . $filename, $filepath, FTP_BINARY))
			{
				$response = true;
			    //echo "Successfully uploaded $filename<br>";
			} 
			else 
			{
			    //echo "There was a problem while uploading $filename<br>";
			}
			fclose($fp);
	    }
	    else
	    {
		    //echo "No file found locally named $filename<br>";
	    }
	}
	if ($connectionValid)
	{
		// close the connection
		ftp_close($conn);
	}
	return $response;
}
function FtpDeleteFile($ftpServer, $ftpUsername, $ftpPassword, $filepath)
{
	$response = false;
	$connectionValid = true;
	// set up basic connection
	$conn = ftp_connect($ftpServer) or $connectionValid = false;
	// login with username and password
	// try to login
	if (@ftp_login($conn, $ftpUsername, $ftpPassword)) {
	    $connectionValid = true;
	    if (FtpGetFilesize($ftpServer, $ftpUsername, $ftpPassword, $filepath) > -1)
	    {
			// try to upload $filepath
			if (ftp_delete($conn, $filepath))
			{
				$response = true;
			    //echo "Successfully deleted $filepath<br>";
			} 
			else 
			{
			    //echo "There was a problem while deleting $filepath<br>";
			}
		}
	}
	if ($connectionValid)
	{
		// close the connection
		ftp_close($conn);
	}
	return $response;
}
function FtpGetFilesize($ftpServer, $ftpUsername, $ftpPassword, $filepath)
{
	$response = -1;
	$connectionValid = true;
	// set up basic connection
	$conn = ftp_connect($ftpServer) or $connectionValid = false;
	// login with username and password
	// try to login
	if (@ftp_login($conn, $ftpUsername, $ftpPassword)) {
	    $connectionValid = true;
		$filesize = ftp_size($conn, $filepath);
		if ($filesize != -1) 
		{ 
			$response = $filesize;
		    //echo "size of $filepath is $filesize bytes";
		} 
		else 
		{
		    //echo "couldn't get the size";
		}
	}
	if ($connectionValid)
	{
		// close the connection
		ftp_close($conn);
	}
	return $response;
}
function FtpGetUniqueFilename($ftpServer, $ftpUsername, $ftpPassword, $destination, $filename, $ext)
{
	$response = $filename;
	$connectionValid = true;
	// set up basic connection
	$conn = ftp_connect($ftpServer) or $connectionValid = false;
	// login with username and password
	// try to login
	if (@ftp_login($conn, $ftpUsername, $ftpPassword)) {
	    $connectionValid = true;
	    $i = 0;
	    $nonParenUsed = false;
	    while (ftp_size($conn, $destination . $filename . $ext) > -1)
	    {
		    $i++;
		    $startingParenPos = strpos($filename, "(");
		    if ($startingParenPos > -1)
		    {
			    $filename = substr($filename, 0, $startingParenPos);
		    }
		    else
		    {
			    $nonParenUsed = true;
		    }
		    if ($nonParenUsed)
		    {
            	$filename .= "(" . $i . ")";
            }
	    }
	    $response = $filename . $ext;
	}
	if ($connectionValid)
	{
		// close the connection
		ftp_close($conn);
	}
	return $response;
}
?>