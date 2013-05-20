<?php

class RoleController extends Zend_Controller_Action
{
    private $model;
    public function init() {
        /* Initialize action controller here */
        $this->model = new Application_Model_Role();
        $this->view->AccordionID  = "Master";
        $this->view->SelectedMenu = "Role";
    }

	public function preDispatch(){
    	$this->view->assign('logouturl', '<a href="login/logout">Logout</a>');
		if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->redirector('index', 'login');
        }
	}

    public function indexAction() {
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
                $this->view->assign("Rights", array("rights"));
                break;

            case "edit":
                $this->view->assign("strFormType", "Edit");
                $this->view->assign("strID", $this->getRequest()->getParam("id"));

                $arrData = $this->model->getSingle($this->getRequest()->getParam("id"));
                $arrRightsTmp = $this->model->getAllRightsByRoleID($this->getRequest()->getParam("id"));
                $arrRights = array("rights");
                if (count($arrRightsTmp) > 0) {
                    foreach ($arrRightsTmp as $arrRightTmp) {
                        array_push($arrRights, $arrRightTmp["menu"]);
                    }
                }
                // Set record data to form
                $this->view->assign("RoleName", $arrData['name']);
                $this->view->assign("Description", $arrData['description']);
                $this->view->assign("Rights", $arrRights);
                break;
        }
    }

    public function saveAction() {

        switch ($this->getRequest()->getParam("type")) {
            case "Add":
                // Check empty fields
                if (!$this->getRequest()->getParam("RoleName")) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please input all required field first!')</script>";
                    exit;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $this->getRequest()->getParam("RoleName"))) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please use only alphanumeric on Role Name!')</script>";
                    break;
                }

                //insert details info
                $id = $this->model->insert(addslashes($this->getRequest()->getParam("RoleName")),
                    addslashes($this->getRequest()->getParam("Description")));

                // Insert rights
                $this->model->deleteRightsByRoleID($id);
                if (count($this->getRequest()->getParam("Rights")) > 0) {
                    $arrRights = $this->getRequest()->getParam("Rights");
                    foreach ($arrRights as $i => $strMenu) {
                        $this->model->insertRights($id, $strMenu);
                    }
                }

                if ($id) {
                    echo "<script>window.parent.saveSuccess('ID', $id);</script>";
                } else {
                    echo "<script>window.parent.saveFailed('Save failed')</script>";
                }
                break;

            case "Edit":
                // Check empty fields
                if (!$this->getRequest()->getParam("RoleName")) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please input all required field first!')</script>";
                    exit;
                }

                // Check Invalid characters
                if (preg_match("/[^\s\d\w]+/", $this->getRequest()->getParam("RoleName"))) {
                    echo "<script type='text/javascript'>window.parent.saveFailed('Please use only alphanumeric on Role Name!')</script>";
                    break;
                }

                $role_id = $this->getRequest()->getParam("ID");

                //update details info
                $this->model->update(addslashes($role_id),
                    addslashes($this->getRequest()->getParam("RoleName")),
                    addslashes($this->getRequest()->getParam("Description")));

                //update rights
                $this->model->deleteRightsByRoleID($role_id);
                if (count($this->getRequest()->getParam("Rights")) > 0) {
                    $arrRights = $this->getRequest()->getParam("Rights");
                    foreach ($arrRights as $i => $strMenu) {
                        $this->model->insertRights($role_id, $strMenu);
                    }
                }

                echo "<script type='text/javascript'>window.parent.editSuccess()</script>";
                break;

            case "Delete":
                $strRoleID = addslashes($this->getRequest()->getParam("ID"));
                $this->model->delete($strRoleID);
                break;
        }
        exit;
    }
}

