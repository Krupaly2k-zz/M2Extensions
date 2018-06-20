<?php
/**
 * Solwin Infotech
 * Solwin ProductAttachment Extension
 * 
 * @category   Solwin
 * @package    Solwin_ProductAttachment
 * @copyright  Copyright © 2006-2016 Solwin (https://www.solwininfotech.com)
 * @license    https://www.solwininfotech.com/magento-extension-license/
 */
namespace Solwin\ProductAttachment\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('solwin_productattachment_attachment')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('solwin_productattachment_attachment')
            )
            ->addColumn(
                'attachment_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Attachment ID'
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Attachment Title'
            )
            ->addColumn(
                'attachment_file',
                Table::TYPE_TEXT,
                255,
                [],
                'Attachment Attachment File'
            )
            ->addColumn(
                'customer_group',
                Table::TYPE_TEXT,
                '64k',
                ['nullable => false'],
                'Attachment Customer Group'
            )
            ->addColumn(
                'status',
                Table::TYPE_INTEGER,
                null,
                [],
                'Attachment Status'
            )

            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Attachment Created At'
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                [],
                'Attachment Updated At'
            )
            ->setComment('Attachment Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('solwin_productattachment_attachment'),
                $setup->getIdxName(
                    $installer->getTable('solwin_productattachment_attachment'),
                    ['title','attachment_file'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['title','attachment_file'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }
        
        
        /**
         * Create table 'solwin_productattachment_store'
         */
        $table = $installer->getConnection()->newTable(
                        $installer->getTable('solwin_productattachment_store')
                )->addColumn(
                        'attachment_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'nullable' => false,
                            'primary' => true
                        ],
                        'Attachment ID'
                )->addColumn(
                        'store_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                        null,
                        [
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                        ],
                        'Store ID'
                )->addIndex(
                        $installer->getIdxName(
                                'solwin_productattachment_store', ['store_id']
                                ), ['store_id']
                )->addForeignKey(
                        $installer->getFkName('solwin_productattachment_store', 
                                'store_id', 'store', 'store_id'),
                        'store_id',
                        $installer->getTable('store'),
                        'store_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->setComment(
                'Product Attachment To Store Linkage Table'
                );
        $installer->getConnection()->createTable($table);
        
        
        $installer->endSetup();
    }
}
