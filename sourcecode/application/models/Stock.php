<?php

class Application_Model_Stock {
	private $sDb;

	function __construct() {
		$this->sDb = Zend_Registry::get('DB');
	}

	function getAll($intLimit = 0, $intOffset = 50) {
		$strSQL = "SELECT * FROM mst_stock LIMIT $intLimit, $intOffset";
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();
		return $res;
	}

	function getAllByProductID($strProductID) {
		if ($strProductID) {
			$strSQL = "SELECT * FROM mst_stock WHERE productid = '$strProductID'";
		} else {
			$strSQL = "SELECT * FROM mst_stock";
		}
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();
		return $res;
	}

    function getAllByBranchID($strBranchID) {
        if ($strBranchID) {
            $strSQL = "SELECT * FROM mst_stock WHERE branchid = '$strBranchID'";
        } else {
            $strSQL = "SELECT * FROM mst_stock";
        }
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }
	/**
	 * Get all ProductID in an array
	 * @return Array ProductID
	 */

	function getSingle($strStockId) {
		$strSQL = "SELECT * FROM mst_stock WHERE id = '$strStockId'";
		$stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetch();
		return $res;
	}

    function getLastNoSeri($product_id) {
        $strSQL = "SELECT max(noseri) as noseri FROM mst_stock WHERE productid = '$product_id'";
        $stmt = $this->sDb->query($strSQL);
        $res = $stmt->fetch();
        return $res;
    }

	function insert($strBranchId, $strProductId, $strNoSeri, $strStock, $strPrice) {
        $strSQL = "INSERT INTO mst_stock (branchid, productid, noseri, stock, price) VALUES ('$strBranchId', '$strProductId', '$strNoSeri', '$strStock', '$strPrice')";
        $stmt = $this->sDb->query($strSQL);
        return $this->sDb->lastInsertId();
	}

	function update($strStockId, $strBranchId, $strProductId, $strNoSeri, $strStock, $strPrice) {
        $strSQL = "UPDATE mst_stock SET branchid = '$strBranchId', productid = '$strProductId', noseri = '$strNoSeri', stock = '$strStock', price = '$strPrice' WHERE id = '$strStockId'";
        $stmt = $this->sDb->query($strSQL);
	}

	function delete($strStockId) {
        $strSQL = "DELETE FROM mst_stock WHERE id = '$strStockId'";
        $stmt = $this->sDb->query($strSQL);
	}

	function getDataTable($BranchId) {
	    $sTable   = "mst_stock";
	    $aColumns = array( 'id', 'branchid', 'productid', 'noseri', 'stock', 'price');
	    $sIndexColumn = "id";

        /*
    	 * Paging
    	 */
    	$sLimit = "";
    	if ( isset( $_POST['iDisplayStart'] ) && $_POST['iDisplayLength'] != '-1' ){
    		$sLimit = "LIMIT ".addslashes( $_POST['iDisplayStart'] ).", " . addslashes( $_POST['iDisplayLength'] );

    	}
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
    				$sOrder .= "`".$aColumns[ intval( $_POST['iSortCol_'.$i] ) ]."` ".
    				 	addslashes( $_POST['sSortDir_'.$i] ) .", ";
    			}
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
    	if ( isset($_POST['sSearch']) && $_POST['sSearch'] != "" )
    	{
    		$sWhere = "WHERE (";
    		for ( $i=0 ; $i<count($aColumns) ; $i++ )
    		{
    			$sWhere .= "`".$aColumns[$i]."` LIKE '%".addslashes( $_POST['sSearch'] )."%' OR ";
    		}
    		$sWhere = substr_replace( $sWhere, "", -3 );
    		$sWhere .= ')';
    	}
    	//echo $sWhere;exit;


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
    			$sWhere .= "`".$aColumns[$i]."` LIKE '%".addslashes($_POST['sSearch_'.$i])."%' ";
    		}
    	}


    	/*
    	 * SQL queries
    	 * Get data to display
    	 */
        if($BranchId > 0){
            if($sWhere == '') $sWhere = ' WHERE branchid = '. $BranchId . ' ';
            else $sWhere .= ' AND branchid = '. $BranchId . ' ';
        }


    	$sQuery = "
    		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
    		FROM   $sTable
    		$sWhere
    		$sOrder
    		$sLimit
    		";
    	//$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
    	$stmt = $this->sDb->query($sQuery);
    	$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
    	$rResult = $stmt->fetchAll();
    	//echo $sQuery;exit;

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
    		SELECT COUNT(`".$sIndexColumn."`)
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
    				$row[] = $aRow[ $aColumns[$i] ];
    			}
    		}
    		$output['aaData'][] = $row;
    	}
	    return $output;
	}
}
?>