<?php
/**
 * Tine 2.0 - http://www.tine20.org
 * 
 * @package     Phone
 * @license     http://www.gnu.org/licenses/agpl.html
 * @copyright   Copyright (c) 2008-2016 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Philipp Schüle <p.schuele@metaways.de>
 */

/**
 * Test class for Tinebase_Group
 */
class Phone_ControllerTest extends TestCase
{
    /**
     * Fixtures
     * 
     * @var array test objects
     */
    protected $_objects = array();

    /**
     * Backend
     *
     * @var Phone_Controller
     */
    protected $_backend;
    
    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
        $this->_backend = Phone_Controller::getInstance();

        $this->_objects['call'] = new Phone_Model_Call(array(
            'id'                    => 'phpunitcallid',
            'line_id'               => 'phpunitlineid',
            'phone_id'              => 'phpunitphoneid',
            'direction'             => Phone_Model_Call::TYPE_INCOMING,
            'source'                => '26',
            'destination'           => '0406437435',    
        ));
    }

    /**
     * test start
     * 
     */
    public function testStartCall()
    {
        // remove old call
        try {
            $call = $this->_backend->getCall($this->_objects['call']->getId());
            $backend = Phone_Backend_Factory::factory(Phone_Backend_Factory::CALLHISTORY);
            $backend->delete($this->_objects['call']->getId());
        } catch (Exception $e) {
            // do nothing
        }
        $call = $this->_backend->callStarted($this->_objects['call']);
        
        $this->assertEquals($this->_objects['call']->destination, $call->destination);
        $this->assertTrue(Tinebase_DateTime::now()->sub($call->start)->getTimestamp() >= 0);
        
        // sleep for 2 secs (ringing...)
        sleep(2);
    }

    /**
     * test connect
     * 
     */
    public function testConnected()
    {
        $call = $this->_backend->getCall($this->_objects['call']->getId());
        $ringing = $call->ringing;
        
        $connectedCall = $this->_backend->callConnected($call);

        $this->assertEquals($this->_objects['call']->destination, $connectedCall->destination);
        $this->assertEquals(-1, $call->start->compare($call->connected));
        
        // sleep for 5 secs (talking...)
        sleep(5);
    }

    /**
     * test disconnect
     * 
     */
    public function testDisconnected()
    {
        $call = $this->_backend->getCall($this->_objects['call']->getId());
        $duration = $call->duration;

        $disconnectedCall = $this->_backend->callDisconnected($call);
        
        $this->assertGreaterThan($duration, $disconnectedCall->duration);
        $this->assertLessThan(10, $disconnectedCall->ringing, 'wrong ringing duration');
        $this->assertLessThan(15, $disconnectedCall->duration, 'wrong duration');
        $this->assertEquals(-1, $disconnectedCall->connected->compare($disconnectedCall->disconnected));
    }
}
