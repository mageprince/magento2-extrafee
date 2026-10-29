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
use Magento\Quote\Model\Quote\Item\AbstractItem;

class PercentageCalculator extends AbstractCalculator
{
    /**
     * @inheritdoc
     */
    public function calculate(Total $total, Quote $quote)
    {
        if ($this->isApplyForMatchingItems()) {
            return $this->calculateForMatchingItems($quote);
        }

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

    /**
     * Calculate percentage fee from the matching cart items subtotal
     *
     * Shipping is never included here since it cannot be attributed to individual items.
     *
     * @param Quote $quote
     * @return float
     */
    protected function calculateForMatchingItems(Quote $quote)
    {
        $fee = $this->_helper->getExtraFee($quote->getStoreId());
        $isIncludeDiscount = $this->_helper->getIsIncludeDiscount();

        $subTotal = 0;
        foreach ($this->getMatchingItems($quote) as $item) {
            $subTotal += $item->getBaseRowTotal();
            if ($isIncludeDiscount) {
                $subTotal -= $this->getItemBaseDiscount($item);
            }
        }

        return ($subTotal * $fee) / 100;
    }

    /**
     * Get item base discount including discount held on its children
     *
     * @param AbstractItem $item
     * @return float
     */
    protected function getItemBaseDiscount(AbstractItem $item)
    {
        $discount = (float) $item->getBaseDiscountAmount();
        if ($item->getHasChildren() && $item->isChildrenCalculated()) {
            foreach ($item->getChildren() as $child) {
                $discount += (float) $child->getBaseDiscountAmount();
            }
        }
        return $discount;
    }
}
