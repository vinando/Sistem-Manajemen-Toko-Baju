<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
class trnfinishserviceController extends Zend_Controller_Action {
    private $model;
    public function init() {
        $this->model = new Application_Model_Trnfinishservice();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Finishservice";
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
                break;

            case "edit":
                $this->view->assign("strFormType", "Edit");
                $this->view->assign("strID", $this->getRequest()->getParam("id"));

                $arrData = $this->model->getSingle($this->getRequest()->getParam("id"));

                // Set record data to form
                $this->view->assign("FinishDate", $this->convertDate($arrData['finishdate']));
                $this->view->assign("FinishNo", $arrData['finishno']);
                $this->view->assign("BranchID", $arrData['branchid']);
                $this->view->assign("ServiceId", $arrData['serviceid']);
                $this->view->assign("ServiceNo", $arrData['serviceno']);
                $this->view->assign("EmployeeId", $arrData['employeeid']);
                $this->view->assign("EmployeeName", $arrData['employeename']);
                $this->view->assign("Price", $arrData['price']);
                break;
        }
    }

    public function saveAction() {
        $service_model = New Application_Model_Trnservice();
        $strBranchID       = $this->getRequest()->getParam("BranchID");
        //$strFinishDate = $this->getRequest()->getParam("FinishDate");
        $strFinishNo = '';
        $strServiceId        = $this->getRequest()->getParam("ServiceId");
        $strServiceNo        = $this->getRequest()->getParam("ServiceNo");
        $strEmployeeId     = $this->getRequest()->getParam("EmployeeId");
        $strEmployeeName   = $this->getRequest()->getParam("EmployeeName");
        $strPrice   = $this->getRequest()->getParam("Price");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                $strFinishDate = date('Y-m-d h:i:s');
                $finish_id = $this->model->insert($strBranchID, $strFinishDate, $strFinishNo, $strServiceId, $strServiceNo, $strEmployeeId, $strEmployeeName, $strPrice);
                $finish_no = 'FS-' . $strBranchID . '-' . $finish_id;
                //echo $order_no;
                $this->model->updateFinishNo($finish_id, $finish_no);
                if ($finish_id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $finish_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $service_model->updateStatusService($strServiceId, 'Finished');
                break;

            case "Edit":
                $strFinishId = $this->getRequest()->getParam("ID");
                $this->model->update($strFinishId, $strEmployeeId, $strEmployeeName, $strPrice);
                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                $service_model->updateStatusService($strServiceId, 'Finished');
                break;

            case "Delete":
                $strFinishID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strFinishID);
                $service_model->updateStatusService($strServiceId, 'Assigned');
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }

}
