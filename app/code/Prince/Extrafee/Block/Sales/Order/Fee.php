<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Tax totals modification block. Can be used just as subblock of \Magento\Sales\Block\Order\Totals
 */
namespace Prince\Extrafee\Block\Sales\Order;

class Fee extends \Magento\Framework\View\Element\Template
{
    /**
     * Tax configuration model
     *
     * @var \Magento\Tax\Model\Config
     */
    protected $_config;

    /**
     * @var Order
     */
    protected $_order;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @var \Prince\Extrafee\Helper
     */
    protected $_helper;

    private $extraFeeModel;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Tax\Model\Config $taxConfig,
        \Prince\Extrafee\Helper\Data $helper,
        \Prince\Extrafee\Model\Extrafee $extraFeeModel,
        array $data = []
    ) {
        $this->_config = $taxConfig;
        $this->_helper = $helper;
        $this->extraFeeModel = $extraFeeModel;
        parent::__construct($context, $data);
    }

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    } 
    public function getStore()
    {
        return $this->_order->getStore();
    }

      /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * Initialize all order totals relates with tax
     *
     * @return \Magento\Tax\Block\Sales\Order\Tax
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();

        $orderId = $this->_order->getId();
        $store = $this->getStore();

        $collection = $this->extraFeeModel->getCollection();
        $collection->addFieldToFilter('order_id', $orderId);

        if($collection->getSize()){

            $extraFee = $collection->getFirstItem()->getFee();
            
            $fee = new \Magento\Framework\DataObject(
                    [
                        'code' => 'fee',
                        'strong' => false,
                        'value' => $extraFee,
                        'label' => $this->_helper->getTitle()
                    ]
                );

            $parent->addTotal($fee, 'fee');
            //$parent->addTotal($fee, 'fee');
        
        }

        return $this;
    }

}
