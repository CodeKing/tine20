<?php
/**
 * Tine 2.0
 *
 * @package     Tasks
 * @subpackage  Frontend
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @copyright   Copyright (c) 2013-2013 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 */

/**
 * class to handle CalDAV tree
 *
 * @package     Tasks
 * @subpackage  Frontend
 */
class Tasks_Frontend_CalDAV extends Addressbook_Frontend_CardDAV
{
    protected $_applicationName = 'Tasks';
    
    protected $_model = 'Task';
}
