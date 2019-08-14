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
 
class Magmodules_Webwinkelkeur_Block_Adminhtml_Webwinkellog_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	public function __construct() {
		parent::__construct();
		$this->setId('webwinkellogGrid');
		$this->setDefaultSort('date');
		$this->setDefaultDir('DESC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() {
		$collection = Mage::getModel('webwinkelkeur/log')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
  
		$this->addColumn('company', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Shop'),
			'index'     => 'company',
			'width'	  	=> '120px',
		));

		$this->addColumn('type', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Type'),
			'align'     => 'left',
			'index'     => 'type',
			'width'	  	=> '120',
			'type'      => 'options',
			'options'   => array(
					  'reviews' => Mage::helper('webwinkelkeur')->__('Reviews'),
					  'invitation' => Mage::helper('webwinkelkeur')->__('Invitation Call'),
			),					  
		));

		$this->addColumn('qty', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Description'),
			'align'     => 'left',
			'index'     => 'qty',
			'renderer'  => 'webwinkelkeur/adminhtml_widget_grid_log',	
			'filter'    => false,		  
			'sortable'  => false,					
		));

		$this->addColumn('cron', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Cron'),
			'align'     => 'left',
			'index'     => 'cron',
			'width'	  	=> '120',
			'type'      => 'options',
			'options'   => array(
					  '' => Mage::helper('webwinkelkeur')->__('Manual'),
					  'stats' => Mage::helper('webwinkelkeur')->__('Stats Cron'),
					  'reviews' => Mage::helper('webwinkelkeur')->__('Reviews Cron'),					  
					  'orderupdate' => Mage::helper('webwinkelkeur')->__('Invitation'),					  					  
			),					  
		));	

		$this->addColumn('time', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Time'),
			'align'     => 'left',
			'index'     => 'time',
			'width'	  	=> '60',			
			'renderer'  => 'webwinkelkeur/adminhtml_widget_grid_seconds',						
		));
		
		$this->addColumn('date', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Date'),
			'align'     => 'left',
			'type'      => 'datetime',
			'index'     => 'date',
			'width'	  	=> '140',
		));     	
					  
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction() {
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('logids');

		$this->getMassactionBlock()->addItem('hide', array(
			 'label'    => Mage::helper('webwinkelkeur')->__('Delete'),
			 'url'      => $this->getUrl('*/*/massDelete'),
		));      
		return $this;
	}

	public function getRowUrl($row)
	{
		return;
	}

}