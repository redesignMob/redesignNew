<?php

class AlexLoghin_Label_Block_Adminhtml_Label_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'label';
        $this->_controller = 'adminhtml_label';
        
        $this->_updateButton('save', 'label', Mage::helper('label')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('label')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('label_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'label_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'label_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('label_data') && Mage::registry('label_data')->getId() ) {
            return Mage::helper('label')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('label_data')->getTitle()));
        } else {
            return Mage::helper('label')->__('Add Item');
        }
    }
}