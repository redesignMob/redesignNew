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
class MLogix_Gallery_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/gallery?id=15 
    	 *  or
    	 * http://site.com/gallery/id/15 	
    	 */
    	/* 
		$gallery_id = $this->getRequest()->getParam('id');

  		if($gallery_id != null && $gallery_id != '')	{
			$gallery = Mage::getModel('gallery/gallery')->load($gallery_id)->getData();
		} else {
			$gallery = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($gallery == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$galleryTable = $resource->getTableName('gallery');
			
			$select = $read->select()
			   ->from($galleryTable,array('gallery_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$gallery = $read->fetchRow($select);
		}
		Mage::register('gallery', $gallery);
		*/
		$this->_redirect('*/album');
			
		$this->loadLayout();     
		$this->renderLayout();
    }
}