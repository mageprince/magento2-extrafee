define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/totals'
], function (
    Component,
    quote,
    totals,
) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Mageprince_Extrafee/checkout/cart/totals/fee'
        },
        totals: quote.getTotals(),
        title: window.checkoutConfig.mageprince_extrafee.title,
        description: window.checkoutConfig.mageprince_extrafee.description,
        isTaxEnabled: window.checkoutConfig.mageprince_extrafee.isTaxEnabled,
        displayBoth: window.checkoutConfig.mageprince_extrafee.displayBoth,
        displayInclTax: window.checkoutConfig.mageprince_extrafee.displayInclTax,
        displayExclTax: window.checkoutConfig.mageprince_extrafee.displayExclTax,

        /**
         * Check is show extra fee
         * @returns {boolean}
         */
        isDisplayed: function () {
            return this.getExtraFee() != 0;
        },

        /**
         * Get extra fee
         * @returns {number}
         */
        getExtraFee: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = parseFloat(totals.getSegment('fee').value);
            }
            return price;
        },

        /**
         * Get extra fee value
         * @returns {String|*}
         */
        getValue: function() {
            return this.getFormattedPrice(this.getExtraFee());
        },

        /**
         * Get extra fee excl. tax
         * @returns {String|*}
         */
        getExtraFeeExclTax: function () {
            return this.getValue();
        },

        /**
         * Get extra fee incl. tax
         * @returns {String|*}
         */
        getExtraFeeInclTax: function () {
            var price = 0;
            if (this.totals() && totals.getSegment('fee')) {
                price = totals.getSegment('fee_incl_tax').value;
            }
            return this.getFormattedPrice(price);
        }
    });
});
