'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var tslib = require('tslib');
var core$1 = require('@angular/core');
var core = require('@ionic-native/core');

var Stripe = /** @class */ (function (_super) {
    tslib.__extends(Stripe, _super);
    function Stripe() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    Stripe.prototype.setPublishableKey = function (publishableKey) { return core.cordova(this, "setPublishableKey", {}, arguments); };
    Stripe.prototype.createCardToken = function (params) { return core.cordova(this, "createCardToken", {}, arguments); };
    Stripe.prototype.createBankAccountToken = function (params) { return core.cordova(this, "createBankAccountToken", {}, arguments); };
    Stripe.prototype.validateCardNumber = function (cardNumber) { return core.cordova(this, "validateCardNumber", {}, arguments); };
    Stripe.prototype.validateCVC = function (cvc) { return core.cordova(this, "validateCVC", {}, arguments); };
    Stripe.prototype.validateExpiryDate = function (expMonth, expYear) { return core.cordova(this, "validateExpiryDate", {}, arguments); };
    Stripe.prototype.getCardType = function (cardNumber) { return core.cordova(this, "getCardType", {}, arguments); };
    Stripe.pluginName = "Stripe";
    Stripe.plugin = "cordova-plugin-stripe";
    Stripe.pluginRef = "cordova.plugins.stripe";
    Stripe.repo = "https://github.com/zyramedia/cordova-plugin-stripe";
    Stripe.platforms = ["Android", "Browser", "iOS"];
    Stripe.decorators = [
        { type: core$1.Injectable }
    ];
    return Stripe;
}(core.IonicNativePlugin));

exports.Stripe = Stripe;
