<?php

class TrnorderassignmentController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_Trnorderassignment();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Trnorderassignment";
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
                $this->view->assign("OrderId", $arrData['orderid']);
                $this->view->assign("OrderNo", $arrData['orderno']);
                $this->view->assign("AssignmentDate", $arrData['assignmentdate']);
                $this->view->assign("EmployeeId", $arrData['employeeid']);
                $this->view->assign("EmployeeName", $arrData['employeename']);
                $this->view->assign("Price", $arrData['price']);
                break;
        }
    }

    public function saveAction() {
        $order_model = New Application_Model_Trnorder();
        $strBranchID       = $this->getRequest()->getParam("BranchID");
        $strOrderId        = $this->getRequest()->getParam("OrderId");
        $strOrderNo        = $this->getRequest()->getParam("OrderNo");
        $strAssignmentDate = $this->getRequest()->getParam("AssignmentDate");
        $strEmployeeId     = $this->getRequest()->getParam("EmployeeId");
        $strEmployeeName   = $this->getRequest()->getParam("EmployeeName");
        $strPrice   = $this->getRequest()->getParam("Price");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                $strAssignmentDate = date('Y-m-d h:i:s');
                $assignment_id = $this->model->insert($strBranchID, $strOrderId, $strOrderNo, $strAssignmentDate, $strEmployeeId, $strEmployeeName, $strPrice);
                if ($assignment_id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', " . $assignment_id . ");</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $order_model->updateStatusOrder($strOrderId, 'Assigned');
                break;

            case "Edit":
                $strAssignmentId = $this->getRequest()->getParam("ID");
                $this->model->update(   $strAssignmentId,
                                        $strBranchID,
                		                $strOrderId,
                                        $strOrderNo,
                                        $strAssignmentDate,
                                        $strEmployeeId,
                                        $strEmployeeName,
                                        $strPrice);
                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                $this->model->updateStatusOrder($strOrderId, 'Assigned');
                break;

            case "Delete":
            	$strAssignmentId = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strAssignmentId);
                $this->model->updateStatusOrder($strOrderId, 'Ordered');
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}

