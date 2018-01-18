<?php

namespace Prince\Extrafee\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

/**
 * Class Fee
 * @package Prince\Extrafee\Model\Creditmemo\Total
 */
class Fee extends AbstractTotal
{
    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        $creditmemo->setFee(0);
        $creditmemo->setBaseFee(0);
        $amount = $creditmemo->getOrder()->getFee();
        $creditmemo->setFee($amount);
        $amount = $creditmemo->getOrder()->getBaseFee();
        $creditmemo->setBaseFee($amount);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $creditmemo->getFee());
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $creditmemo->getBaseFee());

        return $this;
    }
}
