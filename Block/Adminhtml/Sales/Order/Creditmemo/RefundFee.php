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

namespace Mageprince\Extrafee\Block\Adminhtml\Sales\Order\Creditmemo;

use Magento\Backend\Block\Template;
use Magento\Framework\Currency\Data\Currency as CurrencyData;
use Magento\Framework\DataObjectFactory;
use Magento\Sales\Model\Order\Creditmemo;
use Mageprince\Extrafee\Helper\Data;
use Mageprince\Extrafee\Plugin\Sales\Model\Order\CreditmemoFactoryPlugin;

class RefundFee extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Mageprince_Extrafee::order/creditmemo/create/refund_fee.phtml';

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * RefundFee constructor.
     *
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
     * Get credit memo
     *
     * @return Creditmemo|null
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Add the refund fee input row next to the refund shipping adjustments
     *
     * @return $this
     */
    public function initTotals()
    {
        if (!$this->canRefundFee()) {
            return $this;
        }

        $this->getParentBlock()->addTotalBefore(
            $this->dataObjectFactory->create()->setData([
                'code' => 'mageprince_extrafee_refund',
                'block_name' => $this->getNameInLayout()
            ]),
            'adjustments'
        );

        return $this;
    }

    /**
     * Check the order has an extra fee that may be refunded
     *
     * @return bool
     */
    public function canRefundFee()
    {
        $source = $this->getSource();
        if (!$source) {
            return false;
        }

        $order = $source->getOrder();
        return $this->helper->isRefund($order->getStoreId()) && $order->getBaseFee() > 0;
    }

    /**
     * Get input label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        $order = $this->getSource()->getOrder();
        $label = __('Refund %1', $this->helper->getTitle($order->getStoreId()));
        if ($order->getBaseFeeTax() > 0) {
            $label = __('Refund %1 (Excl. Tax)', $this->helper->getTitle($order->getStoreId()));
        }
        return $label;
    }

    /**
     * Get input name
     *
     * @return string
     */
    public function getInputName()
    {
        return 'creditmemo[' . CreditmemoFactoryPlugin::FEE_AMOUNT . ']';
    }

    /**
     * Get base extra fee amount of the credit memo formatted for the input
     *
     * @return string
     */
    public function getFeeAmount()
    {
        return $this->getSource()->getOrder()->getOrderCurrency()->formatPrecision(
            (float) $this->getSource()->getBaseFee(),
            2,
            ['display' => CurrencyData::NO_SYMBOL],
            false,
            false
        );
    }
}
