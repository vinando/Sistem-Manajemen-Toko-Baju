<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:22 PM
 * To change this template use File | Settings | File Templates.
 */
class Application_Model_Trnsalesorder {
    private $sDb;
    function __construct() {
        $this->sDb = Zend_Registry::get('DB');
    }

    function getSingle($strOrderId) {
        $strSQL = "SELECT * FROM trn_sales_order WHERE id = '$strOrderId'";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetch();
        return $res;
    }

    function getAll($intLimit = 0, $intOffset = 50) {
        $strSQL = "SELECT * FROM trn_sales_order LIMIT $intLimit, $intOffset";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

    function getOrderDetails($order_id) {
        $strSQL = " SELECT t1.*, t2.code as productcode, t2.name as productname, t3.name as sizename, t4.name as colorname, t5.name as materialname
                    FROM trn_sales_order_dt t1  LEFT JOIN mst_product t2 ON t1.productid = t2.id
                                                    LEFT JOIN mst_product_size t3 ON t1.sizeid = t3.id
                                                    LEFT JOIN mst_product_color t4 ON t1.colorid = t4.id
                                                    LEFT JOIN mst_product_material t5 ON t1.materialid = t5.id
                    WHERE t1.orderid = '$order_id'";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

    function getProducts() {
        $strSQL = "SELECT * FROM mst_product";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

    function getFinishProducts() {
        $strSQL = "SELECT * FROM mst_product WHERE ptype = 'Finished Product'";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

    function updateOrderNo($order_id, $order_no) {
        $strSQL = "UPDATE trn_sales_order SET orderno = '$order_no' WHERE id = '$order_id'";
        $stmt = $this->sDb->query($strSQL);
    }

    function getSizes() {
        $strSQL = "SELECT * FROM mst_product_size";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

    function getColors() {
        $strSQL = "SELECT * FROM mst_product_color";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

    function getMaterials() {
        $strSQL = "SELECT * FROM mst_product_material";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_BOTH);
        $res = $stmt->fetchAll();
        return $res;
    }

    function insertHeader($strBranchID, $strOrderNo, $strOrderDate, $strCustomerID, $strCustomerName, $strPhone) {
        $strSQL = "INSERT INTO trn_sales_order (branchid, orderno, orderdate, customerid, customername, phone) VALUES ('$strBranchID', '$strOrderNo', '$strOrderDate', '$strCustomerID', '$strCustomerName', '$strPhone')";
        $stmt = $this->sDb->query($strSQL);
        return $this->sDb->lastInsertId();
    }

    function insertDetail($strOrderId, $strProductId, $strNoSeri, $strSizeId, $strColorId, $strMaterialId, $strPrice, $strQty, $strTotal) {
        $strSQL = " INSERT INTO trn_sales_order_dt (orderid, productid, noseri, sizeid, colorid, materialid, price, amount, total)
                    VALUES ('$strOrderId', '$strProductId', '$strNoSeri', '$strSizeId', '$strColorId', '$strMaterialId', '$strPrice', '$strQty', '$strTotal')";
        $stmt = $this->sDb->query($strSQL);

    }

    function updateHeader($strOrderId, $strCustomerId, $strCustomerName, $strPhone) {
        $strSQL = " UPDATE trn_sales_order
                    SET customerid = '$strCustomerId', customername = '$strCustomerName', phone = '$strPhone'
                    WHERE id = '$strOrderId'";
        $stmt = $this->sDb->query($strSQL);
    }

    function delete($strOrderId) {
        //Delete transaction detail items
        $this->deleteDetail($strOrderId);

        //Delete transaction header
        $strSQL = "DELETE FROM trn_sales_order WHERE id = '$strOrderId'";
        $this->sDb->query($strSQL);
    }

    function deleteDetail($strOrderId) {
        $strSQL = "DELETE FROM trn_sales_order_dt WHERE orderid = '$strOrderId'";
        $this->sDb->query($strSQL);
    }

    function updateStock($strBranchId, $strProductId, $strNoSeri, $sign, $qty) {
        $strSQL = "UPDATE mst_stock SET stock = stock $sign $qty WHERE branchid = '$strBranchId' AND productid = '$strProductId' AND noseri = '$strNoSeri'";
        //return $strSQL;
        $this->sDb->query($strSQL);
    }

    function getDataTable() {
        $sTable   = "trn_sales_order";
        $aColumns = array( 'id', 'branchid', 'orderno', 'orderdate', 'customername', 'phone');
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
