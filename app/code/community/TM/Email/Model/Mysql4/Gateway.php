<?php

class TM_Email_Model_Mysql4_Gateway extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        // Note that the id refers to the key field in your database table.
        $this->_init('tm_email/gateway', 'id');
    }

    public function getOptionArray($types = array())
    {
        if (!is_array($types)) {
            $types = array($types);
        }
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable())
            ;

        $rowset = array();
        foreach ($this->_getReadAdapter()->fetchAll($select) as $row)
        {
            if (!empty($types) && !in_array($row['type'], $types)) {
                continue;
            }
            $rowset[$row['id']] = $row['name'];
        }

        return $rowset;
    }
}