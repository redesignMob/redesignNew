<?php
/**
 * Magic Logix Gallery
 *
 * Provides an image gallery extension for Magento
 *
 * @category		MLogix
 * @package		Gallery
 * @author		Brady Matthews
 * @copyright		Copyright (c) 2008 - 2010, Magic Logix, Inc.
 * @license		http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 * @link		http://www.magiclogix.com
 * @link		http://www.magentoadvisor.com
 * @since		Version 1.0
 *
 * Please feel free to modify or distribute this as you like,
 * so long as it's for noncommercial purposes and any
 * copies or modifications keep this comment block intact
 *
 * If you would like to use this for commercial purposes,
 * please contact me at brady@magiclogix.com
 *
 * For any feedback, comments, or questions, please post
 * it on my blog at http://www.magentoadvisor.com/plugins/gallery/
 *
 */
?><?php
class MLogix_Gallery_Block_Gallery extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
    public function getGallery($parent=0)     
    { 
        $model = $this->getCurrentGallery();
        if(!$model) return array();
        
        if($parent)
        	return $model->getGallery($parent);
        else
        	return $model->getGallery($model->getId());
    }
    
    public function getImageUrl($itemId)
    {
    	$model = Mage::getModel('gallery/gallery')->load($itemId);
    	return $model->getImageUrl();
    }
    
    public function getViewUrl($itemId)
    {
    	return $this->getUrl("*/*/view").'id/'.$itemId;
    }
    
    public function getCurrentGallery()
    {
    	if(!Mage::registry('current_gallery'))    	
    		Mage::register('current_gallery', Mage::getModel('gallery/gallery'));
    	
    	return Mage::registry('current_gallery');
    }
    
    public function getGalleryTitle()
    {
    	$cg = $this->getCurrentGallery();
    	if($cg && $cg->getTitle())
    		return $cg->getTitle();    	
    	else
    		return "Gallery";
    }
    
    public function getBreadcrumbs()
    {    	
    	return $this->getCurrentGallery()->getBreadcrumbPath();    	
    }
}