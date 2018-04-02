<?php

/**
 * @var Mage_Core_Model_Resource_Setup
 */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('tm_email_gateway_transport_history'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_BIGINT, 20, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Message ID')
    ->addColumn('to', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
        'nullable'  => false,
        'default'   => '',
        ), 'To')
    ->addColumn('from', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
        'nullable'  => false,
        'default'   => '',
        ), 'From')
    ->addColumn('subject', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        'default'   => '',
        ), 'From')
    ->addColumn('template_id', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Template ID')
    ->addColumn('body', Varien_Db_Ddl_Table::TYPE_VARCHAR, 8192, array(
        'nullable'  => false,
        ), 'Body')
    // ->addColumn('transport_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
    //     'unsigned'  => true,
    //     'nullable'  => false,
    //     ), 'Transport ID')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
        'unsigned'  => true,
        'nullable'  => false,
        ), 'Created')
    ;
$installer->getConnection()->createTable($table);

$installer->endSetup();
