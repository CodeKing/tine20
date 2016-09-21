<?php
/**
 * Tine 2.0
 * 
 * @package     Tinebase
 * @subpackage  Backend
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @author      Philipp Schuele <p.schuele@metaways.de>
 * @copyright   Copyright (c) 2007-2016 Metaways Infosystems GmbH (http://www.metaways.de)
 *
 */

/**
 * Interface for Sql Backends
 * 
 * @package     Tinebase
 * @subpackage  Backend
 */
interface Tinebase_Backend_Sql_Interface extends Tinebase_Backend_Interface
{
    /**
     * get table prefix
     *
     * @return string
     */
    public function getTablePrefix();

    /**
     * get table name
     *
     * @return string
     */
    public function getTableName();

    /**
     * get db adapter
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getAdapter();

    /**
     * returns the db schema
     * 
     * @return array
     */
    public function getSchema();
}
