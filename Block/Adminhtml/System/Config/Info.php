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

namespace Mageprince\Extrafee\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Info extends Fieldset
{
    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        //@codingStandardsIgnoreStart
        $html = '<div class="mageprince-info" style="margin-bottom: 20px;"><span class="message success">';
        $html .= 'Please check pro version <a target="_blank" href="https://marketplace.magento.com/mageprince-module-extrafee-pro.html">Extra Fee Pro</a> to add multiple extra fees.';
        $html .= '</span></div>';
        return $html;
        // @codingStandardsIgnoreEnd
    }
}
