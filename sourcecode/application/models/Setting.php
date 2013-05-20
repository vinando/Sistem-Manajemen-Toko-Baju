<?php

class Application_Model_Setting {
	private $sDb;

	function __construct() {
		$this->sDb = Zend_Registry::get('DB');
	}

	function getAll() {
		$strSQL = "SELECT * FROM mst_setting";
		//echo $strSQL;
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetchAll();
		return $res;
	}

	function getSingle($strKey) {
		$strSQL = "SELECT * FROM mst_setting WHERE Key = '$strKey'";
		$stmt = $this->sDb->query($strSQL);
		$stmt->setFetchMode(Zend_Db::FETCH_BOTH);
		$res = $stmt->fetch();
		return $res;
	}

	function update($strKey, $strValue) {
        $strSQL = "REPLACE INTO mst_setting (`Key`, `Value`) VALUES ('$strKey', '$strValue')";
        $stmt = $this->sDb->query($strSQL);
	}
}


?>