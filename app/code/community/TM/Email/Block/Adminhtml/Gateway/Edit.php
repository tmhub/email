<?php

class TM_Email_Block_Adminhtml_Gateway_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'tm_email';
        $this->_controller = 'adminhtml_gateway';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('tm_email')->__('Save Gateway'));
        $this->_updateButton('delete', 'label', Mage::helper('tm_email')->__('Delete Gateway'));

        $this->_addButton('test', array(
            'label'     => Mage::helper('tm_email')->__('Test Email Gateway Connection'),
            'onclick'   => "$('edit_form').action = '" . $this->getUrl('*/*/test') . "'; editForm.submit();",
            //'onclick'   => "$('edit_form').action = '" . $this->getUrl('*/*/test', array('back' => 'edit')) . "'; editForm.submit();",
            'class'     => 'save',
        ), -100);

    }

    public function getHeaderText()
    {
        if(Mage::registry('tm_email_gateway_data')
            && Mage::registry('tm_email_gateway_data')->getId()) {

            return Mage::helper('tm_email')->__(
                "Edit Email Gateway '%s'",
                $this->htmlEscape(Mage::registry('tm_email_gateway_data')->getName())
            );

        } else {
            return Mage::helper('tm_email')->__('Add New Email Gateway');
        }
    }
}