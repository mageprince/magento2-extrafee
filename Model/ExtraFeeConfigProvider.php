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

namespace Prince\Extrafee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ExtraFeeConfigProvider
 * @package Prince\Extrafee\Model
 */
class ExtraFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Prince\Extrafee\Helper\Data $helper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Prince\Extrafee\Helper\Data $helper,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_helper = $helper;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $extraFeeConfig = [];
        $enabled = $this->_helper->isEnable();
        $minOrderTotal = $this->_helper->getMinOrderTotal();
        $extraFeeConfig['fee_title'] = $this->_helper->getTitle();
        $quote = $this->checkoutSession->getQuote();
        $subTotal = $quote->getSubtotal();
        $priceType = $this->_helper->getPriceType();
        $extraFeeAmount = $this->_helper->getExtraFee();
        if ($priceType) {
            $extraFeeAmount = ($subTotal * $extraFeeAmount) / 100;
        }
        $extraFeeConfig['extra_fee_amount'] = $extraFeeAmount;
        $extraFeeConfig['show_hide_extrafee'] = ($enabled && ($minOrderTotal >= $subTotal) && $quote->getFee()) ? true : false;
        return $extraFeeConfig;
    }
}
