<?php

namespace Wexo\Shipping\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class QuoteSubmitBefore implements ObserverInterface
{

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getData('quote');
        $order = $observer->getEvent()->getData('order');
        if ($quote && $order) {
            $order->setData('wexo_shipping_data', $quote->getData('wexo_shipping_data'));
        }
    }
}
