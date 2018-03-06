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

namespace Prince\Extrafee\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class AfterOrder
 * @package Prince\Extrafee\Observer
 */
class AfterOrder implements ObserverInterface
{
    /**
     * Set payment fee to order
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();
        $extraFee = $quote->getFee();
        $extraBaseFee = $quote->getBaseFee();
        if (!$extraFee || !$extraBaseFee) {
            return $this;
        }
        
        $order = $observer->getOrder();
        $order->setData('fee', $extraFee);
        $order->setData('base_fee', $extraBaseFee);

        return $this;
    }
}
