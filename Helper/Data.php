<?php
/**
 * MagePrince
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageprince.com license that is
 * available through the world-wide-web at this URL:
 * https://mageprince.com/end-user-license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    MagePrince
 * @package     Mageprince_Extrafee
 * @copyright   Copyright (c) MagePrince (https://mageprince.com/)
 * @license     https://mageprince.com/end-user-license-agreement
 */

namespace Mageprince\Extrafee\Helper;

use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory as ConfigDataCollection;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;
use Magento\Store\Model\ScopeInterface;
use Mageprince\Base\Helper\Data as BaseHelper;
use Mageprince\Extrafee\Model\Config\DefaultConfig;

class Data extends AbstractHelper
{
    /**
     * @var ConfigDataCollection
     */
    protected $configDataCollection;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var array
     */
    protected $uncachedConfig = [];

    /**
     * Data constructor.
     * @param Context $context
     * @param ConfigDataCollection $configDataCollection
     * @param State $state
     */
    public function __construct(
        Context $context,
        ConfigDataCollection $configDataCollection,
        State $state
    ) {
        $this->configDataCollection = $configDataCollection;
        $this->state = $state;
        parent::__construct($context);
    }

    /**
     * Get config value
     *
     * @param string $path
     * @param int $storeId
     * @return mixed
     */
    public function getConfig($path, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get module status
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->scopeConfig->isSetFlag(
            DefaultConfig::XML_PATH_IS_ENABLE_MODULE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get extra fee title
     *
     * @param int $storeId
     * @return string
     */
    public function getTitle($storeId = null)
    {
        return (string) $this->getConfig(DefaultConfig::XML_PATH_TITLE, $storeId);
    }

    /**
     * Get extra fee description
     *
     * @return string
     */
    public function getDescription()
    {
        return (string) $this->getConfig(DefaultConfig::XML_PATH_DESCRIPTION);
    }

    /**
     * Get extra fee amount
     *
     * @param int $storeId
     * @return float
     */
    public function getExtraFee($storeId)
    {
        return (float) $this->getConfig(DefaultConfig::XML_PATH_EXTRAFEE_AMOUNT, $storeId);
    }

    /**
     * Get extra fee price type
     *
     * @return int
     */
    public function getPriceType()
    {
        return (int) $this->getConfig(DefaultConfig::XML_PATH_PRICE_TYPE);
    }

    /**
     * Get module status
     *
     * @param int $storeId
     * @return bool
     */
    public function isRefund($storeId = null)
    {
        return (bool) $this->getConfig(DefaultConfig::XML_PATH_IS_REFUND, $storeId);
    }

    /**
     * Check is tax enabled
     *
     * @param int $storeId
     * @return bool
     */
    public function isTaxEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            DefaultConfig::XML_PATH_IS_TAX_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get tax class id
     *
     * @return int
     */
    public function getTaxClassId()
    {
        return $this->getConfig(DefaultConfig::XML_PATH_TAX_CLASS_ID);
    }

    /**
     * Get tax display type
     *
     * @param int $storeId
     * @return mixed
     */
    public function getTaxDisplay($storeId = null)
    {
        return $this->getConfig(DefaultConfig::XML_PATH_TAX_DISPLAY, $storeId);
    }

    /**
     * Check is incl. tax displayed
     *
     * @param int $storeId
     * @return bool
     */
    public function displayInclTax($storeId = null)
    {
        return in_array($this->getTaxDisplay($storeId), [2,3]);
    }

    /**
     * Check is excl. tax displayed
     *
     * @param int $storeId
     * @return bool
     */
    public function displayExclTax($storeId = null)
    {
        return in_array($this->getTaxDisplay($storeId), [1,3]);
    }

    /**
     * Check is tax suffix added
     *
     * @return bool
     */
    public function displaySuffix()
    {
        return ($this->getTaxDisplay() == 3);
    }

    /**
     * Get selected customer groups
     *
     * @return array
     */
    public function getCustomerGroup()
    {
        $customerGroups = $this->getConfig(DefaultConfig::XML_PATH_CUSTOMER_GROUP);
        return explode(',', $customerGroups);
    }

    /**
     * Check if include shipping in subtotal
     *
     * @return bool
     */
    public function getIsIncludeShipping()
    {
        return (bool)$this->getConfig(DefaultConfig::XML_PATH_IS_INCLUDE_SHIPPING);
    }

    /**
     * Check if include discount in subtotal
     *
     * @return bool
     */
    public function getIsIncludeDiscount()
    {
        return (bool)$this->getConfig(DefaultConfig::XML_PATH_IS_INCLUDE_DISCOUNT);
    }

    /**
     * Get uncached extra fee conditions
     *
     * @return mixed
     */
    public function getExtraFeeConditions()
    {
        return $this->getUncachedConfig(DefaultConfig::XML_PATH_EXTRAFEE_CONDITIONS);
    }

    /**
     * Get uncached apply fee for value
     *
     * @return int
     */
    public function getApplyFor()
    {
        return (int) $this->getUncachedConfig(DefaultConfig::XML_PATH_APPLY_FOR);
    }

    /**
     * Get default scope config value bypassing the config cache
     *
     * @param string $path
     * @return mixed
     */
    protected function getUncachedConfig($path)
    {
        if (array_key_exists($path, $this->uncachedConfig)) {
            return $this->uncachedConfig[$path];
        }
        $value = null;
        $configDataCollection = $this->configDataCollection->create();
        $configDataCollection->addFieldToFilter('scope', 'default');
        $configDataCollection->addFieldToFilter('scope_id', 0);
        $configDataCollection->addFieldToFilter('path', $path);
        if ($configDataCollection->count() !== 0) {
            $value = $configDataCollection->getFirstItem()->getValue();
        }
        $this->uncachedConfig[$path] = $value;
        return $value;
    }

    /**
     * Check is admin
     *
     * @return bool
     */
    public function isBackendArea()
    {
        try {
            $isBackend = false;
            if ($this->state->getAreaCode() == \Magento\Framework\App\Area::AREA_ADMINHTML) {
                $isBackend = true;
            }
        } catch (\Exception $e) {
            $isBackend = false;
        }
        return $isBackend;
    }
}
