<?php

class SettingController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_Setting();
        
        $this->view->AccordionID = "Maintenance";
        $this->view->SelectedMenu = "Setting";
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
                $this->view->assign("strID", $arrData['id']);
                $this->view->assign("Name", $arrData['name']);
                $this->view->assign("Value", $arrData['value']);
                break;
        }
    }

    public function saveAction() {
        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                $this->model->insert(addslashes($this->getRequest()->getParam("Name")),
                    addslashes($this->getRequest()->getParam("Value"))
                );

                echo "<script>window.parent.saveSuccess('ID', '" . $this->getRequest()->getParam("ID") . "');window.parent.disableField('ID')</script>";
                break;

            case "Edit":
                $this->model->update(addslashes($this->getRequest()->getParam("ID")),
                    addslashes($this->getRequest()->getParam("Name")),
                    addslashes($this->getRequest()->getParam("Value"))
                );

                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                break;

            case "Delete":
                $this->model->delete(addslashes($this->getRequest()->getParam("ID")));
                break;
        }
        exit;

    }
}

