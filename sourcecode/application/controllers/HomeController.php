<?php

class HomeController extends Zend_Controller_Action {
    private $model;
    private $perpage;  // Limit Paging

    public function init() {
    	$this->model = new Application_Model_News();
    	$this->perpage = 10;
    	
    	$this->view->SelectedMenu = "Home";
    }

    public function preDispatch() {
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
    	
    }
    public function dataAction() {
		echo json_encode($this->model->getDataTableHome($_SESSION["BranchID"]));
    	exit;
    }
    public function detailAction(){
    	$strNewsID = $this->getRequest()->getParam('id');
    	$arrNews = $this->model->getSingle($strNewsID);
    	$this->view->assign("NewsTitle", $arrNews["NewsTitle"]);
    	$this->view->assign("NewsDesc", $arrNews["NewsDesc"]);
    }
}

