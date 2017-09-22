<?php
if (Mage::helper('core')->isModuleOutputEnabled('Aschroder_SMTPPro')) {
    Mage::helper('tmcore')->requireOnce('TM/Email/Model/Template/AschroderSMTPPro.php');
} else {
    class TM_Email_Model_Template_Abstract extends Mage_Core_Model_Email_Template
    {
        protected function _beforeSend($transport, $mail)
        {
        }
    }
}
