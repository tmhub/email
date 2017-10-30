<?php

class TM_Email_Block_Adminhtml_Queue_Queue_Grid extends TM_Email_Block_Adminhtml_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tm_email_queue_grid');
        $this->setDefaultSort('queue_id');
        $this->setDefaultDir('DESC');

        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tm_email/queue_queue')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('queue_id', array(
            'header'    => Mage::helper('tm_email')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'queue_id',
            'type'      => 'number'
        ));

        $this->addColumn('queue_name', array(
            'header'    => Mage::helper('tm_email')->__('Name'),
            'align'     => 'left',
            'index'     => 'queue_name',
        ));


        $this->addColumn('timeout', array(
            'header'    => Mage::helper('tm_email')->__('Timeout'),
            'align'     => 'left',
            'index'     => 'timeout',
            'type'      => 'number'
        ));

        $this->addColumn('default_status', array(
            'header'  => Mage::helper('tm_email')->__('Default Status'),
            'align'   => 'left',
            'width'   => '80px',
            'index'   => 'default_status',
            'type'    => 'options',
            'options' => Mage::getSingleton('tm_email/queue_message_status')->toOptionHash()
        ));

        $this->addColumn('action', array(
            'header'    =>  Mage::helper('tm_email')->__('Action'),
            'width'     => '100',
            'type'      => 'action',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption'   => Mage::helper('tm_email')->__('Edit'),
                    'url'       => array('base'=> '*/*/edit'),
                    'field'     => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'index'     => 'stores',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }
}
