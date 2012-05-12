<?php

class Redesign_Material_Block_Adminhtml_Material_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('material_form', array('legend'=>Mage::helper('material')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('material')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      
      /*
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('material')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
       */
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('material')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('material')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('material')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'text', array(
          'name'      => 'content',
          'label'     => Mage::helper('material')->__('Content'),
          'title'     => Mage::helper('material')->__('Content'),
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getMaterialData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getMaterialData());
          Mage::getSingleton('adminhtml/session')->setMaterialData(null);
      } elseif ( Mage::registry('material_data') ) {
          $form->setValues(Mage::registry('material_data')->getData());
      }
      return parent::_prepareForm();
  }
}