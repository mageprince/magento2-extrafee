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

namespace Mageprince\Extrafee\Model\Calculation;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Item\AbstractItem;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Combine;
use Magento\SalesRule\Model\Rule;
use Magento\SalesRule\Model\Rule\Condition\Product\Combine as ProductCombine;
use Magento\SalesRule\Model\Rule\Condition\Product\Found;
use Magento\SalesRule\Model\RuleFactory;
use Mageprince\Extrafee\Helper\Data as FeeHelper;

class ItemMatcher
{
    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * ItemMatcher constructor.
     *
     * @param FeeHelper $helper
     * @param RuleFactory $ruleFactory
     */
    public function __construct(FeeHelper $helper, RuleFactory $ruleFactory)
    {
        $this->helper = $helper;
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * Get visible quote items matching the product conditions of the fee
     *
     * Items are matched against the "Product found" and "Product subselection" blocks of the
     * condition tree. When the tree has no product conditions, every item matches.
     *
     * @param Quote $quote
     * @return AbstractItem[]
     */
    public function getMatchingItems(Quote $quote)
    {
        $rule = $this->ruleFactory->create();
        $rule->setConditionsSerialized($this->helper->getExtraFeeConditions());
        $conditions = $rule->getConditions();

        $items = [];
        foreach ($quote->getAllVisibleItems() as $item) {
            if ($this->matchCondition($rule, $conditions, $item) !== false) {
                $items[] = $item;
            }
        }
        return $items;
    }

    /**
     * Validate item against a condition
     *
     * Returns null when the condition holds no product conditions and so says nothing about the item.
     *
     * @param Rule $rule
     * @param AbstractCondition $condition
     * @param AbstractItem $item
     * @return bool|null
     */
    protected function matchCondition(Rule $rule, AbstractCondition $condition, AbstractItem $item)
    {
        if ($condition instanceof ProductCombine) {
            // "Product not found" excludes items from the cart, it does not select any.
            if ($condition instanceof Found && !$condition->getValue()) {
                return null;
            }
            return $this->getItemCondition($rule, $condition)->validate($item);
        }

        if (!$condition instanceof Combine) {
            return null;
        }

        $results = [];
        foreach ($condition->getConditions() as $child) {
            $result = $this->matchCondition($rule, $child, $item);
            if ($result !== null) {
                $results[] = $result;
            }
        }

        if (!$results) {
            return null;
        }

        return $condition->getAggregator() === 'all'
            ? !in_array(false, $results, true)
            : in_array(true, $results, true);
    }

    /**
     * Build a per item product condition from a found or subselect condition
     *
     * Found and subselect validate the whole address, so their child conditions are
     * copied into a plain product combine that validates a single item.
     *
     * @param Rule $rule
     * @param ProductCombine $condition
     * @return ProductCombine
     */
    protected function getItemCondition(Rule $rule, ProductCombine $condition)
    {
        $conditionArray = $condition->asArray();
        $itemCondition = $rule->getActionsInstance();
        $itemCondition->setRule($rule)->setId('1')->setPrefix('actions');
        $itemCondition->loadArray([
            'aggregator' => $conditionArray['aggregator'] ?? 'all',
            'value' => true,
            'conditions' => $conditionArray['conditions'] ?? []
        ]);
        return $itemCondition;
    }
}
