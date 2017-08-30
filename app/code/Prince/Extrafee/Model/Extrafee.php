<?php

namespace Prince\Extrafee\Model;

use Magento\Framework\Model\AbstractModel;

class Extrafee extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Prince\Extrafee\Model\ResourceModel\Extrafee');
    }
}
