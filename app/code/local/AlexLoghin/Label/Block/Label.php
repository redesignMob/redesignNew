<?php
class AlexLoghin_Label_Block_Label extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getLabel()     
     { 
        if (!$this->hasData('label')) {
            $this->setData('label', Mage::registry('label'));
        }
        return $this->getData('label');
        
    }
}