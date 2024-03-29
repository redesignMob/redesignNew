<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    My
 * @package     My_Igallery
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Default controller
 *
 * @category   My
 * @package    My_Igallery
 * @author     Theodore Doan <theodore.doan@gmail.com>
 */

class My_Igallery_CategoryController extends Mage_Core_Controller_Front_Action {
    public function viewAction() {
        $galleryId = (int) $this->getRequest()->getParam('id', false);
        if ($galleryId) {
            $gallery = Mage::getModel('igallery/banner')
                ->load($galleryId);
            if ($gallery->getIsActive() == 0) {
                return false;
            }
            Mage::register('current_igallery', $gallery);

            $this->loadLayout();

            if ($gallery->getPageLayout()) {
                $this->getLayout()->helper('page/layout')
                    ->applyTemplate($gallery->getPageLayout());
            }

            $this->renderLayout();
        } elseif (!$this->getResponse()->isRedirect()) {
            $this->_forward('noRoute');
        }
	}
}