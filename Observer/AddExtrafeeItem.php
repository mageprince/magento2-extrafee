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

class AddExtrafeeItem implements ObserverInterface
{
    /**
     * Add custom amount as custom item to payment cart totals
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $cart = $observer->getCart();
        $baseFee = (float) $cart->getSalesModel()->getDataUsingMethod('base_fee');

        if (!$baseFee) {
            return;
        }

        $cart->addCustomItem(__('Extra Fee'), 1, $baseFee, 'extrafee');
    }
}
