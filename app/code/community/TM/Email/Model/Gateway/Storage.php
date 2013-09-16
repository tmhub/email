<?php
class TM_Email_Model_Gateway_Storage extends TM_Email_Model_Gateway
{
    public function getOptionArray()
    {
        return Mage::getModel('tm_email/gateway')->getOptionArray(array(
            TM_Email_Model_Gateway::TYPE_POP3,
            TM_Email_Model_Gateway::TYPE_IMAP
        ));
    }
}