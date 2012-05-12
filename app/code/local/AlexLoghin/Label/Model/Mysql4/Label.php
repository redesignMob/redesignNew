<?php

class AlexLoghin_Label_Model_Mysql4_Label extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the label_id refers to the key field in your database table.
        $this->_init('label/label', 'label_id');
    }
}