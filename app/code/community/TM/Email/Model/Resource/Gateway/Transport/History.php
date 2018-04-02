<?php

class TM_Email_Model_Resource_Gateway_Transport_History extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // Note that the id refers to the key field in your database table.
        $this->_init('tm_email/gateway_transport_history', 'id');
    }
}
