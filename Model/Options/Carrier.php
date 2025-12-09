<?php

namespace Wexo\Shipping\Model\Options;

use Magento\Framework\Data\OptionSourceInterface;
use Wexo\Shipping\Api\Carrier\CarrierInterface;
use Wexo\Shipping\Model\ComponentManagement;

class Carrier implements OptionSourceInterface
{
    /**
     * @param ComponentManagement $componentManagement
     */
    public function __construct(private readonly ComponentManagement $componentManagement)
    {
    }

    /**
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray(): array
    {
        return array_map(fn(CarrierInterface $carrierComponent): array => [
            'value' => $carrierComponent->getTypeName(),
            'label' => ucfirst($carrierComponent->getTypeName())
        ], $this->componentManagement->getAll());
    }
}
