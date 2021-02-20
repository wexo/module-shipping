<?php

namespace Wexo\Shipping\Model\Layout;

class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    const SHIPPING_ADDITIONAL = 'components/checkout/children/steps/children/shipping-step/children/shippingAddress/children/shippingAdditional';

    /**
     * @var array
     */
    private $processors;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \Magento\Framework\Stdlib\ArrayManager
     */
    private $arrayManager;

    /**
     * @var string
     */
    private $setPath;

    /**
     * @param array $processors
     */
    public function __construct(
        \Magento\Framework\Stdlib\ArrayManager $arrayManager,
        $processors = [],
        $path = self::SHIPPING_ADDITIONAL,
        $setPath = self::SHIPPING_ADDITIONAL
    )
    {
        $this->processors = $processors;
        $this->path = $path;
        $this->arrayManager = $arrayManager;
        $this->setPath = $setPath;
    }

    public function process($jsLayout)
    {
        $additionalData = $this->arrayManager->get($this->path, $jsLayout);

        foreach ($this->processors as $processor) {
            $additionalData = $processor->process($additionalData);
        }

        return $this->arrayManager->set($this->setPath, $jsLayout, $additionalData);
    }
}
