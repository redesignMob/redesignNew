<?php

class AlexLoghin_Label_Model_Label extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('label/label');
    }
}