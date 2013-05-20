<?php

class LookupController extends Zend_Controller_Action {
    private $model;

    public function init() {
    	$this->model = new Application_Model_Lookup();
        $helper = $this->_helper->getHelper('Layout');
        $layout = $helper->getLayoutInstance();
        $layout->setLayout("layout_lookup");
    }

    public function preDispatch() {
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
    	
    }

    function orderAction() {}

    function dataorderAction() {
        echo json_encode($this->model->getListOrder());
        exit;
    }

    function serviceAction() {}

    function dataserviceAction() {
        echo json_encode($this->model->getListService());
        exit;
    }

    function employeeAction() {}

    function dataemployeeAction() {
        echo json_encode($this->model->getListEmployee());
        exit;
    }

}

