<?php

namespace Wexo\Shipping\Block\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;

class WexoShippingDataProcessor implements LayoutProcessorInterface
{
    /**
     * @param Session $checkoutSession
     * @param Json $jsonSerializer
     */
    public function __construct(private readonly Session $checkoutSession, private readonly Json $jsonSerializer)
    {
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function process($jsLayout): array
    {
        if (!isset($jsLayout['components']['checkoutProvider']['wexoShippingData'])) {
            $wexoShippingData = $this->checkoutSession->getQuote()->getData('wexo_shipping_data');
            if ($wexoShippingData) {
                $jsLayout['components']['checkoutProvider']['wexoShippingData'] = $this->jsonSerializer->unserialize(
                    $this->checkoutSession->getQuote()->getData('wexo_shipping_data')
                );
            }
        }
        return $jsLayout;
    }
}
