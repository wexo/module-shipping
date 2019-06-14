<?php

namespace Wexo\Shipping\Api\Carrier;

use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Quote\Address\Rate;

interface CarrierInterface
{
    /**
     * Type name that links to the Rate model
     *
     * @return string
     */
    public function getTypeName(): string;

    /**
     * @return array
     */
    public function getMethodTypesHandlers(): array;

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param ShippingMethodInterface $shippingMethod
     * @param Rate $rate
     * @return CarrierInterface
     */
    public function convertAdditionalRateData(ShippingMethodInterface $shippingMethod, Rate $rate);

    /**
     * @param ShippingMethodInterface $shippingMethod
     * @param Rate $rate
     * @param string|null $typeHandler
     * @return mixed
     */
    public function getImageUrl(ShippingMethodInterface $shippingMethod, Rate $rate, $typeHandler);
}
