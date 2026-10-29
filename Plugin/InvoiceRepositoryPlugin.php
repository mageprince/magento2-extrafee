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

namespace Mageprince\Extrafee\Plugin;

use Magento\Sales\Api\Data\InvoiceInterface;
use Magento\Sales\Api\Data\InvoiceSearchResultInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Mageprince\Extrafee\Model\Sales\FeeExtensionAttributes;

class InvoiceRepositoryPlugin
{
    /**
     * @var FeeExtensionAttributes
     */
    protected $feeExtensionAttributes;

    /**
     * InvoiceRepositoryPlugin constructor
     *
     * @param FeeExtensionAttributes $feeExtensionAttributes
     */
    public function __construct(FeeExtensionAttributes $feeExtensionAttributes)
    {
        $this->feeExtensionAttributes = $feeExtensionAttributes;
    }

    /**
     * Add extra fee extension attributes to invoice data object to make them accessible in API data
     *
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceInterface $invoice
     * @return InvoiceInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(InvoiceRepositoryInterface $subject, InvoiceInterface $invoice)
    {
        $this->feeExtensionAttributes->apply($invoice);

        return $invoice;
    }

    /**
     * Add extra fee extension attributes to invoice data objects to make them accessible in API data
     *
     * @param InvoiceRepositoryInterface $subject
     * @param InvoiceSearchResultInterface $searchResult
     * @return InvoiceSearchResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(InvoiceRepositoryInterface $subject, InvoiceSearchResultInterface $searchResult)
    {
        foreach ($searchResult->getItems() as $invoice) {
            $this->feeExtensionAttributes->apply($invoice);
        }

        return $searchResult;
    }
}
