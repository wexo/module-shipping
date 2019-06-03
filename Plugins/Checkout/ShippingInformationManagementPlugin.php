<?php

namespace Wexo\Shipping\Plugins\Checkout;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;

class ShippingInformationManagementPlugin
{
    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        CartRepositoryInterface $quoteRepository
    ) {
        $this->quoteRepository = $quoteRepository;
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
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        if ($addressInformation->getExtensionAttributes()) {
            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setData('wexo_shipping_data', $addressInformation->getExtensionAttributes()->getWexoShippingData());
        }
        return [$cartId, $addressInformation];
    }
}
