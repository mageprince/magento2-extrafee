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

namespace Mageprince\Extrafee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Mageprince\Extrafee\Helper\Data as FeeHelper;

class ExtraFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * ExtraFeeConfigProvider constructor.
     * @param FeeHelper $helper
     */
    public function __construct(FeeHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Set extra fee checkout config
     *
     * @return array
     */
    public function getConfig()
    {
        $displayExclTax = $this->helper->displayExclTax();
        $displayInclTax = $this->helper->displayInclTax();

        $extraFeeConfig = [
            'mageprince_extrafee' => [
                'isEnabled' => $this->helper->isEnable(),
                'title' => $this->helper->getTitle(),
                'description' => $this->helper->getDescription(),
                'isTaxEnabled' => $this->helper->isTaxEnabled(),
                'displayBoth' => ($displayExclTax && $displayInclTax),
                'displayInclTax' => $this->helper->displayInclTax(),
                'displayExclTax' => $this->helper->displayExclTax()
            ]
        ];

        return $extraFeeConfig;
    }
}
