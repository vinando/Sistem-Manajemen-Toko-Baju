<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
class trnfinishorderController extends Zend_Controller_Action {
    private $model;
    public function init() {
        $this->model = new Application_Model_Trnfinishorder();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Finishorder";
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
                $this->view->assign("FinishDate", $this->convertDate($arrData['finishdate']));
                $this->view->assign("FinishNo", $arrData['finishno']);
                $this->view->assign("BranchID", $arrData['branchid']);
                $this->view->assign("OrderId", $arrData['orderid']);
                $this->view->assign("OrderNo", $arrData['orderno']);
                $this->view->assign("EmployeeId", $arrData['employeeid']);
                $this->view->assign("EmployeeName", $arrData['employeename']);
                $this->view->assign("Price", $arrData['price']);
                break;
        }
    }

    public function saveAction() {
        $order_model = New Application_Model_Trnorder();
        $strBranchID       = $this->getRequest()->getParam("BranchID");
        $strFinishDate = $this->getRequest()->getParam("FinishDate");
        $strFinishNo = '';
        $strOrderId        = $this->getRequest()->getParam("OrderId");
        $strOrderNo        = $this->getRequest()->getParam("OrderNo");
        $strEmployeeId     = $this->getRequest()->getParam("EmployeeId");
        $strEmployeeName   = $this->getRequest()->getParam("EmployeeName");;
        $strPrice   = $this->getRequest()->getParam("Price");

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                $strFinishDate = date('Y-m-d h:i:s');
                $finish_id = $this->model->insert($strBranchID, $strFinishDate, $strFinishNo, $strOrderId, $strOrderNo, $strEmployeeId, $strEmployeeName, $strPrice);
                $finish_no = 'FO-' . $strBranchID . '-' . $finish_id;
                //echo $order_no;
                $this->model->updateFinishNo($finish_id, $finish_no);
                if ($finish_id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $finish_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $order_model->updateStatusOrder($strOrderId, 'Finished');
                break;

            case "Edit":
                $strFinishId = $this->getRequest()->getParam("ID");
                $this->model->update($strFinishId, $strEmployeeId, $strEmployeeName, $strPrice);
                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                $order_model->updateStatusOrder($strOrderId, 'Finished');
                break;

            case "Delete":
                $strFinishID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strFinishID);
                $order_model->updateStatusOrder($strOrderId, 'Assigned');
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }

}
