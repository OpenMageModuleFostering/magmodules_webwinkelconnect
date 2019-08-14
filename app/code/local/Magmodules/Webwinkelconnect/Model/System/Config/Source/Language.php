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

class Magmodules_Webwinkelconnect_Model_System_Config_Source_Language {

	public function toOptionArray() 
	{
		$language = array();
		$language[] = array('value' => '', 'label'=> Mage::helper('webwinkelconnect')->__('Use default'));
		$language[] = array('value' => 'cus', 'label'=> Mage::helper('webwinkelconnect')->__('Based on customer country'));
		$language[] = array('value' => 'nld', 'label'=> Mage::helper('webwinkelconnect')->__('Dutch'));
		$language[] = array('value' => 'eng', 'label'=> Mage::helper('webwinkelconnect')->__('English'));
		$language[] = array('value' => 'deu', 'label'=> Mage::helper('webwinkelconnect')->__('German'));
		$language[] = array('value' => 'fra', 'label'=> Mage::helper('webwinkelconnect')->__('French'));
		$language[] = array('value' => 'spa', 'label'=> Mage::helper('webwinkelconnect')->__('Spanish'));
		return $language;
	}
    
} 