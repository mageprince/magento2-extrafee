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

namespace Mageprince\Extrafee\Block\Adminhtml\Extrafee\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Init tab
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('mageprince_extrafee_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Extra Fee Conditions'));
    }
}
