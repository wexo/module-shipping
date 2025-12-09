<?php

namespace Wexo\Shipping\Model;

use InvalidArgumentException;
use Wexo\Shipping\Api\Carrier\CarrierInterface;

class ComponentManagement
{
    /**
     * @param CarrierInterface[] $carriers
     */
    public function __construct(
        private readonly array $carriers = []
    ) {
        foreach ($this->carriers as $carrier) {
            if (!($carrier instanceof CarrierInterface)) {
                throw new InvalidArgumentException(
                    __('%1 is not an instace of %2', $carrier::class, CarrierInterface::class)->__toString()
                );
            }
        }
    }

    /**
     * @param string $name
     * @return CarrierInterface|null
     */
    public function getComponent(string $name): ?CarrierInterface
    {
        return $this->carriers[$name] ?? null;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->carriers;
    }
}
