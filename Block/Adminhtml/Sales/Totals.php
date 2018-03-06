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

namespace Prince\Extrafee\Block\Adminhtml\Sales;

/**
 * Class Totals
 * @package Prince\Extrafee\Block\Adminhtml\Sales
 */
class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;
   
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $_currency;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Prince\Extrafee\Helper\Data $helper
     * @param \Magento\Directory\Model\Currency $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Prince\Extrafee\Helper\Data $helper,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_currency = $currency;
    }

    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        if(!$this->getSource()->getFee()) {
            return $this;
        }
        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'value' => $this->getSource()->getFee(),
                'label' => $this->_helper->getTitle(),
            ]
        );
        $this->getParentBlock()->addTotalBefore($total, 'grand_total');

        return $this;
    }
}
