<?php

class Redesign_Material_Model_Observer
{
    /**
     * Flag to stop observer executing more than once
     *
     * @var static bool
     */
    static protected $_singletonFlag = false;
 
    /**
     * This method will run when the product is saved from the Magento Admin
     * Use this function to update the product model, process the
     * data or anything you like
     *
     * @param Varien_Event_Observer $observer
     */
    public function saveProductTabData(Varien_Event_Observer $observer)
    {
        if (!self::$_singletonFlag) {
            self::$_singletonFlag = true;
 
            $product = $observer->getEvent()->getProduct();
 
            try {
                /**
                 * Perform any actions you want here
                 *
                 */
                $post =  Mage::app()->getRequest()->getPost();
//                pr($post['product']['material']);
//                die;
                $materials = Zend_Json::encode($post['product']['material']);
                /**
                 * Uncomment the line below to save the product
                 *
                 */
                $product->setMaterials($materials);
                $product->save();
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }
    
    public function processMaterialsStock($observer) {
        $order = $observer->getEvent()->getOrder();
        foreach($order->getAllItems() as $o_item) {
            $qty = $o_item->getQtyToInvoice();
            $product_materials = Zend_Json::decode( Mage::getModel('catalog/product')->load($o_item->getProductId())->getMaterials() );
            
            foreach($product_materials['material_id'] as $index => $material_id) {
                $material = Mage::getModel('material/material')->load($material_id);
                $updated_stock = $material->getContent() - $qty * $product_materials['material_qty'][$index];
                $material->setContent($updated_stock);
                $material->save();
            }
        }
        
        return true;
    }
}