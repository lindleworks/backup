<?php
class File
{
	var $filename;
	var $filepath;
	var $filesize;
	var $extension;
	var $dateModified;
	
	function setfilename($filename)
	{
		$this->filename = $filename;
	}
	function getfilename()
	{
		return $this->filename;
	}
	function setfilepath ($filepath)
	{
		$this->filepath = $filepath;
	}
	function getfilepath()
	{
		return $this->filepath;
	}
	function setfilesize($filesize)
	{
		$this->filesize = $filesize;
	}
	function getfilesize()
	{
		return $this->filesize;
	}
	function setextension($extension)
	{
		$this->extension = $extension;
	}
	function getextension()
	{
		return $this->extension;
	}
	function setdateModified($dateModified)
	{
		$this->dateModified = $dateModified;
	}
	function getdateModified()
	{
		return $this->dateModified;
	}
}
?>