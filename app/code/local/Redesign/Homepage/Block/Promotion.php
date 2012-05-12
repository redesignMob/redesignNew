<?php
class Redesign_Homepage_Block_Promotion extends Mage_Core_Block_Template
{
    public function getPromotionList() {
        $promotions = Mage::getModel('catalog/product')->getCollection()
                            ->addAttributeToFilter('homepage_promotion',1)
                            ->addAttributeToSort('name', 'ASC')
                            ->setPageSize(3)
                            ->load();
       
        return $promotions;
    }
}
?>
