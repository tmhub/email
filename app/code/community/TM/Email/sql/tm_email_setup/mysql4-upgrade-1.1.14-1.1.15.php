<?php

/**
 * @var Mage_Core_Model_Resource_Setup
 */
$installer = $this;
$connection = $installer->getConnection();

$installer->startSetup();

$length = 128;
$tables = array('tm_email_gateway_storage', 'tm_email_gateway_transport');
$columns = array('user', 'password');

foreach ($tables as $tableName) {
    $table = $installer->getTable($tableName);
    foreach ($columns as $column) {
        $connection->changeColumn($table, $column, $column, array('length' => $length));
    }
}

$installer->endSetup();

