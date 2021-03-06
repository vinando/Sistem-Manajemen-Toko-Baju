<?php
/**
 * Created by JetBrains PhpStorm.
 * User: weirdo
 * Date: 4/6/13
 * Time: 4:19 PM
 * To change this template use File | Settings | File Templates.
 */
class TrnpurchasesorderController extends Zend_Controller_Action {
    private $model;
    public function init() {
        $this->model = new Application_Model_Trnpurchasesorder();
        $this->view->AccordionID  = "Transaction";
        $this->view->SelectedMenu = "Purchasesorder";
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
                $this->view->assign("SupplierId", $order['supplierid']);
                $this->view->assign("SupplierName", $order['suppliername']);
                $this->view->assign("Phone", $order['phone']);

                $order_details = $this->model->getOrderDetails($order_id);
                $this->view->assign("NumRows", count($order_details));
                $this->view->assign("Items", $order_details);
                break;
        }
        $this->view->assign("Products", $this->model->getProducts());
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
        $supplier_id = $data['SupplierId'];
        $supplier_name = $data['SupplierName'];
        $phone = $data['Phone'];

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                //echo "Add succes atuh";

                $order_id = $this->model->insertHeader($branch_id, $order_no, $order_date, $supplier_id, $supplier_name, $phone);
                $order_no = 'PO-' . $branch_id . '-' . $order_id;
                //echo $order_no;
                $this->model->updateOrderNo($order_id, $order_no);
                if ($order_id && count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $product = $item['product'];
                        $arr = explode('|', $product);
                        $product_id = $arr[0];
                        $this->model->insertDetail($order_id, $product_id, $item['noseri'], $item['size'], $item['color'], $item['material'], $item['price'], $item['qty'], $item['total']);
                        $this->model->insertStock($_SESSION['BranchID'], $product_id, $item['noseri'], $item['qty']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $order_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                $this->model->updateHeader($order_id, $supplier_id, $supplier_name, $phone);
                $oldItems = $this->model->getOrderDetails($order_id);
                foreach($oldItems as $oldItems) {
                    $this->model->deleteStock($_SESSION['BranchID'], $oldItems['productid'], $oldItems['noseri']);
                }
                $this->model->deleteDetail($order_id);
                if (count($data['items']) > 0) {
                    foreach($data['items'] as $idx => $item) {
                        $product = $item['product'];
                        $arr = explode('|', $product);
                        $product_id = $arr[0];
                        $this->model->insertDetail($order_id, $product_id, $item['noseri'], $item['size'], $item['color'], $item['material'], $item['price'], $item['qty'], $item['total']);
                        $this->model->insertStock($_SESSION['BranchID'], $product_id, $item['noseri'], $item['qty']);
                    }
                    echo "<script type='text/javascript'>window.parent.saveSuccess('ID', $order_id);</script>";
                } else {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Delete":
                $strOrderID = addslashes($this->getRequest()->getParam("ID"));
                $oldItems = $this->model->getOrderDetails($strOrderID);
                foreach($oldItems as $oldItems) {
                    $this->model->deleteStock($_SESSION['BranchID'], $oldItems['productid'], $oldItems['noseri']);
                }
                $this->model->delete($strOrderID);
                break;
        }
        exit;
    }

    function jsongetproductbybarcodeAction(){
        $barcode = $this->getRequest()->getParam('barcode');
        $arr = explode('/', $barcode);
        $product_code = $arr[0];
        $no_seri = $arr[1];
        $product_model = new Application_Model_Product();
        $product = $product_model->getSingleByCode($product_code);
        $data = array(
            'product' => $product,
            'noseri' => $no_seri
        );
        echo json_encode($data);
        exit;
    }

    public function convertDate($date){
        $date = explode('-', $date);
        return $date[2].'-'.$date[1].'-'.$date[0];
    }
}