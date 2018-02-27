<?php

namespace Prince\Extrafee\Block\Sales;

/**
 * Class Totals
 * @package Prince\Extrafee\Block\Sales
 */
class Totals extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;
   
    /**
     * @var \Magento\Directory\Model\Currency
     */
    protected $_currency;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Prince\Extrafee\Helper\Data $helper
     * @param \Magento\Directory\Model\Currency $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Prince\Extrafee\Helper\Data $helper,
        \Magento\Directory\Model\Currency $currency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_helper = $helper;
        $this->_currency = $currency;
    }

    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
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
