<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Redesign_Categories_Block_Collections extends Mage_Core_Block_Template
{
    public function getCollectionCategories() {
        $category = Mage::registry('current_category');
        return $this->loadChildren($category->getId());
    }
    
    public function getTypeCategories() {
        $category = Mage::getModel('catalog/category')->loadByAttribute('name', 'Tipuri produse');
        return $this->loadChildren($category->getEntityId());
    }
    
    private function loadChildren($category_id) {
        return Mage::getModel('catalog/category')->getCollection()
                ->addAttributeToFilter('parent_id',$category_id)
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('url_path')
                ->load();
    }
}
?>
