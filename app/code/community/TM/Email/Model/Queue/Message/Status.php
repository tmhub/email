<?php
class TM_Email_Model_Queue_Message_Status
{
    const DISAPPROVED = 0;
    const APPROVED    = 1;
    const SUCCESS     = 2;
    const FAILURE     = 3;
    /**
    *
    * @return array
    */
    public function getOptionArray()
    {
        return array(
            self::DISAPPROVED => Mage::helper('tm_email')->__('Disapproved'),
            self::APPROVED    => Mage::helper('tm_email')->__('Approved'),
            self::SUCCESS     => Mage::helper('tm_email')->__('Success'),
            self::FAILURE     => Mage::helper('tm_email')->__('Failure'),
        );
    }
}