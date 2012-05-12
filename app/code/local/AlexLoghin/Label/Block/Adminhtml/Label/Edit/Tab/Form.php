<?php

class AlexLoghin_Label_Block_Adminhtml_Label_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('label_form', array('legend'=>Mage::helper('label')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('label')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
      
      /*
      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('label')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
       */
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('label')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('label')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('label')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'text', array(
          'name'      => 'content',
          'label'     => Mage::helper('label')->__('Content'),
          'title'     => Mage::helper('label')->__('Content'),
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getLabelData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getLabelData());
          Mage::getSingleton('adminhtml/session')->setLabelData(null);
      } elseif ( Mage::registry('label_data') ) {
          $form->setValues(Mage::registry('label_data')->getData());
      }
      return parent::_prepareForm();
  }
}