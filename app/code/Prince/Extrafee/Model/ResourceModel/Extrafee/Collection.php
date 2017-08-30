<?php

namespace Prince\Extrafee\Model\ResourceModel\Extrafee;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Contact Resource Model Collection
 *
 * @author      Pierre FAY
 */
class Collection extends AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Prince\Extrafee\Model\Extrafee', 'Prince\Extrafee\Model\ResourceModel\Extrafee');
    }
}