<?php

namespace Prince\Extrafee\Model\Config\Source;

class PriceType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 1, 'label' => __('Percentage Price')], ['value' => 0, 'label' => __('Fixed Price')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [0 => __('Percentage Price'), 1 => __('Fixed Price')];
    }
}
