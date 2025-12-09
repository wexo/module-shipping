<?php

namespace Wexo\Shipping\Plugins\Checkout;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;

class ShippingInformationManagementPlugin
{
    /**
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(private readonly CartRepositoryInterface $quoteRepository)
    {
    }

    /**
     * @param ShippingInformationManagementInterface $subject
     * @param int $cartId
     * @param ShippingInformationInterface $addressInformation
     * @return array
     * @throws NoSuchEntityException
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagementInterface $subject,
        int $cartId,
        ShippingInformationInterface $addressInformation
    ): array {
        if ($addressInformation->getExtensionAttributes()) {
            $quote = $this->quoteRepository->getActive($cartId);
            $additionalData = $addressInformation->getExtensionAttributes()->getWexoShippingData();
            if (!empty($additionalData) || empty($quote->getData('wexo_shipping_data'))) {
                $quote->setData(
                    'wexo_shipping_data',
                    $addressInformation->getExtensionAttributes()->getWexoShippingData()
                );
            }
        }
        return [$cartId, $addressInformation];
    }
}
