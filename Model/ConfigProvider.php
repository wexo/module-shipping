<?php

namespace Wexo\Shipping\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Serialize\Serializer\Json;

class ConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @var Json
     */
    private $jsonSerializer;

    /**
     * @param Session $checkoutSession
     * @param Json $jsonSerializer
     */
    public function __construct(
        Session $checkoutSession,
        Json $jsonSerializer
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'wexo_shipping_data' => $this->jsonSerializer->unserialize(
                $this->checkoutSession->getQuote()->getData('wexo_shipping_data') ?? '{}'
            )
        ];
    }
}
