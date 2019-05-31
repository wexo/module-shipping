<?php

namespace Wexo\Shipping\Model;

use InvalidArgumentException;
use Wexo\Shipping\Api\Carrier\CarrierInterface;

class ComponentManagement
{
    /**
     * @var array
     */
    private $carriers;

    /**
     * @param CarrierInterface[] $carriers
     */
    public function __construct(
        array $carriers = []
    ) {
        $this->carriers = $carriers;

        foreach ($this->carriers as $carrier) {
            if (!($carrier instanceof CarrierInterface)) {
                throw new InvalidArgumentException(
                    __('%1 is not an instace of %2', get_class($carrier), CarrierInterface::class)->__toString()
                );
            }
        }
    }

    /**
     * @param $name
     * @return mixed|null
     */
    public function getComponent($name)
    {
        return isset($this->carriers[$name]) ? $this->carriers[$name] : null;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->carriers;
    }
}
