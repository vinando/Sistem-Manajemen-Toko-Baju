<?php

class BranchController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_Branch();
        $this->view->AccordionID  = "Master";
        $this->view->SelectedMenu = "Branch";
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
                $this->view->assign("BranchCode", $arrData['code']);
                $this->view->assign("BranchName", $arrData['name']);
                break;
        }
    }

    public function saveAction() {
    	$strBranchCode = $this->getRequest()->getParam("BranchCode");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                // Check empty fields
                if (!$strBranchCode or !$this->getRequest()->getParam("BranchName")) {
                    echo "<script>window.parent.saveFailed('Please input all required field first!')</script>";
                    exit;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $this->getRequest()->getParam("BranchName"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Branch Name!')</script>";
                    break;
                }

                $id = $this->model->insert($strBranchCode,
                		addslashes($this->getRequest()->getParam("BranchName")));
                if ($id) {
                    echo "<script>window.parent.saveSuccess('ID', $id);</script>";
                } else {
                    echo "<script>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                // Check empty fields
                if (!$strBranchCode or !$this->getRequest()->getParam("BranchName") or !$this->getRequest()->getParam("BranchTimeZone")) {
                    echo "<script>window.parent.saveFailed('Please input all required field first!')</script>";
                    exit;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $this->getRequest()->getParam("BranchName"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Branch Name!')</script>";
                    break;
                }
                //echo $this->getRequest()->getParam("MainDisplay");exit;
                $this->model->update(addslashes($this->getRequest()->getParam("ID")),
                		addslashes($strBranchCode),
                        addslashes($this->getRequest()->getParam("BranchName")));
                echo "<script>window.parent.editSuccess()</script>";
                break;

            case "Delete":
            	$strBranchID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strBranchID);
                break;
        }
        exit;
    }
}

