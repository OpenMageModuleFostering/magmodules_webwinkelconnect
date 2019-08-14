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
 
class Magmodules_Webwinkelkeur_Block_Adminhtml_Webwinkelreviews_Grid extends Mage_Adminhtml_Block_Widget_Grid {

	public function __construct() {
		parent::__construct();
		$this->setId('reviewsGrid');
		$this->setDefaultSort('date');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection() {
		$collection = Mage::getModel('webwinkelkeur/reviews')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns() {
  
		$this->addColumn('company', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Shop'),
			'index'     => 'company',
			'width'	  	=> '120px',
		));
		
		$this->addColumn('name', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Name'),
			'align'     => 'left',
			'index'     => 'name',
		));

		$this->addColumn('experience', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Experience'),
			'align'     => 'left',
			'index'     => 'experience',
			'renderer'  => 'webwinkelkeur/adminhtml_webwinkelreviews_renderer_experience',	           			
		));

		$this->addColumn('rating', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Rating'),
			'align'     => 'left',
			'index'     => 'rating',
			'renderer'  => 'webwinkelkeur/adminhtml_widget_grid_stars',
			'width'	  	=> '90',
			'filter'    => false,		  
			'sortable'  => true,
		));

		$this->addColumn('delivery_time', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Delivery Time'),
			'align'     => 'left',
			'index'     => 'delivery_time',
			'renderer'  => 'webwinkelkeur/adminhtml_widget_grid_stars',
			'width'	  	=> '90',
			'filter'    => false,		  
			'sortable'  => true,
		));

		$this->addColumn('userfriendlyness', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Userfriendlyness'),
			'align'     => 'left',
			'index'     => 'userfriendlyness',
			'renderer'  => 'webwinkelkeur/adminhtml_widget_grid_stars',
			'width'	  	=> '90',
			'filter'    => false,		  
			'sortable'  => true,
		));

		$this->addColumn('price_quality', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Price / Quality'),
			'align'     => 'left',
			'index'     => 'price_quality',
			'renderer'  => 'webwinkelkeur/adminhtml_widget_grid_stars',
			'width'	  	=> '90',
			'filter'    => false,		  
			'sortable'  => true,
		));

		$this->addColumn('aftersales', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Aftersales'),
			'align'     => 'left',
			'index'     => 'aftersales',
			'renderer'  => 'webwinkelkeur/adminhtml_widget_grid_stars',
			'width'	  	=> '90',
			'filter'    => false,		  
			'sortable'  => true,
		));
		
		$this->addColumn('date', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Date'),
			'align'     => 'left',
			'type'      => 'date',
			'index'     => 'date',
			'width'	  	=> '140',
		));

		$this->addColumn('sidebar', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Sidebar'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'sidebar',
			'type'      => 'options',
			'options'   => array(
					  0 => Mage::helper('webwinkelkeur')->__('No'),
					  1 => Mage::helper('webwinkelkeur')->__('Yes'),
			),
		));

		$this->addColumn('status', array(
			'header'    => Mage::helper('webwinkelkeur')->__('Active'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => array(
					  0 => Mage::helper('webwinkelkeur')->__('No'),
					  1 => Mage::helper('webwinkelkeur')->__('Yes'),
			),
		));        
							  
		return parent::_prepareColumns();
	}

	protected function _prepareMassaction() {
		$this->setMassactionIdField('review_id');
		$this->getMassactionBlock()->setFormFieldName('reviewids');

		$this->getMassactionBlock()->addItem('hide', array(
			 'label'    => Mage::helper('webwinkelkeur')->__('Set to invisible'),
			 'url'      => $this->getUrl('*/*/massDisable'),
		));
		$this->getMassactionBlock()->addItem('visible', array(
			 'label'    => Mage::helper('webwinkelkeur')->__('Set to visible'),
			 'url'      => $this->getUrl('*/*/massEnable'),
		));
		$this->getMassactionBlock()->addItem('addsidebar', array(
			 'label'    => Mage::helper('webwinkelkeur')->__('Add to Sidebar'),
			 'url'      => $this->getUrl('*/*/massEnableSidebar'),
		));
		$this->getMassactionBlock()->addItem('removesidebar', array(
			 'label'    => Mage::helper('webwinkelkeur')->__('Remove from Sidebar'),
			 'url'      => $this->getUrl('*/*/massDisableSidebar'),
		));       
		return $this;
	}

	public function getRowUrl($row) {
		return false;
	}

}