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

namespace Mageprince\Extrafee\Plugin\SalesRule\Model\Rule\Condition;

use Magento\SalesRule\Model\Rule\Condition\Address;
use Mageprince\Extrafee\Model\Config\DefaultConfig;

class AddressPlugin
{
    /**
     * Add additional options in conditions
     *
     * @param Address $subject
     * @param array $result
     * @return array|mixed
     */
    public function afterGetValueSelectOptions(
        Address $subject,
        $result
    ) {
        /** Add klarna payment methods */
        if (isset($result['klarna']['value'])) {
            $klarnaPaymentMethods = [];
            $klarnaPayments = DefaultConfig::KLARNA_METHODS;
            foreach ($klarnaPayments as $klarnaPayment) {
                $label = __('Klarna (%1)', $klarnaPayment);
                $klarnaPaymentMethods[$klarnaPayment] = [
                    'value' => $klarnaPayment,
                    'label' => $label
                ];
            }
            $result['klarna']['value'] = $klarnaPaymentMethods;
        }
        return $result;
    }
}
