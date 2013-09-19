<?php

class TM_Email_Block_Adminhtml_Queue_Message_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('tm_email_queue_message_grid');
        $this->setDefaultSort('message_id');
        $this->setDefaultDir('DESC');

        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tm_email/queue_message')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('message_id', array(
          'header'    => Mage::helper('tm_email')->__('ID'),
          'align'     => 'right',
          'width'     => '50px',
          'index'     => 'message_id',
          'type'      => 'number'
        ));

        $queues = Mage::getModel('tm_email/queue_queue')->getOptionArray();
        $this->addColumn('queue_id', array(
          'header'    => Mage::helper('tm_email')->__('Queue'),
          'align'     => 'left',
          'index'     => 'queue_id',
          'type'      => 'options',
          'options'   => $queues
        ));

//        $this->addColumn('handle', array(
//          'header'    => Mage::helper('tm_email')->__('Handle'),
//          'align'     => 'left',
//          'index'     => 'handle',
//        ));

        $this->addColumn('subject', array(
            'header'    => Mage::helper('tm_email')->__('Subject'),
            'align'     => 'left',
            'index'     => 'body',
            'frame_callback' => array($this, 'decorateSubject')
        ));

        $this->addColumn('from', array(
            'header'    => Mage::helper('tm_email')->__('From'),
            'align'     => 'left',
            'index'     => 'body',
            'frame_callback' => array($this, 'decorateFrom')
        ));

        $this->addColumn('to', array(
            'header'    => Mage::helper('tm_email')->__('To'),
            'align'     => 'left',
            'index'     => 'body',
            'frame_callback' => array($this, 'decorateTo')
        ));

//        $this->addColumn('md5', array(
//          'header'    => Mage::helper('tm_email')->__('MD5'),
//          'align'     => 'left',
//          'index'     => 'md5',
//        ));
//
//        $this->addColumn('timeout', array(
//          'header'    => Mage::helper('tm_email')->__('Timeout'),
//          'align'     => 'left',
//          'index'     => 'timeout',
//          'type'      => 'number'
//        ));

        $this->addColumn('created', array(
          'header'    => Mage::helper('tm_email')->__('Created'),
          'align'     => 'left',
          'index'     => 'created',
          'type'      => 'datetime',
        ));

        $this->addColumn('actions', array(
            'header' => Mage::helper('adminnotification')->__('Actions'),
            'width' => '200px',
            'filter' => false,
            'sortable' => false,
            'renderer' => 'tm_email/adminhtml_queue_message_grid_renderer_action'
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('tm_email')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('tm_email')->__('XML'));

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

    /**
     * Decorate status column values
     *
     * @return string
     */
    public function decorateSubject($value, $row, $column, $isExport)
    {
        /* @var $mail Zend_Mail */
        $mail = $row->getMail();
        return Zend_Mime_Decode::decodeQuotedPrintable($mail->getSubject());
    }

    /**
     * Decorate status column values
     *
     * @return string
     */
    public function decorateFrom($value, $row, $column, $isExport)
    {
        /* @var $mail Zend_Mail */
        $mail = $row->getMail();

        return $mail->getFrom();

//        $headers = $mail->getHeaders();
//        $subject = Zend_Mime_Decode::decodeQuotedPrintable($mail->getSubject());
//        $to = Zend_Mime_Decode::decodeQuotedPrintable($headers["To"][0]);
//        $return = "From : {$mail->getFrom()}<br/>"
//            . "Subject : {$subject}<br/>"
//            . "To : {$to}<br/>";
//        return $return;// . substr($value, 0, 100);
    }

    /**
     * Decorate status column values
     *
     * @return string
     */
    public function decorateTo($value, $row, $column, $isExport)
    {
        /* @var $mail Zend_Mail */
        $mail = $row->getMail();

        $headers = $mail->getHeaders();
        $to = isset($headers["To"][0]) ? $headers["To"][0] : '';
        return Zend_Mime_Decode::decodeQuotedPrintable($to);
    }

//    public function getRowUrl($row)
//    {
//        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
//    }
}