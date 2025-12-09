<?php

namespace Wexo\Shipping\Model\Options;

use Magento\Store\Ui\Component\Listing\Column\Store\Options;

class Stores extends Options
{
    const string ALL_STORE_VIEWS = '0';

    /**
     * @return array
     */
    #[\Override]
    public function toOptionArray(): array
    {
        if ($this->options !== null) {
            return $this->options;
        }
        $this->currentOptions['All Store Views']['label'] = __('All Store Views');
        $this->currentOptions['All Store Views']['value'] = static::ALL_STORE_VIEWS;
        $this->generateCurrentOptions();
        $this->options = array_values($this->currentOptions);

        return $this->options;
    }
}
