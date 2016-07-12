<?php namespace SilvershopBankDeposit\Model;

use DataExtension;
use Checkout;
use OrderProcessor;
use SilverStripe\Omnipay\GatewayInfo;

class Order extends DataExtension
{
    /**
     * Send an email with a copy of the order for payment
     *
     * Accessing the extension point is needed to dynamically set send_confirmation to true.
     * Normally, an unpaid order will not generate an email.
     * @see onPlaceOrder is an extension within the placeOrder function within the OrderProcessor class
     */
    public function onPlaceOrder()
    {
        $gateway = Checkout::get($this->owner)->getSelectedPaymentMethod();
        if (OrderProcessor::config()->bank_deposit_send_confirmation && GatewayInfo::isManual($gateway) && $this->owner->Status == "Unpaid") {
            OrderProcessor::config()->send_confirmation = true;
        } else {
            OrderProcessor::config()->send_confirmation = false;
        }
    }
}
