<?php

class TM_Email_Model_System_Config_Source_Gateway_Transport_Auth
{

    static public function toOptionHash()
    {
        return array('login', 'plain', 'crammd5');
    }

    /**
     * Options getter
     *
     * @return array
     */
    static public function toOptionArray()
    {
        $return = array();
        foreach (self::toOptionHash() as $value) {
            $return[] = array(
                'value' => $value,
                'label' => ucfirst($value)
            );
        }

        return $return;
    }
}
