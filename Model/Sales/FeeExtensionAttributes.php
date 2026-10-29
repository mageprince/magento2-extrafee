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

namespace Mageprince\Extrafee\Model\Sales;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Invoice;

class FeeExtensionAttributes
{
    /**
     * Copy the fee columns of an order, invoice or credit memo to its extension attributes
     *
     * @param Order|Invoice|Creditmemo $entity
     * @return void
     */
    public function apply(Order|Invoice|Creditmemo $entity)
    {
        $extensionAttributes = $entity->getExtensionAttributes();
        $extensionAttributes->setExtrafee($entity->getData('fee'));
        $extensionAttributes->setBaseExtrafee($entity->getData('base_fee'));
        $extensionAttributes->setExtrafeeTax($entity->getData('fee_tax'));
        $extensionAttributes->setBaseExtrafeeTax($entity->getData('base_fee_tax'));
        $entity->setExtensionAttributes($extensionAttributes);
    }
}
