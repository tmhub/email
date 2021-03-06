<?php
class TM_Email_Model_Queue //extends Zend_Queue
{
    /**
     *
     * @var string
     */
    protected $_name = null;

    /**
     *
     * @var Zend_Queue
     */
    protected $_queue = null;

    /**
     *
     * @var array
     */
    protected $_registry = array();

    /**
     *
     * @param string $name
     * @return \TM_Email_Model_Queue
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return Zend_Queue
     */
    public function getQueue($name = null)
    {
        if (!empty($name)) {
            $this->setName($name);
        }

        if (!isset($this->_registry[$this->_name])) {
            $options = array(
                Zend_Queue::NAME => $this->_name
            );

            $queue = new Zend_Queue(null, $options);
            $adapter = new TM_Email_Model_Queue_Adapter_Db($options);
            $queue->setAdapter($adapter);
//            $queue->setMessageClass(); // default Zend_Queue_Message

            $this->_registry[$this->_name] = $queue;
        }
        return $this->_registry[$this->_name];
    }

    /**
     *
     * @param Zend_Mail $message
     * @param Zend_Mail_Transport_Abstract $transport
     * @return \TM_Email_Model_Queue
     */
    public function send(Zend_Mail $message, Zend_Mail_Transport_Abstract $transport = null)
    {
        if (empty($transport)) {
            $transport = Zend_Mail::getDefaultTransport();
        }
        $message = serialize(array($message, $transport));
        $this->getQueue()->send($message);
        return $this;
    }

    /**
     *
     * @param  integer $maxMessages
     * @param  integer $timeout
     * @return boolean
     */
    public function receive($maxMessages = null, $timeout = null)
    {
        $status = false;
        try {
            $queue = $this->getQueue();
            /* @var $message Zend_Queue_Message */
            foreach ($queue->receive($maxMessages, $timeout) as $i => $message) {
                /* @var $mail Zend_Mail */
                /* @var $transport Zend_Mail_Transport_Abstract */
                //send the email here
                list($mail, $transport) = unserialize($message->body);
                try {
                    $body = $mail->getBodyText() . $mail->getBodyHtml();
                    $mail->send($transport);
                    $this->addHistoryEntry($mail, $body);
                    $status = true;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $status = false;
                }
                if ($status) {
                    $adapter = $queue->getAdapter();
                    if ($adapter) {
                        $adapter->setMessageStatus(
                            $message,
                            TM_Email_Model_Queue_Message_Status::SUCCESS
                        );
                    }
                }
            }
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }

        return $status;
    }

    /**
     *
     * @param Zend_Mail $mail
     * @param string    $body
     */
    public function addHistoryEntry(Zend_Mail $mail, $body)
    {
        $enable = (bool) Mage::getStoreConfig(TM_Email_Model_Template::CONFIG_DEFAULT_HISTORY);

        if ($enable === false) {
            return;
        }
        $historyModel = Mage::getModel('tm_email/gateway_transport_history');

        $headers = $mail->getHeaders();
        $to = isset($headers["To"][0]) ? $headers["To"][0] : '';

        return $historyModel->setData(array(
            'to' => $to,
            'from' => $mail->getFrom(),
            'subject' => $mail->getSubject(),
            // 'template_id' => $this->getId(),
            'body' => $body,
        ))->save();
    }
}
