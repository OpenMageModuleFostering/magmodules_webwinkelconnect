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
 
class Magmodules_Webwinkelkeur_Block_Snippets extends Mage_Core_Block_Template {

    protected function _construct()
    {
		if(Mage::getStoreConfig('webwinkelkeur/general/enabled')) {
	        $this->setSnippetsEnabled(1);
    	} else {
	        $this->setSnippetsEnabled(0);
	    }     

        parent::_construct();                                   
        $this->setTemplate('magmodules/webwinkelkeur/widget/richsnippets.phtml');
    }
    
	public function getSnippets()
	{
		 return $this->helper('webwinkelkeur')->getTotalScore();
    }	 

	public function getHtmlStars($rating)
	{
		 return $this->helper('webwinkelkeur')->getHtmlStars($rating);
    }	 

	public function getExternalLink()
	{
		 return $this->helper('webwinkelkeur')->getExternalLink();
    }

}