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

namespace Mageprince\Extrafee\Model\Config;

class DefaultConfig
{
    /**
     * Is enabled module config path
     */
    public const XML_PATH_IS_ENABLE_MODULE = 'extrafee/general/active';

    /**
     * Extra fee title config path
     */
    public const XML_PATH_TITLE = 'extrafee/general/title';

    /**
     * Extra fee description config path
     */
    public const XML_PATH_DESCRIPTION = 'extrafee/general/description';

    /**
     * Extra fee amount config path
     */
    public const XML_PATH_EXTRAFEE_AMOUNT = 'extrafee/fee_config/price';

    /**
     * Extra fee Price type config path
     */
    public const XML_PATH_PRICE_TYPE = 'extrafee/fee_config/pricetype';

    /**
     * Is refund enabled config path
     */
    public const XML_PATH_IS_REFUND = 'extrafee/fee_config/refund';

    /**
     * Is tax enabled config path
     */
    public const XML_PATH_IS_TAX_ENABLED = 'extrafee/tax/enable';

    /**
     * Tax class id config path
     */
    public const XML_PATH_TAX_CLASS_ID = 'extrafee/tax/tax_class';

    /**
     * Extra fee conditions config path
     */
    public const XML_PATH_TAX_DISPLAY = 'extrafee/tax/display';

    /**
     * Allowed customer groups config path
     */
    public const XML_PATH_CUSTOMER_GROUP = 'extrafee/fee_config/customer_group';

    /**
     * Is shipping include in subtotal config path
     */
    public const XML_PATH_IS_INCLUDE_SHIPPING = 'extrafee/fee_config/include_shipping';

    /**
     * Is discount included in config path
     */
    public const XML_PATH_IS_INCLUDE_DISCOUNT = 'extrafee/fee_config/include_discount';

    /**
     * Extra fee conditions config path
     */
    public const XML_PATH_EXTRAFEE_CONDITIONS = 'extrafee/fee_config/conditions';

    /**
     * Klarna payment methods
     */
    public const KLARNA_METHODS = [
        'klarna_kp',
        'klarna_pay_now',
        'klarna_pay_later',
        'klarna_pay_over_time',
        'klarna_direct_debit',
        'klarna_direct_bank_transfer'
    ];
}
