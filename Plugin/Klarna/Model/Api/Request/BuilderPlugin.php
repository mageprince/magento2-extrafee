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

namespace Mageprince\Extrafee\Plugin\Klarna\Model\Api\Request;

use Klarna\Kp\Model\Api\Request\Builder;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class BuilderPlugin
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * BuilderPlugin constructor.
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Set extra fee in Klarna totals
     *
     * @param Builder $subject
     * @param array $data
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function beforeAddOrderlines(Builder $subject, $data)
    {
        $baseFee = $this->checkoutSession->getQuote()->getBaseFee();

        if ($baseFee && $baseFee > 0) {
            $value = round($baseFee * 100);

            $extraFeeData = [
                [
                    'type' => 'surcharge',
                    'unit_price' => $value,
                    'quantity' => 1,
                    'name' => 'Extra Fee',
                    'total_amount' => $value
                ]
            ];

            $data = array_merge($extraFeeData, $data);
        }

        return [$data];
    }
}
