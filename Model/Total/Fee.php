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

namespace Mageprince\Extrafee\Model\Total;

use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Tax\Model\Calculation;
use Mageprince\Extrafee\Helper\Data as FeeHelper;
use Mageprince\Extrafee\Model\Calculation\Calculator\CalculatorInterface;

class Fee extends Address\Total\AbstractTotal
{
    /**
     * Amount of fee tax this collector last folded into the shared tax total
     */
    public const APPLIED_TAX = 'mageprince_extrafee_applied_tax';

    /**
     * Base amount of fee tax this collector last folded into the shared tax total
     */
    public const APPLIED_BASE_TAX = 'mageprince_extrafee_applied_base_tax';

    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @var Calculation
     */
    protected $taxCalculator;

    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * @var mixed
     */
    protected $feeConditions;

    /**
     * Fee constructor.
     * @param FeeHelper $helper
     * @param CalculatorInterface $calculator
     * @param Calculation $taxCalculator
     * @param RuleFactory $ruleFactory
     */
    public function __construct(
        FeeHelper $helper,
        CalculatorInterface $calculator,
        Calculation $taxCalculator,
        RuleFactory $ruleFactory
    ) {
        $this->calculator = $calculator;
        $this->helper = $helper;
        $this->taxCalculator = $taxCalculator;
        $this->ruleFactory = $ruleFactory;
    }

    /**
     * Collect totals
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Address\Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $this->resetAmounts($total);

        if (!$this->isApplicable($quote, $shippingAssignment)) {
            return $this;
        }

        $baseFee = 0;
        $fee = 0;
        $baseTax = 0;
        $tax = 0;

        if ($this->helper->isEnable() && $this->validateAddress($quote)) {
            $baseFee = $this->calculator->calculate($total, $quote);
            $fee = $baseFee * $this->getBaseToQuoteRate($quote);

            if ($this->helper->isTaxEnabled()) {
                $taxClassId = $this->helper->getTaxClassId();
                if ($taxClassId) {
                    $taxRateRequest = $this->_getRateTaxRequest($quote);
                    $taxRateRequest->setProductClassId($taxClassId);
                    $rate = $this->taxCalculator->getRate($taxRateRequest);
                    $baseTax = $this->calculateTax($baseFee, $rate);
                    $tax = $this->calculateTax($fee, $rate);
                }
            }
        }

        $total->setTotalAmount($this->getCode(), $fee);
        $total->setBaseTotalAmount($this->getCode(), $baseFee);
        $total->setFee($fee);
        $total->setBaseFee($baseFee);
        $total->setBaseFeeTax($baseTax);
        $total->setFeeTax($tax);
        $total->addBaseTotalAmount('tax', $baseTax);
        $total->addTotalAmount('tax', $tax);
        $total->setData(self::APPLIED_BASE_TAX, $baseTax);
        $total->setData(self::APPLIED_TAX, $tax);
        $quote->setFee($fee);
        $quote->setBaseFee($baseFee);
        $quote->setFeeTax($tax);
        $quote->setBaseFeeTax($baseTax);

        return $this;
    }

    /**
     * Clear every amount a previous pass of this collector left on the total
     *
     * @param Address\Total $total
     * @return void
     */
    protected function resetAmounts(Address\Total $total)
    {
        $appliedTax = (float) $total->getData(self::APPLIED_TAX);
        $appliedBaseTax = (float) $total->getData(self::APPLIED_BASE_TAX);
        if ($appliedTax) {
            $total->addTotalAmount('tax', -$appliedTax);
        }
        if ($appliedBaseTax) {
            $total->addBaseTotalAmount('tax', -$appliedBaseTax);
        }

        $total->setData(self::APPLIED_TAX, 0);
        $total->setData(self::APPLIED_BASE_TAX, 0);
        $total->setTotalAmount($this->getCode(), 0);
        $total->setBaseTotalAmount($this->getCode(), 0);
        $total->setTotalAmount('fee_tax', 0);
        $total->setBaseTotalAmount('fee_tax', 0);
        $total->setFee(0);
        $total->setBaseFee(0);
        $total->setFeeTax(0);
        $total->setBaseFeeTax(0);
    }

