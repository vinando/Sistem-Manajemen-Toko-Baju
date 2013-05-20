<?php

class Application_Form_User extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $userid = new Zend_Form_Element_Text('userid');
        $userid->setLabel('User ID')
        	   ->addFilter('StripTags')	
        	   ->setRequired(true);
        
        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('Password')
        	     ->addFilter('StripTags')	
        	     ->setRequired(true);
        $fullname = new Zend_Form_Element_Text('fullname');
        $fullname->setLabel('Fullname')
        	     ->addFilter('StripTags')	
        	     ->setRequired(true);
        $email = new Zend_Form_Element_Text('email');
        $email->setAttrib('class', 'padding-top');
        //$email_validator = new Zend_Validate_EmailAddress();
        $email->setLabel('Email')
        	  ->addValidator('EmailAddress')
        	  ->setRequired(true);
        $branch = new Zend_Form_Element_Select('branch');
        $optList = array(
        	'01' => 'ACC Fatmawati',
        	'02' => 'Simatupang'
        );
        $branch->addMultiOptions($optList)
        	   ->setLabel('Branch')	;
	    $mode = new Zend_Form_Element_Hidden('mode');
	    $mode->removeDecorator('label');
	    
	    $id = new Zend_Form_Element_Hidden('id');
	    $id->removeDecorator('label');
	    
	   	$id->setAttrib('class','hidden-element');
	    $btnSubmit = new Zend_Form_Element_Submit('btnSubmit');
	   	$btnSubmit->setLabel('Save')
	   			  ->setIgnore(true);
        $this->addElements(array($userid, $password, $fullname, $email, $branch, $btnSubmit, $mode, $id));
		
    }


}

