<?php

class NewsController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_News();
        
        $this->view->AccordionID = "Master";
        $this->view->SelectedMenu = "News";
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
                $this->view->assign("BranchID", $arrData['BranchID']);
                $this->view->assign("NewsTitle", $arrData['NewsTitle']);
                $this->view->assign("NewsDesc", $arrData['NewsDesc']);

                break;
        }
        $model = new Application_Model_Branch();
        $this->view->assign("Branches", $model->getAllByBranchID($_SESSION["BranchID"]));
    }

    public function saveAction() {
    	$strNewsID    = addslashes($this->getRequest()->getParam("ID"));
    	$strBranchID  = addslashes($this->getRequest()->getParam("BranchID"));
    	$strNewsTitle = addslashes($this->getRequest()->getParam("NewsTitle"));
    	$strNewsDesc  = addslashes($this->getRequest()->getParam("NewsDesc"));
    	
        switch ($this->getRequest()->getParam("type")) {
            case "Add":
            	$strNewsID = "";
                $strNewsID = $this->model->insert($strBranchID, $strNewsTitle, $strNewsDesc);
                if ($strNewsID) {
                    echo "<script>window.parent.saveSuccess('ID', '$strNewsID')</script>";
                } else {
                    echo "<script>window.parent.saveFailed('Please fill all required fields first')</script>";
                }
                break;

            case "Edit":
                $this->model->update($strNewsID,
                        $strBranchID,
                		$strNewsTitle,
                		$strNewsDesc);
                echo "<script>window.parent.editSuccess()</script>";
                break;

            case "Delete":
                $this->model->delete($strNewsID);
                break;
        }
        exit;

    }
}

