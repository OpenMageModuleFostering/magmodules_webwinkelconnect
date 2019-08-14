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
 
class Magmodules_Webwinkelconnect_Model_Observer {
  		
    public function processStats() 
    {
		$storeids = Mage::getModel('webwinkelconnect/api')->getStoreIds();
		foreach($storeids as $storeid)  {		
			$enabled = Mage::getStoreConfig('webwinkelconnect/general/enabled', $storeid);
			$cron_enabled = Mage::getStoreConfig('webwinkelconnect/reviews/cron', $storeid);
			if($enabled && $cron_enabled) {
				$crontype = 'stats';
				$start_time = microtime(true);			
				$feed = Mage::getModel('webwinkelconnect/api')->getFeed($storeid, $crontype);							
				$resuls = array();
				$results['stats'] = Mage::getModel('webwinkelconnect/stats')->processFeed($feed, $storeid);	
				$results['company'] = $feed->company;
				$log = Mage::getModel('webwinkelconnect/log')->addToLog('reviews', $storeid, $results, '', (microtime(true) - $start_time), $crontype);
			}
		}	
    }

    public function processReviews() 
    {
		$storeids = Mage::getModel('webwinkelconnect/api')->getStoreIds();
		foreach($storeids as $storeid)  {				
			$enabled = Mage::getStoreConfig('webwinkelconnect/general/enabled', $storeid);
			$cron_enabled = Mage::getStoreConfig('webwinkelconnect/reviews/cron', $storeid);
			if($enabled && $cron_enabled) {
				$crontype = 'reviews';
				$start_time = microtime(true);			
				$feed = Mage::getModel('webwinkelconnect/api')->getFeed($storeid, $crontype);							
				$results = Mage::getModel('webwinkelconnect/reviews')->processFeed($feed, $storeid, $crontype);
				$results['stats'] = Mage::getModel('webwinkelconnect/stats')->processFeed($feed, $storeid);	
				$log = Mage::getModel('webwinkelconnect/log')->addToLog('reviews', $storeid, $results, '', (microtime(true) - $start_time), $crontype);
			}
		}
    }

    public function processHistory() 
    {
		$storeids = Mage::getModel('webwinkelconnect/api')->getStoreIds();
		foreach($storeids as $storeid)  {				
			$enabled = Mage::getStoreConfig('webwinkelconnect/general/enabled', $storeid);
			$cron_enabled = Mage::getStoreConfig('webwinkelconnect/reviews/cron', $storeid);
			if($enabled && $cron_enabled) {		
				$crontype = 'history';
				$start_time = microtime(true); $storeid = 0;				
				$feed = Mage::getModel('webwinkelconnect/api')->getFeed($storeid, $crontype);							
				$results = Mage::getModel('webwinkelconnect/reviews')->processFeed($feed, $storeid, $crontype);
				$results['stats'] = Mage::getModel('webwinkelconnect/stats')->processFeed($feed, $storeid);	
				$log = Mage::getModel('webwinkelconnect/log')->addToLog('reviews', $storeid, $results, '', (microtime(true) - $start_time), $crontype);
			}	
		}
    }	

    public function cleanLog() 
    {
		$enabled = Mage::getStoreConfig('webwinkelconnect/log/clean', 0);
		$days = Mage::getStoreConfig('webwinkelconnect/log/clean_days', 0);
		if(($enabled) && ($days > 0)) {					
			$logmodel = Mage::getModel('webwinkelconnect/log'); 									
			$deldate = date('Y-m-d', strtotime('-' . $days . ' days'));
			$logs = $logmodel->getCollection()->addFieldToSelect('id')->addFieldToFilter('date', array('lteq' => $deldate));
			foreach ($logs as $log) {
				$logmodel->load($log->getId())->delete();
			}			
		}	
    }	

    public function processInvitationcallAfterShipment($observer) 
    {
		$shipment = $observer->getEvent()->getShipment();
		$order    = $shipment->getOrder();
		if((Mage::getStoreConfig('webwinkelconnect/invitation/enabled', $order->getStoreId())) && (Mage::getStoreConfig('webwinkelconnect/general/api_key', $order->getStoreId()))) {
			if($order->getStatus() == Mage::getStoreConfig('webwinkelconnect/invitation/status', $order->getStoreId())) {
				if(Mage::getStoreConfig('webwinkelconnect/invitation/backlog', $order->getStoreId()) > 0) {
					$date_diff = floor(time() - strtotime($order->getCreatedAt()))/(60*60*24);
					if($date_diff < Mage::getStoreConfig('webwinkelconnect/invitation/backlog', $order->getStoreId())) {
						Mage::getModel('webwinkelconnect/api')->sendInvitation($order);
					}
				} else {
					Mage::getModel('webwinkelconnect/api')->sendInvitation($order);				
				}
			}
		}
    }

    public function processInvitationcall($observer) 
    {
        $order = $observer->getEvent()->getOrder();
		if((Mage::getStoreConfig('webwinkelconnect/invitation/enabled', $order->getStoreId())) && (Mage::getStoreConfig('webwinkelconnect/general/api_key', $order->getStoreId()))) {
			if($order->getStatus() == Mage::getStoreConfig('webwinkelconnect/invitation/status', $order->getStoreId())) {
				if(Mage::getStoreConfig('webwinkelconnect/invitation/backlog', $order->getStoreId()) > 0) {
					$date_diff = floor(time() - strtotime($order->getCreatedAt()))/(60*60*24);
					if($date_diff < Mage::getStoreConfig('webwinkelconnect/invitation/backlog', $order->getStoreId())) }
						$value = Mage::getModel('webwinkelconnect/api')->sendInvitation($order);
					}
				} else {
					Mage::getModel('webwinkelconnect/api')->sendInvitation($order);				
				}
			}
		}
    }    
    
}