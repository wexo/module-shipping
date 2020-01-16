<?php

namespace Wexo\Shipping\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Wexo\Shipping\Model\Carrier\AbstractCarrier;
use Wexo\Shipping\Model\ComponentManagement;

class QuoteSubmitBefore implements ObserverInterface
{

    /**
     * @var ComponentManagement
     */
    private $componentManagement;

    /**
     * @param ComponentManagement $componentManagement
     */
    public function __construct(
        ComponentManagement $componentManagement
    ) {
        $this->componentManagement = $componentManagement;
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        /** @var Quote $quote */
        $quote = $observer->getEvent()->getData('quote');
        /** @var Order $order */
        $order = $observer->getEvent()->getData('order');

        if ($quote && $order && !$order->getIsVirtual()) {
            $order->setData('wexo_shipping_data', $quote->getData('wexo_shipping_data'));

            $shippingMethod = $order->getShippingMethod(true);

            /** @var AbstractCarrier $component */
            $component = $this->componentManagement->getComponent($shippingMethod->getData('carrier_code'));

            if ($component) {
                $methodTypeHandler = $component->getMethodTypeHandler(
                    $component->getMethodTypeByMethod(
                        $shippingMethod->getData('method')
                    )
                );

                if ($methodTypeHandler && !isset($methodTypeHandler['type'])) {
                    throw new LocalizedException(__("Could't find type of method type"));
                }
                $methodTypeHandler['type']->saveOrderInformation($quote, $order);
            }
        }
    }
}
