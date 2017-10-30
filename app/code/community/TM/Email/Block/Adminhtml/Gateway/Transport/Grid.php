<?php

class TM_Email_Block_Adminhtml_Gateway_Transport_Grid extends TM_Email_Block_Adminhtml_Gateway_AbstractGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tm_email_gateway_transport_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');

        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tm_email/gateway_transport')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $types = TM_Email_Model_System_Config_Source_Gateway_Transport_Provider::toOptionHash();
        $this->addColumnAfter('type', array(
            'header'    => Mage::helper('tm_email')->__('Type'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'type',
            'type'      => 'options',
            'options'   => $types
        ), 'status');

        return parent::_prepareColumns();
        // return $return;
    }
}
