<?php
/**
 * backend class for Zend_Json_Server
 *
 * This class handles all Json requests for the Crm application
 *
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html
 * @author      Thomas Wadewitz <t.wadewitz@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id: Json.php 199 2007-10-15 16:30:00Z twadewitz $
 *
 */
class Crm_Json extends Egwbase_Application_Json_Abstract
{
    /**
     * the internal name of the application
     *
     * @var string
     */
    protected $_appname = 'Crm';

    
    /**
     * get lead sources
     *
     * @param string $sort
     * @param string $dir
     * @return array
     */
    public function getLeadsources($sort, $dir)
    {     
        $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
        
        if($rows = Crm_Controller::getInstance()->getLeadsources($sort, $dir)) {
            $result['results']      = $rows->toArray();
            $result['totalcount']   = count($result['results']);
        }

        return $result;    
    } 

    /**
	 * save leadsources
	 *
	 * if $_Id is -1 the options element gets added, otherwise it gets updated
	 * this function handles insert and updates as well as deleting vanished items
	 *
	 * @return array
	 */	
	public function saveLeadsources($optionsData)
    {
        $leadSources = Zend_Json::decode($optionsData);
         
        try {
            $leadSources = new Egwbase_Record_RecordSet($leadSources, 'Crm_Model_Leadsource');
        } catch (Exception $e) {
            // invalid data in some fields sent from client
            $result = array('success'           => false,
                            'errorMessage'      => 'filter NOT ok'
            );
            
            return $result;
        }
            
        
        if(Crm_Controller::getInstance()->saveLeadsources($leadSources) === FALSE) {
            $result = array('success'   => FALSE);
        } else {
            $result = array('success'   => TRUE);
        }
        
        return $result;        
    }    


    /**
     * get lead types
     *
     * @param string $sort
     * @param string $dir
     * @return array
     */
   public function getLeadtypes($sort, $dir)
    {
         $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
        
        if($rows = Crm_Controller::getInstance()->getLeadtypes($sort, $dir)) {
            $result['results']      = $rows->toArray();
            $result['totalcount']   = count($result['results']);
        }

        return $result;    
    }  

    /**
	 * save leadtypes
	 *
	 * if $_Id is -1 the options element gets added, otherwise it gets updated
	 * this function handles insert and updates as well as deleting vanished items
	 *
	 * @return array
	 */	
	public function saveLeadtypes($optionsData)
    {
        $leadTypes = Zend_Json::decode($optionsData);
         
        try {
            $leadTypes = new Egwbase_Record_RecordSet($leadTypes, 'Crm_Model_Leadtype');
        } catch (Exception $e) {
            // invalid data in some fields sent from client
            $result = array('success'           => false,
                            'errorMessage'      => 'filter NOT ok'
            );
            
            return $result;
        }
            
        if(Crm_Controller::getInstance()->saveLeadtypes($leadTypes) === FALSE) {
            $result = array('success'   => FALSE);
        } else {
            $result = array('success'   => TRUE);
        }
        
        return $result;     
    }
    
    
    /**
     * get lead states
     *
     * @param string $sort
     * @param string $dir
     * @return array
     */   
   public function getLeadstates($sort, $dir)
    {
         $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
        
        if($rows = Crm_Controller::getInstance()->getLeadstates($sort, $dir)) {
            $result['results']      = $rows->toArray();
            $result['totalcount']   = count($result['results']);
        }

        return $result;   
    }  

    /**
	 * save leadstates
	 *
	 * if $_Id is -1 the options element gets added, otherwise it gets updated
	 * this function handles insert and updates as well as deleting vanished items
	 *
	 * @return array
	 */	
	public function saveLeadstates($optionsData)
    {
        $leadStates = Zend_Json::decode($optionsData);
         
        try {
            $leadStates = new Egwbase_Record_RecordSet($leadStates, 'Crm_Model_Leadstate');
        } catch (Exception $e) {
            // invalid data in some fields sent from client
            $result = array('success'           => false,
                            'errorMessage'      => 'filter NOT ok'
            );
            
            return $result;
        }
            
        
        if(Crm_Controller::getInstance()->saveLeadstates($leadStates) === FALSE) {
            $result = array('success'   => FALSE);
        } else {
            $result = array('success'   => TRUE);
        }
        
        return $result;       
    }    
    
 
    /**
     * get product source
     *
     * @param string $sort
     * @param string $dir
     * @return array
     */
	public function getProductsource($sort, $dir)
	{
         $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
        
        if($rows = Crm_Controller::getInstance()->getProductsAvailable($sort, $dir)) {
            $result['results']      = $rows->toArray();
            $result['totalcount']   = count($result['results']);
        }

        return $result;  
	}    
  
