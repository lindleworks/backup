<?php
	//example core include
	require_once(dirname(dirname(dirname(__FILE__))) . '/_core/classes/core.php');

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$aColumns = array('JobLogId','JobId','DateRan','Status','DbBackupId','DbBackupFilename','DbBackupFilepath','DbBackupFilesize','FileBackupId','FileBackupFilename','FileBackupFilepath','FileBackupFilesize','Success');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = 'JobLogId';
	
	/* custom search elements */
	/* example
		$column1 = $_GET['txtColumn1'];
		$column2 = $_GET['txtColumn2'];
	*/
	
	/* DB table to use */
	$sTable = 'JobLog';
	
	/* Database connection information (optional) */
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * Local functions
	 */
	function fatal_error ( $sErrorMessage = '' )
	{
		header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
		die( $sErrorMessage );
	}
	
	/* 
	 * Database connection
	 */
	$dsn = "sqlite:" . ROOT . "_core/database/" . DB_FILENAME;
	try
	{
		$db = new PDO($dsn);
	
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
				intval( $_GET['iDisplayLength'] );
		}
		
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				//if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				//{
					$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				//}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = "";
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				//if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
				//{
					$sWhere .= "`".$aColumns[$i]."` LIKE '%".escapeString( $_GET['sSearch'] )."%' OR ";
				//}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= "`".$aColumns[$i]."` LIKE '%".escapeString($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		/* custom column filtering */
		/* example
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if (isset($variableName) && $variableName != '')
			{
				if ($aColumns[$i] == 'COLUMN NAME')
				{
					if ( $sWhere == "" )
					{
						$sWhere = "WHERE ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= "`".$aColumns[$i]."` = '".mysql_real_escape_string($variableName)."' ";
				}
			}
		}
		*/
		
		/* filter deleted / inactive records */
		
		
		$sQuery = "
			SELECT `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
			";
		$rResult = $db->query($sQuery);
		
		/* Data set length after filtering */
		$sQuery = "
			SELECT count(`".$sIndexColumn."`) as Total
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
		";
		$rResultFilterTotal = $db->query($sQuery);
		while($row = $rResultFilterTotal->fetch(PDO::FETCH_ASSOC))
		{
			$iFilteredTotal = $row["Total"];
		}
		
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(`".$sIndexColumn."`) as Total
			FROM   $sTable
			$sWhere
		";
		$rResultTotal = $db->query($sQuery);
		while($row = $rResultTotal->fetch(PDO::FETCH_ASSOC))
		{
			$iTotal = $row["Total"];
		}
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		while ($aRow = $rResult->fetch(PDO::FETCH_ASSOC))
		{
			$output['aaData'][] = $aRow;
		}
		
		echo json_encode( $output );
	}
	catch (PDOException $e)
	{
		fatal_error( 'Could not open connection to server' );
	}
?>