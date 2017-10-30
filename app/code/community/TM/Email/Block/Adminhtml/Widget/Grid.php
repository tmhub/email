<?php

abstract class TM_Email_Block_Adminhtml_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected function _prepareColumns()
    {
        $this->addExportType('*/*/exportCsv', Mage::helper('tm_email')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tm_email')->__('XML'));

        return parent::_prepareColumns();
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

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
