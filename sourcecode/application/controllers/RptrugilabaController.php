<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/7/13
 * Time: 2:18 AM
 * To change this template use File | Settings | File Templates.
 */
class RptrugilabaController extends Zend_Controller_Action {
    private $model;
    private $branch_model;
    private $trnorder_model;
    private $trnsales_model;
    private $trnpurchases_model;
    private $trnkasharian_model;

    public function init() {
        $this->model = new Application_Model_Report();
        $this->branch_model = new Application_Model_Branch();
        $this->trnorder_model = new Application_Model_Trnorder();
        $this->trnsales_model = new Application_Model_Trnsalesorder();
        $this->trnpurchases_model = new Application_Model_Trnpurchasesorder();
        $this->trnkasharian_model = new Application_Model_Trnkasharian();
        $this->view->AccordionID  = "Reporting";
        $this->view->SelectedMenu = "Rugi Laba";
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

        $data = $this->getDataRugiLaba();

        $this->view->startdate = $this->getRequest()->getParam('startdate');
        $this->view->enddate = $this->getRequest()->getParam('enddate');
        $this->view->data = $data;
    }

    public function getDataRugiLaba() {
        $branchids = $this->getRequest()->getParam('branchids');
        $branchids = explode(',', $branchids);
        $startdate = $this->getRequest()->getParam('startdate');
        $enddate = $this->getRequest()->getParam('enddate');

        $data = array();
        if(count($branchids) > 0){
            foreach ($branchids as $idx => $branchid){
                $branch = $this->branch_model->getSingle($branchid);
                $data[$idx]['branch'] = $branch;
                $data[$idx]['order'] = $this->model->getOrder($branchid, $this->convertDate($startdate), $this->convertDate($enddate));
                $data[$idx]['service'] = $this->model->getService($branchid, $this->convertDate($startdate), $this->convertDate($enddate));
                $data[$idx]['sales_order'] = $this->model->getPemasukan($branchid, $this->convertDate($startdate), $this->convertDate($enddate));

                $data[$idx]['finish_order'] = $this->model->getFinishOrder($branchid, $this->convertDate($startdate), $this->convertDate($enddate));
                $data[$idx]['finish_service'] = $this->model->getFinishService($branchid, $this->convertDate($startdate), $this->convertDate($enddate));
                $data[$idx]['purchases_order'] = $this->model->getPembelanjaan($branchid, $this->convertDate($startdate), $this->convertDate($enddate));
                $data[$idx]['kas_harian'] = $this->model->getBiayaOperasional($branchid, $this->convertDate($startdate), $this->convertDate($enddate));
            }
        }
        return $data;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}