    /**
     * Check the collector is running against the address the order is built from
     *
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @return bool
     */
    protected function isApplicable(Quote $quote, ShippingAssignmentInterface $shippingAssignment)
    {
        if (!count($shippingAssignment->getItems())) {
            return false;
        }

        $address = $shippingAssignment->getShipping()->getAddress();
        $expectedType = $quote->isVirtual() ? Address::TYPE_BILLING : Address::TYPE_SHIPPING;
        if ($address->getAddressType() && $address->getAddressType() != $expectedType) {
            return false;
        }

        // Multishipping builds one order per shipping address, each with its own totals.
        if ($quote->getIsMultiShipping()) {
            return true;
        }

        $orderAddress = $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
        $orderAddressId = $orderAddress ? $orderAddress->getId() : null;

        return !($orderAddressId && $address->getId() && (int) $address->getId() !== (int) $orderAddressId);
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param Quote $quote
     * @param Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(Quote $quote, Address\Total $total)
    {
        $fee = $total->getFee();

        $result = [
            [
                'code' => $this->getCode(),
                'title' => __($this->helper->getTitle()),
                'value' => $fee
            ]
        ];

        if ($this->helper->isTaxEnabled() &&
            $this->helper->displayInclTax() &&
            !$this->helper->isBackendArea()
        ) {
            $address = $this->getAddressFromQuote($quote);
            $result [] = [
                'code' => 'fee_incl_tax',
                'value' => $fee + $address->getFeeTax()
            ];
        }

        return $result;
    }

    /**
     * Get address from quote
     *
     * @param Quote $quote
     * @return Address
     */
    protected function getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }

    /**
     * Get base to quote currency rate
     *
     * @param Quote $quote
     * @return float
     */
    protected function getBaseToQuoteRate(Quote $quote)
    {
        $rate = (float) $quote->getBaseToQuoteRate();
        if ($rate <= 0) {
            $rate = (float) $quote->getStore()->getCurrentCurrencyRate();
        }
        return $rate > 0 ? $rate : 1.0;
    }

    /**
     * Get tax request for quote address
     *
     * @param Quote $quote
     * @return \Magento\Framework\DataObject
     */
    protected function _getRateTaxRequest(Quote $quote)
    {
        $rateTaxRequest = $this->taxCalculator->getRateRequest(
            $quote->getShippingAddress(),
            $quote->getBillingAddress(),
            $quote->getCustomerTaxClassId(),
            $quote->getStore(),
            $quote->getCustomerId()
        );
        return $rateTaxRequest;
    }

    /**
     * Calculate tax
     *
     * @param float $fee
     * @param float $rate
     * @return float|int
     */
    protected function calculateTax($fee, $rate)
    {
        return $this->taxCalculator->calcTaxAmount(
            $fee,
            $rate,
            false,
            true
        );
    }

    /**
     * Check if condition match
     *
     * @param Quote $quote
     * @return bool
     */
    public function validateAddress(Quote $quote)
    {
        $valid = false;
        $conditions = $this->helper->getExtraFeeConditions();
        $salesRule = $this->ruleFactory->create();
        $salesRule->setConditionsSerialized($conditions);
        $address = $quote->getShippingAddress();
        if ($quote->isVirtual()) {
            $address = $quote->getBillingAddress();
        }
        $address->setCollectShippingRates(true);
        //$address->collectShippingRates(); //Fix infinite loop

        if ($address->getTotalQty() === null) {
            $address->setData('total_qty', $quote->getData('items_qty'));
        }
        if ($address->getBaseSubtotal() === null) {
            $address->setData('base_subtotal', $quote->getData('base_subtotal'));
        }

        if ($salesRule->validate($address)) {
            $valid = true;
        }
        return $valid;
    }

    /**
     * Get label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __($this->helper->getTitle());
    }
}
