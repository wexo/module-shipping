<?php

namespace Wexo\Shipping\Model\Options;

use Magento\Framework\Data\OptionSourceInterface;
use Wexo\Shipping\Api\Carrier\MethodTypeInterface;
use Wexo\Shipping\Model\Carrier\AbstractCarrier;
use Wexo\Shipping\Model\ComponentManagement;

class CarrierMethodType implements OptionSourceInterface
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
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $option = [];

        /** @var AbstractCarrier $carrierComponent */
        foreach ($this->componentManagement->getAll() as $carrierComponent) {

            /**
             * @var string $key
             * @var MethodTypeInterface $methodType
             */
            foreach ($carrierComponent->getMethodTypes() as $key => $methodType) {
                $option[] = [
                    'value' => "{$carrierComponent->getTypeName()}_$key",
                    'label' => $methodType->getTitle(),
                    'carrier' => $carrierComponent->getTypeName()
                ];
            }
        }
        return $option;
    }
}
