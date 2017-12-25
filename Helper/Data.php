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
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopInterface
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
    ) {
        $this->_scopeConfig = $scopeInterface;
    }

    public function getConfig($config)
    {
        return $this->_scopeConfig->getValue(
            $config, 
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getTitle()
    {
        return $this->getConfig('customfee/general/title');
    }

    public function getExtraFee()
    {
        return $this->getConfig('customfee/general/price');
    }

    public function getPriceType()
    {
        return $this->getConfig('customfee/general/pricetype');
    }
}