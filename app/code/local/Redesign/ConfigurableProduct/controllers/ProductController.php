<?php

class Redesign_ConfigurableProduct_ProductController extends Mage_Core_Controller_Front_Action
{
    public function imageAction() {
        $_product_id = Mage::app()->getRequest()->getParam('id');
        $_product = Mage::getModel('catalog/product')->load($_product_id);
        echo Mage::helper('catalog/image')->init($_product, 'image');
        die;
    }
    
    public function attributeAction() {
        $_attribute_option_id = Mage::app()->getRequest()->getParam('id');
        echo '<div style="position:relative;float:left;margin:5px"><a onclick="spConfig.setOption(\''.$_attribute_option_id.'\')" style="cursor:pointer"><img src="'.Mage::helper('attributeoptionimage')->getAttributeOptionImage($_attribute_option_id).'" alt="" /></a>';
        die;
    }
}
