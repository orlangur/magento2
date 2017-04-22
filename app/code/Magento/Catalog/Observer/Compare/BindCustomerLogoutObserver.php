<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Observer\Compare;

use Magento\Catalog\Model\Product\Compare\Item;
use Magento\Framework\Event\ObserverInterface;

/**
 * Catalog Compare Item Model
 *
 */
class BindCustomerLogoutObserver implements ObserverInterface
{
    /**
     * @param Item $item
     */
    public function __construct(
        Item $item
    ) {
        $this->item = $item;
    }

    /**
     * Customer login bind process
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->item->bindCustomerLogout();

        return $this;
    }
}
