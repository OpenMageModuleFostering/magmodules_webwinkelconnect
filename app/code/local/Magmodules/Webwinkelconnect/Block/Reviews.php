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
 
class Magmodules_Webwinkelconnect_Block_Reviews extends Mage_Core_Block_Template {

	public function __construct() 
	{
		parent::__construct();
		$collection = Mage::getModel('webwinkelconnect/reviews')->getCollection();
		$collection->setOrder('date', 'DESC');
		$collection->addFieldToFilter('status',1);
		$collection->addFieldToFilter('shop_id', Mage::getStoreConfig('webwinkelconnect/general/api_id'));		
		$this->setReviews($collection);			
		$stats = Mage::getModel('webwinkelconnect/stats')->load(Mage::getStoreConfig('webwinkelconnect/general/api_id'), 'shop_id');
		$this->setStats($stats);				
	}	
	
	public function _prepareLayout() 
	{
		parent::_prepareLayout();
		$pager = $this->getLayout()->createBlock('page/html_pager', 'webwinkelconnect.pager');
        if(Mage::getStoreConfig('webwinkelconnect/overview/enable_paging')) {
            $fieldPerPage = Mage::getStoreConfig('webwinkelconnect/overview/paging_settings');
            $fieldPerPage = explode(',', $fieldPerPage);
            $fieldPerPage = array_combine($fieldPerPage, $fieldPerPage);
            $pager->setAvailableLimit($fieldPerPage);
        } else {
            $pager->setAvailableLimit(array('all'=>'all'));
        }
		$pager->setCollection($this->getReviews());
		$this->setChild('pager', $pager);
		$this->getReviews()->load();
		return $this;
    }
    	
	public function getPagerHtml() 
	{
		return $this->getChildHtml('pager');
	}
	
	public function getFormUrl()
	{
		return $this->helper('webwinkelconnect')->getFormUrl();
	}
	
}