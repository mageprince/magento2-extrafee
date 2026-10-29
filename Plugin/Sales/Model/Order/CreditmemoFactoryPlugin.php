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

namespace Mageprince\Extrafee\Plugin\Sales\Model\Order;

use Magento\Framework\Locale\FormatInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Order\Invoice;
use Mageprince\Extrafee\Model\Creditmemo\Total\Fee;

class CreditmemoFactoryPlugin
{
    /**
     * Credit memo data key holding the extra fee amount to refund
     */
    public const FEE_AMOUNT = 'fee_amount';

    /**
     * @var FormatInterface
     */
    protected $localeFormat;

    /**
     * CreditmemoFactoryPlugin constructor.
     *
     * @param FormatInterface $localeFormat
     */
    public function __construct(FormatInterface $localeFormat)
    {
        $this->localeFormat = $localeFormat;
    }

    /**
     * Pass the requested extra fee refund amount to the credit memo fee collector
     *
     * @param CreditmemoFactory $subject
     * @param Order $order
     * @param array $data
     * @return null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCreateByOrder(CreditmemoFactory $subject, Order $order, array $data = [])
    {
        $this->setRefundAmount($order, $data);
        return null;
    }

    /**
     * Pass the requested extra fee refund amount to the credit memo fee collector
     *
     * @param CreditmemoFactory $subject
     * @param Invoice $invoice
     * @param array $data
     * @return null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCreateByInvoice(CreditmemoFactory $subject, Invoice $invoice, array $data = [])
    {
        $this->setRefundAmount($invoice->getOrder(), $data);
        return null;
    }

    /**
     * Store the requested amount on the order, or clear it when none was requested
     *
     * @param Order $order
     * @param array $data
     * @return void
     */
    protected function setRefundAmount(Order $order, array $data)
    {
        $amount = isset($data[self::FEE_AMOUNT])
            ? (float) $this->localeFormat->getNumber($data[self::FEE_AMOUNT])
            : null;
        $order->setData(Fee::REFUND_AMOUNT, $amount);
    }
}
