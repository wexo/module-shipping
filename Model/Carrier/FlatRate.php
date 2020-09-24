<?php


namespace Wexo\Shipping\Model\Carrier;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Quote\Address\Rate;
use Wexo\Shipping\Api\Carrier\FlatRateInterface;

class FlatRate extends AbstractCarrier implements FlatRateInterface
{
    public $_code = self::TYPE_NAME;

    public function getTypeName(): string
    {
        return $this->_code;
    }

    public function getImageUrl(ShippingMethodInterface $shippingMethod, Rate $rate, $typeHandler)
    {
        return null;
    }
}
