<?php

namespace Wexo\Shipping\Model\MethodType;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Wexo\Shipping\Api\Carrier\MethodTypeHandlerInterface;

class Address implements MethodTypeHandlerInterface
{

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return __('Address');
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return 'address';
    }

    public function saveOrderInformation(CartInterface $quote, OrderInterface $order)
    {
        return true;
    }
}
