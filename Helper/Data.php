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
     * @var null|mixed
     */
    protected $feeConditions = null;

    /**
     * @var bool
     */
    protected $feeConditionFlag = false;

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
     * @param string $path
     * @param string $scope
     * @param int $scopeId
     * @return mixed
     */
    public function getExtraFeeConditions()
    {
        if ($this->feeConditionFlag) {
            return $this->feeConditions;
        }
        $configDataCollection = $this->configDataCollection->create();
        $configDataCollection->addFieldToFilter('scope', 'default');
        $configDataCollection->addFieldToFilter('scope_id', 0);
        $configDataCollection->addFieldToFilter('path', DefaultConfig::XML_PATH_EXTRAFEE_CONDITIONS);
        if ($configDataCollection->count() !== 0) {
            $this->feeConditions = $configDataCollection->getFirstItem()->getValue();
        }
        $this->feeConditionFlag = true;
        return $this->feeConditions;
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
