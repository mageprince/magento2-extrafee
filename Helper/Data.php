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

namespace Prince\Extrafee\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Prince\Extrafee\Helper
 */
class Data extends AbstractHelper
{
    /**
     * @param $config
     * @return mixed
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue($config, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get module status
     *
     * @return bool
     */
    public function isEnable()
    {
        return (bool) $this->getConfig('extrafee/general/active');
    }

    /**
     * Get minimum order amount to add extrafee
     *
     * @return float
     */
    public function getMinOrderTotal()
    {
        return (float) $this->getConfig('extrafee/general/minorderamount');
    }

    /**
     * Get minimum order amount to add extrafee
     *
     * @return float
     */
    public function getMaxOrderTotal()
    {
        return (float) $this->getConfig('extrafee/general/maxorderamount');
    }

    /**
     * Get extrafee title
     *
     * @return string
     */
    public function getTitle()
    {
        return (string) $this->getConfig('extrafee/general/title');
    }

    /**
     * Get extrafee amount
     *
     * @return float
     */
    public function getExtraFee()
    {
        return (float) $this->getConfig('extrafee/general/price');
    }

    /**
     * Get extrafee price type
     *
     * @return int
     */
    public function getPriceType()
    {
        return (int) $this->getConfig('extrafee/general/pricetype');
    }

    /**
     * Get module status
     *
     * @return bool
     */
    public function isRefund()
    {
        return (bool) $this->getConfig('extrafee/general/refund');
    }
}