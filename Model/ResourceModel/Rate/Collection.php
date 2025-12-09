<?php

namespace Wexo\Shipping\Model\ResourceModel\Rate;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Wexo\Shipping\Model\Rate;
use Wexo\Shipping\Model\ResourceModel\Rate as RateResource;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    public function _construct(): void
    {
        $this->_init(Rate::class, RateResource::class);
    }
}
