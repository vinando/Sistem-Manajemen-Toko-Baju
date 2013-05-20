<?php
class Application_Model_Report {
	private $sDb;

	function __construct() {
		$this->sDb = Zend_Registry::get('DB');
	}

    // RPT GAJI
    function getGajiPenjahit($BranchID, $EmployeeID, $StartDate, $EndDate) {
        $strSQL = " SELECT (t3.salary * t4.totalfinishproduct) as totalgaji
                    FROM mst_employee t3, (
                        SELECT SUM(t2.amount) as totalfinishproduct
                        FROM trn_finish_order t1, trn_order_dt t2
                        WHERE   t1.orderid = t2.orderid
                            AND t1.branchid = '$BranchID'
                            AND t1.employeeid = '$EmployeeID'
                            AND finishdate >= '$StartDate'
                            AND finishdate < DATE_ADD('$EndDate', INTERVAL 1 DAY)) t4
                    WHERE t3.id = '$EmployeeID'";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
        //return $strSQL;
    }

    function getGajiDetail($branchid, $employeeid, $startdate, $enddate) {
        $strSQL = " SELECT t1.id, t1.orderno, t1.finishdate, t1.finishno, t2.productid, t3.name, t2.amount, t4.salary, (t2.amount * t4.salary) as total
                    FROM trn_finish_order t1, trn_order_dt t2, mst_product t3, mst_employee t4
                    WHERE   t1.orderid = t2.orderid
                        AND t2.productid = t3.id
                        AND t1.employeeid = t4.id
                        AND t1.branchid = '$branchid'
                        AND t1.employeeid = '$employeeid'
                        AND finishdate >= '$startdate'
                        AND finishdate < DATE_ADD('$enddate', INTERVAL 1 DAY)";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
        //return $strSQL;
    }


    //RPT Rugi-laba   ----> Order
    function getOrder($branchid, $startdate, $enddate) {
        $strSQL = " SELECT t1.*, (SELECT SUM(total) FROM trn_order_dt WHERE orderid = t1.id) as total
                    FROM
                        (SELECT *
                        FROM trn_order
                        WHERE
                            branchid = '$branchid' AND
                            orderdate >= '$startdate' AND
                            orderdate < DATE_ADD('$enddate', INTERVAL 1 DAY)) t1";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    //RPT Rugi-laba   ----> Service
    function getService($branchid, $startdate, $enddate) {
        $strSQL = " SELECT t1.*, (SELECT SUM(total) FROM trn_service_dt WHERE trnid = t1.id) as total
                    FROM
                        (SELECT *
                        FROM trn_service
                        WHERE
                            branchid = '$branchid' AND
                            trndate >= '$startdate' AND
                            trndate < DATE_ADD('$enddate', INTERVAL 1 DAY)) t1";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    //RPT Rugi-laba   ----> Finished Order
    function getFinishOrder($branchid, $startdate, $enddate) {
        $strSQL = " SELECT *
                    FROM trn_finish_order
                    WHERE
                        branchid = '$branchid' AND
                        finishdate >= '$startdate' AND
                        finishdate < DATE_ADD('$enddate', INTERVAL 1 DAY)";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    //RPT Rugi-laba   ----> Finished Order
    function getFinishService($branchid, $startdate, $enddate) {
        $strSQL = " SELECT *
                    FROM trn_finish_service
                    WHERE
                        branchid = '$branchid' AND
                        finishdate >= '$startdate' AND
                        finishdate < DATE_ADD('$enddate', INTERVAL 1 DAY)";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    //RPT PEMBELANJAAN
    function getPembelanjaan($branchid, $startdate, $enddate) {
        $strSQL = " SELECT t1.*, (SELECT SUM(total) FROM trn_purchases_order_dt WHERE orderid = t1.id) as total
                    FROM
                        (SELECT *
                        FROM trn_purchases_order
                        WHERE
                            branchid = '$branchid' AND
                            orderdate >= '$startdate' AND
                            orderdate < DATE_ADD('$enddate', INTERVAL 1 DAY)) t1";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    //RPT PEMASUKAN
    function getPemasukan($branchid, $startdate, $enddate) {
        $strSQL = " SELECT t1.*, (SELECT SUM(total) FROM trn_sales_order_dt WHERE orderid = t1.id) as total
                    FROM
                        (SELECT *
                        FROM trn_sales_order
                        WHERE
                            branchid = '$branchid' AND
                            orderdate >= '$startdate' AND
                            orderdate < DATE_ADD('$enddate', INTERVAL 1 DAY)) t1";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    //RPT BIAYA OPERATIONAL
    function getBiayaOperasional($branchid, $startdate, $enddate) {
        $strSQL = " SELECT *
                    FROM trn_daily_cash
                    WHERE
                        branchid = '$branchid' AND
                        trndate >= '$startdate' AND
                        trndate < DATE_ADD('$enddate', INTERVAL 1 DAY)";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    function getPemasukanHarian($branchid, $startdate, $enddate) {
        $strSQL = " SELECT *
                    FROM trn_daily_cash
                    WHERE
                        branchid = '$branchid' AND
                        trndate >= '$startdate' AND
                        trndate < DATE_ADD('$enddate', INTERVAL 1 DAY)";
        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    //RPT STOCK OP NAME
    function getStock($branchid) {
        $strSQL = " SELECT t1.productid, sum(t1.stock) as stock, t2.code, t2.name, t2.ptype, t2.size, t2.color, t2.material
                    FROM mst_stock t1, mst_product t2
                    WHERE
                        t1.productid = t2.id AND
                        t1.branchid = '$branchid'
                    GROUP BY t2.code";

        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }

    function getStockDetail($branchid, $productid) {
        $strSQL = " SELECT t1.*, t2.code, t2.name, t2.ptype, t2.size, t2.color, t2.material
                    FROM mst_stock t1, mst_product t2
                    WHERE
                        t1.productid = t2.id AND
                        t1.branchid = '$branchid' AND
                        t1.productid = '$productid'";

        $stmt = $this->sDb->query($strSQL);
        $stmt->setFetchMode(Zend_Db::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        return $res;
    }


}
?>