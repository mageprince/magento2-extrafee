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

namespace Mageprince\Extrafee\Block\Sales;

use Magento\Framework\DataObjectFactory;
use Magento\Framework\View\Element\Template;
use Mageprince\Extrafee\Helper\Data;

class Totals extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * Totals constructor.
     * @param Template\Context $context
     * @param Data $helper
     * @param DataObjectFactory $dataObjectFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helper,
        DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct($context, $data);
    }

    /**
     * Get source
     *
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Init totals
     *
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $source = $this->getSource();
        $storeId = $source->getStoreId();

        if ($source->getFee() == 0) {
            return $this;
        }

        $extraFeeTitle = $this->helper->getTitle($storeId);

        $extraFeeExclTax = $source->getFee();
        $baseExtraFeeExclTax = $source->getBaseFee();
        $extraFeeExclTaxTotal = [
            'code' => 'fee',
            'strong' => false,
            'value' => $extraFeeExclTax,
            'base_value' => $baseExtraFeeExclTax,
            'label' => $extraFeeTitle,
        ];

        $extraFeeInclTax = $extraFeeExclTax + $source->getFeeTax();
        $baseExtraFeeInclTax = $baseExtraFeeExclTax + $source->getBaseFeeTax();
        $extraFeeInclTaxTotal = [
            'code' => 'fee_incl_tax',
            'strong' => false,
            'value' => $extraFeeInclTax,
            'base_value' => $baseExtraFeeInclTax,
            'label' => $extraFeeTitle,
        ];

        if ($this->helper->displayExclTax($storeId) && $this->helper->displayInclTax($storeId)) {
            $inclTxt = __('Incl. Tax');
            $exclTxt = __('Excl. Tax');
            $extraFeeInclTaxTotal['label'] .= ' ' . $inclTxt;
            $extraFeeExclTaxTotal['label'] .= ' ' . $exclTxt;
        }

        if ($this->helper->displayExclTax($storeId)) {
            $parent->addTotal(
                $this->dataObjectFactory->create()->setData($extraFeeExclTaxTotal),
                'shipping'
            );
        }

        if ($this->helper->displayInclTax($storeId)) {
            $parent->addTotal(
                $this->dataObjectFactory->create()->setData($extraFeeInclTaxTotal),
                'shipping'
            );
        }

        return $this;
    }
}
