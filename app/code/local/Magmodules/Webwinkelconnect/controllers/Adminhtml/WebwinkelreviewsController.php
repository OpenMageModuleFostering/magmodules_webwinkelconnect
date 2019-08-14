<?php 
/**
 * Magmodules.eu - http://www.magmodules.eu
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@magmodules.eu so we can send you a copy immediately.
 *
 * @category    Magmodules
 * @package     Magmodules_Webwinkelconnect
 * @author      Magmodules <info@magmodules.eu)
 * @copyright   Copyright (c) 2016 (http://www.magmodules.eu)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Magmodules_Webwinkelconnect_Adminhtml_WebwinkelreviewsController extends Mage_Adminhtml_Controller_Action {

	protected function _initAction() 
	{
		$this->loadLayout()->_setActiveMenu('webwinkelconnect/webwinkelreviews')->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));		
		return $this;
	}   
 
	public function indexAction()
	{
		$this->_initAction()->renderLayout();
	}

	public function processAction() 
	{			
		$storeids = Mage::getModel('webwinkelconnect/api')->getStoreIds();				
		$start_time = microtime(true);		
		foreach($storeids as $storeid)  {
			$msg = '';
			$api_id = Mage::getStoreConfig('webwinkelconnect/general/api_id', $storeid);
			$result = Mage::getModel('webwinkelconnect/api')->processFeed($storeid, 'history');		
			$log = Mage::getModel('webwinkelconnect/log')->addToLog('reviews', $storeid, $result, '', (microtime(true) - $start_time), '', '');

			if(($result['review_new'] > 0) || ($result['review_updates'] > 0) || ($result['stats'] == true)) {
				$msg = Mage::helper('webwinkelconnect')->__('Webwinkel ID %s:', $api_id) . ' '; 
				$msg .= Mage::helper('webwinkelconnect')->__('%s new review(s)', $result['review_new']) . ', '; 
				$msg .= Mage::helper('webwinkelconnect')->__('%s review(s) updated', $result['review_updates']) . ' & '; 
				$msg .= Mage::helper('webwinkelconnect')->__('and total score updated.');
			}

			if($msg) {
				Mage::getSingleton('adminhtml/session')->addSuccess($msg);							
			} else {
				if(Mage::getSingleton('adminhtml/session')->getMessages()->count() < 1) {
					Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webwinkelconnect')->__('Webwinkel ID %s: no updates found, feed is empty or not foud!', $api_id));
				}	
			}
		}				
		$this->_redirect('adminhtml/system_config/edit/section/webwinkelconnect');
	}

	public function testapiAction() 
	{
		$this->_redirect('adminhtml/system_config/edit/section/webwinkelconnect');
	}    

    public function massDisableAction() 
    {    
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if(!is_array($reviewIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webwinkelconnect')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $review_id) {
					$reviews = Mage::getModel('webwinkelconnect/reviews')->load($review_id);
					$reviews->setStatus(0)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('webwinkelconnect')->__('Total of %d review(s) were disabled.', count($reviewIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }    
    
    public function massEnableAction() 
    {    
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if(!is_array($reviewIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webwinkelconnect')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $review_id) {
					$reviews = Mage::getModel('webwinkelconnect/reviews')->load($review_id);
					$reviews->setStatus(1)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('webwinkelconnect')->__('Total of %d review(s) were enabled.', count($reviewIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }    

    public function massEnableSidebarAction() 
    {    
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if(!is_array($reviewIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webwinkelconnect')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $review_id) {
					$reviews = Mage::getModel('webwinkelconnect/reviews')->load($review_id);
					$reviews->setSidebar(1)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('webwinkelconnect')->__('Total of %d review(s) were added to the sidebar.', count($reviewIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }  

    public function massDisableSidebarAction() 
    {    
        $reviewIds = $this->getRequest()->getParam('reviewids');
        if(!is_array($reviewIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webwinkelconnect')->__('Please select item(s)'));
        } else {
            try {
                foreach ($reviewIds as $review_id) {
					$reviews = Mage::getModel('webwinkelconnect/reviews')->load($review_id);
					$reviews->setSidebar(0)->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('webwinkelconnect')->__('Total of %d review(s) were removed from the sidebar.', count($reviewIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    } 
    
	public function truncateAction() 
	{     
		$i = 0;
		$collection = Mage::getModel('webwinkelconnect/reviews')->getCollection();
		foreach ($collection as $item) {
			$item->delete();
			$i++;
		}
		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('webwinkelconnect')->__('Succefully deleted all %s saved review(s).', $i));
        $this->_redirect('*/*/index');	
	}

	protected function _isAllowed() 
	{
        return Mage::getSingleton('admin/session')->isAllowed('admin/webwinkelconnect/webwinkelconnect_log');
    }
    
}