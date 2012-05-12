<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Redesign_Slide_Block_Slideup extends Mage_Core_Block_Template
{
    public function getProducts() {
        
        $products_collection = Mage::getModel('catalog/product')
                                            ->setStoreId(Mage::app()->getStore()->getId())
                                            ->getCollection()
                                            ->addFieldToFilter('status',1)
                                            ->addAttributeToSelect('name')
                                            ->addAttributeToSelect('url_path')
                                            ->addAttributeToSelect('small_image')
                                            ->load();

        return $products_collection;
    }
}
?>
