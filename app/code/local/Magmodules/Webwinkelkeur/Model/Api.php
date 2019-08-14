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
 
class Magmodules_Webwinkelkeur_Model_Api extends Mage_Core_Model_Abstract {

	public function processFeed($storeid = 0, $type) {				
		if($feed = $this->getFeed($storeid, $type)) {
			$results		 	= Mage::getModel('webwinkelkeur/reviews')->processFeed($feed, $storeid, $type);			
			$results['stats'] 	= Mage::getModel('webwinkelkeur/stats')->processFeed($feed, $storeid);	
			return $results;
		} else {
			return false;
		}	
	}

	public function getFeed($storeid, $type = '') {
		$api_id			= Mage::getStoreConfig('webwinkelkeur/general/api_id', $storeid);
		$api_key		= Mage::getStoreConfig('webwinkelkeur/general/api_key', $storeid);

		if($type != 'stats') {		
			$api_url = 'https://www.webwinkelkeur.nl/apistatistics.php?id=' . $api_id . '&password=' . $api_key . '&showall=1';
		} else {
			$api_url = 'https://www.webwinkelkeur.nl/apistatistics.php?id=' . $api_id . '&password=' . $api_key;		
		}
			
		if($api_id && $api_key) {
			$xml = simplexml_load_file($api_url);	
			if($xml) {
				return $xml;
			} else {
				return false;
			}	
		} else {
			return false;
		}		
	}
	
	public function sendInvitation($order) {
		$start_time = microtime(true);		
		$crontype 	= 'orderupdate';
		$order_id 	= $order->getIncrementId(); 
		$api_id 	= Mage::getStoreConfig('webwinkelkeur/general/api_id', $order->getStoreId());
		$api_key 	= Mage::getStoreConfig('webwinkelkeur/general/api_key', $order->getStoreId());
		$delay		= Mage::getStoreConfig('webwinkelkeur/invitation/delay', $order->getStoreId());
		$email		= $order->getCustomerEmail();
		$api_url 	= 'https://www.webwinkelkeur.nl/api.php?id=' . $api_id . '&password=' . $api_key . '&email=' . $email . '&order=' . $order_id . '&delay=' . $delay;
		
		// Connect to API
		$winkelconnect = curl_init($api_url);
		curl_setopt($winkelconnect, CURLOPT_VERBOSE, 1);
		curl_setopt($winkelconnect, CURLOPT_FAILONERROR, false);
		curl_setopt($winkelconnect, CURLOPT_HEADER, 0);
		curl_setopt($winkelconnect, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($winkelconnect, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($winkelconnect, CURLOPT_SSL_VERIFYPEER, false);		
		$repsonse = curl_exec($winkelconnect);
		curl_close($winkelconnect);
		
		// Write to log
		$writelog = Mage::getModel('webwinkelkeur/log')->addToLog('invitation', $order->getStoreId(), '', $repsonse, (microtime(true) - $start_time), $crontype, $api_url, $order->getId());
		return true;
    }

	public function getStoreIds() {
		$resource = Mage::getSingleton('core/resource');
		$read = $resource->getConnection('core_read');
		$query = "SELECT DISTINCT value, scope_id FROM " . $resource->getTableName('core/config_data') . " WHERE path LIKE 'webwinkelkeur/general/api_id'";
		$results = $read->fetchAll($query);
		$storeids = array(); 
		
		foreach($results as $result)  {
			if($result['value'] > 0) {
				$storeids[] = $result['scope_id'];
			}
		}						
		return $storeids; 		
	}
	    
}