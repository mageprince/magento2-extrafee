<?php

namespace Prince\Extrafee\Model\Total;

/**
 * Class Fee
 * @package Prince\Extrafee\Model\Total
 */
class Fee extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    
    /**
     * @var \Magento\Quote\Model\QuoteValidator
     */   
    protected $quoteValidator = null; 

    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;

    /**
     * @param \Magento\Quote\Model\QuoteValidator $quoteValidator
     * @param \Prince\Extrafee\Helper\Data $helper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteValidator $quoteValidator,
        \Prince\Extrafee\Helper\Data $helper
    ) {
        $this->quoteValidator = $quoteValidator;
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $enabled = $this->_helper->isEnable();
        $minOrderTotal = $this->_helper->getMinOrderTotal();
        $subTotal = $quote->getSubtotal();
        $fee = 0;

        if ($enabled && $minOrderTotal >= $subTotal) {
            $priceType = $this->_helper->getPriceType();
            $fee = $this->_helper->getExtraFee();

            if ($priceType) {
                $fee = ($subTotal * $fee) / 100;
            }            
        }

        $total->setTotalAmount('fee', $fee);
        $total->setFee($fee);
        $total->setBaseFee($fee);
        $quote->setFee($fee);
        $quote->setBaseFee($fee);
        $total->setBaseGrandTotal($total->getBaseGrandTotal() + $fee);

        return $this;
    }

    /**
     * @param Address\Total $total
     */
    protected function clearValues(Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }

    /**
     * Assign subtotal amount and label to address object
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $enabled = $this->_helper->isEnable();
        $minOrderTotal = $this->_helper->getMinOrderTotal();
        $subTotal = $quote->getSubtotal();

        $result = [];

        if ($enabled && $minOrderTotal >= $subTotal) {

            $priceType = $this->_helper->getPriceType();
            $fee = $this->_helper->getExtraFee();

            if ($priceType){
                $subTotal = $quote->getSubtotal();
                $fee = ($subTotal * $fee) / 100;
            }

            $result = [
                'code' => 'fee',
                'title' => $this->getLabel(),
                'value' => $fee
            ];
        }

        return $result;
    }

    /**
     * Get label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __($this->_helper->getTitle());
    }
}
