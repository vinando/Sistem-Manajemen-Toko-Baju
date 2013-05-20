<?php

class Application_Model_Role {
	private $sDb;

	function __construct() {
		$this->sDb = Zend_Registry::get('DB');
	}

	function getAll($intLimit = 0, $intOffset = 50) {
		$strSQL = "SELECT * FROM mst_role LIMIT $intLimit, $intOffset";
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();
		return $res;
	}

	function getAllByRoleID($strRoleID) {
		if ($strRoleID) {
			$strSQL = "SELECT * FROM mst_role WHERE id = '$strRoleID'";
		} else {
			$strSQL = "SELECT * FROM mst_role";
		}
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();
		return $res;
	}

	/**
	 * Get all BranchID in an array
	 * @return Array BranchID
	 */
	function getAllRoleID() {
		$strSQL = "SELECT id FROM mst_role";
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();
		return $res;
	}

    function getAllRightsByRoleID($strRoleID) {
        $strSQL = "SELECT * FROM mst_role_dt WHERE roleid = '$strRoleID'";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

	function getSingle($strRoleID) {
		$strSQL = "SELECT * FROM mst_role WHERE id = '$strRoleID'";
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetch();
		return $res;
	}

	function insert($strRoleName, $strDescription) {
        $strSQL = "INSERT INTO mst_role (name, description) VALUES ('$strRoleName', '$strDescription')";
        $stmt = $this->sDb->query($strSQL);
        return $this->sDb->lastInsertId();
	}

    function insertRights($strRoleID, $strMenu) {
        $strSQL = "INSERT INTO mst_role_dt (roleid, menu) VALUES ('$strRoleID', '$strMenu')";
        $stmt = $this->sDb->query($strSQL);
    }

	function update($strRoleID, $strRoleName, $strDescription) {
        $strSQL = "UPDATE mst_role SET name = '$strRoleName', description = '$strDescription' WHERE id = '$strRoleID'";
        $stmt = $this->sDb->query($strSQL);
	}

	function delete($strRoleID) {
        $strSQL = "DELETE FROM mst_role WHERE id = '$strRoleID'";
        $stmt = $this->sDb->query($strSQL);
	}

    function deleteRightsByRoleID($strRoleID) {
        $strSQL = "DELETE FROM mst_role_dt WHERE roleid = '$strRoleID'";
        $stmt = $this->sDb->query($strSQL);
    }

	function getDataTable() {
	    $sTable   = "mst_role";
	    $aColumns = array( 'id', 'name', 'description');
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

    	/* Total data set length */
    	$sQuery = "
    		SELECT COUNT(`".$sIndexColumn."`)
    		FROM   $sTable
    	";
    	$stmt = $this->sDb->query($sQuery);
    	$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
    	//$aResultTotal = mysql_fetch_array($rResultTotal);
    	$aResultTotal = $stmt->fetch();
    	$iTotal = $aResultTotal[0];

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