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

namespace Prince\Extrafee\Block\Adminhtml\Sales\Order\Invoice;

class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $_invoice = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Prince\Extrafee\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Prince\Extrafee\Helper\Data $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
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
