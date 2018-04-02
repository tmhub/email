<?php
class TM_Email_Block_Adminhtml_Gateway_Transport_History extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_gateway_transport_history';
        $this->_blockGroup = 'tm_email';
        $this->_headerText = Mage::helper('tm_email')->__('History');
        parent::__construct();
        $this->_removeButton('add');
    }
}
