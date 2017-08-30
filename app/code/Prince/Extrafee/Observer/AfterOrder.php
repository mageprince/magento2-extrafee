<?php

namespace Prince\Extrafee\Observer;

use Magento\Framework\Event\ObserverInterface;

class AfterOrder implements ObserverInterface
{
    //private $orderFacory;

    private $extraFeeModel;

    private $helper;

    public function __construct(
    	\Prince\Extrafee\Model\Extrafee $extraFeeModel,
    	\Prince\Extrafee\Helper\Data $helper,
    	\Magento\Quote\Model\QuoteFactory $quoteFactory
    	//\Magento\Sales\Model\Order $orderFacory
    ) {
    	$this->extraFeeModel = $extraFeeModel;
        $this->helper = $helper;
        //$this->orderFacory = $orderFacory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderIds = $observer->getEvent()->getOrderIds();
        $lastorderId = $orderIds[0];
        $extraFee = $this->helper->getExtraFee();
        $this->extraFeeModel->setOrderId($lastorderId);
        $this->extraFeeModel->setFee($extraFee);
        $this->extraFeeModel->save();
        //$order = $this->orderFacory->load($lastorderId);
        //print_r($order->getData()); exit;
    }
}
