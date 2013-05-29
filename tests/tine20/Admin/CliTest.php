<?php
/**
 * Tine 2.0 - http://www.tine20.org
 * 
 * @package     Admin
 * @license     http://www.gnu.org/licenses/agpl.html
 * @copyright   Copyright (c) 2009-2013 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Philipp Schüle <p.schuele@metaways.de>
 */

/**
 * Test helper
 */
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'TestHelper.php';

/**
 * Test class for Tinebase_Admin
 */
class Admin_CliTest extends PHPUnit_Framework_TestCase
{
    /**
     * Backend
     *
     * @var Admin_Frontend_Cli
     */
    protected $_cli;
    
    /**
     * @var array test objects
     */
    protected $objects = array();
    
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite('Tine 2.0 Admin Cli Tests');
        PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
        Tinebase_TransactionManager::getInstance()->startTransaction(Tinebase_Core::getDb());
        
        $this->_cli = new Admin_Frontend_Cli();
        
        $this->objects['config'] = '<?xml version="1.0" encoding="UTF-8"?>
        <config>
            <dryrun>0</dryrun>
            <encoding>ISO-8859-1</encoding>
            <mapping>
                <field>
                    <source>firstname</source>
                    <destination>accountFirstName</destination>
                </field>
                <field>
                    <source>lastname</source>
                    <destination>accountLastName</destination>
                </field>
                <field>
                    <source>loginname</source>
                    <destination>accountLoginName</destination>
                </field>
                <field>
                    <source>password</source>
                    <destination>password</destination>
                </field>
                <field>
                    <source>email</source>
                    <destination>accountEmailAddress</destination>
                </field>
            </mapping>
        </config>';
        
        $this->objects['configWithHeadline'] = '<?xml version="1.0" encoding="UTF-8"?>
        <config>
            <headline>1</headline>
            <dryrun>0</dryrun>
            <encoding>ISO-8859-1</encoding>
            <mapping>
                <field>
                    <source>firstname</source>
                    <destination>accountFirstName</destination>
                </field>
                <field>
                    <source>lastname</source>
                    <destination>accountLastName</destination>
                </field>
                <field>
                    <source>loginname</source>
                    <destination>accountLoginName</destination>
                </field>
                <field>
                    <source>password</source>
                    <destination>password</destination>
                </field>
                <field>
                    <source>email</source>
                    <destination>accountEmailAddress</destination>
                </field>
            </mapping>
        </config>';
    }

    /**
     * Tears down the fixture
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
        Tinebase_TransactionManager::getInstance()->rollBack();
    }
    
    /**
     * test to import admin users
     *
     */
    public function testImportUsers()
    {
        $this->_importUsers($this->objects['config'], dirname(__FILE__) . '/files/test.csv', 'admin_user_import_csv_test');
    }

    /**
     * test to import admin users
     */
    public function testImportUsersWithHeadline()
    {
        $this->_importUsers($this->objects['configWithHeadline'], dirname(__FILE__) . '/files/testHeadline.csv', 'admin_user_import_csv_test_headline');
    }
    
    /**
     * import users
     *
     * @param string $_config xml config
     * 
     * @see 0008300: Import User via CLI don't import all fields
     */
    protected function _importUsers($_config, $_filename, $_definition)
    {
        // create definition / check if exists
        try {
            $definition = Tinebase_ImportExportDefinition::getInstance()->getByName($_definition);
            $definition->plugin_options = $_config;
        } catch (Tinebase_Exception_NotFound $e) {
            $definition = Tinebase_ImportExportDefinition::getInstance()->create(new Tinebase_Model_ImportExportDefinition(array(
                'application_id'    => Tinebase_Application::getInstance()->getApplicationByName('Admin')->getId(),
                'name'              => $_definition,
                'type'              => 'import',
                'model'             => 'Tinebase_Model_FullUser',
                'plugin'            => 'Admin_Import_Csv',
                'plugin_options'    => $_config
            )));
        }
        
        $tempFilename = TestServer::replaceEmailDomainInFile($_filename);
        
        $opts = new Zend_Console_Getopt('abp:');
        $opts->setArguments(array($tempFilename, 'definition=' . $_definition));
        
        // start import (dry run)
        ob_start();
        $this->_cli->importUser($opts);
        $out = ob_get_clean();
        
        // check output
        $this->assertEquals("Imported 3 records. Import failed for 0 records. \n", $out);
        
        // check if users (with their data) have been added to tine20
        $hmoster = Tinebase_User::getInstance()->getFullUserByLoginName('hmoster');
        $this->assertEquals('Hins', $hmoster->accountFirstName);
        $config = TestServer::getInstance()->getConfig();
        $maildomain = ($config->maildomain) ? $config->maildomain : 'tine20.org';
        $this->assertEquals('hmoster@' . $maildomain, $hmoster->accountEmailAddress);
    }
}
