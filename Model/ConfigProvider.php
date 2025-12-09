<?php

namespace Wexo\Shipping\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @param Session $checkoutSession
     * @param Json $jsonSerializer
     */
    public function __construct(private readonly Session $checkoutSession, private readonly Json $jsonSerializer)
    {
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getConfig(): array
    {
        return [
            'wexo_shipping_data' => $this->jsonSerializer->unserialize(
                $this->checkoutSession->getQuote()->getData('wexo_shipping_data') ?? '{}'
            )
        ];
    }
}
