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
 
class Magmodules_Webwinkelconnect_Model_Log extends Mage_Core_Model_Abstract {

    public function _construct() 
    {
        parent::_construct();
        $this->_init('webwinkelconnect/log');
    }

	public function addToLog($type, $storeid, $review = '', $inivation = '', $time, $crontype = '', $api_url = '', $orderid = '') 
	{		
		if(Mage::getStoreConfig('webwinkelconnect/log/enabled')) {
			$api_id	= Mage::getStoreConfig('webwinkelconnect/general/api_id', $storeid);
			$company = Mage::getStoreConfig('webwinkelconnect/general/company', $storeid);
			$review_updates	= '';
			$review_new = '';			

			if($review) {				
				$company = $review['company'];
				if(!empty($review['review_updates'])) {
					$review_updates	= $review['review_updates'];
				} else {
					$review_updates	= 0;				
				}	
				if(!empty($review['review_new'])) {				
					$review_new = $review['review_new'];						
				} else {
					$review_new = 0;				
				}	
			}
			
			$model = Mage::getModel('webwinkelconnect/log');		
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
	}
	
}