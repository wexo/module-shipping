<?php

namespace Wexo\Shipping\Plugins\Sales;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderRepositoryPlugin
{
    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $ret
     * @return OrderInterface
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $ret): OrderInterface
    {
        $ret->getExtensionAttributes()->setWexoShippingData($ret->getData('wexo_shipping_data'));
        return $ret;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $ret
     * @return OrderSearchResultInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $ret
    ): OrderSearchResultInterface {
        /** @var OrderInterface $order */
        foreach ($ret->getItems() as $order) {
            $order->getExtensionAttributes()->setWexoShippingData($order->getData('wexo_shipping_data'));
        }
        return $ret;
    }
}
