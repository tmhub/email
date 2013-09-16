<?php
class TM_Email_Model_Observer
{
    /**
     *
     * @param Mage_Cron_Model_Schedule $schedule
     * @return TM_Email_Model_Observer
     */
    public function scheduledSend($schedule)
    {
        $collection = Mage::getModel('tm_email/queue_queue')->getCollection();
        foreach ($collection as $_queue) {
//            Zend_Debug::dump($_queue->getData());
            $queue = Mage::getModel('tm_email/queue')->getQueue(
                $_queue->getQueueName()
            );
            /* @var $message Zend_Queue_Message */
            foreach ($queue->receive() as $i => $message) {
                /* @var $mail Zend_Mail */
                /* @var $transport Zend_Mail_Transport_Abstract */
                //send the email here
                list($mail, $transport) = unserialize($message->body);
                try {
                    $mail->send($transport);
                    $status = true;
                } catch (Exception $e) {
                    Mage::logException($e);
                    $status = false;
                }
                if ($status) {
                    $queue->deleteMessage($message);
                }
            }

        }
        return $this;
    }
}
