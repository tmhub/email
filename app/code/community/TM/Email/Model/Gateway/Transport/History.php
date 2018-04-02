<?php
class TM_Email_Model_Gateway_Transport_History extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('tm_email/gateway_transport_history');
    }

    protected function _beforeSave()
    {
        $createdAt = $this->getCreatedAt();
        if (empty($createdAt)) {
            $this->setCreatedAt(now());
        }

        return parent::_beforeSave();
    }
}