    /**
	 * save productsources
	 *
	 * if $_Id is -1 the options element gets added, otherwise it gets updated
	 * this function handles insert and updates as well as deleting vanished items
	 *
	 * @return array
	 */	
	public function saveProductsource($optionsData)
    {
        $productSource = Zend_Json::decode($optionsData);
         
        try {
            $productSource = new Egwbase_Record_RecordSet($productSource, 'Crm_Model_Productsource');
        } catch (Exception $e) {
            // invalid data in some fields sent from client
            $result = array('success'           => false,
                            'errorMessage'      => 'filter NOT ok'
            );
            
            return $result;
        }
            
        
        if(Crm_Controller::getInstance()->saveProductSource($productSource) === FALSE) {
            $result = array('success'   => FALSE);
        } else {
            $result = array('success'   => TRUE);
        }
        
        return $result;       
    }     
    

// handle PRODUCTS
   public function getProductsById($_id)
    {
        $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );

        if(empty($filter)) {
            $filter = NULL;
        }

        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        if($rows = $backend->getProductsById($_id)) {
            $result['results']    = $rows->toArray();
            //$result['totalcount'] = $backend->getCountByOwner($owner);
        }

        return $result;
    } 
    
    /**
	 * save products
	 *
	 * 
	 * 
	 *
	 * @return array
	 */
   public function saveProducts($products, $lead_id) {	
   
        $_products = Zend_Json::decode($products);
        $_productsData = array();

       	if(is_array($_products)) {
    		foreach($_products AS $_product) {
    			if($_product['lead_id'] == "NULL") {
    				unset($_product['lead_id']);
    			}
                if($_product['lead_lead_id'] == "-1" || empty($_product['lead_lead_id'])) {
    				$_product['lead_lead_id'] = $lead_id;
    
    			}			
                
                $_productsData[] = $_product;
    	    }
           
            try {
                $_productsData = new Egwbase_Record_RecordSet($_productsData, 'Crm_Model_Product');
            } catch (Exception $e) {
                // invalid data in some fields sent from client
                $result = array('success'           => false,
                                'errorMessage'      => 'products filter NOT ok'
                );
                
                return $result;
            } 
        }
            
        if(Crm_Controller::getInstance()->saveProducts($_productsData) === FALSE) {
            $result = array('success'   => FALSE);
        } else {
            $result = array('success'   => TRUE);
        }
       
        return $result;  

   }



     /**
	 * save one lead
	 *
	 * if $_leadId is NULL the lead gets added, otherwise it gets updated
	 *
	 * @return array
	 */	
	public function saveLead($linkedCustomer, $linkedPartner, $linkedAccount)
    {
        //if(empty($_POST['lead_id'])) {
        //    unset($_POST['lead_id']);
        //}

        // lead modifier
        //$_POST['lead_modifier'] = Zend_Registry::get('currentAccount')->account_id;

        $leadData = new Crm_Model_Lead();
        try {
            $leadData->setFromUserData($_POST);
        } catch (Exception $e) {
            // invalid data in some fields sent from client
            $result = array(
                'success'       => false,
                'errors'        => $leadData->getValidationErrors(),
                'errorMessage'  => 'invalid data for some fields'
            );
            
            return $result;
        }
            
        //error_log(print_r($leadData->toArray(), true));
        
        $savedLead = Crm_Controller::getInstance()->saveLead($leadData);
        
        // set linked contacts
        $linkedCustomer = Zend_Json::decode($linkedCustomer);
        Crm_Controller::getInstance()->setLinkedCustomer($savedLead->lead_id, $linkedCustomer);

        $linkedPartner = Zend_Json::decode($linkedPartner);
        Crm_Controller::getInstance()->setLinkedPartner($savedLead->lead_id, $linkedPartner);

        $linkedAccount = Zend_Json::decode($linkedAccount);
        Crm_Controller::getInstance()->setLinkedAccount($savedLead->lead_id, $linkedAccount);

        // products
		if(strlen($_POST['products']) > 2) {	    
            $this->saveProducts($_POST['products'], $savedLead->lead_id);
		} else {
            Crm_Controller::getInstance()->deleteProducts($savedLead->lead_id);    
        }         

        $result = array(
            'success'   => TRUE
        );
            
        return $result;  
    }      

     /**
     * save an array of contacts (belonging to one lead)
     *
     * @param array $_contacts  contacts data
     * @param int $_id  id of the lead
     * @return array
     */
    public function saveContacts($_contacts, $_id)
    {  
        $contacts = Zend_Json::decode($_contacts);

       	if(is_array($contacts)) {            
            for($i = 0; $i < count($contacts); $i++) {
                $contacts[$i]['link_id1'] = $_id;
            }
        }    
    
        
        if(Crm_Controller::getInstance()->saveContacts($contacts, $_id) === FALSE) {
            $result = array('success'   => FALSE);
        } else {
            $result = array('success'   => TRUE);
        }
        
        return $result;  
    }



     /**
     * delete a array of leads
     *
     * @param array $_leadIDs
     * @return array
     */
    public function deleteLeads($_leadIds)
    {
        $leadIds = Zend_Json::decode($_leadIds);

        if(is_array($leadIds)) {
            $leads = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
            foreach($leadIds as $leadId) {
                $leads->deleteLeadById($leadId);
            }

            $result = array('success'   => TRUE, 'ids' => $leadIds);
        } else {
            $result = array('success'   => FALSE);
        }

        return $result;
        
    } 
    
 

     
    public function getLeadsByOwner($filter, $owner, $start, $sort, $dir, $limit, $leadstate, $probability)
    {
        $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );

        if(empty($filter)) {
            $filter = NULL;
        }
        
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        if($rows = $backend->getLeadsByOwner($owner, $filter, $sort, $dir, $limit, $start, $leadstate, $probability)) {
            $result['results']    = $rows->toArray();
            if($start == 0 && count($result['results']) < $limit) {
                $result['totalcount'] = count($result['results']);
            } else {
                $result['totalcount'] = $backend->getCountByOwner($owner, $filter);
            }
        }

        $this->getLinkedContacts($result['results']);

        return $result;
    }
        
     public function getLeadsByFolder($folderId, $filter, $start, $sort, $dir, $limit, $leadstate, $probability)
    {
        $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
                
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        if($rows = $backend->getLeadsByFolder($folderId, $filter, $sort, $dir, $limit, $start, $leadstate, $probability)) {
            $result['results']    = $rows->toArray();
            if($start == 0 && count($result['results']) < $limit) {
                $result['totalcount'] = count($result['results']);
            } else {
                $result['totalcount'] = $backend->getCountByFolderId($folderId, $filter);
            }
        }
        
        $this->getLinkedContacts($result['results']);
        
        return $result;
    }    
 

    /**
     * get data for the overview
     *
     * returns the data to be displayed in a ExtJS grid
     *
     * @todo implement correc total count for lists
     * @param int $start
     * @param int $sort
     * @param string $dir
     * @param int $limit
     * @param string $options json encoded array of additional options
     * @return array
     */
    public function getSharedLeads($filter, $sort, $dir, $limit, $start, $leadstate, $probability)
    {
        $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
                
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        $rows = $backend->getSharedLeads($filter, $sort, $dir, $limit, $start, $leadstate, $probability);
        
        if($rows !== false) {
            $result['results']    = $rows->toArray();
            if($start == 0 && count($result['results']) < $limit) {
                $result['totalcount'] = count($result['results']);
            } else {
                //$result['totalcount'] = $backend->getCountOfSharedLeads();
            }
        }

        $this->getLinkedContacts($result['results']);

        return $result;
    }

    /**
     * get data for the overview
     *
     * returns the data to be displayed in a ExtJS grid
     *
     * @todo implement correc total count for lists
     * @param int $start
     * @param int $sort
     * @param string $dir
     * @param int $limit
     * @param string $options json encoded array of additional options
     * @return array
     */
    public function getOtherPeopleLeads($filter, $sort, $dir, $limit, $start, $dateFrom, $dateTo, $leadstate, $probability)
    {
        $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );
                
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        $rows = $backend->getOtherPeopleLeads($filter, $sort, $dir, $limit, $start, $dateFrom, $dateTo, $leadstate, $probability);
        
        if($rows !== false) {
            $result['results']    = $rows;//->toArray();
            //$result['totalcount'] = $backend->getCountOfOtherPeopleLeads();
        }

        $this->getLinkedContacts($result['results']);

        return $result;
    }
  
 
 
   /**
     * get data for the overview
     *
     * returns the data to be displayed in a ExtJS grid
     *
     * @todo implement correc total count for lists
     * @param int $start
     * @param int $sort
     * @param string $dir
     * @param int $limit
     * @param string $dateFrom
     * @param string $datenTo
     * @param string $options json encoded array of additional options
     * @return array
     */
    public function getAllLeads($filter, $start, $sort, $dir, $limit, $dateFrom = NULL, $dateTo = NULL, $leadstate, $probability)
    {
        $result = array(
            'results'     => array(),
            'totalcount'  => 0
        );

        if(!empty($dateFrom)) {
            $df_tmp = explode("/", $dateFrom);
            $dateFrom = mktime(0,0,0,$df_tmp[0],$df_tmp[1],$df_tmp[2]);
        }

        if(!empty($dateTo)) {
            $dt_tmp = explode("/", $dateTo);
            $dateTo = mktime(0,0,0,$dt_tmp[0],$dt_tmp[1],$dt_tmp[2]);            
        }
               

        
        if($rows = Crm_Controller::getInstance()->getAllLeads($filter, $sort, $dir, $limit, $start, $dateFrom, $dateTo, $leadstate, $probability)) {
            $result['results']      = $rows->toArray();
            $result['totalcount']   = count($result['results']);
        }

        $this->getLinkedContacts($result['results']);
     
        return $result;                
  
    } 
    
    public function getLinkedContacts(array &$_leads)
    {
        $controller = Crm_Controller::getInstance();
        
        foreach($_leads as $id => $lead) {
            $links = $controller->getLinks($lead['lead_id'], 'addressbook');
            foreach($links as $link) {
                switch($link['remark']) {
                    case 'partner':
                        try {
                            $contact = Addressbook_Controller::getInstance()->getContact($link['recordId']);
                            $_leads[$id]['lead_partner'][] = $contact->toArray();
                        } catch (Exception $e) {
                            // do nothing
                        }
                        break;
                    case 'customer':
                        try {
                            $contact = Addressbook_Controller::getInstance()->getContact($link['recordId']);
                            $_leads[$id]['lead_customer'][] = $contact->toArray();
                        } catch (Exception $e) {
                            // do nothing
                        }
                        break;
                }
            }
        }
    }
          
