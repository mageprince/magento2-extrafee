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

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Mageprince\Extrafee\Helper\Data;

class Fee extends AbstractTotal
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * Fee constructor.
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Data $helper,
        array $data = []
    ) {
        parent::__construct($data);
        $this->helper = $helper;
    }

    /**
     * Collect totals
     *
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setFee(0);
        $creditmemo->setBaseFee(0);
        $creditmemo->setFeeTax(0);
        $creditmemo->setBaseFeeTax(0);

        $storeId = $creditmemo->getOrder()->getStoreId();
        if (!$this->helper->isRefund($storeId)) {
            return $this;
        }

        $order = $creditmemo->getOrder();
        $fee = $order->getFee();
        $baseFee = $order->getBaseFee();
        $feeTax = $order->getFeeTax();
        $baseFeeTax = $order->getBaseFeeTax();

        if ($fee != 0) {
            $creditmemo->setFee($fee);
            $creditmemo->setBaseFee($baseFee);
            $creditmemo->setFeeTax($feeTax);
            $creditmemo->setBaseFeeTax($baseFeeTax);
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $fee);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseFee);
        }

        return $this;
    }
}
