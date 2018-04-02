<?php

class TM_Email_Block_Adminhtml_Gateway_Transport_History_View extends Mage_Adminhtml_Block_Widget
{
    /**
     * Prepare html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        /** @var $template Mage_Core_Model_Email_Template */
//        $template = Mage::getModel('core/email_template');
        $id = (int)$this->getRequest()->getParam('id');

        $message = Mage::getModel('tm_email/gateway_transport_history')->load($id);

        if (!$message) {
            return;
        }
        //Zend_Debug::dump($message->getData());

        $body = $message->getBody();
        if (!$body) {
            return;
        }
        $body = quoted_printable_decode($body);

        return $body;
    }
}
