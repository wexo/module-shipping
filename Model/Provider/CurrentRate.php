<?php

namespace Wexo\Shipping\Model\Provider;

use Wexo\Shipping\Api\Data\RateInterface;

class CurrentRate
{
    private ?RateInterface $currentRate = null;

    /**
     * @return RateInterface|null
     */
    public function getCurrentRate(): ?RateInterface
    {
        return $this->currentRate;
    }

    /**
     * @param RateInterface $rate
     * @return CurrentRate
     */
    public function setCurrentRate(RateInterface $rate): CurrentRate
    {
        $this->currentRate = $rate;
        return $this;
    }
}
