<?php

class Application_Model_Lookup {
	private $sDb;

	function __construct() {
		$this->sDb = Zend_Registry::get('DB');
	}

    function getListOrder() {
        $sTable   = "trn_order";
        $aColumns = array( 'id', 'branchid', 'orderno', 'orderdate', 'status', 'customername', 'phone');
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

    function getListService() {
        $sTable   = "trn_service";
        $aColumns = array( 'id', 'branchid', 'servicetype', 'trnno', 'trndate', 'status', 'customername', 'phone');
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

    function getListEmployee() {
        $sTable   = "mst_employee";
        $aColumns = array( 'id', 'branchid', 'type', 'code', 'name', 'salary');
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