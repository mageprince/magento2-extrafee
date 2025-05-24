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

namespace Mageprince\Extrafee\Model\Calculation\Calculator;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;

class PercentageCalculator extends AbstractCalculator
{
    /**
     * @inheritdoc
     */
    public function calculate(Total $total, Quote $quote)
    {
        $storeId = $quote->getStoreId();
        $fee = $this->_helper->getExtraFee($storeId);

        $subTotal = $total->getBaseSubtotal();

        if ($this->_helper->getIsIncludeShipping()) {
            $subTotal += $total->getBaseShippingAmount();
        }

        if ($this->_helper->getIsIncludeDiscount()) {
            $discount = 0;
            foreach ($quote->getAllItems() as $item) {
                $discount -= $item->getBaseDiscountAmount();
            }
            $subTotal += $discount;
        }

        return ($subTotal * $fee) / 100;
    }
}
