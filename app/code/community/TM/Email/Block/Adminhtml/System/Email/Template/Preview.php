<?php

class TM_Email_Block_Adminhtml_System_Email_Template_Preview extends Mage_Adminhtml_Block_Widget
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
        $message = Mage::getModel('tm_email/queue_message')->load($id);

        if (!$message) {
            return;
        }
        $mail = $message->getMail();
        if (!$mail) {
            return;
        }
//        Zend_Debug::dump($mail->getHeaderEncoding());
//        new Zend_Mime_Part();

        $part = $mail->getBodyHtml();
        if ($part == false || !$part instanceof Zend_Mime_Part) {
            $part = $mail->getBodyText();
        }
        $content = $part->getContent();
        if ('quoted-printable' == $mail->getHeaderEncoding()) {
            $content = quoted_printable_decode($content);
        }
        return $content;
    }
}
