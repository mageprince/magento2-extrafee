<?php

namespace Prince\Extrafee\Block\Adminhtml\Sales\Order\Invoice;

class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $_invoice = null;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;

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

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();

        if(!$this->getSource()->getFee()) {
            return $this;
        }
        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'fee',
                'value' => $this->getSource()->getFee(),
                'label' => $this->_helper->getTitle(),
            ]
        );

        $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        return $this;
    }
}
