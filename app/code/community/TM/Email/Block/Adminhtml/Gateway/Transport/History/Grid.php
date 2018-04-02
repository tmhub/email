<?php

class TM_Email_Block_Adminhtml_Gateway_Transport_History_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tm_email_gateway_transport_history_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');

        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tm_email/gateway_transport_history')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('tm_email')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'id',
            'type'      => 'number'
        ));

        $this->addColumn('from', array(
            'header'    => Mage::helper('tm_email')->__('From'),
            'align'     => 'left',
            'index'     => 'from',
            'frame_callback' => array($this, 'decorateMime'),
        ));

        $this->addColumn('to', array(
            'header'    => Mage::helper('tm_email')->__('To'),
            'align'     => 'left',
            'index'     => 'to',
            'frame_callback' => array($this, 'decorateMime'),
        ));

        $this->addColumn('subject', array(
            'header'    => Mage::helper('tm_email')->__('Subject'),
            'align'     => 'left',
            'index'     => 'subject',
            'frame_callback' => array($this, 'decorateMime'),
        ));

        $this->addColumn('template_id', array(
            'header'    => Mage::helper('tm_email')->__('Template'),
            'align'     => 'left',
            'index'     => 'template_id',
        ));

        $this->addColumn('actions', array(
            'header'   => Mage::helper('tm_email')->__('Actions'),
            'width'    => '200px',
            'filter'   => false,
            'sortable' => false,
            'renderer' => 'tm_email/adminhtml_gateway_transport_history_grid_renderer_action'
        ));

        return parent::_prepareColumns();
        // return $return;
    }

    public function decorateMime($value, $row, $column, $isExport)
    {
        return iconv_mime_decode($value);
        // return imap_utf8($value);
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('tm_email');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'   => Mage::helper('tm_email')->__('Delete'),
            'url'     => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('tm_email')->__('Are you sure?')
        ));

        return $this;
    }
}
