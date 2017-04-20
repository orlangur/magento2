<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\CatalogInventory\Model\ResourceModel\Product;

use Magento\Catalog\Model\ResourceModel\Product\BaseSelectProcessorInterface;
use Magento\CatalogInventory\Model\Stock;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;

/**
 * Class StockStatusBaseSelectProcessor
 */
class StockStatusBaseSelectProcessor implements BaseSelectProcessorInterface
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Indexer\Model\ResourceModel\FrontendResource
     */
    private $indexerStockFrontendResource;

    /**
     * @param ResourceConnection $resource
     * @param null|\Magento\Indexer\Model\ResourceModel\FrontendResource $indexerStockFrontendResource
     */
    public function __construct(
        ResourceConnection $resource,
        \Magento\Indexer\Model\ResourceModel\FrontendResource $indexerStockFrontendResource = null
    ) {
        $this->resource = $resource;
        $this->indexerStockFrontendResource = $indexerStockFrontendResource ?: ObjectManager::getInstance()
            ->get(\Magento\CatalogInventory\Model\ResourceModel\Indexer\Stock\FrontendResource::class);
    }

    /**
     * Add stock item filter to selects
     *
     * @param Select $select
     * @return Select
     */
    public function process(Select $select)
    {
        $stockStatusTable = $this->indexerStockFrontendResource->getMainTable();

        /** @var Select $select */
        $select->join(
            ['stock' => $stockStatusTable],
            sprintf('stock.product_id = %s.entity_id', BaseSelectProcessorInterface::PRODUCT_TABLE_ALIAS),
            []
        )
            ->where('stock.stock_status = ?', Stock::STOCK_IN_STOCK);
        return $select;
    }
}
