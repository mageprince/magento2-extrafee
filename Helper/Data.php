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

    /**
     * Get module status
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->getConfig('extrafee/general/active');
    }

    /**
     * Get minimum order amount to add extrafee
     *
     * @return bool
     */
    public function getMinOrderTotal()
    {
        return $this->getConfig('extrafee/general/minorderamount');
    }

    /**
     * Get extrafee title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfig('extrafee/general/title');
    }

    /**
     * Get extrafee amount
     *
     * @return number
     */
    public function getExtraFee()
    {
        return $this->getConfig('extrafee/general/price');
    }

    /**
     * Get extrafee price type
     *
     * @return bool
     */
    public function getPriceType()
    {
        return $this->getConfig('extrafee/general/pricetype');
    }
}