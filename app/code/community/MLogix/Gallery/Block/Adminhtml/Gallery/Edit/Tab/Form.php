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

class MLogix_Gallery_Block_Adminhtml_Gallery_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('gallery_form', array('legend'=>Mage::helper('gallery')->__('Item information')));
     
      
      
      
      $model = Mage::getModel('gallery/gallery');
      
      $categories = $model->getCategories(0,0);
      
      $ac = array();
      
      $ac[0] = array('value'=>0, 'label'=>Mage::helper('gallery')->__('Gallery Categories (Root)'));
      foreach($categories as $key=>$category)
      	$ac[] = array('value'=>$category['gallery_id'], 'label'=>Mage::helper('gallery')->__($category['item_title']));
      
      $fieldset->addField('parent_id', 'select', array(
      	'label' => Mage::helper('gallery')->__('Parent'),
      	'required' => true,
      	'name'=>'parent_id',
      	'values'=>$ac
      ));      
      
      $fieldset->addField('item_title', 'text', array(
          'label'     => Mage::helper('gallery')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'item_title',
      ));
      
      $fieldset->addField('description', 'text', array(
          'label'     => Mage::helper('gallery')->__('Description'),          
          'required'  => false,
          'name'      => 'description',
      ));      

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('gallery')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
	  
	  $galleryId = $this->getRequest()->getParam('id');
	  if($galleryId)
	  {
	  	  $galleryModel = Mage::getModel('gallery/gallery')->load($galleryId);	  	  
	  	  
	  	  $filename = $galleryModel->getFilename();
	  	  $mediaUrl = $galleryModel->getMediaUrl();	
	  	  
	  	  if($filename)
	  	  {
			  $fieldset->addField('img', 'note', array(
			  	'label'	=> 'Image',
			  	'required' => false,
			  	'text' => '<img src="'.$mediaUrl.$filename.'"/>'			  	
			  ));
	  	  }
	  }	  
	  
      $fieldset->addField('alt', 'text', array(
          'label'     => Mage::helper('gallery')->__('Alt'),          
          'required'  => false,
          'name'      => 'alt',
      ));      	  
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('gallery')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('gallery')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('gallery')->__('Disabled'),
              ),
          ),
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getGalleryData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getGalleryData());
          Mage::getSingleton('adminhtml/session')->setGalleryData(null);
      } elseif ( Mage::registry('gallery_data') ) {
          $form->setValues(Mage::registry('gallery_data')->getData());
      }
      return parent::_prepareForm();
  }
}