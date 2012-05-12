<?php
class AlexLoghin_Label_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/label?id=15 
    	 *  or
    	 * http://site.com/label/id/15 	
    	 */
    	/* 
		$label_id = $this->getRequest()->getParam('id');

  		if($label_id != null && $label_id != '')	{
			$label = Mage::getModel('label/label')->load($label_id)->getData();
		} else {
			$label = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($label == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$labelTable = $resource->getTableName('label');
			
			$select = $read->select()
			   ->from($labelTable,array('label_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$label = $read->fetchRow($select);
		}
		Mage::register('label', $label);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}