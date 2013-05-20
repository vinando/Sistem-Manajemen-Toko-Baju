<?php
class IndexController extends Zend_Controller_Action
{

    public function init() {
        /* Initialize action controller here */
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        } else {
            $this->_helper->redirector('home', '');
        }
    }

    public function indexAction() {
    }
}
