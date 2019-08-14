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
 
class Magmodules_Webwinkelkeur_Model_Log extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('webwinkelkeur/log');
    }

	public function addToLog($type, $storeid, $review = '', $inivation = '', $time, $crontype = '', $api_url = '', $orderid = '') {
		
		if(Mage::getStoreConfig('webwinkelkeur/log/enabled')) {

			$api_id			= Mage::getStoreConfig('webwinkelkeur/general/api_id', $storeid);
			$company		= Mage::getStoreConfig('webwinkelkeur/general/company', $storeid);
			$review_updates	= '';
			$review_new 	= '';			

			if($review) {				
				$company		= $review['company'];
				$review_updates	= $review['review_updates'];
				$review_new 	= $review['review_new'];						
			}
			
			$model = Mage::getModel('webwinkelkeur/log');		
			$model->setType($type)
				  ->setShopId($api_id)
				  ->setCompany($company)
				  ->setReviewUpdate($review_updates)
				  ->setReviewNew($review_new)			  
				  ->setResponse($inivation)
				  ->setOrderId($orderid)
				  ->setCron($crontype)
				  ->setDate(now())
				  ->setTime($time)
				  ->setApiUrl($api_url)
				  ->save();			
		}
		
		return;		
	}
	
}