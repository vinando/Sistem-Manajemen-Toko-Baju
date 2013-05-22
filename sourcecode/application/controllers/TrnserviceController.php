<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
class TrnserviceController extends Zend_Controller_Action {
    private $model;
    public function init() {
        $this->model = new Application_Model_Trnservice();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Service";
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
                $this->view->assign("ServiceType", $trn['servicetype']);
                $this->view->assign("TrnNo", $trn['trnno']);
                $this->view->assign("TrnDate", $trn['trndate']);
                $this->view->assign("CustomerId", $trn['customerid']);
                $this->view->assign("CustomerName", $trn['customername']);
                $this->view->assign("Phone", $trn['phone']);
                $this->view->assign("Total", $trn['total']);
                $this->view->assign("Dp", $trn['dp']);

                $trn_details = $this->model->getTrnDetails($trn_id);
                $this->view->assign("NumRows", count($trn_details));
                $this->view->assign("Items", $trn_details);
                break;
        }
        $this->view->assign("Products", $this->model->getFinishProducts());
        $this->view->assign("Sizes", $this->model->getSizes());
        $this->view->assign("Colors", $this->model->getColors());
        $this->view->assign("Materials", $this->model->getMaterials());
    }

    public function saveAction() {
        $jsonstring = $this->getRequest()->getParam('jsonstring');
        $data = json_decode($jsonstring, 'array');
        $trn_id = $data['ID'];
        $branch_id = $data['BranchID'];
        $service_type = $data['ServiceType'];
        $trn_no = '';
        $trn_date = date('Y-m-d h:i:s');
        $customer_id = $data['CustomerId'];
        $customer_name = $data['CustomerName'];
        $phone = $data['Phone'];
        $total = $data['Total'];
        $dp = $data['Dp'];

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                //echo "Add succes atuh";

                $trn_id = $this->model->insertHeader($branch_id, $service_type, $trn_no, $trn_date, $customer_id, $customer_name, $phone, $total, $dp);
                $trn_no = 'S-' . $branch_id . '-' . $trn_id;
                //echo $order_no;
                $this->model->updateTrnNo($trn_id, $trn_no);
                if ($trn_id && count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $productname = $item['productname'];
                        $this->model->insertDetail($trn_id, $productname, $item['sizename'], $item['colorname'], $item['materialname'], $item['price'], $item['qty'], $item['total']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $trn_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $this->model->updateStatusService($trn_id, 'Ordered');
                break;

            case "Edit":
                $this->model->updateHeader($trn_id, $service_type, $customer_id, $customer_name, $phone, $total, $dp);
                $this->model->deleteDetail($trn_id);
                if (count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $productname = $item['productname'];
                        $this->model->insertDetail($trn_id, $productname, $item['sizename'], $item['colorname'], $item['materialname'], $item['price'], $item['qty'], $item['total']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $trn_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $this->model->updateStatusService($trn_id, 'Ordered');
                break;

            case "Delete":
                $strTrnID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strTrnID);
                break;
        }

        exit;
    }

    public function cetaknotaAction() {
        $trn_id = $this->getRequest()->getParam("id");
        $trn = $this->model->getSingle($trn_id);

        // Set record data to form
        $this->view->assign("BranchID", $trn['branchid']);
        $this->view->assign("ServiceType", $trn['servicetype']);
        $this->view->assign("TrnNo", $trn['trnno']);
        $this->view->assign("TrnDate", $trn['trndate']);
        $this->view->assign("CustomerId", $trn['customerid']);
        $this->view->assign("CustomerName", $trn['customername']);
        $this->view->assign("Phone", $trn['phone']);
        $this->view->assign("Total", $trn['total']);
        $this->view->assign("Dp", $trn['dp']);

        $trn_details = $this->model->getTrnDetails($trn_id);
        $this->view->assign("Items", $trn_details);
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }

}
