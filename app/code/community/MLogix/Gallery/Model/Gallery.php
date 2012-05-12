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

/**
 * SortHelper is just a quick hack
 * to help with the "after_id"
 * prototype js tree ordering
 */
class SortHelper
{
	public $object;
	public $afters;
	
	function __construct($mainobj = 0)
	{
		$this->object = $mainobj;				
		$this->afters = array();

		return $this;			
	}
	
	public function getArray()
	{
		$arr = array();
		
		if($this->object)
		{
			$arr[] = $this->object;
									
			foreach($this->afters as &$obj)
			{
				$arr = array_merge($arr, $obj->getArray());	
			}
		}	
		else 
		{			
			foreach($this->afters as &$obj)
			{
				// put parent=0's at the top
				if(!$obj->object->getAfterId())
					$arr = array_merge($arr, $obj->getArray());	
			}
						
			foreach($this->afters as &$obj)
			{
				if($obj->object->getAfterId())
					$arr = array_merge($arr, $obj->getArray());	
			}			
		}

		return $arr;
	}
	
	public function addAfter($sorthelper)
	{

		if($sorthelper->object)
		{
			if($this->object && $sorthelper->object->getAfterId() == $this->object->getId() || (!$this->object && !$sorthelper->object->getAfterId()))
			{
				$this->afters[] = $sorthelper;

				return true;			
			}		
			else
			{
				foreach($this->afters as &$after)
				{
					if($after->addAfter($sorthelper))
						return true;	
				}				
			}
		}
		return false;
	}
}

