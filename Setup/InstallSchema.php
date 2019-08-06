<?php
/**
 * Copyright (c) 2018. Orba Sp. z o.o. (http://orba.pl)
 */

namespace Orba\Ceneopl\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * Class InstallSchema
 * @package Orba\Ceneopl\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $tableName = $setup->getTable('orba_ceneo_category');
        $table = $setup->getConnection()->newTable($tableName);
        $table->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ],
            'ID'
        )->addColumn(
            'external_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'unsigned' => true,
                'nullable' => false
            ],
            'External ID'
        )->addColumn(
            'parent_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'unsigned' => true,
                'nullable' => false,
                'default' => 0
            ],
            'Parent ID'
        )->addColumn(
            'path',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Path'
        )->addColumn(
            'position',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'unsigned' => true,
                'nullable' => false,
                'default' => 0
            ],
            'Position'
        )->addColumn(
            'level',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'unsigned' => true,
                'nullable' => false,
                'default' => 0
            ],
            'Level'//
        )->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [
                'nullable' => false,
            ],
            'Name'
        )->addIndex(
            $setup->getIdxName($tableName, ['name'], AdapterInterface::INDEX_TYPE_FULLTEXT), ['name'], ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment('Orba ceneo');

        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }

}