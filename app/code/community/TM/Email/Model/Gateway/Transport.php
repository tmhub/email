<?php
class TM_Email_Model_Gateway_Transport extends TM_Email_Model_Gateway
{
    public function getOptionArray()
    {
        return Mage::getModel('tm_email/gateway')->getOptionArray(
            TM_Email_Model_Gateway::TYPE_SMTP
        );
    }
}