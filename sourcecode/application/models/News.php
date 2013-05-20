<?php

class Application_Model_News {
	private $sDb;

	function __construct() {
		$this->sDb = Zend_Registry::get('DB');
	}

	function getAll($intLimit = 0, $intOffset = 50) {
		$strSQL = 'SELECT * FROM mst_news LIMIT '. $intLimit .', '. $intOffset;
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();

		return $res;
	}

	function getAllByKeyword($strKeyword) {
		$strSQL = "SELECT NewsID as value, NewsTitle as label FROM mst_news WHERE NewsTitle REGEXP '$strKeyword'";
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();

		return $res;
	}

	function getSingle($strID) {
		$strSQL = 'SELECT * FROM mst_news WHERE NewsID = '. $strID;
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetch();

		return $res;
	}

	function insert($strBranchID, $strNewsTitle, $strDescription) {
        $strSQL = "INSERT INTO mst_news (BranchID, NewsTitle, NewsDesc, CreateBy, CreateDate) VALUES ('$strBranchID', '$strNewsTitle', '$strDescription', '{$_SESSION['UserID']}', '" . date("Y-m-d H:i:s") . "')";
        $stmt = $this->sDb->query($strSQL);
        return $this->sDb->lastInsertId();
	}


	function update($strNewsID, $strBranchID, $strNewsTitle, $strDescription) {
        $strSQL = "UPDATE mst_news SET 
        		BranchID  = '$strBranchID',
        		NewsTitle = '$strNewsTitle',
        		NewsDesc  = '$strDescription',
        		UpdateBy  = '{$_SESSION["UserID"]}' 
        		WHERE NewsID = '$strNewsID'";
        $stmt = $this->sDb->query($strSQL);
	}

	function delete($strNewsID) {
        $strSQL = "DELETE FROM mst_news WHERE NewsID = '$strNewsID'";
        $stmt = $this->sDb->query($strSQL);
	}

	function getDataTable() {
	    $sTable    = "mst_news table1";
	    $sTable2   = "mst_branch table2";
	    $aColumns  = array('table1.NewsID', 'table1.BranchID', 'table2.BranchCode', 'table2.BranchName', 'table1.NewsTitle', 'table1.CreateBy', 'table1.CreateDate');
	    $aColumns2 = array('NewsID', 'BranchID', 'BranchCode', 'BranchName', 'NewsTitle', 'CreateBy', 'CreateDate');
	    $sIndexColumn = "NewsID";

        /*
    	 * Paging
    	 */
    	$sLimit = "";
    	if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' ){
    		$sLimit = "LIMIT ".addslashes( $_POST['iDisplayStart'] ).", " . addslashes( $_POST['iDisplayLength'] );
    	}
        //echo $sLimit;exit;

    	/*
    	 * Ordering
    	 */
    	$sOrder = "";
    	if ( isset( $_POST['iSortCol_0'] ) )
    	{
    		$sOrder = "ORDER BY  ";
    		for ( $i=0 ; $i<intval( $_POST['iSortingCols'] ) ; $i++ )
    		{
    			if ( $_POST[ 'bSortable_'.intval($_POST['iSortCol_'.$i]) ] == "true" )
    			{
    				$sOrder .= "".$aColumns[ intval( $_POST['iSortCol_'.$i] ) ]." ".
    				 	addslashes( $_POST['sSortDir_'.$i] ) .", ";
    			}
    		}

    		$sOrder = substr_replace( $sOrder, "", -2 );
    		if ( $sOrder == "ORDER BY" )
    		{
    			$sOrder = "";
    		}
    	}
    	//echo $sOrder;exit;


    	/*
    	 * Filtering
    	 * NOTE this does not match the built-in DataTables filtering which does it
    	 * word by word on any field. It's possible to do here, but concerned about efficiency
    	 * on very large tables, and MySQL's regex functionality is very limited
    	 */
    	$sWhere = "";
    	if ( isset($_POST['sSearch']) && $_POST['sSearch'] != "" )
    	{
    		$sWhere = "WHERE (";
    		for ( $i=0 ; $i<count($aColumns) ; $i++ )
    		{
    			$sWhere .= "".$aColumns[$i]." LIKE '%".addslashes( $_POST['sSearch'] )."%' OR ";
    		}
    		$sWhere = substr_replace( $sWhere, "", -3 );
    		$sWhere .= ')';
    	}

    	/* Individual column filtering */
    	for ( $i=0 ; $i<count($aColumns) ; $i++ )
    	{
    		if ( isset($_POST['bSearchable_'.$i]) && $_POST['bSearchable_'.$i] == "true" && $_POST['sSearch_'.$i] != '' )
    		{
    			if ( $sWhere == "" )
    			{
    				$sWhere = "WHERE ";
    			}
    			else
    			{
    				$sWhere .= " AND ";
    			}
    			$sWhere .= "".$aColumns[$i]." LIKE '%".addslashes($_POST['sSearch_'.$i])."%' ";
    		}
    	}

    	/*if ( $sWhere == "" ) {
    	    $sWhere = "WHERE ";
    	} else {
    	    $sWhere .= " AND ";
    	}
        $sWhere .= "(table1.BranchID = table2.BranchID) OR table1.BranchID = 0";*/

    	/*
    	 * SQL queries
    	 * Get data to display
    	 */
    	$sQuery = "
    		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
    		FROM $sTable LEFT JOIN $sTable2 ON table1.BranchID = table2.BranchID
    		$sWhere
    		$sOrder
    		$sLimit
    		";
    	//echo $sQuery;exit;
    	//$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
    	$stmt = $this->sDb->query($sQuery);
    	$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
    	$rResult = $stmt->fetchAll();
    	//var_dump($rResult);