class MLogix_Gallery_Model_Gallery extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('gallery/gallery');

    	$path = Mage::getBaseDir('media') . DS . 'gallery';
    	$thumbpath = Mage::getBaseDir('media') . DS . 'gallery' . DS . 'thumbs';

	if(!file_exists($path)) mkdir($path);
	if(!file_exists($thumbpath)) mkdir($thumbpath);

    }
    
    public function getMediaUrl()
    {
    	return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'gallery/';    	
    }
        
    
    public function isActive()
    {
    	return ($this->getStatus()==2?0:1);	
    }
	
	public function getChildren()
	{
		$id = ($this->getId()?$this->getId():0);

		
		if(is_array($this->children))
		  return $this->children;
		  
		$this->children = array();
		  
		$collection = Mage::getModel('gallery/gallery')->getCollection();
		//$collection->addFieldToFilter('status',1);
		
		$collection->addFieldToFilter('parent_id',$id);				
		$sq = $collection->load();	
		

		foreach($collection as &$child)
		{			
			$child->parent = $this;
			$child->load($child->getId(), null, true);			

		}

		$this->children = $collection;
		
		$this->sortChildren();
		
			
		return $this->children;
	}
	
	public function getPath()
	{
		if(!$this->parent)
			return '0';
		else
			return $this->parent->getPath() . '/' . $this->getId();
	}
	
	public function getJsonArray()
	{
		$me = array();
		
		$me['text'] = ($this->getId()?$this->getTitle():'Root');
		$me['id'] = ($this->getId()?$this->getId():2);
		$me['store'] = 0;
		$me['path'] = $this->getPath();

		$me['cls'] = ($this->hasChildren()?'folder':'leaf');
		$me['allowDrop'] = true;
		$me['allowDrag'] = true;

		$me['category_id'] = ($this->getCategoryId()?$this->getCategoryId():0);
		$me['after_id'] = ($this->getAfterId()?$this->getAfterId():0);
		$me['expanded'] = 1;
		
		$me['children'] = array();

		if(count($this->children))
		foreach($this->children as $child)
			$me['children'][] = $child->getJsonArray();

		if($this->getId()) return $me;

		else return $me['children'];
	}
	

	public function getSimpleArray()
	{
		
		$me = array();
		$me['id'] = $this->getId();
		$me['after'] = ($this->after&&$this->after->getId()?$this->after->getId():0);
		$me['parent'] = ($this->parent&&$this->parent->getId()?$this->parent->getId():0);
		$me['title'] = $this->getTitle();		
		$me['tier'] = $this->getTier();
		
		if($this->children && count($this->children))
		{
			$me['children'] = array();
		
			foreach($this->children as $child)
			{
				$newchild = $child->getSimpleArray();			
				if($newchild) $me['children'][] = $newchild;
				
			}
		}
		
		if($this->getId()) return $me;
		else return $me['children'];
	}		
	
	public function array_flatten($array, $return)
	{
		 if(is_array($array))
		 foreach($array as $item)
		 {
			if(isset($item['children']))
				$children = $item['children'];
			else
				$children = array();
			
			unset($item['children']);
			
			$return[] = $item;
			
			foreach($children as $child)
			{
				$return = $this->array_flatten(array($child), $return);	
			}
		 	

		 }
		 return $return;
	}

	
	public function getTier()
	{
		if(!$this->parent) return 0;
			
		return ($this->parent->getTier() + 1);
	}
	
	/**
	 * Shifts the array right, starting at position $position
	 * and inserts $value at $position
	 *
	 * @param unknown_type $array
	 * @param unknown_type $position
	 * @param unknown_type $value
	 */
	public function arrayInsert(&$array, $position, $value)
	{
		if($position == count($array))
		{
			$array[] = $value;
			return;
		}
		
		$firsthalf = array_slice($array, 0, $position);
		$secondhalf = array_slice($array, $position, count($array) - count($firsthalf));
		
		$array = array_merge($firsthalf, array($value), $secondhalf);		
	}
	
	public function arrayDelete(&$array, $position)
	{
		if($position >= count($array)) return -1;
		
		$deletedItem = $array[$position];
		
		$firsthalf = array_slice($array, 0, $position);
		if($position < count($array) - 1)
		{
			$secondhalf = array_slice($array, $position + 1, count($array) - count($firsthalf) - 1);
			$array = array_merge($firsthalf, $secondhalf);
		}
		else $array = $firsthalf;
		
		return $deletedItem;
	}
	
	public function arrayFindId($array, $id)
	{
		foreach($array as $key=>$item)
		  if($item->getId() == $id)
		    return $key;
		return -1;
	}
	
	public function insertAfter(&$array, $after_id, $value)
	{
		$keypos = $this->arrayFindId($array, $after_id);
		
		if($keypos < 0) {
			return 0;
		}
		
		$this->arrayInsert($array, $keypos+1, $value);
		
		return 1;		
	}
	
	public function validateChildrenAfterIds()
	{
		// Validate+fix after_id's.. just in case the id's get screwed up somehow
		foreach($this->children as $child)
			if($child->getAfterId() && !$child->getSibling($child->getAfterId()))
			{
				$child->setAfterId(0);
				$child->save();				
			}		
	}
	
	public function sortChildren()
	{		
		$this->validateChildrenAfterIds();

		foreach($this->children as &$child)		
			$child->after = $child->getSibling($child->getAfterId());		

		$sorthelpers = array();			
		foreach($this->children as &$child)
			$sorthelpers[] = new SortHelper($child);

		$limit = count($this->children);

		$z = 0;
		while($z++ < $limit && count($this->children))
		{						
			$x = array_pop($sorthelpers);
			$go = 1;
			foreach($sorthelpers as &$sorthelper)
			{

				if($go)
				{
					if($sorthelper->addAfter($x))		
						$go = 0;
				}
			}
			if($go)
			{
				$this->arrayInsert($sorthelpers, 0, $x);	
			}

		}
		
		$sortedarray = array();
		
		foreach($sorthelpers as &$helper)
		{
			$sortedarray = array_merge($sortedarray, $helper->getArray());	
		}

		$this->children = $sortedarray;
		
	}	
		
	public function getSibling($id)
	{
		if(!$this->getId()) return 0;
		if(!$this->parent) return 0;
		
		return $this->parent->getChild($id);		
	}	
	
	public function getChild($id)
	{
		foreach($this->children as &$child)
		{
			if($child->getId() == $id)
				return $child;
		}
		
		return 0;
	}
	
	public function initTree()
	{
	//unused
	}
	
	public function load($id, $field=null, $tree=false)
	{
		parent::load($id, $field);

		if($this->getId() && $this->getId() == $this->getParentId())
		{
			$this->setParentId(0);
			$this->parent = 0;
			$this->save();	
		}
		
		if($this->getId() && $this->getId() == $this->getAfterId())
		{
			$this->setAfterId(0);
			$this->save();	
		}				
		
		if(!$this->getId() || $tree)
        	$this->getChildren();	

        

        	
		return $this;
	}
       
    /**
     * Returns an array of active categories
     * Default behavior is to nest the array by parent->children relationship
     * @return array
     */
    public function getCategories($node = 0, $json = 1)
    {
    	$root = Mage::getModel('gallery/gallery')->load(0);
		
		if($json)
		{
			$ar = $root->getJsonArray();

			return $ar;
			
		} 	
    	
    	$rawcategories = $this->getCollection()->addFieldToFilter('status','1')->toArray();
    	
    	$rawcategories = $rawcategories['items'];
    	
		$categories = array();
		
		if($node) return $categories;
		
		foreach($rawcategories as $category)
		{
			$id = $category['gallery_id'];
			$categories[$id] = $category;
		}
		

    	
        return $categories;
    }
    
    /**
     * Moves this category to a new parent and/or repositions it     
     *
     * @param int $newParentId the parent's gallery_id
     * @param int $afterId the Id of the category this appears after (for sorting)
     * @param int $categoryId Used if the category isn't loaded yet (with ->load($id))
     * @return string Response message (for json)
     */
    public function move($newParentId, $afterId, $categoryId=0)
    {
        if($categoryId) $this->load($categoryId);
        else $categoryId = $this->getCategoryId();
        
        try {
        	$newParentId = (int)$newParentId;
        	$categoryId = (int)$categoryId;
        	$afterId = (int)$afterId;
			$prevAfterId = (int)$this->getAfterId();

			$tableName = $this->getResource()->getTable('gallery/gallery');

			$write = Mage::getSingleton('core/resource')->getConnection('core_write');
			
			$write->raw_query("update $tableName set after_id = $prevAfterId where after_id = $categoryId");			
			$write->raw_query("update $tableName set after_id = $categoryId where after_id = $afterId and parent_id = $newParentId and gallery_id != $categoryId");			

			$this->setParentId($newParentId);
			$this->setAfterId($afterId);
			$this->save();

            return "SUCCESS";
        }
        catch (Mage_Core_Exception $e) {
            return $e->getMessage();
        }
        catch (Exception $e){
        	return $e->getMessage();
        	//return Mage::helper('catalog')->__('Category move error');
        }	    	
    	
    }    
    

    
