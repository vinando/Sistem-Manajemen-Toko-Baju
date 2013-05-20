<?php
class ExportdataController extends Zend_Controller_Action
{
	private $branch_model;
	private $export_model;
	private $category_model;
	
    public function init()
    {
        /* Initialize action controller here */
        $this->branch_model   = new Application_Model_Branch();
		$this->export_model   = new Application_Model_Export();
		$this->category_model = new Application_Model_Ticketcategory();
		
        $this->view->AccordionID  = "Maintenance";
        $this->view->SelectedMenu = "ExportData";
    }
    
	public function preDispatch() {
    	if (!Zend_Auth::getInstance()->hasIdentity()) {
    	    $this->_helper->redirector('login', '');
        }
    }
    
    public function indexAction() {
        // action body
        if($_SESSION['BranchID'] == '') $this->view->branchs = $this->branch_model->getAll();
        $this->view->branchs = $this->branch_model->getSingleBranch($_SESSION['BranchID']);
    }
    
    public function doexportAction() {
        // action body
        $this->_helper->layout->disableLayout();
    	//$this->_helper->viewRenderer->setNoRender();
    	
        $branchids = $this->getRequest()->getParam('branchids');
        $branchids = explode(',', $branchids);
        $startdate = $this->getRequest()->getParam('startdate');
        $enddate   = $this->getRequest()->getParam('enddate');
        
        $startdate = $this->reverseDate($startdate); 
        $enddate   = $this->reverseDate($enddate); 
        
        // Looping to get trn data foreach Branch
    	foreach($branchids as $idx => $branchid){
    		$branch  = $this->branch_model->getSingle($branchid);
    		$datatrn = $this->export_model->getDataPeriod($branchid, $startdate, $enddate);
    		
    		// Looping to add attribute CategoryName to $datatrn
    		foreach($datatrn as $idxdata => $data_trn){
    			$TicketCategory = $this->category_model->getSingle($data_trn['TicketCategoryID']);
    			$datatrn[$idxdata]['TicketCategoryName'] = $TicketCategory['TicketCategoryName'];
    		} 
    		
    		// Add new Attribute and assign value into array $data
    		$data[$idx]['BranchID']   = $branchid;
    		$data[$idx]['BranchName'] = $branch['BranchName'];
    		$data[$idx]['Data']       = $datatrn;
    	}
    	
    	// Assign array $data to view's variable <$data>
    	$this->view->data = $data;	
    		      
    }
    
    function reverseDate($date){
    	$arr = explode('-',$date);
    	return $arr[2].'-'.$arr[1].'-'.$arr[0];
    }
}

