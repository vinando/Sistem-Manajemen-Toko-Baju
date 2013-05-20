<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/7/13
 * Time: 2:18 AM
 * To change this template use File | Settings | File Templates.
 */
class RptpembelanjaanController extends Zend_Controller_Action {
    private $model;
    private $branch_model;
    private $trnpurchases_model;

    public function init() {
        $this->model = new Application_Model_Report();
        $this->branch_model = new Application_Model_Branch();
        $this->trnpurchases_model = new Application_Model_Trnpurchasesorder();
        $this->view->AccordionID  = "Reporting";
        $this->view->SelectedMenu = "Pembelanjaan";
    }

    public function preDispatch() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
        $branch_model = new Application_Model_Branch();
        if($_SESSION['RoleID'] == '1') $this->view->branchs = $branch_model->getAll();
        else $this->view->branchs = $branch_model->getSingleBranch($_SESSION['BranchID']);
    }

    public function viewresultAction() {
        // action body
        $data = $this->getDataPembelanjaan();
        $this->view->startdate = $this->getRequest()->getParam('startdate');
        $this->view->enddate = $this->getRequest()->getParam('enddate');
        $this->view->data = $data;
    }

    public function viewdetailAction() {
        $helper = $this->_helper->getHelper('Layout');
        $layout = $helper->getLayoutInstance();
        $layout->setLayout("layout_report_detail");

        $order_id = $this->getRequest()->getParam('orderid');
        $dataHeader = $this->trnpurchases_model->getSingle($order_id);
        $dataDetail = $this->trnpurchases_model->getOrderDetails($order_id);
        $this->view->dataHeader = $dataHeader;
        $this->view->dataDetail = $dataDetail;
    }

    public function getDataPembelanjaan() {
        $branchids = $this->getRequest()->getParam('branchids');
        $branchids = explode(',', $branchids);
        $startdate = $this->getRequest()->getParam('startdate');
        $enddate = $this->getRequest()->getParam('enddate');
        $data = array();
        if(count($branchids) > 0){
            foreach ($branchids as $idx => $branchid){
                $branch = $this->branch_model->getSingle($branchid);
                $data[$idx]['branch'] = $branch;
                $data[$idx]['data'] = $this->model->getPembelanjaan($branchid, $this->convertDate($startdate), $this->convertDate($enddate));
            }
        }
        else {

        }
        return $data;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}
