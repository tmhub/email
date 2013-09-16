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

            $this->_registry[$this->_name] = $queue;
        }
        return $this->_registry[$this->_name];
    }


    public function send(Zend_Mail $message, Zend_Mail_Transport_Abstract $transport = null)
    {
        if (empty($transport)) {
            $transport = Zend_Mail::getDefaultTransport();
        }
        $message = serialize(array($message, $transport));
        $this->getQueue()->send($message);
        return $this;
    }


    public function receive($maxMessages = null, $timeout = null)
    {
        try {
            $queue = $this->getQueue();
            foreach ($queue->receive($maxMessages, $timeout) as $i => $message) {
                //send the email here

                list($mail, $transport) = unserialize($message->body);
                $mail->send($transport);

                $queue->deleteMessage($message);
            }
        } catch (Exception $e) {
            Mage::logException($e);
            return false;
        }

        return true;
    }
}