<?php
/**
 * MagePrince
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageprince.com license that is
 * available through the world-wide-web at this URL:
 * https://mageprince.com/end-user-license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    MagePrince
 * @package     Mageprince_Extrafee
 * @copyright   Copyright (c) MagePrince (https://mageprince.com/)
 * @license     https://mageprince.com/end-user-license-agreement
 */

namespace Mageprince\Extrafee\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AfterOrder implements ObserverInterface
{
    /**
     * Set extra fee to order
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getQuote();
        $extraFee = $quote->getFee();
        $extraBaseFee = $quote->getBaseFee();
        if (!$extraFee || !$extraBaseFee) {
            return $this;
        }

        $extraFeeTax = $quote->getFeeTax();
        $extraBaseFeeTax = $quote->getBaseFeeTax();
        $order = $observer->getOrder();
        $order->setData('fee', $extraFee);
        $order->setData('base_fee', $extraBaseFee);
        $order->setData('fee_tax', $extraFeeTax);
        $order->setData('base_fee_tax', $extraBaseFeeTax);

        return $this;
    }
}
