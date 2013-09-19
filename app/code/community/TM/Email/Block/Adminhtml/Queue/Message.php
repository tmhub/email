<?php
class TM_Email_Block_Adminhtml_Queue_Message extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_queue_message';
        $this->_blockGroup = 'tm_email';
        $this->_headerText = Mage::helper('tm_email')->__('Message');
        $this->_addButtonLabel = Mage::helper('tm_email')->__('need remove');
        parent::__construct();
    }
}