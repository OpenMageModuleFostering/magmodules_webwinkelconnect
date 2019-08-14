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
 * @copyright   Copyright (c) 2014 (http://www.magmodules.eu)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Magmodules_Webwinkelconnect_Model_Reviews extends Mage_Core_Model_Abstract {

    public function _construct() 
    {
        parent::_construct();
        $this->_init('webwinkelconnect/reviews');
    }

	public function loadbyHash($hash) 
	{
        $this->_getResource()->load($this, $hash, 'hash');
        return $this;
    } 
      
	public function processFeed($feed, $storeid = 0, $type) 
	{					
		$updates = 0; $new = 0; $history = 0;
		$api_id	= Mage::getStoreConfig('webwinkelconnect/general/api_id', $storeid);				
		$company = $feed->company;
		
		foreach($feed->reviews->review as $review) {
			$hash = $review->hash;														
			$name = $review->name;
			$experience = $review->experience;
			$date = $review->date;
			$rating = $review->rating;
			$delivery_time = $review->ratings->delivery_time;
			$userfriendlyness = $review->ratings->userfriendlyness;					
			$price_quality = $review->ratings->price_quality;										
			$aftersales = $review->ratings->aftersales;			
			$indatabase = $this->loadbyHash($hash);
			
			if($indatabase->getReviewId()) {
				if($type == 'history') {
					$reviews = Mage::getModel('webwinkelconnect/reviews');		
					$reviews->setReviewId($indatabase->getReviewId())
							->setShopId($api_id)
							->setCompany($company)							
							->setHash($hash)
							->setName($name)
							->setExperience($experience)
							->setDate($date)
							->setRating($rating)
							->setDeliveryTime($delivery_time)
							->setUserfriendlyness($userfriendlyness)
							->setPriceQuality($price_quality)
							->setAftersales($aftersales)
							->save();
					$updates++;					
				}	
			} else {
				$reviews = Mage::getModel('webwinkelconnect/reviews');		
				$reviews->setShopId($api_id)
						->setCompany($company)							
						->setHash($hash)
						->setName($name)
						->setExperience($experience)
						->setDate($date)
						->setRating($rating)
						->setDeliveryTime($delivery_time)
						->setUserfriendlyness($userfriendlyness)
						->setPriceQuality($price_quality)
						->setAftersales($aftersales)
					  	->save();
				$new++;	
			}
		}		
		
		$config = new Mage_Core_Model_Config();
		$config->saveConfig('webwinkelconnect/reviews/lastrun', now(), 'default', $storeid);										
		$result = array();
		$result['review_updates'] = $updates; 
		$result['review_new'] = $new; 		
		$result['company'] = $company;
		return $result; 
	}	

}