<?php

namespace Wexo\Shipping\Model\Layout;

use Magento\Framework\Stdlib\ArrayManager;

class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    const string SHIPPING_ADDITIONAL = 'components/checkout/children/steps/children/shipping-step/children/'
        . 'shippingAddress/children/shippingAdditional';

    /**
     * @param ArrayManager $arrayManager
     * @param array $processors
     * @param string $path
     * @param string $setPath
     */
    public function __construct(
        private readonly ArrayManager $arrayManager,
        private readonly array $processors = [],
        private readonly string $path = self::SHIPPING_ADDITIONAL,
        private readonly string $setPath = self::SHIPPING_ADDITIONAL
    ) {
    }

    public function process($jsLayout): array
    {
        $additionalData = $this->arrayManager->get($this->path, $jsLayout);

        foreach ($this->processors as $processor) {
            $additionalData = $processor->process($additionalData);
        }

        return $this->arrayManager->set($this->setPath, $jsLayout, $additionalData);
    }
}
