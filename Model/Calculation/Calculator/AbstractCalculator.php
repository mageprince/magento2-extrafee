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
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Mageprince\Extrafee\Helper\Data as FeeHelper;
use Mageprince\Extrafee\Model\Calculation\ItemMatcher;
use Mageprince\Extrafee\Model\Config\Source\ApplyFor;

abstract class AbstractCalculator implements CalculatorInterface
{
    /**
     * @var FeeHelper
     */
    protected $_helper;

    /**
     * @var ItemMatcher
     */
    protected $itemMatcher;

    /**
     * AbstractCalculation constructor.
     *
     * @param FeeHelper $helper
     * @param ItemMatcher $itemMatcher
     */
    public function __construct(FeeHelper $helper, ItemMatcher $itemMatcher)
    {
        $this->_helper = $helper;
        $this->itemMatcher = $itemMatcher;
    }

    /**
     * Check if fee is applied for matching cart items only
     *
     * @return bool
     */
    protected function isApplyForMatchingItems()
    {
        return $this->_helper->getApplyFor() === ApplyFor::MATCHING_ITEMS;
    }

    /**
     * Get cart items matching the fee conditions
     *
     * @param Quote $quote
     * @return AbstractItem[]
     */
    protected function getMatchingItems(Quote $quote)
    {
        return $this->itemMatcher->getMatchingItems($quote);
    }
}
