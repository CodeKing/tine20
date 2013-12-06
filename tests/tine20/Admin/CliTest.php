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
class Admin_CliTest extends TestCase
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
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->_cli = new Admin_Frontend_Cli();
        
        $this->_usernamesToDelete = array('hmaster', 'hmeister', 'hmoster', 'irmeli');
        
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
        
        $this->objects['configSemicolon'] = '<?xml version="1.0" encoding="UTF-8"?>
        <config>
            <model>Tinebase_Model_FullUser</model>
            <plugin>Admin_Import_Csv</plugin>
            <type>import</type>
            <headline>1</headline>
            <dryrun>0</dryrun>
            <extension>csv</extension>
            <delimiter>;</delimiter>
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
     * test to import admin users
     *
     */
    public function testImportUsers()
    {
        $out = $this->_importUsers($this->objects['config'], dirname(__FILE__) . '/files/test.csv', 'admin_user_import_csv_test');
        $this->_checkResult($out);
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
        
        return $out;
    }
    
    /**
     * check import result
     * 
     * @param string $out
     */
    protected function _checkResult($out, $username = 'hmoster')
    {
        // check output
        if ($username == 'hmoster') {
            $this->assertEquals("Imported 3 records. Import failed for 0 records. \n", $out);
        } else {
            $this->assertEquals("Imported 1 records. Import failed for 2 records. \n", $out);
        }
        
        // check if users (with their data) have been added to tine20
        $user = Tinebase_User::getInstance()->getFullUserByLoginName($username);
        if ($username == 'hmoster') {
            $this->assertEquals('Hins', $user->accountFirstName);
        }
        $config = TestServer::getInstance()->getConfig();
        $maildomain = ($config->maildomain) ? $config->maildomain : 'tine20.org';
        $this->assertEquals($username . '@' . $maildomain, $user->accountEmailAddress);
    }

    /**
     * test to import admin users
     */
    public function testImportUsersWithHeadline()
    {
        $out = $this->_importUsers($this->objects['configWithHeadline'], dirname(__FILE__) . '/files/testHeadline.csv', 'admin_user_import_csv_test_headline');
        $this->_checkResult($out);
    }
    
    /**
     * testImportUsersWithEmailAndSemicolon
     * 
     * @see 0008300: Import User via CLI don't import all fields
     */
    public function testImportUsersWithEmailAndSemicolon()
    {
        $out = $this->_importUsers($this->objects['configSemicolon'], dirname(__FILE__) . '/files/tine_user3.csv', 'admin_user_import_csv_test_semicolon');
        $this->_checkResult($out, 'irmeli');
    }
}
