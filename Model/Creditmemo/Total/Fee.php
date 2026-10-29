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

namespace Mageprince\Extrafee\Model\Creditmemo\Total;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Magento\Sales\Model\Order\Invoice;
use Mageprince\Extrafee\Helper\Data;

class Fee extends AbstractTotal
{
    /**
     * Order data key holding the base extra fee amount the admin asked to refund
     */
    public const REFUND_AMOUNT = 'mageprince_extrafee_refund_amount';

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * Fee constructor.
     * @param Data $helper
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        Data $helper,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($data);
        $this->helper = $helper;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Collect totals
     *
     * Refunds the requested base amount, capped at the invoiced fee not yet refunded.
     * Without a requested amount the whole remaining fee is refunded.
     *
     * @param Creditmemo $creditmemo
     * @return $this
     * @throws LocalizedException
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setFee(0);
        $creditmemo->setBaseFee(0);
        $creditmemo->setFeeTax(0);
        $creditmemo->setBaseFeeTax(0);

        $order = $creditmemo->getOrder();
        if (!$this->helper->isRefund($order->getStoreId()) || $order->getBaseFee() <= 0) {
            return $this;
        }

        $allowed = $this->getAllowedAmounts($creditmemo);
        $desiredAmount = $order->getData(self::REFUND_AMOUNT);

        if ($desiredAmount === null) {
            $amounts = $allowed;
        } else {
            $desiredAmount = $this->priceCurrency->round($desiredAmount);
            // Note: ($x > $y + 0.0001) means ($x > $y) for floats
            if ($desiredAmount > $this->priceCurrency->round($allowed['base_fee']) + 0.0001) {
                throw new LocalizedException(
                    __(
                        'Maximum %1 amount allowed to refund is: %2',
                        $this->helper->getTitle($order->getStoreId()),
                        $order->getBaseCurrency()->format($allowed['base_fee'], null, false)
                    )
                );
            }
            $amounts = $desiredAmount > $allowed['base_fee'] - 0.0001
                ? $allowed
                : $this->getAmountsByRatio($order, $desiredAmount / $order->getBaseFee());
        }

        if ($amounts['base_fee'] <= 0) {
            return $this;
        }

        $creditmemo->setFee($amounts['fee']);
        $creditmemo->setBaseFee($amounts['base_fee']);
        $creditmemo->setFeeTax($amounts['fee_tax']);
        $creditmemo->setBaseFeeTax($amounts['base_fee_tax']);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $amounts['fee']);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $amounts['base_fee']);

        return $this;
    }

    /**
     * Get fee amounts invoiced and not yet refunded
     *
     * @param Creditmemo $creditmemo
     * @return array
     */
    protected function getAllowedAmounts(Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();
        $invoices = $creditmemo->getInvoice() ? [$creditmemo->getInvoice()] : $order->getInvoiceCollection();

        $allowed = $this->getEmptyAmounts();
        foreach ($invoices as $invoice) {
            if ($invoice->getState() != Invoice::STATE_CANCELED) {
                $allowed = $this->addAmounts($allowed, $invoice, 1);
            }
        }

        if ($order->getId()) {
            foreach ($order->getCreditmemosCollection() as $refunded) {
                if ($refunded->getState() != Creditmemo::STATE_CANCELED && $refunded->getId() != $creditmemo->getId()) {
                    $allowed = $this->addAmounts($allowed, $refunded, -1);
                }
            }
        }

        return array_map(function ($amount) {
            return max(0, $this->priceCurrency->round($amount));
        }, $allowed);
    }

    /**
     * Get order fee amounts scaled by ratio
     *
     * @param Order $order
     * @param float $ratio
     * @return array
     */
    protected function getAmountsByRatio(Order $order, $ratio)
    {
        return [
            'fee' => $this->priceCurrency->round($order->getFee() * $ratio),
            'base_fee' => $this->priceCurrency->round($order->getBaseFee() * $ratio),
            'fee_tax' => $this->priceCurrency->round($order->getFeeTax() * $ratio),
            'base_fee_tax' => $this->priceCurrency->round($order->getBaseFeeTax() * $ratio)
        ];
    }

    /**
     * Add fee amounts of a sales document to the running amounts
     *
     * @param array $amounts
     * @param \Magento\Framework\DataObject $document
     * @param int $sign
     * @return array
     */
    protected function addAmounts(array $amounts, $document, $sign)
    {
        foreach (array_keys($amounts) as $key) {
            $amounts[$key] += $sign * (float) $document->getData($key);
        }
        return $amounts;
    }

    /**
     * Get zero fee amounts
     *
     * @return array
     */
    protected function getEmptyAmounts()
    {
        return ['fee' => 0.0, 'base_fee' => 0.0, 'fee_tax' => 0.0, 'base_fee_tax' => 0.0];
    }
}
