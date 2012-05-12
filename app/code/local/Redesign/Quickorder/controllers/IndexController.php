<?php

class Redesign_Quickorder_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {
//        echo Zend_Json::decode('{"name":"30320003.JPG","type":"application/octet-stream","tmp_name":"C:\wamp\tmp\php1E9A.tmp","error":0,"size":705418,"path":"C:\wamp\www\redesign\redesign\media\igallery","file":"/3/0/30320003_3.jpg","url":"http://localhost/redesign/redesign/media/igallery/3/0/30320003_3.jpg","cookie":{"name":"adminhtml","value":"77b95rl9nobkrvr0i9jpk0qm10","lifetime":"3600","path":"/redesign/redesign","domain":"localhost"}}');
//        die;
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function childrenAction() {
        $category_id = $this->getRequest()->getParam('id',0);
        $category = Mage::getModel('catalog/category')->load($category_id);
        $children_categories = Mage::getModel('catalog/category')->getResource()->getAllChildren($category);
        
        if(count($children_categories) && $children_categories != array($category_id)) {
            $data = array();
            foreach($children_categories as $cid) {
                if($cid != $category_id) {
                    $data[] = Mage::getModel('catalog/category')->load($cid);
                }
            }
            $template = 'quickorder/categories.phtml';
        } else {
            $data = $category->getProductCollection()
                        ->addAttributeToSelect('*')
                        ->addAttributeToFilter('visibility',Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
            $template = 'quickorder/products.phtml';
        }
        
        echo $this->getLayout()->createBlock('quickorder/products')
                        ->setQuickorderData($data)
                        ->setTemplate($template)
                        ->toHtml();
    }
    
    public function addAction() {
        try{
            $product_id = $this->getRequest()->getParam('product',0);
            $params = $this->getRequest()->getParams();
            //$qty = 1;
            
            $product = Mage::getModel('catalog/product')->load($product_id);

            $session = Mage::getSingleton('core/session', array('name'=>'frontend'));
            $cart = Mage::helper('checkout/cart')->getCart();

            $cart->addProduct($product, $params);

            $session->setLastAddedProductId($product->getId());
            $session->setCartWasUpdated(true);

            $cart->save();

            $result = "success";
            echo $result;

        } catch (Exception $e) {
            $result = $e->getMessage();
            echo $result;
        }
    }
    
    public function getcartAction() {
        $this->loadLayout(false);
        $this->renderLayout();
    }
       
}