<?php
/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE_EULA.html.
 * It is also available through the world-wide-web at this URL:
 * http://www.codnitive.com/en/terms-of-service-softwares/
 * http://www.codnitive.com/fa/terms-of-service-softwares/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_Pardakht
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
	->newTable($installer->getTable('pardakht/setorder'))
	->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'identity'  => true,
		'unsigned'  => true,
		'nullable'  => false,
		'primary'   => true,
		), 'Value Id')
	->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
		'unsigned'  => true,
		'nullable'  => false,
		'default'   => '0',
		), 'Reward ID')
	->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, 1024, array(
		'nullable'  => true,
		), 'Result Description')
	->addColumn('post_track_number', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
		'nullable'  => true,
		), 'Post Tracking number')
	->addColumn('pardakht_order_number', Varien_Db_Ddl_Table::TYPE_TEXT, 128, array(
		'nullable'  => true,
		), 'Pardakht Order Number')
	->addIndex($installer->getIdxName('pardakht/setorder', array('order_id')),
		array('order_id'))
	->addForeignKey($installer->getFkName('pardakht/setorder', 'order_id', 'sales/order', 'entity_id'),
		'order_id', $installer->getTable('sales/order'), 'entity_id',
		Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
	->setComment('Pardakht Set Order Status');
$installer->getConnection()->createTable($table);

$this->addAttribute('customer_address', 'pardakht_region_id', array(
	'type' => 'varchar',
	'input' => 'text',
	'label' => 'Pardakht City',
	'global' => 1,
	'visible' => 1,
	'required' => 0,
	'user_defined' => 1,
	'visible_on_front' => 1
));
Mage::getSingleton('eav/config')
	->getAttribute('customer_address', 'pardakht_region_id')
	->setData('used_in_forms', array('customer_register_address','customer_address_edit','adminhtml_customer_address'))
	->save();

$tablequote = $this->getTable('sales/quote_address');
$installer->run("ALTER TABLE  $tablequote ADD  `pardakht_region_id` varchar(255) NOT NULL");

$tablequote = $this->getTable('sales/order_address');
$installer->run("ALTER TABLE  $tablequote ADD  `pardakht_region_id` varchar(255) NOT NULL");

$this->addAttribute('customer_address', 'pardakht_city', array(
	'type' => 'varchar',
	'input' => 'text',
	'label' => 'Pardakht City',
	'global' => 1,
	'visible' => 1,
	'required' => 0,
	'user_defined' => 1,
	'visible_on_front' => 1
));
Mage::getSingleton('eav/config')
	->getAttribute('customer_address', 'pardakht_city')
	->setData('used_in_forms', array('customer_register_address','customer_address_edit','adminhtml_customer_address'))
	->save();

$tablequote = $this->getTable('sales/quote_address');
$installer->run("ALTER TABLE  $tablequote ADD  `pardakht_city` varchar(255) NOT NULL");

$tablequote = $this->getTable('sales/order_address');
$installer->run("ALTER TABLE  $tablequote ADD  `pardakht_city` varchar(255) NOT NULL");

$installer->endSetup();
