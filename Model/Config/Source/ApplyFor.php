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

namespace Mageprince\Extrafee\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ApplyFor implements OptionSourceInterface
{
    /**
     * Apply fee for variants
     */
    public const WHOLE_CART = 0;
    public const MATCHING_ITEMS = 1;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::WHOLE_CART, 'label' => __('Whole Cart')],
            ['value' => self::MATCHING_ITEMS, 'label' => __('Matching Cart Items Only')]
        ];
    }
}
