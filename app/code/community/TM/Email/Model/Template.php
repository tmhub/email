<?php
/**
 * Template model
 *
 * Example:
 *
 * // Loading of template
 * $emailTemplate  = Mage::getModel('core/email_template')
 *    ->load(Mage::getStoreConfig('path_to_email_template_id_config'));
 * $variables = array(
 *    'someObject' => Mage::getSingleton('some_model')
 *    'someString' => 'Some string value'
 * );
 * $emailTemplate->send('some@domain.com', 'Name Of User', $variables);
 *
 * @method Mage_Core_Model_Resource_Email_Template _getResource()
 * @method Mage_Core_Model_Resource_Email_Template getResource()
 * @method string getTemplateCode()
 * @method Mage_Core_Model_Email_Template setTemplateCode(string $value)
 * @method string getTemplateText()
 * @method Mage_Core_Model_Email_Template setTemplateText(string $value)
 * @method string getTemplateStyles()
 * @method Mage_Core_Model_Email_Template setTemplateStyles(string $value)
 * @method int getTemplateType()
 * @method Mage_Core_Model_Email_Template setTemplateType(int $value)
 * @method string getTemplateSubject()
 * @method Mage_Core_Model_Email_Template setTemplateSubject(string $value)
 * @method string getTemplateSenderName()
 * @method Mage_Core_Model_Email_Template setTemplateSenderName(string $value)
 * @method string getTemplateSenderEmail()
 * @method Mage_Core_Model_Email_Template setTemplateSenderEmail(string $value)
 * @method string getAddedAt()
 * @method Mage_Core_Model_Email_Template setAddedAt(string $value)
 * @method string getModifiedAt()
 * @method Mage_Core_Model_Email_Template setModifiedAt(string $value)
 * @method string getOrigTemplateCode()
 * @method Mage_Core_Model_Email_Template setOrigTemplateCode(string $value)
 * @method string getOrigTemplateVariables()
 * @method Mage_Core_Model_Email_Template setOrigTemplateVariables(string $value)
 *
 */
class TM_Email_Model_Template extends Mage_Core_Model_Email_Template
{
    /**
     *
     * @var string
     */
    protected $_queueName = null;

    /**
     *
     * @param string $name
     * @return \TM_Email_Model_Template
     */
    public function setQueueName($name)
    {
        $this->_queueName = $name;
        return $this;
    }

//    /**
//     *
//     * @param string $name
//     * @return Zend_Queue
//     */
//    protected function _getQueue()
//    {
//        $options = array(
//            Zend_Queue::NAME => $this->_queueName
//        );
//
//        $queue = new Zend_Queue(null, $options);
//        $adapter = new TM_Email_Model_Queue_Adapter_Db($options);
//        $queue->setAdapter($adapter);
//
//        return $queue;
//    }

    /**
     *
     * @param Zend_Mail_Transport_Abstract $transport
     * @return \TM_Email_Model_Template
     */
    public function setTransport(Zend_Mail_Transport_Abstract $transport)
    {
        Zend_Mail::setDefaultTransport($transport);
        return $this;
    }

    /**
     * Send mail to recipient
     *
     * @param   array|string       $email        E-mail(s)
     * @param   array|string|null  $name         receiver name(s)
     * @param   array              $variables    template variables
     * @return  boolean
     **/
    public function send($email, $name = null, array $variables = array())
    {
        if (!$this->isValidForSend()) {
            Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
            return false;
        }

        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        $transport = Zend_Mail::getDefaultTransport();
        if (!$transport instanceof Zend_Mail_Transport_Abstract) {
            ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
            ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));

            $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
            switch ($setReturnPath) {
                case 1:
                    $returnPathEmail = $this->getSenderEmail();
                    break;
                case 2:
                    $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                    break;
                default:
                    $returnPathEmail = null;
                    break;
            }

            if ($returnPathEmail !== null) {
                $transport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
//                Zend_Mail::setDefaultTransport($mailTransport);
            } else {
                $transport = new Zend_Mail_Transport_Sendmail();
            }
        }

        $mail = $this->getMail();

        foreach ($emails as $key => $email) {
            $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
        }

        $this->setUseAbsoluteLinks(true);
        $variables['boundary'] = $boundary = strtoupper(uniqid('--boundary_'));//'--BOUNDARY_TEXT_OF_CHOICE';
        $text = $this->getProcessedTemplate($variables, true);

        if($this->isPlain()) {
            $mail->setBodyText($text);
        } elseif(strpos($text, $boundary)) {
            $_text = substr($text, 0, strpos($text, $boundary));
            $_html = str_replace($boundary, '', substr($text, strpos($text, $boundary)));

            $mail->setBodyText($_text);
            $mail->setBodyHTML($_html);
        } else {
            $mail->setBodyHTML($text);
        }

        $mail->setSubject('=?utf-8?B?' . base64_encode($this->getProcessedTemplateSubject($variables)) . '?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

        try {
            if (empty($this->_queueName)) {
                $mail->send($transport);
            } else {
                Mage::getModel('tm_email/queue')
                    ->setName($this->_queueName)
                    ->send($mail, $transport)
                ;
            }
            $this->_mail = null;
        }
        catch (Exception $e) {
            $this->_mail = null;
            Mage::logException($e);
            return false;
        }

        return true;
    }
}
