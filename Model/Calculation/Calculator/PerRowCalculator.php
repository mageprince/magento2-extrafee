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

class PerRowCalculator extends AbstractCalculator
{
    /**
     * @inheritdoc
     */
    public function calculate(Total $total, Quote $quote)
    {
        $storeId = $quote->getStoreId();
        $fee = $this->_helper->getExtraFee($storeId);
        return $fee * \count($quote->getAllVisibleItems());
    }
}
