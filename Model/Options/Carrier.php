<?php

namespace Wexo\Shipping\Model\Options;

use Magento\Framework\Data\OptionSourceInterface;
use Wexo\Shipping\Api\Carrier\CarrierInterface;
use Wexo\Shipping\Model\ComponentManagement;

class Carrier implements OptionSourceInterface
{
    /**
     * @var ComponentManagement
     */
    private $componentManagement;

    /**
     * @param ComponentManagement $componentManagement
     */
    public function __construct(
        ComponentManagement $componentManagement
    ) {
        $this->componentManagement = $componentManagement;
    }

    /**
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array_map(function (CarrierInterface $carrierComponent) {
            return [
                'value' => $carrierComponent->getTypeName(),
                'label' => ucfirst($carrierComponent->getTypeName())
            ];
        }, $this->componentManagement->getAll());
    }
}
