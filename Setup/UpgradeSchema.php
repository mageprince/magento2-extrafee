<?php

namespace Prince\Extrafee\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Class UpgradeSchema
 * @package Prince\Extrafee\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $quoteTable = 'quote';
        $quoteAddressTable = 'quote_address';
        $orderTable = 'sales_order';
        $invoiceTable = 'sales_invoice';
        $creditmemoTable = 'sales_creditmemo';

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Fee'
                ]
            );
            
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteAddressTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Fee'
                ]
            );

        $setup->getConnection()
            ->addColumn(
              $setup->getTable($quoteAddressTable),
                'base_fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Base Fee'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteTable),
                'base_fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Base Fee'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Fee'
                ]
            );

         $setup->getConnection()
             ->addColumn(
                $setup->getTable($orderTable),
                'base_fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Base Fee'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($invoiceTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Fee'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($invoiceTable),
                'base_fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Base Fee'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($creditmemoTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Fee'
                ]
            );

        $setup->getConnection()
            ->addColumn(
                $setup->getTable($creditmemoTable),
                'base_fee',
                [
                   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length' => '12,4',
                    'default' => '0.0000',
                    'comment' => 'Base Fee'
                ]
            );

        $setup->endSetup();
    }
}
