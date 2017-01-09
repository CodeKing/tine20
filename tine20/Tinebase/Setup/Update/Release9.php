<?php
/**
 * Tine 2.0
 *
 * @package     Tinebase
 * @subpackage  Setup
 * @license     http://www.gnu.org/licenses/agpl.html AGPL3
 * @copyright   Copyright (c) 2015 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Philipp Schüle <p.schuele@metaways.de>
 */
class Tinebase_Setup_Update_Release9 extends Setup_Update_Abstract
{
    /**
     * update to 9.1
     * 
     * @see 0011178: allow to lock preferences for individual users
     */
    public function update_0()
    {
        $update8 = new Tinebase_Setup_Update_Release8($this->_backend);
        $update8->update_11();
        $this->setApplicationVersion('Tinebase', '9.1');
    }

    /**
     * update to 9.2
     *
     * adds index to relations
     */
    public function update_1()
    {
        $update8 = new Tinebase_Setup_Update_Release8($this->_backend);
        $update8->update_12();
        $this->setApplicationVersion('Tinebase', '9.2');
    }

    /**
     * update to 9.3
     *
     * adds ondelete cascade to some indices (tags + roles)
     */
    public function update_2()
    {
        $update8 = new Tinebase_Setup_Update_Release8($this->_backend);
        $update8->update_13();
        $this->setApplicationVersion('Tinebase', '9.3');
    }

    /**
     * update to 9.4
     *
     * move keyFieldConfig defaults to config files
     */
    public function update_3()
    {
        $update8 = new Tinebase_Setup_Update_Release8($this->_backend);
        $update8->update_14();
        $this->setApplicationVersion('Tinebase', '9.4');
    }

    /**
     * update to 9.5
     *
     * @see 0012300: add container owner column
     */
    public function update_4()
    {
        if ($this->getTableVersion('container') < 10) {
            $declaration = new Setup_Backend_Schema_Field_Xml(
            '<field>
                <name>owner_id</name>
                <type>text</type>
                <length>40</length>
                <notnull>false</notnull>
            </field>
            ');
            $this->_backend->addCol('container', $declaration);

            $declaration = new Setup_Backend_Schema_Index_Xml('
                <index>
                    <name>owner_id</name>
                    <field>
                        <name>owner_id</name>
                    </field>
                </index>
            ');

            $this->_backend->addIndex('container', $declaration);
            $this->setTableVersion('relations', '8');
        }
        $this->setTableVersion('container', '10');

        Tinebase_Container::getInstance()->setContainerOwners();

        $this->setApplicationVersion('Tinebase', '9.5');
    }

    /**
     * update to 9.6
     *
     * change length of groups.description column from varchar(255) to text
     */
    public function update_5()
    {
        if ($this->getTableVersion('groups') < 6) {
            $declaration = new Setup_Backend_Schema_Field_Xml(
                '<field>
                    <name>description</name>
                    <type>text</type>
                    <notnull>false</notnull>
                </field>
            ');
            $this->_backend->alterCol('groups', $declaration);
            $this->setTableVersion('groups', '6');
        }

        $this->setApplicationVersion('Tinebase', '9.6');
    }
}
