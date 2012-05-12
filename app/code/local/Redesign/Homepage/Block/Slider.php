<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Redesign_Homepage_Block_Slider extends Mage_Core_Block_Template
{
    public function getSliderItems() {
        $slides = array();
        $i = 0;
        
        $slider_image_collection = Mage::getModel('cms/block')
                                            ->setStoreId(Mage::app()->getStore()->getId())
                                            ->getCollection()
                                            ->addFieldToFilter('identifier',array('like'=>'slide_%'))
                                            ->load();
        
        foreach($slider_image_collection as $index=>$slide) {
            $i++;
            $slide_image = str_replace("{{media url=\"",$this->getBaseUrl().'media/',$slide->getContent());
            $slide_image = str_replace("\"}}","",$slide_image);
            $slide_image = str_replace("<p>","",$slide_image);
            $slide_image = str_replace("</p>","",$slide_image);
            $slide_image = str_replace("img","img id=\"slide_".$i."\"",$slide_image);
            $slides[] = $slide_image;
        }

        return $slides;
    }
}

?>
