<?php

namespace Wexo\Shipping\Setup;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        ProductMetadataInterface $productMetadata
    ) {
        $this->productMetadata = $productMetadata;
    }

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($this->productMetadata->getVersion(), '2.3.0', '<')) {
            $installer = $setup;
            $installer->startSetup();

            if (!$installer->getConnection()->isTableExists($installer->getTable('wexo_shipping_rate'))) {
                $table = $installer->getConnection()->newTable($installer->getTable('wexo_shipping_rate'));

                $table->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'id'
                )->addColumn(
                    'carrier_type',
                    Table::TYPE_TEXT,
                    32,
                    [],
                    'carrier type'
                )->addColumn(
                    'method_type',
                    Table::TYPE_TEXT,
                    128,
                    [],
                    'method type'
                )->addColumn(
                    'store_id',
                    Table::TYPE_TEXT,
                    128,
                    [],
                    'store ids'
                )->addColumn(
                    'is_active',
                    Table::TYPE_BOOLEAN,
                    null,
                    [],
                    'is active'
                )->addColumn(
                    'allow_free',
                    Table::TYPE_BOOLEAN,
                    null,
                    [],
                    'allow free'
                )->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    [],
                    'sort order'
                )->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    [],
                    'title'
                )->addColumn(
                    'price',
                    Table::TYPE_DECIMAL,
                    null,
                    [],
                    'price'
                )->addColumn(
                    'conditions_serialized',
                    Table::TYPE_TEXT,
                    65536,
                    [],
                    'carrier type'
                );

                $installer->getConnection()->createTable($table);
            }
            if (!$installer->getConnection()->tableColumnExists($installer->getTable('sales_order'),
                'wexo_shipping_data')) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('sales_order'),
                    'wexo_shipping_data',
                    Table::TYPE_TEXT,
                    null,
                    ['length' => 65536],
                    'Wexo Shipping Data'
                );
            }
            if (!$installer->getConnection()->tableColumnExists($installer->getTable('quote'), 'wexo_shipping_data')) {
                $installer->getConnection()->addColumn(
                    $installer->getTable('quote'),
                    'wexo_shipping_data',
                    Table::TYPE_TEXT,
                    null,
                    ['length' => 65536],
                    'Wexo Shipping Data'
                );
            }
            $installer->endSetup();
        }
    }
}