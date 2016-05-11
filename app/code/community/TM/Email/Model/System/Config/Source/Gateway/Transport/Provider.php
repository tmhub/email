<?php

class TM_Email_Model_System_Config_Source_Gateway_Transport_Provider
{

    static public function toOptionHash()
    {
        return array(
            // TM_Email_Model_Gateway_Transport::TYPE_SENDMAIL => 'Sendmail',
            TM_Email_Model_Gateway_Transport::TYPE_SMTP => 'Smtp',
            // TM_Email_Model_Gateway_Transport::TYPE_GMAIL => 'Gmail',
        );
    }

    /**
     * Options getter
     *
     * @return array
     */
    static public function toOptionArray()
    {
        $return = array();
        $helper = Mage::helper('tm_email');
        foreach (self::toOptionHash() as $key => $value) {
            $return[] = array(
                'value' => $key,
                'label' => $helper->__($value)
            );
        }

        return $return;
    }
}
