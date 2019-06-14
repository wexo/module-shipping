<?php

namespace Wexo\Shipping\Model\Provider;

use Wexo\Shipping\Api\Data\RateInterface;

class CurrentRate
{
    /**
     * @var RateInterface|null
     */
    private $currentRate = null;

    /**
     * @return RateInterface|null
     */
    public function getCurrentRate()
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
