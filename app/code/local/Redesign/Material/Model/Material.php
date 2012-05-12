<?php

class Redesign_Material_Model_Material extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('material/material');
    }
}