// handle FOLDERS
    public function getFoldersByOwner($owner)
    {
        $treeNodes = array();
        
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        if($rows = $backend->getFoldersByOwner($owner)) {
            foreach($rows as $folderData) {
                $childNode = new Egwbase_Ext_Treenode('Crm', 'leads', 'folder-' . $folderData->container_id, $folderData->container_name, TRUE);
                $childNode->folderId = $folderData->container_id;
                $childNode->nodeType = 'singleFolder';
                $treeNodes[] = $childNode;
            }
        }
        
        echo Zend_Json::encode($treeNodes);

        // exit here, as the Zend_Server's processing is adding a result code, which breaks the result array
        exit;
    }    


    public function getSharedFolders()
    {
        $treeNodes = array();
        
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        if($rows = $backend->getSharedFolders()) {
            foreach($rows as $folderData) {
                $childNode = new Egwbase_Ext_Treenode('Crm', 'leads', 'shared-' . $folderData->container_id, $folderData->container_name, TRUE);
                $childNode->folderId = $folderData->container_id;
                $childNode->nodeType = 'singleFolder';
                $treeNodes[] = $childNode;
            }
        }
        
        echo Zend_Json::encode($treeNodes);

        // exit here, as the Zend_Server's processing is adding a result code, which breaks the result array
        exit;
    }    

   /**
     * returns a list a accounts who gave current account at least read access to 1 personal folder 
     *
     */
    public function getOtherUsers()
    {
        $treeNodes = array();
        
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);
        try {
            $rows = $backend->getOtherUsers();
        
            foreach($rows as $accountData) {
                $treeNode = new Egwbase_Ext_Treenode(
                    'Crm',
                    'leads',
                    'otherfolder_'. $accountData->account_id, 
                    $accountData->account_name,
                    false
                );
                $treeNode->owner  = $accountData->account_id;
                $treeNode->nodeType = 'userFolders';
                $treeNodes[] = $treeNode;
            }
        } catch (Exception $e) {
            // do nothing
            // or throw Execption???
        }
        echo Zend_Json::encode($treeNodes);

        // exit here, as the Zend_Server's processing is adding a result code, which breaks the result array
        exit;
    }  


/*    public function getAccounts($filter, $start, $sort, $dir, $limit)
    {
        $internalContainer = Egwbase_Container::getInstance()->getInternalContainer('crm');
        
        $folderId = $internalContainer->container_id;
        
        $result = $this->getLeadsByFolder($folderId, $filter, $start, $sort, $dir, $limit);

        return $result;
    }
*/

   public function addFolder($name, $type)
    {
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);

        $id = $backend->addFolder($name, $type);
        
        $result = array('folderId' => $id);
        
        return $result;
    }
    
    public function deleteFolder($folderId)
    {
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);

        $backend->deleteFolder($folderId);
            
        return TRUE;
    }
    
    public function renameFolder($folderId, $name)
    {
        $backend = Crm_Backend_Factory::factory(Crm_Backend_Factory::SQL);

        $backend->renameFolder($folderId, $name);
            
        return TRUE;
    }     
     
}