public function createthumb($name,$filename,$new_w,$new_h,$forcepng=false){
	$system=explode('.',$name);
	$ext = $system[count($system)-1];
	
	if (preg_match('/jpg|jpeg/i',$ext)){
		$src_img=imagecreatefromjpeg($name);
	}
	if (preg_match('/png/i',$ext)){
		$src_img=imagecreatefrompng($name);
	}
	
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	/*
	if ($old_x > $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$old_y*($new_h/$old_x);
	}
	if ($old_x < $old_y) {
		$thumb_w=$old_x*($new_w/$old_y);
		$thumb_h=$new_h;
	}
	if ($old_x == $old_y) {
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}*/
	
	$thumb_w=$new_w;
	$thumb_h=$new_h;

	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	
	
	//imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	
	if ($forcepng||preg_match("/png/i",$system[1]))
	{
		imagepng($dst_img,$filename); 
	} else {
		imagejpeg($dst_img,$filename); 
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img); 

}    

    public function getThumbnail($width=130, $height=120)
    {
    	$thumbname = $this->makeThumbnail($width, $height);
    	
    	return $this->getMediaUrl() . 'thumbs/' . $thumbname;
    }
    
    public function makeThumbnail($width, $height, $refresh=false)
    {
		$forcepng = true;

    	$filename = $this->getFilename();
    	$thumbname = $width . '_' . $height . '_' . $filename;
    	
    	if($forcepng)
    		$thumbname = preg_replace("/\.[^\.]+$/",".png",$thumbname);
    	
    	if(!$this->getId() || !$filename) return '';
    	
    	$path = Mage::getBaseDir('media') . DS . 'gallery' . DS;
    	$thumbpath = Mage::getBaseDir('media') . DS . 'gallery' . DS . 'thumbs' . DS;
    	
    	$file = $path . $filename;
    	$thumb = $thumbpath . $thumbname;
    	
    	
    	
    	//return '';
    	if(!$refresh && file_exists($thumb)) return $thumbname;
    	
    	$this->createthumb($file,$thumb,$width,$height,$forcepng);

    	return $thumbname;    	
    }
    
    public function getGallery($parent=0)
    {
    	$items =       $this->getCollection()
    						->addFieldToFilter('status','1')
    						->addFieldToFilter('parent_id',$parent);  	
    	return $items;
    }
       
    public function getImageUrl()
    {
    	return $this->getMediaUrl().$this->getFilename();
    }        
    
    public function getBreadcrumbPath()
    {
    	$resource = $this->getResource();
		$read = $resource->getReadConnection();		
   		$table = $resource->getMainTable();
   		
   		$path = array();
   		
   		//if($this->getId() > 0)
   		//	$path[] = $this;

   		$parent = $this->getParentId();   		   		
   		
   		$count=0;
   		while($parent != 0)
   		{
   			// An infinite loop shouldn't happen, but just in case
   			if($count++ > 100) { echo 'Error in Model/Gallery'; die(); }
   			
	   		$sql = "SELECT gallery_id, parent_id FROM ".$table." where gallery_id = ?";
	   		//echo $sql;
	   		//die();
			$stmt = $read->query($sql, $parent);
			
			if ($row = $stmt->fetch()) {
			  $path[] = Mage::getModel('gallery/gallery')->load($row['gallery_id']);
			  $parent = $row['parent_id'];
			}
   		}		
 		
   		return array_reverse($path);		
    }
    
    public function hasChildren()
    {
    	$resource = $this->getResource();
		$read = $resource->getReadConnection();		
   		$table = $resource->getMainTable();    	
   		
   		$sql = "SELECT gallery_id, parent_id FROM ".$table." where parent_id = ?";
   		//echo $sql;
   		//die();
		$stmt = $read->query($sql, $this->getId());
		
		if ($row = $stmt->fetch()) {
		  return true;
		}   		
		
		return false;
    }
    
    public function getDescription()
    {
    	//return str_replace("\"","%22",parent::getDescription());
    	return parent::getDescription();
    }
    
    public function getTitle()
    {
    	return str_replace("\"","%22",parent::getData('item_title'));
    }
    
    public function getAlt()
    {
    	return str_replace("\"","%22",parent::getAlt());
    }
    
    public function getCategoryId()
    {
    	return $this->getGalleryId();	
    }
}