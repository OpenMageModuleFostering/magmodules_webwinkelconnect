<?php
/**
 * Magmodules.eu - http://www.magmodules.eu - info@magmodules.eu
 * =============================================================
 * NOTICE OF LICENSE [Single domain license]
 * This source file is subject to the EULA that is
 * available through the world-wide-web at:
 * http://www.magmodules.eu/license-agreement/
 * =============================================================
 * @category    Magmodules
 * @package     Magmodules_Shopreview
 * @author      Magmodules <info@magmodules.eu>
 * @copyright   Copyright (c) 2013 (http://www.magmodules.eu)
 * @license     http://www.magmodules.eu/license-agreement/  
 * =============================================================
 */

class Magmodules_Webwinkelkeur_Block_Adminhtml_Widget_Grid_Log extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action {

	public function render(Varien_Object $row) {
		$type = $row->getType();
		$msg = '';
		
		if($type == 'reviews') {
			$updates = '';
			if($row->getReviewNew() > 0) {			
				$msg .= Mage::helper('webwinkelkeur')->__('%s new review(s)', $row->getReviewNew()); 
				$updates++;
			}
			if($row->getReviewUpdate() > 0) {				
				if($updates > 0) {
					$msg .= ', ';
				}						
				$msg .= Mage::helper('webwinkelkeur')->__('%s review(s) updated', $row->getReviewUpdate()); 
				$updates++;
			}				
			if($updates > 0) {
				$msg .= ' & ';
			}	
			$msg .= Mage::helper('webwinkelkeur')->__('total score updated.');			
		}

		if($type == 'invitation') {
			if($row->getOrderId()) {
				$order = Mage::getModel('sales/order')->load($row->getOrderId());
			    $increment_id = $order->getIncrementId();   
			    $order_url = Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array('order_id'=> $row->getOrderId()));			    
				$msg = Mage::helper('webwinkelkeur')->__('%s - Repsonse: %s', '<a href="' . $order_url . '">#' . $increment_id .'</a>', $row->getResponse());			
			}	
		}
													
		return ucfirst($msg);
	}

}