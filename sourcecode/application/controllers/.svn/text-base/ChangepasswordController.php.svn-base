<?php

class ChangepasswordController extends Zend_Controller_Action {
    private $model;

    public function init() {
    }

    public function preDispatch() {
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        }
        $this->model = new Application_Model_User();
        $this->view->SelectedMenu = "ChangePassword";
    }

    public function indexAction(){
    }

    public function saveAction() {
        // Check empty fields
        if (!$this->getRequest()->getParam("OldPassword") OR
                !$this->getRequest()->getParam("NewPassword") OR
                !$this->getRequest()->getParam("ConfirmNewPassword")
        ) {
            echo "<script>window.parent.saveFailed('Please fill all required fields!')</script>";
            break;
        }

        // Check invalid old password
        $arrUser = $this->model->getSingle($_SESSION['UserID']);
        if (md5($this->getRequest()->getParam("OldPassword")) != $arrUser['Password']) {
            echo "<script>window.parent.saveFailed('Old Password is invalid!')</script>";
            exit;
        }

        // Check Password and Confirm Password
        if ($this->getRequest()->getParam("NewPassword") != $this->getRequest()->getParam("ConfirmNewPassword")) {
            echo "<script>window.parent.saveFailed('Password and Confirm password do not match!')</script>";
            exit;
        }

        $this->model->updatePassword($_SESSION['UserID'], $this->getRequest()->getParam("NewPassword"));

        echo "<script>window.parent.editSuccess()</script>";
        exit;
    }
}

