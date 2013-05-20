<?php

class EmployeeController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_Employee();
        $this->view->AccordionID  = "Master";
        $this->view->SelectedMenu = "Employee";
    }

    public function preDispatch() {
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
    }

    public function dataAction() {
        //echo $this->model->getDataTable();
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
                $this->view->assign("EmployeeCode", $arrData['code']);
                $this->view->assign("EmployeeName", $arrData['name']);
                $this->view->assign("BranchID", $arrData['branchid']);
                $this->view->assign("DOB", $this->convertDate($arrData['dob']));
                $this->view->assign("Gender", $arrData['gender']);
                $this->view->assign("Salary", $arrData['salary']);
                break;
        }
        $model = new Application_Model_Branch();
        $this->view->assign("Branches", $model->getAll());
    }

    public function saveAction() {
    	$strEmployeeCode    = $this->getRequest()->getParam("EmployeeCode");
        $strBranchID        = $this->getRequest()->getParam("BranchID");
        $strEmployeeName    = $this->getRequest()->getParam("EmployeeName");
        $strDOB             = $this->convertDate($this->getRequest()->getParam("DOB"));
        $strGender          = $this->getRequest()->getParam("Gender");
        $strSalary          = $this->getRequest()->getParam("Salary");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                // Check empty fields
                if (!$strEmployeeCode or !$strEmployeeName) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please input all required field first!')</script>";
                    exit;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $strEmployeeName)) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please use only alphanumeric on Employee Name!')</script>";
                    break;
                }

                $id = $this->model->insert($strBranchID, $strEmployeeCode, addslashes($strEmployeeName), $strDOB, $strGender, $strSalary);
                if ($id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                $EmployeeID = $this->getRequest()->getParam("ID");
                // Check empty fields
                if (!$strEmployeeCode or !$strEmployeeName) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please input all required field first!')</script>";
                    exit;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $strEmployeeName)) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please use only alphanumeric on Employee Name!')</script>";
                    break;
                }
                //echo $this->getRequest()->getParam("MainDisplay");exit;
                $this->model->update(   addslashes($EmployeeID),
                                        $strBranchID,
                		                addslashes($strEmployeeCode),
                                        addslashes($strEmployeeName),
                                        $strDOB,
                                        $strGender,
                                        $strSalary);
                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                break;

            case "Delete":
            	$strEmployeeID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strEmployeeID);
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}

