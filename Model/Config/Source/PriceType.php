<?php

/**
 * MagePrince
 * Copyright (C) 2018 Mageprince
 *
 * NOTICE OF LICENSE
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see http://opensource.org/licenses/gpl-3.0.html
 *
 * @category MagePrince
 * @package Prince_Extrafee
 * @copyright Copyright (c) 2018 MagePrince
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MagePrince
 */

namespace Prince\Extrafee\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class PriceType
 * @package Prince\Extrafee\Model\Config\Source
 */
class PriceType implements ArrayInterface
{
    /**
     * Price type variants
     */
    public const TYPE_PERCENTAGE = 1;
    public const TYPE_FIXED = 0;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::TYPE_PERCENTAGE, 'label' => __('Percentage Price')],
            ['value' => self::TYPE_FIXED, 'label' => __('Fixed Price')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach ($this->toOptionArray() as $option) {
            $result[$option['value']] = $option['label'];
        }
        return $result;
    }
}
