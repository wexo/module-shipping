<?php

namespace Wexo\Shipping\Plugins\Quote\Model\Cart;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Quote\Api\Data\ShippingMethodExtensionFactory;
use Magento\Quote\Api\Data\ShippingMethodInterface;
use Magento\Quote\Model\Cart\ShippingMethodConverter;
use Magento\Quote\Model\Quote\Address\Rate;
use Magento\Shipping\Model\Config;
use Wexo\Shipping\Api\Carrier\CarrierInterface;

class ShippingMethodConverterPlugin
{
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var ShippingMethodExtensionFactory
     */
    private $shippingMethodExtensionFactory;

    /**
     * @var Config
     */
    private $shippingConfig;

    /**
     * ShippingMethodConvertPlugin constructor.
     * @param DataObjectHelper $dataObjectHelper
     * @param ShippingMethodExtensionFactory $shippingMethodExtensionFactory
     * @param Config $shippingConfig
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        ShippingMethodExtensionFactory $shippingMethodExtensionFactory,
        Config $shippingConfig
    )
    {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->shippingMethodExtensionFactory = $shippingMethodExtensionFactory;
        $this->shippingConfig = $shippingConfig;
    }

    /**
     * @param ShippingMethodConverter $subject
     * @param ShippingMethodInterface $ret
     * @param Rate $rate
     * @return ShippingMethodInterface
     */
    public function afterModelToDataObject(
        ShippingMethodConverter $subject,
        ShippingMethodInterface $ret,
        Rate $rate
    ): ShippingMethodInterface {

        if(!$ret->getExtensionAttributes()) {
            $ret->setExtensionAttributes($this->shippingMethodExtensionFactory->create());
        }

        $carriers = $this->shippingConfig->getAllCarriers();
        if(isset($carriers[$rate->getCarrier()])) {
            $carrier = $carriers[$rate->getCarrier()];

            if($carrier instanceof CarrierInterface) {
                $carrier->convertAdditionalRateData(
                    $ret,
                    $rate
                );
            }
        }

        return $ret;
    }
}
