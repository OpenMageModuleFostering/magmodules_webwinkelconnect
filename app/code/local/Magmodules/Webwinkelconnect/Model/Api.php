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
 
class Magmodules_Webwinkelconnect_Model_Api extends Mage_Core_Model_Abstract {

	public function processFeed($storeid = 0, $type) 
	{				
		if($feed = $this->getFeed($storeid, $type)) {
			$results = Mage::getModel('webwinkelconnect/reviews')->processFeed($feed, $storeid, $type);			
			$results['stats'] = Mage::getModel('webwinkelconnect/stats')->processFeed($feed, $storeid);	
			return $results;
		} else {
			return false;
		}	
	}

	public function getFeed($storeid, $type = '') 
	{
		$api_id	= trim(Mage::getStoreConfig('webwinkelconnect/general/api_id', $storeid));
		$api_key = trim(Mage::getStoreConfig('webwinkelconnect/general/api_key', $storeid));

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
				$error = file_get_contents($api_url);
				Mage::getSingleton('adminhtml/session')->addError(Mage::helper('webwinkelconnect')->__('%s, please check the online manual for suggestions.', $error));
				return false;
			}	
		} else {
			return false;
		}		
	}
	
	public function sendInvitation($order) 
	{
		$start_time = microtime(true);		
		$crontype = 'orderupdate';
		$order_id = $order->getIncrementId(); 
		$api_id = trim(Mage::getStoreConfig('webwinkelconnect/general/api_id', $order->getStoreId()));
		$api_key = trim(Mage::getStoreConfig('webwinkelconnect/general/api_key', $order->getStoreId()));
		$delay = trim(Mage::getStoreConfig('webwinkelconnect/invitation/delay', $order->getStoreId()));
		$language = Mage::getStoreConfig('webwinkelconnect/invitation/language', $order->getStoreId());
		$email = $order->getCustomerEmail();
		$customer_name = $order->getCustomerName();
		
		$url = 'https://www.webwinkelkeur.nl/api.php?id=' . $api_id . '&password=' . $api_key . '&email=' . urlencode($email) . '&order=' . $order_id . '&delay=' . $delay . '&client=magento&customername=' . urlencode($customer_name);

		if(!empty($language)) {
			if($language == 'cus') {
				$lan_array = array('NL' => 'nld', 'EN' => 'eng', 'DE' => 'deu', 'FR' => 'fra', 'ES' => 'spa');
				$address = $order->getShippingAddress();
				if(isset($lan_array[$address->getCountry()])) {
					$url = $url . '&language=' . $lan_array[$address->getCountry()];		
				}	
			} else {			
				$url = $url . '&language=' . $language;
			}	
		}	

		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);		
		$response = curl_exec($curl);
		curl_close($curl);
		
		if($response) {
			$response_html = $response;					
		} else {
			$response_html = 'No response from https://www.webwinkelkeur.nl';
		}

		$writelog = Mage::getModel('webwinkelconnect/log')->addToLog('invitation', $order->getStoreId(), '', $response_html, (microtime(true) - $start_time), $crontype, $url, $order->getId());
		return true;
    }


	public function getStoreIds()
	{
		$store_ids = array(); $api_ids = array();
		$stores = Mage::getModel('core/store')->getCollection();
		foreach ($stores as $store) {		
			if($store->getIsActive()) {
				$api_id	= Mage::getStoreConfig('webwinkelconnect/general/api_id', $store->getId());
				if(!in_array($api_id, $api_ids)) {
					$api_ids[] = $api_id; $store_ids[] = $store->getId();
				}		
			}
		}
		return $store_ids;	
	}
	    
}