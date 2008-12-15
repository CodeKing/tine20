<?php
/**
 * Tine 2.0
 * 
 * @package     Timetracker
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2008 Metaways Infosystems GmbH (http://www.metaways.de)
 * @version     $Id:TimesheetFilter.php 5576 2008-11-21 17:04:48Z p.schuele@metaways.de $
 *
 */

/**
 * contract filter Class
 * @package     Timetracker
 */
class Timetracker_Model_TimesheetFilter extends Tinebase_Record_AbstractFilter
{
    /**
     * application the record belongs to
     *
     * @var string
     */
    protected $_application = 'Timetracker';
    
    /**
     * the constructor
     * it is needed because we have more validation fields in Tasks
     * 
     * @param mixed $_data
     * @param bool $bypassFilters sets {@see this->bypassFilters}
     * @param bool $convertDates sets {@see $this->convertDates}
     * 
     * @todo    add more validators/filters
     */
    public function __construct($_data = NULL, $_bypassFilters = false, $_convertDates = true)
    {
        $this->_validators = array_merge($this->_validators, array(
            'timeaccount_id'        => array(Zend_Filter_Input::ALLOW_EMPTY => true, 'special' => TRUE),
            'account_id'            => array(Zend_Filter_Input::ALLOW_EMPTY => true),
        ));
        
        // define query fields
        $this->_queryFields = array(
            'description',
        );
        
        parent::__construct($_data, $_bypassFilters, $_convertDates);
    }    
    
    /**
     * gets record related properties
     * 
     * @param string name of property
     * @return mixed value of property
     */
    public function __get($_name)
    {
        switch ($_name) {
            case 'timeaccount_id':
                $this->_resolveTimeaccount();
                break;
            default:
        }
        return parent::__get($_name);
    }
    
    /**
     * Resolves timeaccount_id
     * 
     * @return void
     */
    protected function _resolveTimeaccount()
    {
        if (isset($this->_properties['timeaccount_id']) && is_array($this->_properties['timeaccount_id'])) {
            return;
        }
        
        // @todo we should need only one function call here
        // @todo what about MANAGE_ALL ?
        $timeaccountsViewAll = Timetracker_Model_TimeaccountGrants::getTimeaccountsByAcl(Timetracker_Model_TimeaccountGrants::VIEW_ALL, TRUE); 
        $timeaccountsBookAll = Timetracker_Model_TimeaccountGrants::getTimeaccountsByAcl(Timetracker_Model_TimeaccountGrants::BOOK_ALL, TRUE);
      
        $this->_properties['timeaccount_id'] = array_merge($timeaccountsViewAll, $timeaccountsBookAll);
    }    

    /**
     * appends current filters to a given select object
     * 
     * @param  Zend_Db_Select
     * @return void
     */
    public function appendFilterSql($_select)
    {
        $db = Tinebase_Core::getDb();
        
        if (!Timetracker_Controller_Timesheet::getInstance()->checkRight(Timetracker_Acl_Rights::MANAGE_TIMEACCOUNTS, FALSE, FALSE)) {
            if (!empty($this->timeaccount_id)) {
                $_select->where($db->quoteInto($db->quoteIdentifier('timeaccount_id') . ' IN (?)', $this->timeaccount_id) .
                    ' OR ' . $db->quoteInto($db->quoteIdentifier('account_id'). ' = ?', Tinebase_Core::getUser()->getId()));
            } elseif (empty($this->account_id)) {
                $_select->where($db->quoteInto($db->quoteIdentifier('account_id'). ' = ?', Tinebase_Core::getUser()->getId()));
            }
        }
        
        parent::appendFilterSql($_select);
    }
    
}
