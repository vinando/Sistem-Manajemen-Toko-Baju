<?php

class LoginController extends Zend_Controller_Action {
    private $model;

    public function init() {
        $this->model = new Application_Model_User();
        $helper = $this->_helper->getHelper('Layout');
        $layout = $helper->getLayoutInstance();
        $layout->setLayout("layout_login");
    }
    public function submitAction() {
        if (!$this->getRequest()->getParam("Username") or !$this->getRequest()->getParam("Password")) {
            echo "<script>parent.loginFailed('Please input UserID and Password first!')</script>";
            exit;
        }

        if (preg_match("/[^\d\w\\-_]+/", $this->getRequest()->getParam("Username")) or preg_match("/[^\d\w]+/", $this->getRequest()->getParam("Password"))) {
            echo "<script>parent.loginFailed('Invalid charactes!')</script>";
            exit;
        }

        $arrUser = $this->model->getSingleByUserIDPassword($this->getRequest()->getParam("Username"), $this->getRequest()->getParam("Password"));

        //print_r($arrUser);exit;

        if ($arrUser["username"] == $this->getRequest()->getParam("Username") and $arrUser['password'] == md5($this->getRequest()->getParam("Password"))) {
            if ( $this->authenticate($this->getRequest()) ) {
                //echo "masuk";exit;
                // Save user credential to session
                $_SESSION['Username'] = $this->getRequest()->getParam("Username");
                $_SESSION['FullName'] = $arrUser['fullname'];
                $_SESSION['BranchID'] = $arrUser['branchid'];
                $_SESSION['RoleID'] = $arrUser['roleid'];
                $branch_model = new Application_Model_Branch();
                $branch = $branch_model->getSingle($arrUser['branchid']);
                $_SESSION['BranchName'] = $branch['name'];
                // Save Controllers Access Rights
                $arrRightsTmp = $this->model->getAllRightsByRoleID( $arrUser["roleid"] );
                $arrRights = array("rights");
                if (count($arrRightsTmp) > 0) {
                    foreach ($arrRightsTmp as $arrRightTmp) {
                        array_push($arrRights, $arrRightTmp["menu"]);
                    }
                }
                $_SESSION['Rights'] = $arrRights;
                $_SESSION['LoginTime'] = date("M, d Y H:i:s");

                echo "<script type='text/javascript'>parent.location='/home';</script>";
            } else {
                echo "<script type='text/javascript'>parent.loginFailed('Account not found!')</script>";
            }
        } else {
            echo "<script type='text/javascript'>parent.loginFailed('Account not found!')</script>";
            exit;
        }
        exit;
    }

    public function authenticate($request) {
        Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');

        $dbAdapter   = Zend_Registry::get('DB');
        $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
        $auth        = Zend_Auth::getInstance();

        $authAdapter->setTableName('mst_user');
        $authAdapter->setIdentityColumn('username');
        $authAdapter->setCredentialColumn('password');

        $authAdapter->setIdentity($request->getParam("Username"));
        $authAdapter->setCredential($request->getParam("Password"));
        $authAdapter->setCredentialTreatment('MD5(?)');
        $result = $auth->authenticate($authAdapter);

        if ($result->isValid()) {
            $data = $authAdapter->getResultRowObject(null,'password');
            $auth->getStorage()->write($data);
            return true;
        } else {
            return false;
        }
    }

    public function preDispatch() {
    	if (Zend_Auth::getInstance()->hasIdentity()) {
            // If the user is logged in, we don't want to show the login form;
            // however, the logout action should still be available
            if ('logout' != $this->getRequest()->getActionName()) {
                $this->_helper->redirector('home', '');
            }
        }
    }

    public function indexAction() {
        $model = new Application_Model_Branch();
        $this->view->assign("Branches", $model->getAll());
    }

    public function logoutAction() {
        // Check if user still has checkin session and do chekout if possible

        // Remove session and authentication
        Zend_Auth::getInstance()->clearIdentity();

        // back to login page
        $this->_helper->redirector('login', '');
    }
}





