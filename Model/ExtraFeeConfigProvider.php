<?php
namespace Prince\Extrafee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;

class ExtraFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Prince\Extrafee\Helper\Data
     */
    protected $_helper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param \Prince\Extrafee\Helper\Data $helper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Prince\Extrafee\Helper\Data $helper,
        \Magento\Checkout\Model\Session $checkoutSession
    ) {
        $this->_helper = $helper;
        $this->checkoutSession = $checkoutSession;
    }

    public function getConfig()
    {
        $extraFeeConfig = [];
        $enabled = $this->_helper->isEnable();
        $minOrderTotal = $this->_helper->getMinOrderTotal();
        $extraFeeConfig['fee_title'] = $this->_helper->getTitle();
        $quote = $this->checkoutSession->getQuote();
        $subTotal = $quote->getSubtotal();
        $priceType = $this->_helper->getPriceType();
        $extraFeeAmount = $this->_helper->getExtraFee();
        if ($priceType) {
            $extraFeeAmount = ($subTotal * $extraFeeAmount) / 100;
        }
        $extraFeeConfig['extra_fee_amount'] = $extraFeeAmount;
        $extraFeeConfig['show_hide_extrafee'] = ($enabled && ($minOrderTotal >= $subTotal) && $quote->getFee()) ? true : false;
        return $extraFeeConfig;
    }
}
