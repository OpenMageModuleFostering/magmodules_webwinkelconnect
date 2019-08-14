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
 
class Magmodules_Webwinkelconnect_Block_Sidebar extends Mage_Core_Block_Template {

    function getSidebarCollection($sidebar) 
    {		
		$enabled = '';  
		$qty = '5';
		if(Mage::getStoreConfig('webwinkelconnect/general/enabled')) {
			if($sidebar == 'left') {
				$qty = Mage::getStoreConfig('webwinkelconnect/sidebar/left_qty');
				$enabled = Mage::getStoreConfig('webwinkelconnect/sidebar/left');
			}
			if($sidebar == 'right') {
				$qty = Mage::getStoreConfig('webwinkelconnect/sidebar/right_qty');
				$enabled = Mage::getStoreConfig('webwinkelconnect/sidebar/right');
			}			
		}
		if($enabled) {
			$shop_id = Mage::getStoreConfig('webwinkelconnect/general/api_id');		
			$collection = Mage::getModel("webwinkelconnect/reviews")->getCollection();
			$collection->setOrder('date', 'DESC');
			$collection->addFieldToFilter('status',1);
			$collection->addFieldToFilter('sidebar',1);			
			$collection->addFieldToFilter('shop_id', array('eq'=> array($shop_id)));			
			$collection->setPageSize($qty);
			$collection->load();			
			return $collection;
		} else {
			return false;
		}
    }
    
    function formatContent($sidebarreview, $sidebar = 'left')
    {    	
		$content = $sidebarreview->getExperience();
		if($sidebar == 'left') {
			$char_limit = Mage::getStoreConfig('webwinkelconnect/sidebar/left_lenght');
		}
		if($sidebar == 'right') {
			$char_limit = Mage::getStoreConfig('webwinkelconnect/sidebar/right_lenght');
		}	
		$content = Mage::helper('core/string')->truncate($content, $char_limit, ' ...', $_remainder, false);    
    	return $content;

	}

    function getReviewsUrl($sidebar = 'left') 
    {    	
		if($sidebar == 'left') {
			$link = Mage::getStoreConfig('webwinkelconnect/sidebar/left_link');
		}
		if($sidebar == 'right') {
			$link = Mage::getStoreConfig('webwinkelconnect/sidebar/left_right');
		}
		if($link == 'internal') {
			$url = $this->getUrl('webwinkelconnect');
			$class = '';
		}
		if($link == 'external') {
			$url = Mage::getStoreConfig('webwinkelconnect/general/url');
			$class = 'webwinkelkeurReviews';
		}
		if($url) {
			return '<a href="' . $url . '" class="' . $class .'">' . $this->__('View all reviews') . '</a>';
		} else {
			return false;
		}		
	}

    function getSnippetsEnabled($sidebar = 'left') 
    {    	
		if($sidebar == 'left') {
			return Mage::getStoreConfig('webwinkelconnect/sidebar/left_snippets');
		}
		if($sidebar == 'right') {
			return Mage::getStoreConfig('webwinkelconnect/sidebar/right_snippets');
		}
	}

	public function getTotalScore() 
	{
		 return $this->helper('webwinkelconnect')->getTotalScore();
    }
	
}	