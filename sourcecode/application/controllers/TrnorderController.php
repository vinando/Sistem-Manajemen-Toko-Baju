<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
class TrnorderController extends Zend_Controller_Action {
    private $model;
    public function init() {
        $this->model = new Application_Model_Trnorder();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Order";
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
                $this->view->assign("NumRows", "0");
                $this->view->assign("Items", array());
                break;

            case "edit":
                $order_id = $this->getRequest()->getParam("id");
                $this->view->assign("strFormType", "Edit");
                $this->view->assign("strID", $order_id);

                $order = $this->model->getSingle($order_id);

                // Set record data to form
                $this->view->assign("BranchID", $order['branchid']);
                $this->view->assign("OrderNo", $order['orderno']);
                $this->view->assign("OrderDate", $order['orderdate']);
                $this->view->assign("CustomerId", $order['customerid']);
                $this->view->assign("CustomerName", $order['customername']);
                $this->view->assign("Phone", $order['phone']);
                $this->view->assign("Total", $order['total']);
                $this->view->assign("Dp", $order['dp']);

                $order_details = $this->model->getOrderDetails($order_id);
                $this->view->assign("NumRows", count($order_details));
                $this->view->assign("Items", $order_details);
                break;
        }
        $this->view->assign("Products", $this->model->getFinishProducts());
        $this->view->assign("Sizes", $this->model->getSizes());
        $this->view->assign("Colors", $this->model->getColors());
        $this->view->assign("Materials", $this->model->getMaterials());
    }

    public function saveAction() {
        $jsonstring = $this->getRequest()->getParam('jsonstring');
        $data = json_decode($jsonstring, 'array');
        $order_id = $data['ID'];
        $branch_id = $data['BranchID'];
        $order_no = '';
        $order_date = date('Y-m-d h:i:s');
        $customer_id = $data['CustomerId'];
        $customer_name = $data['CustomerName'];
        $phone = $data['Phone'];
        $total = $data['Total'];
        $dp = $data['Dp'];

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                //echo "Add succes atuh";

                $order_id = $this->model->insertHeader($branch_id, $order_no, $order_date, $customer_id, $customer_name, $phone, $total, $dp);
                $order_no = 'O-' . $branch_id . '-' . $order_id;
                //echo $order_no;
                $this->model->updateOrderNo($order_id, $order_no);
                if ($order_id && count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $product = $item['product'];
                        $arr = explode('|', $product);
                        $product_id = $arr[0];
                        $this->model->insertDetail($order_id, $product_id, $item['size'], $item['color'], $item['material'], $item['price'], $item['qty'], $item['total']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $order_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $this->model->updateStatusOrder($order_id, 'Ordered');
                break;

            case "Edit":
                $this->model->updateHeader($order_id, $customer_id, $customer_name, $phone, $total, $dp);
                $this->model->deleteDetail($order_id);
                if (count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $product = $item['product'];
                        $arr = explode('|', $product);
                        $product_id = $arr[0];
                        $this->model->insertDetail($order_id, $product_id, $item['size'], $item['color'], $item['material'], $item['price'], $item['qty'], $item['total']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $order_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                $this->model->updateStatusOrder($order_id, 'Ordered');
                break;

            case "Delete":
                $strOrderID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strOrderID);
                break;
        }
        exit;
    }

    public function cetaknotaAction() {
        $order_id = $this->getRequest()->getParam("orderid");
        $order = $this->model->getSingle($order_id);

        // Set record data to form
        $this->view->assign("BranchID", $order['branchid']);
        $this->view->assign("OrderNo", $order['orderno']);
        $this->view->assign("OrderDate", $order['orderdate']);
        $this->view->assign("CustomerId", $order['customerid']);
        $this->view->assign("CustomerName", $order['customername']);
        $this->view->assign("Phone", $order['phone']);
        $this->view->assign("Total", $order['total']);
        $this->view->assign("Dp", $order['dp']);

        $order_details = $this->model->getOrderDetails($order_id);
        $this->view->assign("Items", $order_details);
    }
    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }

}
