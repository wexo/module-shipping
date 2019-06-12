<?php

namespace Wexo\Shipping\Api\Carrier;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;

interface MethodTypeHandlerInterface
{
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @param CartInterface $quote
     * @param OrderInterface $order
     * @return void
     */
    public function saveOrderInformation(CartInterface $quote, OrderInterface $order);
}
