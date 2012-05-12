<?php

class AlexLoghin_Label_Model_Mysql4_Label_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('label/label');
    }
}