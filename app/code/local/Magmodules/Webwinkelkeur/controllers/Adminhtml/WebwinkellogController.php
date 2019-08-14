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
 * @package     Magmodules_Webwinkelkeur
 * @author      Magmodules <info@magmodules.eu)
 * @copyright   Copyright (c) 2014 (http://www.magmodules.eu)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
class Magmodules_Webwinkelkeur_Adminhtml_WebwinkellogController extends Mage_Adminhtml_Controller_Action {

	protected function _initAction() {
		$this->loadLayout()->_setActiveMenu('webwinkelkeur/webwinkelreviews')->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()->renderLayout();
	}

    public function massDeleteAction() {    
        $LogIds = $this->getRequest()->getParam('logids');
        if(!is_array($LogIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webwinkelkeur')->__('Please select item(s)'));
        } else {
            try {
                foreach ($LogIds as $id) {
					$log = Mage::getModel('webwinkelkeur/log')->load($id)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('webwinkelkeur')->__('Total of %d log record(s) deleted.', count($LogIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    } 
       
 	public function cleanAction() {
		$enabled = Mage::getStoreConfig('webwinkelkeur/log/clean');
		$days = Mage::getStoreConfig('webwinkelkeur/log/clean_days');
		$i = 0;
		if(($enabled) && ($days > 0)) {					
			$logmodel = Mage::getModel('webwinkelkeur/log'); 									
			$deldate = date('Y-m-d', strtotime('-' . $days . ' days'));
			$logs = $logmodel->getCollection()->addFieldToSelect('id')->addFieldToFilter('date', array('lteq' => $deldate));
			foreach ($logs as $log) {
				$logmodel->load($log->getId())->delete();
				$i++;
			}	
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('webwinkelkeur')->__('Total of %s log record(s) deleted.', $i));					
		}	
	$this->_redirect('*/*/index');
    }	
        
}