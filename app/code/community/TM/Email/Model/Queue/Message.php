<?php
class TM_Email_Model_Queue_Message extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('tm_email/queue_message');
    }
}