<?php
class MySqlTableStructure
{
	var $ColumnName;
	var $TypeName;
	var $MaxLength;
	var $IsNullable;
	var $IsIdentity;
	
	function setColumnName($ColumnName)
	{
		$this->ColumnName = $ColumnName;
	}
	function getColumnName()
	{
		return $this->ColumnName;
	}
	function setTypeName ($TypeName)
	{
		$this->TypeName = $TypeName;
	}
	function getTypeName()
	{
		return $this->TypeName;
	}
	function setMaxLength($MaxLength)
	{
		$this->MaxLength = $MaxLength;
	}
	function getMaxLength()
	{
		return $this->MaxLength;
	}
	function setIsNullable ($IsNullable)
	{
		$this->IsNullable = $IsNullable;
	}
	function getIsNullable()
	{
		return $this->IsNullable;
	}
	function setIsIdentity ($IsIdentity)
	{
		$this->IsIdentity = $IsIdentity;
	}
	function getIsIdentity()
	{
		return $this->IsIdentity;
	}
}
?>