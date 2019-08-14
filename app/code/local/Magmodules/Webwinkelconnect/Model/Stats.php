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

class Magmodules_Webwinkelconnect_Model_Stats extends Mage_Core_Model_Abstract {

    public function _construct() 
    {
        parent::_construct();
        $this->_init('webwinkelconnect/stats');
    }

	public function processFeed($feed, $storeid = 0) 
	{
		$api_id	= Mage::getStoreConfig('webwinkelconnect/general/api_id', $storeid);				
		
		if($storeid == 0) {
			$config = new Mage_Core_Model_Config();
			$config->saveConfig('webwinkelconnect/general/url', $feed->link, 'default', $storeid);								
			$config->saveConfig('webwinkelconnect/general/company', $feed->company, 'default', $storeid);								
		} else {
			$config = new Mage_Core_Model_Config();
			$config->saveConfig('webwinkelconnect/general/url', $feed->link, 'stores', $storeid);								
			$config->saveConfig('webwinkelconnect/general/company', $feed->company, 'stores', $storeid);										
		}
		
		if($feed->votes > 0) {	
			$company = $feed->company;		
			$average = floatval($feed->average);
			$average_stars = floatval($feed->average_stars);
			$average = ($average * 10);
			$average_stars = ($average_stars * 10);
			$votes = $feed->votes;
			$percentage_positive = $feed->percentage_positive;
			$number_positive = $feed->number_positive;
			$percentage_neutral = $feed->percentage_neutral;
			$number_neutral = $feed->number_neutral;
			$percentage_negative = $feed->percentage_negative;
			$number_negative = $feed->number_negative;

			// Check for update or save
			if($indatabase = $this->loadbyApiId($api_id)) {
				$id = $indatabase->getId();
			} else {
				$id = '';
			}
			
			// Save Review Stats
			$model = Mage::getModel('webwinkelconnect/stats');		
			$model->setId($id)
				  ->setShopId($api_id)
				  ->setCompany($company)
				  ->setAverage($average)
				  ->setAverageStars($average_stars)
				  ->setVotes($votes)
				  ->setPercentagePositive($percentage_positive)
				  ->setNumberPositive($number_positive)
				  ->setPercentageNeutral($percentage_neutral)
				  ->setNumberNeutral($number_neutral)
				  ->setPercentageNegative($percentage_negative)
				  ->setNumberNegative($number_negative)
				  ->save();	  
			return true;
		} else {
			return false;
		}				
	}

	public function loadbyApiId($api_id) 
	{
        $this->_getResource()->load($this, $api_id, 'shop_id');
        return $this;
    } 	
	
}