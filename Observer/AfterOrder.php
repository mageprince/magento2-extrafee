<?php

namespace Prince\Extrafee\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class AfterOrder
 * @package Prince\Extrafee\Observer
 */
class AfterOrder implements ObserverInterface
{
    /**
     * Set payment fee to order
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();
        $extraFee = $quote->getFee();
        $extraBaseFee = $quote->getBaseFee();
        if (!$extraFee || !$extraBaseFee) {
            return $this;
        }
        
        $order = $observer->getOrder();
        $order->setData('fee', $extraFee);
        $order->setData('base_fee', $extraBaseFee);

        return $this;
    }
}
