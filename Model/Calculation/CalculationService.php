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

namespace Prince\Extrafee\Model\Calculation;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Quote\Model\Quote;
use Prince\Extrafee\Helper\Data as FeeHelper;
use Prince\Extrafee\Model\Calculation\Calculator\CalculatorInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CalculationService acts as wrapper around actual CalculatorInterface so logic valid for all calculations like
 * min order amount is only done once.
 *
 * @package Prince\Extrafee\Model\Calculation
 */
class CalculationService implements CalculatorInterface
{
    /**
     * @var CalculatorFactory
     */
    protected $factory;

    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CalculationService constructor.
     * @param CalculatorFactory $factory
     * @param FeeHelper $helper
     * @param LoggerInterface $logger
     */
    public function __construct(CalculatorFactory $factory, FeeHelper $helper, LoggerInterface $logger)
    {
        $this->factory = $factory;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function calculate(Quote $quote): float
    {
        // If module not enabled the fee is 0.0
        if (!$this->helper->isEnable()) {
            return 0.0;
        }

        if (!$this->hasMinOrderTotal($quote)) {
            return 0.0;
        }

        if ($this->hasMaxOrderTotal($quote)) {
            return 0.0;
        }

        try {
            return $this->factory->get()->calculate($quote);
        } catch (ConfigurationMismatchException $e) {
            $this->logger->error($e);
            return 0.0;
        }
    }

    /**
     * @param Quote $quote
     * @return bool
     */
    private function hasMinOrderTotal(Quote $quote): bool
    {
        $amount = $quote->getSubtotal();
        return $amount - $this->helper->getMinOrderTotal() > 0.0001;
    }



    /**
     * @param Quote $quote
     * @return bool
     */
    private function hasMaxOrderTotal(Quote $quote): bool
    {
        $amount = $quote->getSubtotal();
        return $amount - $this->helper->getMaxOrderTotal() > 0.0001;
    }
}