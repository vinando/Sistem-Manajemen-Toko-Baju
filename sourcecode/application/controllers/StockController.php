<?php

class StockController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_Stock();
        $this->view->AccordionID  = "Master";
        $this->view->SelectedMenu = "Stock";
    }

    public function preDispatch() {
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
    }

    public function dataAction() {
        echo json_encode($this->model->getDataTable(1));
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
                $this->view->assign("ProductId", $arrData['productid']);
                $this->view->assign("NoSeri", $arrData['noseri']);
                $this->view->assign("Stock", $arrData['stock']);
                $this->view->assign("Price", $arrData['price']);
                break;
        }
        $branch_model = new Application_Model_Branch();
        $product_model = new Application_Model_Product();

        $this->view->assign("Branches", $branch_model->getAll());
        $this->view->assign("Products", $product_model->getAll());
    }

    public function saveAction() {
        $strBranchID       = $this->getRequest()->getParam("BranchID");
        $strProductId    = $this->getRequest()->getParam("ProductId");
        $strNoSeri    = $this->getRequest()->getParam("NoSeri");
        $strStock          = $this->getRequest()->getParam("Stock");
        $strPrice          = $this->getRequest()->getParam("Price");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                $id = $this->model->insert($strBranchID, $strProductId, $strNoSeri, $strStock, $strPrice);
                if ($id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                $StockId = $this->getRequest()->getParam("ID");

                $this->model->update(
                                        $StockId,
                                        $strBranchID,
                		                $strProductId,
                                        $strNoSeri,
                                        $strStock,
                                        $strPrice);
                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                break;

            case "Delete":
            	$strStockId = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strStockId);
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}

