<?php

class TrnserviceassignmentController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_Trnserviceassignment();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Trnserviceassignment";
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
                $this->view->assign("BranchID", $arrData['branchid']);
                $this->view->assign("TrnId", $arrData['trnid']);
                $this->view->assign("TrnNo", $arrData['trnno']);
                $this->view->assign("AssignmentDate", $arrData['assignmentdate']);
                $this->view->assign("EmployeeId", $arrData['employeeid']);
                $this->view->assign("EmployeeName", $arrData['employeename']);
                $this->view->assign("Price", $arrData['price']);
                break;
        }
    }

    public function saveAction() {
        $service_model = New Application_Model_Trnservice();
        $strBranchID       = $this->getRequest()->getParam("BranchID");
        $strTrnId        = $this->getRequest()->getParam("TrnId");
        $strTrnNo        = $this->getRequest()->getParam("TrnNo");
        $strAssignmentDate = $this->getRequest()->getParam("AssignmentDate");
        $strEmployeeId     = $this->getRequest()->getParam("EmployeeId");
        $strEmployeeName   = $this->getRequest()->getParam("EmployeeName");
        $strPrice   = $this->getRequest()->getParam("Price");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                $strAssignmentDate = date('Y-m-d h:i:s');
                $assignment_id = $this->model->insert($strBranchID, $strTrnId, $strTrnNo, $strAssignmentDate, $strEmployeeId, $strEmployeeName, $strPrice);
                if ($assignment_id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', " . $assignment_id . ");</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $service_model->updateStatusService($strTrnId, 'Assigned');
                break;

            case "Edit":
                $strAssignmentId = $this->getRequest()->getParam("ID");
                $this->model->update(   $strAssignmentId,
                                        $strBranchID,
                		                $strTrnId,
                                        $strTrnNo,
                                        $strAssignmentDate,
                                        $strEmployeeId,
                                        $strEmployeeName,
                                        $strPrice);
                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                $service_model->updateStatusService($strTrnId, 'Assigned');
                break;

            case "Delete":
            	$strAssignmentId = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strAssignmentId);
                $service_model->updateStatusService($strTrnId, 'Ordered');
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}

