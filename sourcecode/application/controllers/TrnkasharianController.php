<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
class TrnkasharianController extends Zend_Controller_Action {
    private $model;
    public function init() {
        $this->model = new Application_Model_Trnkasharian();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Dailycash";
    }

    public function preDispatch() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
        //echo count($this->model->getLastBalance());exit;
        //echo $this->model->getLastBalance()[0];exit;
        //print_r($this->model->getLastBalance());exit;
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
                $this->view->assign("NumRows", "0");
                $this->view->assign("Items", array());
                break;

            case "edit":
                $trn_id = $this->getRequest()->getParam("id");
                $this->view->assign("strFormType", "Edit");
                $this->view->assign("strID", $trn_id);

                $trn = $this->model->getSingle($trn_id);

                // Set record data to form
                $this->view->assign("BranchID", $trn['branchid']);
                $this->view->assign("TrnNo", $trn['trnno']);
                $this->view->assign("TrnDate", $trn['trndate']);
                $this->view->assign("Description", $trn['description']);
                $this->view->assign("Debit", $trn['debit']);
                $this->view->assign("Credit", $trn['credit']);
                break;
        }
    }

    public function saveAction() {
        $trn_id = $this->getRequest()->getParam('ID');
        $branch_id = $this->getRequest()->getParam('BranchID');
        $trn_no = '';
        $trn_date = date('Y-m-d h:i:s');
        $description = $this->getRequest()->getParam('Description');
        $debit = $this->getRequest()->getParam('Debit');
        $credit = $this->getRequest()->getParam('Credit');

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                //echo "Add succes atuh";

                $trn_id = $this->model->insert($branch_id, $trn_no, $trn_date, $description, $debit, $credit);
                $trn_no = 'DC-' . $branch_id . '-' . $trn_id;
                //echo $order_no;
                $this->model->updateTrnNo($trn_id, $trn_no);
                if ($trn_id) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $trn_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                $this->model->update($trn_id, $description, $debit, $credit);
                if (true) {
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $trn_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Delete":
                $strTrnId = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strTrnId);
                break;
        }
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }

}
