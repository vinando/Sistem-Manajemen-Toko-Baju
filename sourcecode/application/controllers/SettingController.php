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
        $arrSettings = $this->model->getAll();
        //var_dump($arrSettings);exit;

        // Set record data to form
        foreach ($arrSettings as $arrSetting) {
            //var_dump($arrSetting);
            $this->view->assign($arrSetting["Key"], $arrSetting["Value"]);
        }

        $model = new Application_Model_Ticketcategory();
        $this->view->assign("Categories", $model->getAll());
    }

    public function saveAction() {
        $arrRequest = $this->getRequest()->getParams();
        $arrKeys = array_keys($arrRequest);

        foreach ($arrKeys as $strKey) {
            if ($strKey != "controller" and $strKey != "action" and $strKey != "module" and $strKey != "type")
            $this->model->update($strKey, $arrRequest[$strKey]);
        }

        echo "<script>window.parent.editSuccess()</script>";
        exit;
    }
}

