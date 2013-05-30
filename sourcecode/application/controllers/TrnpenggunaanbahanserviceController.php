<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
class TrnpenggunaanbahanserviceController extends Zend_Controller_Action {
    private $model;
    public function init() {
        $this->model = new Application_Model_Trnpenggunaanbahanservice();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Penggunaanbahanservice";
    }

    public function preDispatch() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
    }

    public function dataAction() {
        echo json_encode($this->model->getDataTable());
        exit;
    }

    public function formAction(){
        switch ($this->getRequest()->getParam("type")) {
            case "add";
                $this->view->assign("strFormType", "Add");
                $this->view->assign("strID", "Automatic");
                $this->view->assign("NumRows", "0");
                $this->view->assign("Items", array());
                break;

            case "edit":
                $trn_id = $this->getRequest()->getParam("id");
                $this->view->assign("strFormType", "Edit");
                $this->view->assign("strID", $trn_id);

                $trn = $this->model->getSingle($trn_id);

                // Set record data to form
                $this->view->assign("BranchID", $trn['branchid']);
                $this->view->assign("ServiceId", $trn['serviceid']);
                $this->view->assign("ServiceNo", $trn['serviceno']);
                $this->view->assign("TrnNo", $trn['trnno']);
                $this->view->assign("TrnDate", $trn['trndate']);

                $trn_details = $this->model->getTrnDetails($trn_id);
                $this->view->assign("NumRows", count($trn_details));
                $this->view->assign("Items", $trn_details);
                break;
        }
        $this->view->assign("Products", $this->model->getNonFinishProducts());
    }

    public function saveAction() {
        $jsonstring = $this->getRequest()->getParam('jsonstring');
        $data = json_decode($jsonstring, 'array');
        $trn_id = $data['ID'];
        $branch_id = $data['BranchID'];
        $service_id = $data['ServiceId'];
        $service_no = $data['ServiceNo'];
        $trn_no = '';
        $trn_date = date('Y-m-d h:i:s');

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                //echo "Add succes atuh";

                $trn_id = $this->model->insertHeader($branch_id, $service_id, $service_no, $trn_no, $trn_date);
                $trn_no = 'URMS-' . $branch_id . '-' . $trn_id;
                $this->model->updateTrnNo($trn_id, $trn_no);
                if ($trn_id && count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $product = $item['product'];
                        $arr = explode('|', $product);
                        $product_id = $arr[0];
                        $this->model->insertDetail($trn_id, $product_id, $item['qty']);
                        $this->model->updateStock($_SESSION['BranchID'], $product_id, '-', $item['qty']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $service_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                $trn_no = $data['TrnNo'];
                $this->model->updateHeader($trn_id, $branch_id, $service_id, $service_no, $trn_no, $trn_date);
                $oldItems = $this->model->getTrnDetails($trn_id);
                foreach($oldItems as $oldItems) {
                    $this->model->updateStock($_SESSION['BranchID'], $oldItems['productid'], '+', $oldItems['amount']);
                }
                $this->model->deleteDetail($trn_id);
                if (count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $product = $item['product'];
                        $arr = explode('|', $product);
                        $product_id = $arr[0];
                        $this->model->insertDetail($trn_id, $product_id, $item['qty']);
                        $this->model->updateStock($_SESSION['BranchID'], $product_id, '-', $item['qty']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $service_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Delete":
                $strTrnID = addslashes($this->getRequest()->getParam("ID"));
                $oldItems = $this->model->getTrnDetails($strTrnID);
                foreach($oldItems as $oldItems) {
                    $this->model->updateStock($_SESSION['BranchID'], $oldItems['productid'], '+', $oldItems['amount']);
                }
                $this->model->delete($strTrnID);
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }

}
