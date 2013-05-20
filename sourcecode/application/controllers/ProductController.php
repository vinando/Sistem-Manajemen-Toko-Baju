<?php

class ProductController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_Product();
        $this->view->AccordionID  = "Master";
        $this->view->SelectedMenu = "Product";
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
                $this->view->assign("ProductCode", $arrData['code']);
                $this->view->assign("ProductName", $arrData['name']);
                $this->view->assign("ProductType", $arrData['ptype']);
                $this->view->assign("Size", $arrData['size']);
                $this->view->assign("Color", $arrData['color']);
                $this->view->assign("Material", $arrData['material']);
                break;
        }
        $branch_model = new Application_Model_Branch();
        $this->view->assign("Sizes", $this->model->getSizes());
        $this->view->assign("Colors", $this->model->getColors());
        $this->view->assign("Materials", $this->model->getMaterials());
        $this->view->assign("Branches", $branch_model->getAll());
    }

    public function saveAction() {
    	$strProductCode    = $this->getRequest()->getParam("ProductCode");
        $strProductName    = $this->getRequest()->getParam("ProductName");
        $strType           = $this->getRequest()->getParam("ProductType");
        $strSize           = $this->getRequest()->getParam("Size");
        $strColor          = $this->getRequest()->getParam("Color");
        $strMaterial       = $this->getRequest()->getParam("Material");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                $id = $this->model->insert($strProductCode, $strProductName, $strType, $strSize, $strColor, $strMaterial);
                if ($id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                $ProductID = $this->getRequest()->getParam("ID");

                $this->model->update(   addslashes($ProductID),
                		                addslashes($strProductCode),
                                        addslashes($strProductName),
                                        $strType,
                                        $strSize,
                                        $strColor,
                                        $strMaterial);
                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                break;

            case "Delete":
            	$strProductID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strProductID);
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}

