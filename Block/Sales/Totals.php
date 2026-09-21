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
use Magento\Framework\View\Element\AbstractBlock;
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

        if ($this->isAlreadyAdded($parent)) {
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

        $displayExclTax = $this->helper->displayExclTax($storeId);
        $displayInclTax = $this->helper->displayInclTax($storeId);

        if ($displayExclTax && $displayInclTax && $extraFeeInclTax == $extraFeeExclTax) {
            $displayInclTax = false;
        }

        if ($displayExclTax && $displayInclTax) {
            $inclTxt = __('Incl. Tax');
            $exclTxt = __('Excl. Tax');
            $extraFeeInclTaxTotal['label'] .= ' ' . $inclTxt;
            $extraFeeExclTaxTotal['label'] .= ' ' . $exclTxt;
        }

        if ($displayExclTax) {
            $parent->addTotal(
                $this->dataObjectFactory->create()->setData($extraFeeExclTaxTotal),
                'shipping'
            );
        }

        if ($displayInclTax) {
            $parent->addTotal(
                $this->dataObjectFactory->create()->setData($extraFeeInclTaxTotal),
                'shipping'
            );
        }

        return $this;
    }

    /**
     * Check the fee rows are already on the parent totals block
     *
     * @param AbstractBlock|null $parent
     * @return bool
     */
    protected function isAlreadyAdded($parent)
    {
        if (!$parent || !method_exists($parent, 'getTotals')) {
            return false;
        }

        $totals = $parent->getTotals();

        return is_array($totals) && (isset($totals['fee']) || isset($totals['fee_incl_tax']));
    }
}
