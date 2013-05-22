<?php

class UserController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_User();
        
        $this->view->AccordionID  = "Master";
        $this->view->SelectedMenu = "User";
    }

    public function preDispatch() {
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        }
    }

    public function indexAction(){
    	//$dataTable = $this->model->getDataTable();
    	//echo json_encode($dataTable);
    }

    public function dataAction() {
        echo json_encode($this->model->getDataTable());
    	exit;
    }

    public function formAction(){
        switch ($this->getRequest()->getParam("type")) {
            case "add";
                $this->view->assign("strFormType", "Add");
                $this->view->assign("strID", "");
                break;

            case "edit":
                $this->view->assign("strFormType", "Edit");
                $this->view->assign("strID", $this->getRequest()->getParam("id"));

                $arrData = $this->model->getSingle($this->getRequest()->getParam("id"));

                // Set record data to form
                $this->view->assign("Username", $arrData['username']);
                $this->view->assign("Fullname", $arrData['fullname']);
                $this->view->assign("Email", $arrData['email']);
                $this->view->assign("RoleID", $arrData['roleid']);
                $this->view->assign("BranchID", $arrData['branchid']);
                break;
        }

        $model = new Application_Model_Branch();
        $modelRoles = new Application_Model_Role();
        $this->view->assign("Branches", $model->getAll());
        $this->view->assign("Roles", $modelRoles->getAll());

    }

    public function saveAction() {
        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                // Check empty fields
                if (!$this->getRequest()->getParam("UserName") OR
                        !$this->getRequest()->getParam("FullName") OR
                        !$this->getRequest()->getParam("Email") OR
                        !$this->getRequest()->getParam("Password") OR
                        !$this->getRequest()->getParam("ConfirmPassword")
                ) {
                    echo "<script>window.parent.saveFailed('Please fill all required fields!')</script>";
                    break;
                }

                // Check Invalid characters
                if (preg_match("/[^\d\w\\-_]+/", $this->getRequest()->getParam("UserName"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on User Name!')</script>";
                    break;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $this->getRequest()->getParam("FullName"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Full Name!')</script>";
                    break;
                }

                // Check Invalid characters
                if (preg_match("/[^\d\w\\-_@.]+/", $this->getRequest()->getParam("Email"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Email!')</script>";
                    break;
                }

                // Check Invalid characters
                if (preg_match("/[^\d\w]+/", $this->getRequest()->getParam("Password"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Password!')</script>";
                    break;
                }

                // Check Password and Confirm Password
                if ($this->getRequest()->getParam("Password") != $this->getRequest()->getParam("ConfirmPassword")) {
                    echo "<script>window.parent.saveFailed('Password and Confirm password do not match!')</script>";
                    break;
                }

                // Check duplicate user id
                $arrUser = $this->model->getSingle($this->getRequest()->getParam("UserName"));
                if ($arrUser["username"] == $this->getRequest()->getParam("UserName")) {
                    echo "<script>window.parent.saveFailed('UserName already exist!')</script>";
                    break;
                }

                // Insert details
                $this->model->insert(addslashes($this->getRequest()->getParam("UserName")),
                        addslashes($this->getRequest()->getParam("Password")),
                        addslashes($this->getRequest()->getParam("FullName")),
                        addslashes($this->getRequest()->getParam("Email")),
                        addslashes($this->getRequest()->getParam("BranchID"))
                );

                echo "<script>window.parent.saveSuccess('ID', '" . $this->getRequest()->getParam("ID") . "');window.parent.disableField('ID')</script>";
                break;

            case "Edit":
                // Check empty fields
                if (!$this->getRequest()->getParam("UserName") OR
                        !$this->getRequest()->getParam("FullName") OR
                        !$this->getRequest()->getParam("Email")
                ) {
                    echo "<script>window.parent.saveFailed('Please fill all required fields!')</script>";
                    break;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $this->getRequest()->getParam("FullName"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Full Name!')</script>";
                    break;
                }

                // Check Invalid characters
                if (preg_match("/[^\d\w@\\-_.]+/", $this->getRequest()->getParam("Email"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Email!')</script>";
                    break;
                }

                // Check Invalid characters
                if ($this->getRequest()->getParam("Password") and preg_match("/[^\d\w]+/", $this->getRequest()->getParam("Password"))) {
                    echo "<script>window.parent.saveFailed('Please use only alphanumeric on Password!')</script>";
                    break;
                }

                // Check Password and Confirm Password
                if ($this->getRequest()->getParam("Password")) {
                    if ($this->getRequest()->getParam("Password") != $this->getRequest()->getParam("ConfirmPassword")) {
                        echo "<script>window.parent.saveFailed('Password and Confirm password do not match!')</script>";
                        break;
                    }
                }

                // Update details
                $this->model->update(addslashes($this->getRequest()->getParam("UserID")),
                        addslashes($this->getRequest()->getParam("Password")),
                        addslashes($this->getRequest()->getParam("FullName")),
                        addslashes($this->getRequest()->getParam("Email")),
                        addslashes($this->getRequest()->getParam("BranchID"))
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

