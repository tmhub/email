<?php

class TM_Email_Model_Resource_Gateway_Transport extends TM_Email_Model_Resource_Gateway_AbstractGateway
{
    public function _construct()
    {
        // Note that the id refers to the key field in your database table.
        $this->_init('tm_email/gateway_transport', 'id');
    }
}
