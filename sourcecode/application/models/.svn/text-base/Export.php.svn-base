<?php

class Application_Model_Export
{
	private $sDb;
	function __construct(){
		$this->sDb = Zend_Registry::get('DB');
	}

	function getDataPeriod($BranchID, $start, $end){
		$strSQL = "SELECT * FROM trn_ticketing WHERE branchid = '".$BranchID."' AND createdate between '".$start."' AND '".$end."'";
		//echo $strSQL;
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
		$result = $stmt->fetchAll();	
		return $result;
	}
}

