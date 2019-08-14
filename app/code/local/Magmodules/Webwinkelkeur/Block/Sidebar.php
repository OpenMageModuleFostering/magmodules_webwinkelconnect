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
 
class Magmodules_Webwinkelkeur_Block_Sidebar extends Mage_Core_Block_Template {

    protected function _construct()  {
		parent::_construct();                                   
    }
    
    function getSidebarCollection($sidebar) {
		
		$enabled = ''; 
		$qty = '5';
				
		if($sidebar == 'left'):
			$qty = Mage::getStoreConfig('webwinkelkeur/sidebar/left_qty');
			$enabled = Mage::getStoreConfig('webwinkelkeur/sidebar/left');
		endif;
		if($sidebar == 'right'):
			$qty = Mage::getStoreConfig('webwinkelkeur/sidebar/right_qty');
			$enabled = Mage::getStoreConfig('webwinkelkeur/sidebar/right');
		endif;
			
		if($enabled):		
			$shop_id = Mage::getStoreConfig('webwinkelkeur/general/api_id');		
			$collection = Mage::getModel("webwinkelkeur/reviews")->getCollection();
			$collection->setOrder('date', 'DESC');
			$collection->addFieldToFilter('status',1);
			$collection->addFieldToFilter('sidebar',1);			
			$collection->addFieldToFilter('shop_id', array('eq'=> array($shop_id)));			
			$collection->setPageSize($qty);
			$collection->load();			
			return $collection;
		else:
			return false;
		endif;	
    }
    
    function formatContent($sidebarreview, $sidebar = 'left') {    	

		$content = $sidebarreview->getExperience();
		
		if($sidebar == 'left'):
			$char_limit = Mage::getStoreConfig('webwinkelkeur/sidebar/left_lenght');
		endif;
		if($sidebar == 'right'):
			$char_limit = Mage::getStoreConfig('webwinkelkeur/sidebar/right_lenght');
		endif;
	
		$content = Mage::helper('core/string')->truncate($content, $char_limit, ' ...', $_remainder, false);    
    	return $content;

	}

    function getReviewsUrl($sidebar = 'left') {    	

		if($sidebar == 'left'):
			$link = Mage::getStoreConfig('webwinkelkeur/sidebar/left_link');
		endif;
		if($sidebar == 'right'):
			$link = Mage::getStoreConfig('webwinkelkeur/sidebar/left_right');
		endif;

		if($link == 'internal'):
			$url = $this->getUrl('webwinkelkeur');
		endif;
		if($link == 'external'):
			$url = Mage::getStoreConfig('webwinkelkeur/general/url');
			$class = 'webwinkelkeurReviews';
		endif;
		
		if($url) {
			return '<a href="' . $url . '" class="' . $class .'">' . $this->__('View all reviews') . '</a>';
		} else {
			return false;
		}		

	}

    function getSnippetsEnabled($sidebar = 'left') {    	
		$enabled = Mage::getStoreConfig('webwinkelkeur/snippets/sidebar');
		$homepage = Mage::getBlockSingleton('page/html_header')->getIsHomePage();
		
		if($enabled && $homepage) {
			return true;
		} else {
			return false;
		}			
	}

	public function getTotalScore() {
		 return $this->helper('webwinkelkeur')->getTotalScore();
    }
	
}	