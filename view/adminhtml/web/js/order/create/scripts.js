define([
    'jquery',
    'Magento_Sales/order/create/scripts'
], function (jQuery) {
    'use strict';

    window.AdminOrder.prototype.setShippingMethod = function(method) {
        var data = {};
        data['order[shipping_method]'] = method;
        this.loadArea(['shipping_method', 'items', 'totals', 'billing_method'], true, data);
    };

    window.payment.switchMethod = function(method) {
        this.setPaymentMethod(method);
        var data = {};
        data['order[payment_method]'] = method;
        this.loadArea(['card_validation', 'totals', 'items'], true, data);
    }.bind(window.order);
});
