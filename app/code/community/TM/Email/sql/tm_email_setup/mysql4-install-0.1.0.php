<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('tm_email_gateway'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, 11, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'ID')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(), 'Name')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable'  => false,
        'default'   => 1,
        ), 'Status')
    ->addColumn('type', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable'  => false,
        'default'   => 1,
        ), 'Type (2 - imap else pop3)')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Email')
    ->addColumn('host', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Host')
    ->addColumn('user', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(
        'nullable'  => false,
        'default'   => '',
        ), 'User')
    ->addColumn('password', Varien_Db_Ddl_Table::TYPE_TEXT, 45, array(
        'nullable'  => false,
        'default'   => '',
        ), 'Password')
    ->addColumn('port', Varien_Db_Ddl_Table::TYPE_SMALLINT, 5, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => 110,
        ), 'Port')
    ->addColumn('secure', Varien_Db_Ddl_Table::TYPE_SMALLINT, 1, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => 0,
        ), 'Secure (1 - ssl, 2 - tsl else none)')
    ->addColumn('remove', Varien_Db_Ddl_Table::TYPE_TINYINT, 1, array(
        'nullable'  => false,
        'default'   => 0,
        ), 'Remove')
    ;
$installer->getConnection()->createTable($table);

//throw new Exception;
$installer->run("

INSERT INTO `{$this->getTable('tm_email_gateway')}`(`id`,`name`,`status`,`email`,`host`,`user`,`port`)
VALUES (1, 'Default', 0, 'your email here', 'your host here', 'your user here', 110);


");
$installer->endSetup();
