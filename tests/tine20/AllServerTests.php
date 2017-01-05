<?php
/**
 * Tine 2.0 - http://www.tine20.org
 * 
 * @package     Tinebase
 * @license     http://www.gnu.org/licenses/agpl.html AGPL Version 3
 * @copyright   Copyright (c) 2014-2014 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * all server tests
 * 
 * @package     Tinebase
 */
class AllServerTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Tine 2.0 all server tests');
        
        $suite->addTestSuite('ActiveSync_Server_HttpTests');
        $suite->addTestSuite('Tinebase_ControllerServerTest');
        $suite->addTestSuite('Tinebase_Server_WebDAVTests');
        $suite->addTestSuite('Tinebase_Server_JsonTests');
        $suite->addTestSuite('Tinebase_Server_HttpTests');

        return $suite;
    }
}
