<?php

/**
 * MagePrince
 * Copyright (C) 2018 Mageprince
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html
 *
 * @category MagePrince
 * @package Prince_Extrafee
 * @copyright Copyright (c) 2018 MagePrince
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MagePrince
 */

namespace Prince\Extrafee\Model\Total;

use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Prince\Extrafee\Helper\Data as FeeHelper;
use Prince\Extrafee\Model\Calculation\CalculatorInterface;

/**
 * Class Fee
 * @package Prince\Extrafee\Model\Total
 */
class Fee extends Address\Total\AbstractTotal
{
    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @param FeeHelper $helper
     * @param CalculatorInterface $calculator
     */
    public function __construct(FeeHelper $helper, CalculatorInterface $calculator) {
        $this->calculator = $calculator;
        $this->helper = $helper;
    }

    /**
     * @param Quote $quote
     * @param ShippingAssignmentInterface $shippingAssignment
     * @param Address\Total $total
     * @return $this
     */
    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, Address\Total $total)
    {
        parent::collect($quote, $shippingAssignment, $total);

        $fee = $this->calculator->calculate($quote);
        $total->setTotalAmount('fee', $fee);
        $total->setBaseTotalAmount('fee', $fee);
        $total->setFee($fee);
        $total->setBaseFee($fee);
        $quote->setFee($fee);
        $quote->setBaseFee($fee);
        $quote->setGrandTotal($total->getGrandTotal() + $fee);
        $quote->setBaseGrandTotal($total->getBaseGrandTotal() + $fee);
        
        return $this;
    }

    /**
     * @param Address\Total $total
     */
    protected function clearValues(Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param Quote $quote
     * @param Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(Quote $quote, Address\Total $total): array
    {
        $result = [];
        $fee = $this->calculator->calculate($quote);

        if ($fee > 0.0) {
            $result = [
                'code' => 'fee',
                'title' => $this->getLabel(),
                'value' => $fee
            ];
        }

        return $result;
    }

    /**
     * Get label
     *
     * @return Phrase
     */
    public function getLabel(): Phrase
    {
        return __($this->helper->getTitle());
    }
}