    	/* Data set length after filtering */
    	$sQuery = "
    		SELECT FOUND_ROWS()
    	";
    	//$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
    	$stmt = $this->sDb->query($sQuery);
    	$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
    	//$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    	$aResultFilterTotal = $stmt->fetch();
    	//$aResultFilterTotal = $stmt->fetchAll();
    	$iFilteredTotal = $aResultFilterTotal[0];
    	//echo $iFilteredTotal;exit;
    	//print_r($aResultFilterTotal);exit;

    	/* Total data set length */
    	$sQuery = "
    		SELECT COUNT(".$sIndexColumn.")
    		FROM   $sTable
    	";
    	//$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
    	//echo $sQuery;exit;
    	$stmt = $this->sDb->query($sQuery);
    	$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
    	//$aResultTotal = mysql_fetch_array($rResultTotal);
    	$aResultTotal = $stmt->fetch();
    	$iTotal = $aResultTotal[0];
    	//echo $iTotal;exit;


    	/*
    	 * Output
    	 */
    	$output = array(
    		"sEcho" => intval($_POST['sEcho']),
    		"iTotalRecords" => $iTotal,
    		"iTotalDisplayRecords" => $iFilteredTotal,
    		"aaData" => array()
    	);

    	//var_dump($rResult);

    	foreach ($rResult as $aRow)
    	{
    		$row = array();
    		for ( $i=0 ; $i<count($aColumns) ; $i++ )
    		{
    			if ( $aColumns[$i] == "version" )
    			{
    				/* Special output formatting for 'version' column */
    				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
    			}
    			else if ( $aColumns[$i] != ' ' )
    			{
    				/* General output */
    				$row[] = $aRow[ $aColumns2[$i] ];
    				//var_dump($aRow);
    			}
    		}
    		$output['aaData'][] = $row;
    		//var_dump($row);
    	}
	    return $output;
	}
	
	function getDataTableHome($strBranchID = "") {
		$sTable    = "mst_news table1";
		$aColumns  = array('table1.NewsID', 'table1.NewsTitle', 'table1.CreateBy', 'table1.CreateDate');
		$aColumns2 = array('NewsID', 'NewsTitle', 'CreateBy', 'CreateDate');
		$sIndexColumn = "NewsID";
	
		/*
		 * Paging
		*/
		$sLimit = "";
		if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' ){
			$sLimit = "LIMIT ".addslashes( $_POST['iDisplayStart'] ).", " . addslashes( $_POST['iDisplayLength'] );
		}
		//echo $sLimit;exit;
	
		/*
		 * Ordering
		*/
		$sOrder = "";
		if ( isset( $_POST['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_POST['iSortingCols'] ) ; $i++ )
			{
				if ( $_POST[ 'bSortable_'.intval($_POST['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "".$aColumns[ intval( $_POST['iSortCol_'.$i] ) ]." ".
							addslashes( $_POST['sSortDir_'.$i] ) .", ";
				}
			}
	
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		//echo $sOrder;exit;
	
	
		/*
		 * Filtering
		* NOTE this does not match the built-in DataTables filtering which does it
		* word by word on any field. It's possible to do here, but concerned about efficiency
		* on very large tables, and MySQL's regex functionality is very limited
		*/
		$sWhere = "";
		if ( isset($_POST['sSearch']) && $_POST['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= "".$aColumns[$i]." LIKE '%".addslashes( $_POST['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
	
		/* Individual column filtering */
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_POST['bSearchable_'.$i]) && $_POST['bSearchable_'.$i] == "true" && $_POST['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= "".$aColumns[$i]." LIKE '%".addslashes($_POST['sSearch_'.$i])."%' ";
			}
		}
		
		if ( $sWhere == "" ) {
			$sWhere = "WHERE ";
		} else {
			$sWhere .= " AND ";
		}	
		$sWhere .= "(table1.BranchID = '0'";
		
		if ($strBranchID) {
			$sWhere .= " OR table1.BranchID = '$strBranchID')";
		} else {
			$sWhere .= ")";
		}
	
		/*
		 * SQL queries
		* Get data to display
		*/
		$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
		//echo $sQuery;exit;
		//$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$stmt = $this->sDb->query($sQuery);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$rResult = $stmt->fetchAll();
		//var_dump($rResult);
	
	
		/* Data set length after filtering */
		$sQuery = "
		SELECT FOUND_ROWS()
		";
		//$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$stmt = $this->sDb->query($sQuery);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		//$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$aResultFilterTotal = $stmt->fetch();
		//$aResultFilterTotal = $stmt->fetchAll();
		$iFilteredTotal = $aResultFilterTotal[0];
		//echo $iFilteredTotal;exit;
		//print_r($aResultFilterTotal);exit;
	
		/* Total data set length */
		$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
				FROM   $sTable
				 ";
		//$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		//echo $sQuery;exit;
		$stmt = $this->sDb->query($sQuery);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		//$aResultTotal = mysql_fetch_array($rResultTotal);
		$aResultTotal = $stmt->fetch();
		$iTotal = $aResultTotal[0];
		//echo $iTotal;exit;
	
	
		/*
		* Output
		*/
		$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
				);
	
				//var_dump($rResult);
	
				foreach ($rResult as $aRow)
				{
				$row = array();
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
				if ( $aColumns[$i] == "version" )
				{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
				}
				else if ( $aColumns[$i] != ' ' )
					{
					 /* General output */
					$row[] = $aRow[ $aColumns2[$i] ];
					//var_dump($aRow);
				}
				}
					$output['aaData'][] = $row;
				//var_dump($row);
				}
				return $output;
				}	
}


?>