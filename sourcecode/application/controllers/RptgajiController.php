<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/7/13
 * Time: 2:18 AM
 * To change this template use File | Settings | File Templates.
 */
class RptgajiController extends Zend_Controller_Action {
    private $model;
    private $branch_model;
    private $employee_model;

    public function init() {
        $this->model = new Application_Model_Report();
        $this->branch_model = new Application_Model_Branch();
        $this->employee_model = new Application_Model_Employee();
        $this->view->AccordionID  = "Reporting";
        $this->view->SelectedMenu = "Gajipegawai";
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
        $data = $this->getDataGaji();
        $this->view->startdate = $this->getRequest()->getParam('startdate');
        $this->view->enddate = $this->getRequest()->getParam('enddate');
        $this->view->data = $data;
    }

    public function viewdetailAction() {
        $helper = $this->_helper->getHelper('Layout');
        $layout = $helper->getLayoutInstance();
        $layout->setLayout("layout_report_detail");

        $startdate = $this->getRequest()->getParam('startdate');
        $enddate = $this->getRequest()->getParam('enddate');
        $branchid = $this->getRequest()->getParam('branchid');
        $employeeid = $this->getRequest()->getParam('employeeid');

        $data = $this->model->getGajiDetail($branchid, $employeeid, $this->convertDate($startdate), $this->convertDate($enddate));
        $this->view->data = $data;
        $this->view->branch = $this->branch_model->getSingle($branchid);
        $this->view->employee = $this->employee_model->getSingle($employeeid);
        $this->view->startdate = $startdate;
        $this->view->enddate = $enddate;
    }

    public function getDataGaji() {
        $branchids = $this->getRequest()->getParam('branchids');
        $branchids = explode(',', $branchids);
        $startdate = $this->getRequest()->getParam('startdate');
        $enddate = $this->getRequest()->getParam('enddate');
        $data = array();
        if(count($branchids) > 0){
            foreach ($branchids as $idx => $branchid){
                $branch = $this->branch_model->getSingle($branchid);
                $data[$idx]['branch'] = $branch;

                //$employees = $this->employee_model->getAllByBranchID($branchid);
                $penjahits = $this->employee_model->getAllPenjahitByBranchID($branchid);
                $karyawans = $this->employee_model->getAllKaryawanByBranchID($branchid);
                $data[$idx]['karyawans'] = $karyawans;
                foreach ($penjahits as $idx1 => $employee){
                    $data[$idx]['penjahits'][$idx1]['employee'] = $employee;
                    $data[$idx]['penjahits'][$idx1]['datagaji'] = $this->model->getGajiPenjahit($branchid, $employee['id'], $this->convertDate($startdate), $this->convertDate($enddate));
                }
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
