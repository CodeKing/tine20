<?php
/**
 * Tine 2.0
 * 
 * @package     Crm
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id$
 *
 * @todo        add more fields?
 * @todo        do we need the datetime fields here?
 * @todo        replace it with the tinebase pagination model?
 */

/**
 * Leads Pagination Class
 * @package Tasks
 */
class Crm_Model_LeadPagination extends Tinebase_Record_Abstract
{
	/**
     * key in $_validators/$_properties array for the filed which 
     * represents the identifier
     * 
     * @var string
     */    
    protected $_identifier = 'id';
    
    /**
     * application the record belongs to
     *
     * @var string
     */
    protected $_application = 'Crm';
    
    protected $_validators = array(
        'id'                   => array('allowEmpty' => true,  'Int'   ),
        
        'start'                => array('allowEmpty' => true,  'Int'   ),
        'limit'                => array('allowEmpty' => true,  'Int'   ),
        'sort'                 => array('allowEmpty' => true,          ),
        'dir'                  => array('allowEmpty' => true,  'Alpha' ),
    /*
        'start'                => array('allowEmpty' => true           ),
        'end'                  => array('allowEmpty' => true           ),
        'end_scheduled'        => array('allowEmpty' => true           ),
    */    
    );
    
    /*
    protected $_datetimeFields = array(
        'start',
        'end',
        'end_scheduled',
    );
    */    
}
