<?php

namespace Wexo\Shipping\Model\Layout\Aheadworks\OneStepCheckout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Api\ObjectFactory;
use Magento\Framework\Stdlib\ArrayManager;
use Wexo\Shipping\Model\Layout\LayoutProcessor;

class ShippingProcessor implements LayoutProcessorInterface
{
    const string NEW_PATH = 'components/checkout/children/shippingMethod/children/shippingAdditional';

    /**
     * @param ObjectFactory $objectFactory
     * @param ArrayManager $arrayManager
     * @param string $processor
     */
    public function __construct(
        private readonly ObjectFactory $objectFactory,
        private readonly ArrayManager $arrayManager,
        private readonly string $processor = LayoutProcessor::class
    ) {
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout): array
    {
        $processor = $this->objectFactory->create($this->processor, [
            'setPath' => static::NEW_PATH
        ]);
        $jsLayout = $processor->process($jsLayout);

        return $this->arrayManager->set(static::NEW_PATH . '/displayArea', $jsLayout, 'delivery-date');
    }
}
