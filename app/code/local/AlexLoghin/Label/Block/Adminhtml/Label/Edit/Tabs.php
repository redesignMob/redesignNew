<?php

class AlexLoghin_Label_Block_Adminhtml_Label_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('label_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('label')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('label')->__('Item Information'),
          'title'     => Mage::helper('label')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('label/adminhtml_label_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}