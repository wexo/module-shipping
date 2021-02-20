<?php

namespace Wexo\Shipping\Model\Layout\Aheadworks\OneStepCheckout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Framework\Api\ObjectFactory;
use Magento\Framework\Stdlib\ArrayManager;
use Wexo\Shipping\Model\Layout\LayoutProcessor;

class ShippingProcessor implements LayoutProcessorInterface
{
    const NEW_PATH = 'components/checkout/children/shippingMethod/children/shippingAdditional';

    /**
     * @var ObjectFactory
     */
    private $objectFactory;

    /**
     * @var string
     */
    private $processor;

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @param ObjectFactory $objectFactory
     * @param string $processor
     */
    public function __construct(
        ObjectFactory $objectFactory,
        ArrayManager $arrayManager,
        $processor = LayoutProcessor::class
    ) {
        $this->objectFactory = $objectFactory;
        $this->arrayManager = $arrayManager;
        $this->processor = $processor;
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        $processor = $this->objectFactory->create($this->processor, [
            'setPath' => static::NEW_PATH
        ]);
        $jsLayout = $processor->process($jsLayout);
        $jsLayout = $this->arrayManager->set(static::NEW_PATH . '/displayArea', $jsLayout, 'delivery-date');

        return $jsLayout;
    }
}
