<?php

class TM_Email_Block_Adminhtml_Gateway_Storage_Grid extends TM_Email_Block_Adminhtml_Gateway_AbstractGrid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tm_email_gateway_storage_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');

        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tm_email/gateway_storage')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumnAfter('type', array(
            'header'    => Mage::helper('tm_email')->__('Type'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'type',
            'type'      => 'options',
            'options'   => array(
                1     => Mage::helper('tm_email')->__('Pop3'),
                2     => Mage::helper('tm_email')->__('Imap')
            )
        ), 'status');

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('tm_email');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('tm_email')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('tm_email')->__('Are you sure?')
        ));

        return $this;
    }
}
