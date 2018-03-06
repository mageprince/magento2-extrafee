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

/**
 * Class Data
 * @package Prince\Extrafee\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * ScopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopInterface
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
    ) {
        $this->_scopeConfig = $scopeInterface;
    }

    /**
     * @param $config
     * @return mixed
     */
    public function getConfig($config)
    {
        return $this->_scopeConfig->getValue(
            $config, 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get module status
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->getConfig('extrafee/general/active');
    }

    /**
     * Get minimum order amount to add extrafee
     *
     * @return bool
     */
    public function getMinOrderTotal()
    {
        return $this->getConfig('extrafee/general/minorderamount');
    }

    /**
     * Get extrafee title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfig('extrafee/general/title');
    }

    /**
     * Get extrafee amount
     *
     * @return number
     */
    public function getExtraFee()
    {
        return $this->getConfig('extrafee/general/price');
    }

    /**
     * Get extrafee price type
     *
     * @return bool
     */
    public function getPriceType()
    {
        return $this->getConfig('extrafee/general/pricetype');
    }
}