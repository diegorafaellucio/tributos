<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->run("/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `monitoramento`
--

DROP TABLE IF EXISTS `ispshop_tributos_monitoramento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ispshop_tributos_monitoramento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operacao` varchar(50) DEFAULT NULL,
  `ncm` varchar(20) DEFAULT NULL,
  `minha_descricao` varchar(100) DEFAULT NULL,
  `meu_codigo` varchar(100) DEFAULT NULL,
  `descricao_tipi` varchar(5000) DEFAULT NULL,
  `ipi` decimal(16,2) DEFAULT '0.00',
  `obs_ipi` text,
  `base_legal_ipi` text,
  `carga_trib_media_nacional` decimal(16,2) DEFAULT '0.00',
  `carga_trib_media_importada` decimal(16,2) DEFAULT '0.00',
  `desoneracao_folha_pagamento` varchar(50) DEFAULT NULL,
  `possui_similar_nacional` varchar(50) DEFAULT NULL,
  `obs_similar_nacional` text,
  `estado_saida` varchar(10) DEFAULT NULL,
  `estado_entrada` varchar(10) DEFAULT NULL,
  `aliquota_interestadual` decimal(16,2) DEFAULT '0.00',
  `aliquota_interna` decimal(16,2) DEFAULT '0.00',
  `fundo_pobreza` decimal(16,2) DEFAULT '0.00',
  `norma_st` text,
  `ncm_norma` varchar(50) DEFAULT NULL,
  `descricao_norma` text,
  `segmento` varchar(150) DEFAULT NULL,
  `mva` decimal(16,2) DEFAULT '0.00',
  `mva_ajustada` decimal(16,2) DEFAULT '0.00',
  `mva_ajustada_4` decimal(16,2) DEFAULT '0.00',
  `obs_st` text,
  `beneficios_st` text,
  `obs_variacao_st` text,
  `mva_positiva` decimal(16,2) DEFAULT '0.00',
  `mva_negativa` decimal(16,2) DEFAULT '0.00',
  `mva_neutra` decimal(16,2) DEFAULT '0.00',
  `chave` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Chave` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=1346 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;");

/**
 * Create table 'ispshop_tributos_ncm'
 */
$tableNCM = $installer->getConnection()
// The following call to getTable('ispshop_tributos/ncm') will lookup the resource for ispshop_tributos (ispshop_tributos_mysql4), and look
// for a corresponding entity called mac. The table name in the XML is ispshop_tributos_ncm, so this is what is created.
        ->newTable($installer->getTable('ispshop_tributos/ncm'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
                ), 'id')
        ->addColumn('id_monitoramento_ncm', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
                ), 'id_monitoramento_ncm')
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
                ), 'product_id')
        ->addColumn('region_id', Varien_Db_Ddl_Table::TYPE_VARCHAR, 2, array(
            'nullable' => false,
                ), 'region_id')
        ->addColumn('observacao', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
            'nullable' => true,
                ), 'observacao')
        ->addColumn('aliquota_icms_interno', Varien_Db_Ddl_Table::TYPE_DECIMAL, '16,2', array(
            'nullable' => true,
                ), 'aliquota_icms_interno')
        ->addColumn('aliquota_icms_interestadual', Varien_Db_Ddl_Table::TYPE_DECIMAL, '16,2', array(
            'nullable' => true,
                ), 'aliquota_icms_interestadual')
        ->addColumn('mva_ajustada', Varien_Db_Ddl_Table::TYPE_DECIMAL, '16,2', array(
            'nullable' => true,
                ), 'mva_ajustada')
        ->addColumn('mva', Varien_Db_Ddl_Table::TYPE_DECIMAL, '16,2', array(
            'nullable' => true,
                ), 'mva')
        ->addColumn('mva_ajustada_4', Varien_Db_Ddl_Table::TYPE_DECIMAL, '16,2', array(
    'nullable' => true,
        ), 'mva_ajustada_4');
$installer->getConnection()->createTable($tableNCM);

$tableLog = $installer->getConnection()
// The following call to getTable('ispshop_tributos/ncm') will lookup the resource for ispshop_tributos (ispshop_tributos_mysql4), and look
// for a corresponding entity called mac. The table name in the XML is ispshop_tributos_ncm, so this is what is created.
        ->newTable($installer->getTable('ispshop_tributos/log'))
        ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'nullable' => false,
            'primary' => true,
                ), 'id')
        ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
                ), 'product_id')
        ->addColumn('updated_fields', Varien_Db_Ddl_Table::TYPE_VARCHAR, 5000, array(
            'nullable' => true,
                ), 'updated_fields')
        ->addColumn('country_region', Varien_Db_Ddl_Table::TYPE_VARCHAR, 4, array(
            'nullable' => true,
                ), 'country_region')
        ->addColumn('ncm', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
                ), 'ncm')
        ->addColumn('type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
            'nullable' => true,
                ), 'type')
        ->addColumn('responsable', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
                ), 'responsable')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, 0, array(
    'nullable' => false,
        ), 'created_at');
$installer->getConnection()->createTable($tableLog);

//campos adicionais order
$installer->getConnection()->addColumn($installer->getTable('sales/order'), 'for_consumption', 'TINYINT(1) UNSIGNED  AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/order'), 'amount_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/order'), 'amount_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/order_item'), 'value_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/order_item'), 'value_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/order_item'), 'price_st_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');


//campo adicionais quote
$installer->getConnection()->addColumn($installer->getTable('sales/quote'), 'for_consumption', 'TINYINT(1) UNSIGNED  AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/quote'), 'amount_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/quote'), 'amount_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_item'), 'value_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_item'), 'value_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/quote_item'), 'price_st_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');



//campo adicionais invoice
$installer->getConnection()->addColumn($installer->getTable('sales/invoice'), 'amount_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/invoice'), 'amount_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/invoice_item'), 'value_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER base_price');
$installer->getConnection()->addColumn($installer->getTable('sales/invoice_item'), 'value_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER base_price');
$installer->getConnection()->addColumn($installer->getTable('sales/invoice_item'), 'price_st_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER base_price');


//campo adicionais creditmemo
$installer->getConnection()->addColumn($installer->getTable('sales/creditmemo'), 'amount_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/creditmemo'), 'amount_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER store_id');
$installer->getConnection()->addColumn($installer->getTable('sales/creditmemo_item'), 'value_st', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER parent_id');
$installer->getConnection()->addColumn($installer->getTable('sales/creditmemo_item'), 'value_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER parent_id');
$installer->getConnection()->addColumn($installer->getTable('sales/creditmemo_item'), 'price_st_icms', 'DECIMAL(16,2) UNSIGNED DEFAULT 0 AFTER parent_id');




$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

$setup->removeAttribute('catalog_product', 'ncm');

$setup->addAttribute('catalog_product', 'NCM', array(
    'label' => 'NCM',
    //'group'        => 'General',
    'visible' => 0,
    'required' => 1,
    'user_defined' => 0,
    'position' => 3,
    'type' => 'text',
    'input' => 'text',
));


$setup->addAttribute('catalog_product', 'is_imported', array(
    'label' => 'É importado?',
    //'group'        => 'General',
    'visible' => 0,
    'required' => 1,
    'user_defined' => 0,
    'position' => 2,
    'source' => 'eav/entity_attribute_source_boolean',
    'type' => 'int',
    'input' => 'boolean',
    'default' => '0',
));

$setupCustomer = Mage::getModel('customer/entity_setup', 'core_setup');

$setupCustomer->addAttribute('customer', 'is_simples', array(
    'label' => 'Possui cadastro no Simples?',
    //'group'        => 'General',
    'visible' => 0,
    'required' => 1,
    'user_defined' => 0,
    'position' => 2,
    'source' => 'eav/entity_attribute_source_boolean',
    'type' => 'int',
    'input' => 'boolean',
    'default' => '0',
));


Mage::getSingleton('eav/config')
        ->getAttribute('customer', 'is_simples')
        ->setData('used_in_forms', array('adminhtml_customer', 'customer_account_create', 'customer_account_edit', 'checkout_register'))
        ->save();

if (version_compare(Mage::getVersion(), '1.6.0', '<=')) {
    $customer = Mage::getModel('customer/customer');
    $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
    $setup->addAttributeToSet('customer', $attrSetId, 'General', 'is_simples');
}

if (version_compare(Mage::getVersion(), '1.4.2', '>=')) {
    Mage::getSingleton('eav/config')
            ->getAttribute('customer', 'is_simples')
            ->setData('used_in_forms', array('adminhtml_customer', 'customer_account_create', 'customer_account_edit', 'checkout_register'))
            ->save();
}




$installer->endSetup();
