<?php

namespace Wexo\Shipping\Api\Carrier;

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
    public function getMethodTypes(): array;

    /**
     * @return string
     */
    public function getTitle();
}
