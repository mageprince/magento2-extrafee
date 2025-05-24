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

namespace Mageprince\Extrafee\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    /**
     * Collect totals
     *
     * @param Invoice $invoice
     * @return $this
     */
    public function collect(Invoice $invoice)
    {
        $invoice->setFee(0);
        $invoice->setBaseFee(0);
        $invoice->setFeeTax(0);
        $invoice->setBaseFeeTax(0);

        $order = $invoice->getOrder();

        if ($order->getInvoiceCollection()->getTotalCount()) {
            return $this;
        }

        $fee = $order->getFee();
        $baseFee = $order->getBaseFee();
        $feeTax = $order->getFeeTax();
        $baseFeeTax = $order->getBaseFeeTax();

        if ($fee != 0) {
            $invoice->setFee($fee);
            $invoice->setBaseFee($baseFee);
            $invoice->setFeeTax($feeTax);
            $invoice->setBaseFeeTax($baseFeeTax);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $fee + $feeTax);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFee + $baseFeeTax);
            $invoice->setTaxAmount($invoice->getTaxAmount() + $feeTax);
            $invoice->setBaseTaxAmount($invoice->getBaseTaxAmount() + $baseFeeTax);
        }

        return $this;
    }
}
