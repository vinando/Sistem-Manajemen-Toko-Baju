<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/7/13
 * Time: 2:18 AM
 * To change this template use File | Settings | File Templates.
 */
class RptstockopnameController extends Zend_Controller_Action {
    private $model;
    private $branch_model;
    private $product_model;
    private $stock_model;

    public function init() {
        $this->model = new Application_Model_Report();
        $this->branch_model = new Application_Model_Branch();
        $this->product_model = new Application_Model_Product();
        $this->stock_model = new Application_Model_Stock();
        $this->view->AccordionID  = "Reporting";
        $this->view->SelectedMenu = "Stockopname";
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
        $data = $this->getDataStock();
        $this->view->startdate = $this->getRequest()->getParam('startdate');
        $this->view->enddate = $this->getRequest()->getParam('enddate');
        $this->view->data = $data;
    }

    public function viewdetailAction() {
        $helper = $this->_helper->getHelper('Layout');
        $layout = $helper->getLayoutInstance();
        $layout->setLayout("layout_report_detail");

        $branch_id = $this->getRequest()->getParam('branchid');
        $product_id = $this->getRequest()->getParam('productid');
        $data['Branch'] = $this->branch_model->getSingle($branch_id);
        $data['Product'] = $this->product_model->getSingle($product_id);
        $data['Detail'] = $this->model->getStockDetail($branch_id, $product_id);
        $this->view->data = $data;
    }

    public function getDataStock() {
        $branchids = $this->getRequest()->getParam('branchids');
        $branchids = explode(',', $branchids);

        $data = array();
        if(count($branchids) > 0){
            foreach ($branchids as $idx => $branchid){
                $branch = $this->branch_model->getSingle($branchid);
                $data[$idx]['branch'] = $branch;
                $data[$idx]['data'] = $this->model->getStock($branchid);
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
