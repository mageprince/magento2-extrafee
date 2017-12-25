<?php

namespace Prince\Extrafee\Observer;

use Magento\Framework\Event\ObserverInterface;

class AfterOrder implements ObserverInterface
{
    /**
     * @var \Prince\Extrafee\Model\Extrafee
     */
    private $extraFeeModel;

    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    private $helper;

    /**
     * @param \Prince\Extrafee\Model\Extrafee $extraFeeModel
     * @param \Prince\Extrafee\Helper\Data $helper
     * @param \Magento\Quote\Model\QuoteFactory $quoteFactory
     */
    public function __construct(
    	\Prince\Extrafee\Model\Extrafee $extraFeeModel,
    	\Prince\Extrafee\Helper\Data $helper,
    	\Magento\Quote\Model\QuoteFactory $quoteFactory
    ) {
    	$this->extraFeeModel = $extraFeeModel;
        $this->helper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        $lastorderId = $orderIds[0];
        $extraFee = $this->helper->getExtraFee();
        $this->extraFeeModel->setOrderId($lastorderId);
        $this->extraFeeModel->setFee($extraFee);
        $this->extraFeeModel->save();
    }
}
