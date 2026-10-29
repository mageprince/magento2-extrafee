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

use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Api\Data\CreditmemoInterface;
use Magento\Sales\Api\Data\CreditmemoSearchResultInterface;
use Mageprince\Extrafee\Model\Sales\FeeExtensionAttributes;

class CreditmemoRepositoryPlugin
{
    /**
     * @var FeeExtensionAttributes
     */
    protected $feeExtensionAttributes;

    /**
     * CreditmemoRepositoryPlugin constructor
     *
     * @param FeeExtensionAttributes $feeExtensionAttributes
     */
    public function __construct(FeeExtensionAttributes $feeExtensionAttributes)
    {
        $this->feeExtensionAttributes = $feeExtensionAttributes;
    }

    /**
     * Add extra fee extension attributes to credit memo data object to make them accessible in API data
     *
     * @param CreditmemoRepositoryInterface $subject
     * @param CreditmemoInterface $creditmemo
     * @return CreditmemoInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(CreditmemoRepositoryInterface $subject, CreditmemoInterface $creditmemo)
    {
        $this->feeExtensionAttributes->apply($creditmemo);

        return $creditmemo;
    }

    /**
     * Add extra fee extension attributes to credit memo data objects to make them accessible in API data
     *
     * @param CreditmemoRepositoryInterface $subject
     * @param CreditmemoSearchResultInterface $searchResult
     * @return CreditmemoSearchResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(CreditmemoRepositoryInterface $subject, CreditmemoSearchResultInterface $searchResult)
    {
        foreach ($searchResult->getItems() as $creditmemo) {
            $this->feeExtensionAttributes->apply($creditmemo);
        }

        return $searchResult;
    }
}
