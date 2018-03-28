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

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\ConfigurationMismatchException;
use Prince\Extrafee\Helper\Data as FeeHelper;
use Prince\Extrafee\Model\Config\Source\PriceType;

class CalculatorFactory
{
    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * CalculationFactory constructor.
     *
     * @param ObjectManager $objectManager
     * @param FeeHelper $helper
     */
    public function __construct(ObjectManager $objectManager, FeeHelper $helper)
    {
        $this->helper = $helper;
        $this->objectManager = $objectManager;
    }

    /**
     * @return Calculator\CalculatorInterface
     * @throws ConfigurationMismatchException
     */
    public function get(): Calculator\CalculatorInterface
    {
        switch ($this->helper->getPriceType()) {
            case PriceType::TYPE_FIXED:
                return $this->objectManager->get(Calculator\FixedCalculator::class);
            case PriceType::TYPE_PERCENTAGE:
                return $this->objectManager->get(Calculator\PercentageCalculator::class);
            default:
                throw new ConfigurationMismatchException(__('Could not find price calculator for type %1', $this->helper->getPriceType()));
        }
    }
}