<?php
class Variable
{
	var $Name;
	var $ReplaceText;
	var $Value;
	
	function setName($Name)
	{
		$this->Name = $Name;
	}
	function getName()
	{
		return $this->Name;
	}
	function setReplaceText ($ReplaceText)
	{
		$this->ReplaceText = $ReplaceText;
	}
	function getReplaceText()
	{
		return $this->ReplaceText;
	}
	function setValue($Value)
	{
		$this->Value = $Value;
	}
	function getValue()
	{
		return $this->Value;
	}
}
?>