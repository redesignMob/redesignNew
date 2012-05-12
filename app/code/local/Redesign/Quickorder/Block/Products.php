<?php

class Redesign_Quickorder_Block_Products extends Mage_Core_Block_Template
{
    public function getMainCategories() {
        $categories = Mage::getModel('catalog/category')->getCollection()
                            ->addAttributeToFilter('level',2)
                            ->addAttributeToSelect('*')
                            ->load();
        
        return $categories;
    }
}