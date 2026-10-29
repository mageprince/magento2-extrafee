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

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Mageprince\Extrafee\Model\Sales\FeeExtensionAttributes;

class OrderRepositoryPlugin
{
    /**
     * Extrafee field name
     */
    public const FIELD_NAME = 'fee';

    /**
     * @var FeeExtensionAttributes
     */
    protected $feeExtensionAttributes;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param FeeExtensionAttributes $feeExtensionAttributes
     */
    public function __construct(FeeExtensionAttributes $feeExtensionAttributes)
    {
        $this->feeExtensionAttributes = $feeExtensionAttributes;
    }

    /**
     * Add extra fee extension attributes to order data object to make them accessible in API data
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        $this->feeExtensionAttributes->apply($order);

        return $order;
    }

    /**
     * Add extra fee extension attributes to order data objects to make them accessible in API data
     *
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     * @return OrderSearchResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        foreach ($searchResult->getItems() as $order) {
            $this->feeExtensionAttributes->apply($order);
        }

        return $searchResult;
    }
}
