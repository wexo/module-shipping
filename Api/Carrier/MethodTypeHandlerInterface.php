<?php

namespace Wexo\Shipping\Api\Carrier;

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
}
