<?php
namespace Infobeans\Faq\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        
        $installer = $setup;

        $installer->startSetup();
        
        /**
         * Create table 'infobeans_faq_category'
         */
        $table = $installer->getConnection()
                    ->newTable($installer->getTable('infobeans_faq_category'))
                    ->addColumn(
                        'category_id',
                        Table::TYPE_SMALLINT,
                        null,
                        ['identity' => true, 'nullable' => false, 'primary' => true],
                        'Category ID'
                    )
                    ->addColumn('category_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'FAQ Category Name')
                    ->addColumn(
                        'sort_order',
                        Table::TYPE_SMALLINT,
                        null,
                        ['nullable' => false, 'default' => '0'],
                        'Sort Order'
                    )
                    ->addColumn(
                        'is_active',
                        Table::TYPE_SMALLINT,
                        null,
                        ['nullable' => false, 'default' => '1'],
                        'Is FAQ Active?'
                    )
                    ->addColumn('creation_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
                    ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
                    ->setComment('Infobeans FAQ Category');

                $installer->getConnection()->createTable($table);
        
        /**
         * Create table 'infobeans_faq'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('infobeans_faq'))
            ->addColumn(
                'faq_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Faq ID'
            )
            ->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => false], 'FAQ Question')
            ->addColumn('content', Table::TYPE_TEXT, '2M', [], 'FAQ Answer')
            ->addColumn('category_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Category Id')
            ->addColumn(
                'is_active',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => '1'],
                'Is FAQ Active?'
            )
            ->addColumn('creation_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Creation Time')
            ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
            ->setComment('Infobeans FAQ')
            ->addIndex(
                $setup->getIdxName(
                    $installer->getTable('infobeans_faq'),
                    ['title', 'content'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title', 'content'],
                ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
            )->addForeignKey(
                $installer->getFkName(
                    'infobeans_faq',
                    'category_id',
                    'infobeans_faq_category',
                    'category_id'
                ),
                'category_id',
                $installer->getTable('infobeans_faq_category'),
                'category_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
