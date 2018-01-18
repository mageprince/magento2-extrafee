<?php

namespace Prince\Extrafee\Block\Adminhtml\Sales\Order\Creditmemo;

class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Sales\Model\Order\Creditmemo
     */
    protected $_creditmemo = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Prince\Extrafee\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Prince\Extrafee\Helper\Data $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCreditmemo()
    {
        return $this->getParentBlock()->getCreditmemo();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getCreditmemo();
        $this->getSource();

        if(!$this->getSource()->getFee()) {
            return $this;
        }
        
        $fee = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'strong' => false,
                'value' => $this->getSource()->getFee(),
                'label' => $this->_helper->getTitle(),
            ]
        );

        $this->getParentBlock()->addTotalBefore($fee, 'grand_total');

        return $this;
    }
}
