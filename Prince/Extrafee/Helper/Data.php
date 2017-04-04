<?php

namespace Prince\Extrafee\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * ScopeConfig
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopInterface
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
    )
    {
        $this->_scopeConfig = $scopeInterface;
    }

    public function getTitle()
    {
        return $this->_scopeConfig->getValue(
            'customfee/general/title', 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getExtraFee()
    {
        return $this->_scopeConfig->getValue(
            'customfee/general/price', 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

    }
}